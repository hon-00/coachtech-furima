<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Requests\AddressRequest;

class OrderController extends Controller
{
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $payment = $request->query('payment', ''); // クエリパラメータから支払い方法を取得

        return view('orders.purchase', compact('item', 'user', 'payment'));
    }

    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // 決済処理や注文登録の処理を書く場所
        // Order::create([...]);

        return redirect()->route('home')->with('success', '購入が完了しました');
    }

    public function editAddress(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('orders.edit_address', compact('item', 'user'));
    }

    public function updateAddress(AddressRequest $request, $item)
    {
        $item = Item::findOrFail($item);
        $user = auth()->user();

        // バリデーション済みデータを取得
        $validated = $request->validated();

        // ユーザーの住所を更新
        $user->update([
            'postal_code' => $validated['postal_code'],
            'address'     => $validated['address'],
            'building'    => $validated['building'] ?? null,
        ]);

        return redirect()->route('purchase.create', ['item' => $item->id])->with('success', '住所が更新されました');
    }
}