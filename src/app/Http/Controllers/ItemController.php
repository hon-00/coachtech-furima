<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $items = Item::query()
        ->when($keyword, fn($query) => $query->where('name', 'like', "%{$keyword}%"))
        ->latest()
        ->get();

        return view('items.index', compact('items'));
    }

    public function show($item_id)
    {
        $item = Item::with('user', 'comments.user')->findOrFail($item_id);

        return view('items.show', compact('item'));
    }
}
