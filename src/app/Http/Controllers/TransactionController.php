<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompletedMail;
use App\Models\Review;

class TransactionController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $buyer = $request->user();
        $seller = $item->user;

        if ($buyer->id === $seller->id) {
            return redirect()->back()->with('error', '自分の商品を購入することはできません。');
        }

        if ($item->transaction()->exists()) {
            return redirect()->back()->with('error', 'この商品は既に取引中です。');
        }

        $transaction = Transaction::create([
            'item_id'   => $item->id,
            'buyer_id'  => $buyer->id,
            'seller_id' => $seller->id,
            'status'    => Transaction::STATUS_TRADING,
        ]);

        return redirect()->route('purchase.create', ['transaction' => $transaction->id]);
    }

    public function show(Transaction $transaction)
    {
        $currentUserId = auth()->id();

        if (! $this->canViewTransaction($transaction, $currentUserId)) {
            abort(403, 'この取引を閲覧する権限がありません。');
        }

        $transaction->load(['item', 'buyer', 'seller']);

        $messages = $transaction->messages()
            ->with('sender')
            ->oldest()
            ->get();

        $this->markMessagesAsRead($transaction, $currentUserId);

        // サイドバー用取引一覧の取得
        $sidebarTransactions = $this->getSidebarTransactions($currentUserId, $transaction->id);

        $chatPartner = $this->getChatPartner($transaction, $currentUserId);

        $hasReviewed = Review::query()
            ->where('transaction_id', $transaction->id)
            ->where('reviewer_id', $currentUserId)
            ->exists();

        return view('transactions.show', compact(
            'transaction',
            'messages',
            'sidebarTransactions',
            'chatPartner',
            'hasReviewed',
        ));
    }

    public function complete(Transaction $transaction)
    {
        $currentUserId = auth()->id();

        if ($transaction->buyer_id !== $currentUserId) {
            abort(403);
        }

        if (! $transaction->isTrading()) {
            return redirect()->route('transactions.show', $transaction);
        }

        $transaction->update([
            'status'       => Transaction::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        // ここで seller/buyer/item をロードしてから送る（N+1/未ロード事故防止）
        $transaction->load(['seller', 'buyer', 'item']);
        try {
            Mail::to($transaction->seller->email)->send(new TransactionCompletedMail($transaction));
        } catch (\Throwable $mailSendException) {
            \Log::error('transaction completed mail failed', [
                'transaction_id'      => $transaction->id,
                'exception_class'     => get_class($mailSendException),
                'exception_message'   => $mailSendException->getMessage(),
            ]);
        }

        return redirect()->route('transactions.show', $transaction);
    }

    private function canViewTransaction(Transaction $transaction, int $currentUserId): bool
    {
        return $transaction->buyer_id === $currentUserId
            || $transaction->seller_id === $currentUserId;
    }

    private function markMessagesAsRead(Transaction $transaction, int $currentUserId): void
    {
        $unreadMessageIds = $transaction->messages()
            ->where('sender_id', '!=', $currentUserId)
            ->whereDoesntHave('reads', function ($readQuery) use ($currentUserId) {
                $readQuery->where('user_id', $currentUserId);
            })
            ->pluck('id');

        if ($unreadMessageIds->isEmpty()) {
            return;
        }

        $now = now();

        $rows = $unreadMessageIds->map(function (int $messageId) use ($currentUserId, $now) {
            return [
                'message_id' => $messageId,
                'user_id'    => $currentUserId,
                'read_at'    => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        MessageRead::insert($rows);
    }

    private function getSidebarTransactions(int $currentUserId, int $currentTransactionId)
    {
        return Transaction::query()
            ->where(function ($transactionQuery) use ($currentUserId) {
                $transactionQuery->where('buyer_id', $currentUserId)
                    ->orWhere('seller_id', $currentUserId);
            })
            ->where('id', '!=', $currentTransactionId)
            ->with(['item:id,name'])
            ->addSelect([
                'latest_message_at' => DB::table('messages')
                    ->selectRaw('MAX(created_at)')
                    ->whereColumn('transaction_id', 'transactions.id'),
            ])
            ->orderByDesc('latest_message_at')
            ->get(['id', 'item_id']);
    }

    private function getChatPartner(Transaction $transaction, int $currentUserId)
    {
        return $transaction->buyer_id === $currentUserId
            ? $transaction->seller
            : $transaction->buyer;
    }
}