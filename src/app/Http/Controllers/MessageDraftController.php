<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MessageDraftController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $userId = $request->user()->id;

        if ($transaction->buyer_id !== $userId && $transaction->seller_id !== $userId) {
            abort(403);
        }

        if ($transaction->isCompleted()) {
            return response()->json(['ok' => true]);
        }

        $validated = $request->validate([
            'body' => ['nullable', 'string', 'max:400'],
        ]);

        $key = $this->draftKey($transaction->id, $userId);
        session()->put($key, (string)($validated['body'] ?? ''));

        return response()->json(['ok' => true]);
    }

    private function draftKey(int $transactionId, int $userId): string
    {
        return "message_drafts.{$userId}.{$transactionId}";
    }
}