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

        $apiKey = env('GEMINI_API_KEY');
        $parsed = null;
        $isAi = false;

        if (!empty($apiKey)) {
            try {
                $prompt = $this->buildPrompt($query);
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json'
                    ]
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $textResponse = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($textResponse) {
                        $parsed = json_decode($textResponse, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $isAi = true;
                        }
                    }
                } else {
                    Log::error('Gemini API request failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Error calling Gemini API: ' . $e->getMessage());
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
            'explanation' => 'Tìm kiếm cơ bản với từ khóa: "' . htmlspecialchars($query) . '" (Bật Gemini API Key ở backend để có kết quả tìm kiếm AI thông minh hơn).'
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
You are an AI Search Assistant for a Vietnamese fashion e-commerce store.

PRODUCT CATEGORIES (8 categories):
- "Coat": Áo khoác, áo len, áo gió, áo sweater, áo jacket, áo hoodie
- "Shirt": Áo thun, áo polo, áo sơ mi, áo croptop, áo Henley
- "Jeans": Quần jeans, quần denim, quần short jeans, quần jean skinny/baggy/slim
- "Dress": Váy, đầm, váy maxi, váy midi, đầm bodycon, đầm wrap
- "Shoes": Giày thể thao, giày sneaker, giày Converse, boot, sandal, dép
- "Bag": Túi xách, balo, túi tote, túi clutch, túi đeo chéo, túi vai
- "Hat": Mũ, nón, snapback, bucket hat, baseball cap, beanie, nón lưỡi trai
- "Towel": Khăn quàng cổ, khăn len, khăn tay, khăn thể thao, khăn lụa
- "Accessories": Kính mắt, kính râm, thắt lưng, vòng tay, dây chuyền, ví

PRICE FORMAT: All prices are in Vietnamese Dong (VND).
Examples: 149000 = 149,000 VND = 149k, 299000 = 299k, 799000 = 799k, 1290000 = 1,290k = 1.29 triệu.
When user says: "dưới 300k" → max_price: 300000 | "khoảng 500k" → min: 400000, max: 600000 | "từ 200k đến 500k" → min: 200000, max: 500000 | "rẻ nhất" → sort_by: "price_asc" | "đắt nhất" → sort_by: "price_desc"

Given the user's natural language search query in Vietnamese: "${query}"

Your tasks:
1. Identify the product category if mentioned (return exactly one of the 9 category values above, or null).
2. Extract price range in full VND numbers (e.g. 300000, NOT 300).
3. Extract relevant keywords to search in product name/description (Vietnamese or English).
4. Determine sort order if user mentions cheapest/most expensive.
5. Write a short, friendly explanation in Vietnamese about what you are searching for.

Output ONLY a valid JSON object with this exact structure (no markdown, no backticks):
{
  "category": string or null (must be exactly one of: "Coat", "Shirt", "Jeans", "Dress", "Shoes", "Bag", "Hat", "Towel", "Accessories", or null),
  "min_price": number or null (full VND e.g. 200000),
  "max_price": number or null (full VND e.g. 500000),
  "keywords": string[] (search keywords in name/description),
  "sort_by": "price_asc" or "price_desc" or null,
  "explanation": string (Vietnamese explanation, friendly tone)
}
PROMPT;
    }
}
