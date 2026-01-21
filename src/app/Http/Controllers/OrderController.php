<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function authorizeTransaction(Transaction $transaction)
    {
        $userId = auth()->id();

        if (
            $transaction->buyer_id !== $userId &&
            $transaction->seller_id !== $userId
            )  {
                abort(403, 'この取引を閲覧する権限がありません。');
            }
    }

    public function create(Request $request ,Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $payment = $request->query('payment', '');

        return view('orders.purchase',[
            'transaction' => $transaction,
            'item' => $transaction->item,
            'user' => auth()->user(),
            'payment' => $payment,
        ]);
    }

    public function store(PurchaseRequest $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        if ($transaction->status === Transaction::STATUS_COMPLETED) {
            return redirect()->route('transactions.show', $transaction);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $transaction) {
            Order::firstOrCreate(
                ['transaction_id' => $transaction->id,],
                [
                    'payment_method' => $validated['payment'],
                    'address' => $validated['address'],
                ]
            );

            $transaction->update([
                'status'       => Transaction::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        });

        return redirect()->route('transactions.show', $transaction);
    }

    public function editAddress(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        return view('orders.edit_address',[
            'transaction' => $transaction,
            'item' => $transaction->item,
            'user' => auth()->user(),
        ]);
    }

    public function updateAddress(AddressRequest $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
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