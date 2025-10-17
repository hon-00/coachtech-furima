<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

class OrderController extends Controller
{
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $payment = $request->query('payment', '');

        return view('orders.purchase', compact('item', 'user', 'payment'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $validated = $request->validated();

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $validated['payment'],
            'address' => $validated['address'],
        ]);

        $item->update(['sold_flag' => true]);

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

        $validated = $request->validated();

        $user->update([
            'postal_code' => $validated['postal_code'],
            'address'     => $validated['address'],
            'building'    => $validated['building'] ?? null,
        ]);

        return redirect()->route('purchase.create', ['item' => $item->id])->with('success', '住所が更新されました');
    }
}