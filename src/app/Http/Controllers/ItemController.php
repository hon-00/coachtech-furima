<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->query('tab', 'recommend');

        // マイリスト（お気に入り一覧）
        if ($tab === 'mylist' && auth()->check()) {
            $query = auth()->user()->likedItems();

            // nameをキーワードで部分一致検索
            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }

            $items = $query->latest()->get();
        }
        // 通常のおすすめ（全商品または検索）
        else {
            $query = Item::query();

            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }

            $items = $query->latest()->get();
        }

        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    public function show($item_id)
    {
        $item = Item::with([
            'user',
            'likes',
            'comments.user',
        ])
        ->withCount('comments')
        ->findOrFail($item_id);

        return view('items.show', compact('item'));
    }
}