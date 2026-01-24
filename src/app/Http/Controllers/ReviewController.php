<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Transaction $transaction): RedirectResponse
    {
        $userId = auth()->id();

        // 取引当事者以外は不可
        $isBuyer  = ($transaction->buyer_id === $userId);
        $isSeller = ($transaction->seller_id === $userId);

        if (! $isBuyer && ! $isSeller) {
            abort(403, 'この取引を評価する権限がありません。');
        }

        if (! $transaction->isCompleted()) {
            return redirect()
                ->route('transactions.show', $transaction)
                ->with('error', '取引完了後に評価してください。');
        }

        $revieweeId = $isBuyer ? $transaction->seller_id : $transaction->buyer_id;

        $already = Review::query()
            ->where('transaction_id', $transaction->id)
            ->where('reviewer_id', $userId)
            ->exists();

        if ($already) {
            return redirect()
                ->route('transactions.show', $transaction)
                ->with('error', 'この取引は既に評価済みです。');
        }

        $validated = $request->validated();

        Review::create([
            'transaction_id' => $transaction->id,
            'reviewer_id'    => $userId,
            'reviewee_id'    => $revieweeId,
            'rating'         => $validated['rating'],
        ]);

        return redirect()
            ->route('home')
            ->with('success', '評価を送信しました。');
    }
}