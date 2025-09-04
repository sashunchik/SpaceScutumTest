<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Список коментарів до товару
    public function index(Product $product)
    {
        return response()->json($product->comments()->with('user')->latest()->get());
    }

    // Додати коментар
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $product->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return response()->json($comment, 201);
    }

    // Видалити коментар
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

}
