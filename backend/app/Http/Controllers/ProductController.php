<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('query');

        if (empty($query)) {
            $products = Product::paginate(8);
            return response()->json([
                'success' => true,
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                ],
                'is_ai' => false,
                'explanation' => null
            ]);
        }

        $apiKey = env('GROQ_API_KEY');
        $parsed = null;
        $isAi = false;

        if (!empty($apiKey)) {
            Log::debug('GROQ_API_KEY present', ['len' => strlen($apiKey)]);
            try {
                Log::debug('Entering Groq call block');
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
                    // Note: Groq's response formatting options may differ from OpenAI's.
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.1,
                ]);

                // If the model was deprecated, try a recommended replacement once
                if (! $response->successful()) {
                    $body = $response->body();
                    Log::error('Groq API request failed: ' . $body);
                    if (stripos($body, 'model_decommissioned') !== false || stripos($body, 'decommissioned') !== false) {
                        // mapping of deprecated -> replacement (expand as needed)
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
                    // Log raw response for debugging (can be noisy in production)
                    Log::debug('Groq raw response', $result ?: []);

                    // Try several possible locations for the model text
                    $textResponse = null;
                    if (isset($result['choices'][0]['message']['content'])) {
                        $textResponse = $result['choices'][0]['message']['content'];
                    } elseif (isset($result['choices'][0]['text'])) {
                        $textResponse = $result['choices'][0]['text'];
                    } elseif (isset($result['output'][0]['content'][0]['text'])) {
                        $textResponse = $result['output'][0]['content'][0]['text'];
                    }

                    if ($textResponse) {
                        // Remove surrounding markdown fences (```json / ```), whitespace
                        $cleanJson = preg_replace('/(^```(?:json)?\s*|\s*```$)/i', '', trim($textResponse));

                        // First attempt to decode directly
                        $parsed = json_decode($cleanJson, true);

                        // If decode failed or produced non-array, try to extract a JSON object from the text
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
                        }
                    }
                } else {
                    Log::error('Groq API request failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Error calling Groq API: ' . $e->getMessage());
            }
        }


        if ($isAi && $parsed) {
            $dbQuery = Product::query();


            if (!empty($parsed['category'])) {
                $dbQuery->where('category', $parsed['category']);
            }


            if (isset($parsed['min_price']) && is_numeric($parsed['min_price'])) {
                $dbQuery->where('price', '>=', $parsed['min_price']);
            }
            if (isset($parsed['max_price']) && is_numeric($parsed['max_price'])) {
                $dbQuery->where('price', '<=', $parsed['max_price']);
            }


            if (!empty($parsed['keywords']) && is_array($parsed['keywords'])) {
                $dbQuery->where(function ($q) use ($parsed) {
                    foreach ($parsed['keywords'] as $keyword) {
                        $q->orWhere('name', 'like', '%' . $keyword . '%')
                          ->orWhere('description', 'like', '%' . $keyword . '%');
                    }
                });
            }


            if (!empty($parsed['sort_by'])) {
                if ($parsed['sort_by'] === 'price_asc') {
                    $dbQuery->orderBy('price', 'asc');
                } elseif ($parsed['sort_by'] === 'price_desc') {
                    $dbQuery->orderBy('price', 'desc');
                }
            }

            $products = $dbQuery->paginate(8);
            $explanation = $parsed['explanation'] ?? 'Tìm kiếm kết quả theo mô tả của bạn.';

            return response()->json([
                'success' => true,
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                ],
                'is_ai' => true,
                'explanation' => $explanation,
                'parsed_criteria' => $parsed
            ]);
        }


        $words = array_filter(explode(' ', trim($query)));
        $dbQuery = Product::query();

        if (!empty($words)) {
            $dbQuery->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('name', 'like', '%' . $word . '%')
                      ->orWhere('description', 'like', '%' . $word . '%')
                      ->orWhere('category', 'like', '%' . $word . '%');
                }
            });
        }

        $products = $dbQuery->paginate(8);

        return response()->json([
            'success' => true,
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
            'is_ai' => false,
            'explanation' => 'Tìm kiếm cơ bản với từ khóa: "' . htmlspecialchars($query) . '" (Bật API Key ở backend để có kết quả tìm kiếm AI thông minh hơn).'
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    private function buildPrompt($query)
        {
            return <<<PROMPT
    Bạn là AI hỗ trợ tìm kiếm cho cửa hàng thời trang. 
    Nhiệm vụ: Phân tích câu truy vấn của người dùng và trả về JSON.

    DANH MỤC: "Coat", "Shirt", "Jeans", "Dress", "Shoes", "Bag", "Hat", "Towel", "Accessories".
    GIÁ: Trả về số nguyên VND (ví dụ: 300k = 300000).

    Câu hỏi của người dùng: "${query}"

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
