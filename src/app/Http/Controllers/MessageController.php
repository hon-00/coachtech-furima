<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Transaction;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request, Transaction $transaction)
    {
        $userId = (int) $request->user()->id;

        if ((int) $transaction->buyer_id !== $userId && (int) $transaction->seller_id !== $userId) {
            abort(403, 'この取引にアクセスできません。');
        }

        $this->assertTransactionActive($transaction, '取引完了後はメッセージを送信できません。');

        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('message_images', 'public');
        }

        Message::create([
            'transaction_id' => $transaction->id,
            'sender_id'      => $userId,
            'body'           => $validated['body'],
            'image_path'     => $imagePath,
        ]);

        session()->forget("message_drafts.{$userId}.{$transaction->id}");

        return redirect()->route('transactions.show', $transaction);
    }

    public function update(UpdateMessageRequest $request, Transaction $transaction, Message $message)
    {
        if ((int) $message->transaction_id !== (int) $transaction->id) {
            abort(404);
        }

        $this->assertTransactionActive($transaction, '取引完了後はメッセージを編集できません。');

        $validated = $request->validated();

        $message->update([
            'body' => $validated['edit_body'],
        ]);

        return redirect()->route('transactions.show', $transaction);
    }

    public function destroy(Transaction $transaction, Message $message)
    {
        $this->assertMessageEditable($transaction, $message);
        $this->assertTransactionActive($transaction, '取引完了後はメッセージを削除できません。');

        $message->delete();

        return redirect()->route('transactions.show', $transaction);
    }

    private function assertMessageEditable(Transaction $transaction, Message $message): void
    {
        if ((int) $message->transaction_id !== (int) $transaction->id) {
            abort(404);
        }

        $userId = (int) auth()->id();

        if ((int) $transaction->buyer_id !== $userId && (int) $transaction->seller_id !== $userId) {
            abort(403, 'この取引にアクセスできません。');
        }

        if ((int) $message->sender_id !== $userId) {
            abort(403, '自分のメッセージ以外は操作できません。');
        }
    }

    private function assertTransactionActive(Transaction $transaction, string $actionMessage): void
    {
        if ($transaction->isCompleted()) {
            abort(403, $actionMessage);
        }
    }
}