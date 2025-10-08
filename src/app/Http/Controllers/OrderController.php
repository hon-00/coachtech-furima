<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

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
}