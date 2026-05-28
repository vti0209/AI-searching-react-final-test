<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductSearchService;

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

        $parsed = null;
        $isAi = false;
        $aiExplanation = null;

        $searchService = new ProductSearchService();
        $aiResult = $searchService->analyze($query);
        $parsed = $aiResult['parsed'] ?? null;
        $isAi = $aiResult['is_ai'] ?? false;
        $aiExplanation = $aiResult['explanation'] ?? null;


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
            $explanation = $parsed['explanation'] ?? $aiExplanation ?? 'Tìm kiếm kết quả theo mô tả của bạn.';

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

    
}
