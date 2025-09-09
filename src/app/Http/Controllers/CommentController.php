<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Comment::create([
            'content' => $request->validated()['content'],
            'user_id' => auth()->id(),
            'item_id' => $item->id,
        ]);

        return redirect()->route('items.show', $item_id);
    }
}