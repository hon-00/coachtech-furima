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

            // 自分の出品を除外
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
        ])
        ->withCount('comments')
        ->findOrFail($item_id);

        return view('items.show', compact('item'));
    }


    // 商品出品画面表示
    public function create()
    {
        // カテゴリー一覧を取得
        $categories = Category::orderBy('id')->get();

        return view('items.create', compact('categories'));
    }

    // 商品出品処理
    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        // 画像アップロード
        $validated['image'] = $request->file('image')?->store('item_images', 'public');

        // 出品者と売却フラグ
        $validated['user_id'] = auth()->id();
        $validated['sold_flag'] = false;

        // Item登録
        $item = Item::create($validated);

        // カテゴリー登録（存在する場合のみ）
        if (!empty($validated['category_id'])) {
            $item->categories()->sync($validated['category_id']);
        }

        return redirect()->route('items.show', $item->id)
                            ->with('success', '商品を出品しました');
    }
}