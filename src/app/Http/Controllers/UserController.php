<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\Review;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $listedItems = $user->items()
            ->with('transaction')
            ->latest()
            ->get();

        $purchasedTransactions = $user->buyingTransactions()
            ->where('status', Transaction::STATUS_COMPLETED)
            ->with('item')
            ->latest()
            ->get();

        $latestMessageAtSubquery = DB::table('messages')
            ->selectRaw('MAX(created_at)')
            ->whereColumn('transaction_id', 'transactions.id');

        $tradingTransactions = Transaction::query()
            ->where('status', Transaction::STATUS_TRADING)
            ->where(function ($transactionQuery) use ($user) {
                $transactionQuery->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->with('item')
            ->withCount([
                'messages as unread_count' => function ($messageQuery) use ($user) {
                    $messageQuery->where('sender_id', '!=', $user->id)
                        ->whereDoesntHave('reads', function ($readQuery) use ($user) {
                            $readQuery->where('user_id', $user->id);
                        });
                },
            ])
            ->selectSub($latestMessageAtSubquery, 'latest_message_at')
            ->orderByDesc('latest_message_at')
            ->get();

        $unreadTotal = (int) $tradingTransactions->sum('unread_count');

        $reviewStats = Review::query()
            ->where('reviewee_id', $user->id)
            ->selectRaw('COUNT(*) as review_count, AVG(rating) as avg_rating')
            ->first();

        $reviewCount = (int) ($reviewStats->review_count ?? 0);
        $averageRating = $reviewCount > 0 ? (int) round((float) $reviewStats->avg_rating) : null;

        return view('mypage.show', compact(
            'user',
            'listedItems',
            'purchasedTransactions',
            'tradingTransactions',
            'unreadTotal',
            'averageRating',
            'reviewCount'
        ));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        return view('mypage.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')
                ->store('profile_images', 'public');
        }

        $user->update($validated);

        return redirect()->route('mypage.show')
            ->with('status', 'プロフィールを更新しました。');
    }
}