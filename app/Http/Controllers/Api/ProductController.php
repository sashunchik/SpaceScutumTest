<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Отримати всі товари
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        if ($request->has('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('popular') && $request->popular) {
            $query->orderBy('sales_count', 'desc');
        }

        $products = $query->get();

        return response()->json($products);
    }

    // Створити новий товар
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|string', // можна буде зробити upload
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    // Отримати один товар
    public function show(Product $product)
    {
        return $product;
    }

    // Оновити товар
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|string',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    // Видалити товар
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
