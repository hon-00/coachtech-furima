<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist' && auth()->check()) {
            $query = auth()->user()->likedItems()->with('transaction');

            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }

            $items = $query->latest()->get();
        }

        else {
            $query = Item::query()->with('transaction');

            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

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
            'transaction',
        ])
        ->withCount('comments')
        ->findOrFail($item_id);

        return view('items.show', compact('item'));
    }


    // 商品出品画面表示
    public function create()
    {
        $categories = Category::orderBy('id')->get();

        return view('items.create', compact('categories'));
    }

    // 商品出品処理
    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $validated['image'] = $request->file('image')?->store('item_images', 'public');

        $validated['user_id'] = auth()->id();

        $item = Item::create($validated);

        if (!empty($validated['category_id'])) {
            $item->categories()->sync($validated['category_id']);
        }

        return redirect()->route('items.show', $item->id)
                            ->with('success', '商品を出品しました');
    }
}
