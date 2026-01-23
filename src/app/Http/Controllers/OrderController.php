<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function authorizeTransaction(Transaction $transaction): ?RedirectResponse
    {
        $userId = auth()->id();

        if ($transaction->buyer_id !== $userId) {
            abort(403, '購入者以外は購入手続きできません。');
        }

        if (! $transaction->isTrading()) {
            return redirect()->route('transactions.show', $transaction);
        }

        return null;
    }

    public function create(Request $request ,Transaction $transaction)
    {
        if ($response = $this->authorizeTransaction($transaction)) {
            return $response;
        }

        $payment = $request->query('payment', '');

        return view('orders.purchase', [
            'transaction' => $transaction,
            'item' => $transaction->item,
            'user' => auth()->user(),
            'payment' => $payment,
        ]);
    }

    public function store(PurchaseRequest $request, Transaction $transaction)
    {
        if ($response = $this->authorizeTransaction($transaction)) {
            return $response;
        }

        if ($transaction->order()->exists()) {
            return redirect()->route('transactions.show', $transaction);
        }

        $validated = $request->validated();

        Order::firstOrCreate(
            ['transaction_id' => $transaction->id],
            [
                'payment_method' => $validated['payment'],
                'address'        => $validated['address'],
            ]
        );

        return redirect()->route('transactions.show', $transaction);
    }

    public function editAddress(Request $request, Transaction $transaction)
    {
        if ($response = $this->authorizeTransaction($transaction)) {
            return $response;
        }

        return view('orders.edit_address', [
            'transaction' => $transaction,
            'item' => $transaction->item,
            'user' => auth()->user(),
        ]);
    }

    public function updateAddress(AddressRequest $request, Transaction $transaction)
    {
        if ($response = $this->authorizeTransaction($transaction)) {
            return $response;
        }

        $user = auth()->user();

        $validated = $request->validated();

        $user->update([
            'postal_code' => $validated['postal_code'],
            'address'     => $validated['address'],
            'building'    => $validated['building'] ?? null,
        ]);

        return redirect()->route('purchase.create', ['transaction' => $transaction->id])->with('success', '住所が更新されました');
    }
}