<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $like = $item->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
        } else {
            $item->likes()->create([
                'user_id' => auth()->id(),
            ]);
        }

        return back();
    }
}