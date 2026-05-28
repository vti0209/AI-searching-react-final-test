<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductSearchService
{
    public function analyze(string $query): array
    {
        $apiKey = env('GROQ_API_KEY');
        $parsed = null;
        $isAi = false;
        $explanation = null;

        if (empty($apiKey)) {
            return ['is_ai' => false, 'parsed' => null, 'explanation' => null];
        }

        try {
            $prompt = $this->buildPrompt($query);
            $model = 'llama-3.3-70b-versatile';

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])->post("https://api.groq.com/openai/v1/chat/completions", [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.1,
            ]);

            if (! $response->successful()) {
                $body = $response->body();
                Log::error('Groq API request failed: ' . $body);
                if (stripos($body, 'model_decommissioned') !== false || stripos($body, 'decommissioned') !== false) {
                    $replacements = [
                        'llama-3.1-70b-versatile' => 'llama-3.3-70b-versatile',
                    ];
                    if (isset($replacements[$model])) {
                        $newModel = $replacements[$model];
                        Log::info('Retrying Groq request with replacement model: ' . $newModel);
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => "Bearer {$apiKey}",
                        ])->post("https://api.groq.com/openai/v1/chat/completions", [
                            'model' => $newModel,
                            'messages' => [
                                ['role' => 'user', 'content' => $prompt]
                            ],
                            'response_format' => ['type' => 'json_object'],
                            'temperature' => 0.1,
                        ]);
                    }
                }
            }

            if ($response->successful()) {
                $result = $response->json();
                Log::debug('Groq raw response', $result ?: []);

                $textResponse = null;
                if (isset($result['choices'][0]['message']['content'])) {
                    $textResponse = $result['choices'][0]['message']['content'];
                } elseif (isset($result['choices'][0]['text'])) {
                    $textResponse = $result['choices'][0]['text'];
                } elseif (isset($result['output'][0]['content'][0]['text'])) {
                    $textResponse = $result['output'][0]['content'][0]['text'];
                }

                if ($textResponse) {
                    $cleanJson = preg_replace('/(^```(?:json)?\s*|\s*```$)/i', '', trim($textResponse));
                    $parsed = json_decode($cleanJson, true);

                    if ((json_last_error() !== JSON_ERROR_NONE) || !is_array($parsed)) {
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::warning('Initial JSON decode failed: ' . json_last_error_msg());
                        }

                        $start = strpos($cleanJson, '{');
                        $end = strrpos($cleanJson, '}');
                        if ($start !== false && $end !== false && $end > $start) {
                            $maybe = substr($cleanJson, $start, $end - $start + 1);
                            $maybeParsed = json_decode($maybe, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($maybeParsed)) {
                                $parsed = $maybeParsed;
                            } else {
                                Log::warning('Attempted extracting JSON object failed: ' . json_last_error_msg());
                            }
                        }
                    }

                    if (is_array($parsed) && json_last_error() === JSON_ERROR_NONE) {
                        $isAi = true;
                        $explanation = $parsed['explanation'] ?? null;
                    }
                }
            } else {
                Log::error('Groq API request failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error calling Groq API: ' . $e->getMessage());
        }

        return ['is_ai' => $isAi, 'parsed' => $parsed, 'explanation' => $explanation];
    }

    private function buildPrompt($query)
    {
        return <<<PROMPT
Bạn là AI hỗ trợ tìm kiếm cho cửa hàng thời trang. 
Nhiệm vụ: Phân tích câu truy vấn của người dùng và trả về JSON.

DANH MỤC: "Coat", "Shirt", "Jeans", "Dress", "Shoes", "Bag", "Hat", "Towel", "Accessories".
GIÁ: Trả về số nguyên VND (ví dụ: 300k = 300000).

Câu hỏi của người dùng: "{$query}"

Yêu cầu output duy nhất 1 đối tượng JSON, không giải thích gì thêm, đúng cấu trúc sau:
{
"category": string|null,
"min_price": number|null,
"max_price": number|null,
"keywords": string[],
"sort_by": "price_asc"|"price_desc"|null,
"explanation": "Câu giải thích thân thiện bằng tiếng Việt"
}
PROMPT;
    }
}
