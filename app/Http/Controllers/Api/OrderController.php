<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Показати історію покупок користувача
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()->with('products')->get();

        return response()->json($orders);
    }

    // Створити нове замовлення
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;

        $order = Order::create([
            'user_id' => $user->id,
            'total' => 0, // тимчасово
        ]);

        foreach ($validated['products'] as $item) {
            $product = \App\Models\Product::find($item['id']);
            $price = $product->price;
            $quantity = $item['quantity'];
            $total += $price * $quantity;

            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'price' => $price
            ]);
        }

        $order->update(['total' => $total]);

        return response()->json($order->load('products'), 201);
    }
}

