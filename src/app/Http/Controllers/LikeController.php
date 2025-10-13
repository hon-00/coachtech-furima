<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $user = auth()->user();

        $user->likedItems()->toggle($item->id);

        return back();
    }
}