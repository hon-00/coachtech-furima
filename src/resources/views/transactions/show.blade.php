@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transactions/show.css') }}">
@endsection

@section('content')
@php
    $currentUserId = auth()->id();
    $isBuyer = ($transaction->buyer_id === $currentUserId);
    $transactionItem = $transaction->item;
    $editingId = (int) request()->query('edit_message_id', 0);
    $shouldShowReviewForm = $transaction->isCompleted() && ! $hasReviewed;
@endphp

<div class="content">
    <div class="content-side">
        <p class="content-side__title">その他の取引</p>

        @forelse($sidebarTransactions as $sidebarTransaction)
            <div class="content-side__group">
                <a class="content-side__item" href="{{ route('transactions.show', $sidebarTransaction->id) }}">
                    {{ $sidebarTransaction->item?->name ?? '（商品なし）' }}
                </a>
            </div>
        @empty
        @endforelse
    </div>

    <div class="content-main">
        <div class="content-main__header">
            <!-- 取引完了：購入者のみ + trading の時だけ -->
            <div class="content-main__header--left">
                <img class="content-main__header--icon" src="{{ $chatPartner->profile_image_url }}" alt="{{ $chatPartner->name ?? '（不明）' }}">
                <h2 class="content-title">「{{ $chatPartner->name ?? '（不明）' }}」さんとの取引画面</h2>
            </div>
            @if($isBuyer && $transaction->isTrading())
            <div class="content-button">
                <form class="content-form" method="POST" action="{{ route('transactions.complete', $transaction) }}">
                    @csrf
                    @method('PATCH')
                    <button class="form-button" type="submit">取引を完了する</button>
                </form>
            </div>
            @endif
        </div>

        <div class="content-info">
            <div class="content-info__img">
                @php
                    $img = $transactionItem->image ?? null;
                    $src = $img ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset('storage/' . $img))
                                : asset('images/no_image.png');
                @endphp
                <img src="{{ $src }}" alt="{{ $transactionItem->name }}">
            </div>
            <div class="content-info__details">
                <h3 class="content-info__title">{{ $transactionItem->name }}</h3>
                <p class="content-info__price"><span>¥</span>{{ number_format($transactionItem->price) }}<span>(税込)</span></p>
            </div>
        </div>

        <div class="content-chat">
            <div class="content-chat__messages">
                @foreach($messages as $chatMessage)
                    @php
                        $isMine = ($chatMessage->sender_id === $currentUserId);
                        $isEditing = $isMine && $transaction->isTrading() && ((int)$chatMessage->id === $editingId);
                    @endphp

                    <div class="message {{ $isMine ? 'sent' : 'received' }}">
                        <div class="message-meta {{ $isMine ? 'message-meta--sent' : 'message-meta--received' }}">
                            <img class="message-meta__icon" src="{{ $chatMessage->sender->profile_image_url }}" alt="{{ $chatMessage->sender->name ?? '（不明）' }}">
                            <span class="message-meta__name">
                                {{ $chatMessage->sender->name ?? '（不明）' }}
                            </span>
                        </div>
                        @if($isEditing)
                            @error('edit_body')
                                <p class="error">{{ $message }}</p>
                            @enderror

                            <form method="POST" action="{{ route('messages.update', [$transaction, $chatMessage]) }}?edit_message_id={{ $chatMessage->id }}">
                                @csrf
                                @method('PATCH')

                                <textarea name="edit_body" class="message-edit__text">{{ old('edit_body', $chatMessage->body) }}</textarea>
                                <button class="message-operation" type="submit">保存</button>
                                <a class="message-operation" href="{{ route('transactions.show', $transaction) }}">キャンセル</a>
                            </form>
                        @else
                            @if($chatMessage->body)
                                <p class="message-text">{{ $chatMessage->body }}</p>
                            @endif

                            @if($chatMessage->image_path)
                                <div class="message-image">
                                    <img src="{{ asset('storage/' . $chatMessage->image_path) }}" alt="message image">
                                </div>
                            @endif

                            <!-- 自分の発言だけ：編集/削除 -->
                            @if($isMine && $transaction->isTrading())
                                <div class="message-ops">
                                    <a class="message-operation" href="{{ route('transactions.show', $transaction) }}?edit_message_id={{ $chatMessage->id }}">編集</a>

                                    <form method="POST" action="{{ route('messages.destroy', [$transaction, $chatMessage]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="message-operation" type="submit">削除</button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            @if($transaction->isTrading())
                <div class="content-chat__form-wrapper">
                    @error('body')
                        <p class="error">{{ $message }}</p>
                    @enderror

                    @error('image')
                        <p class="error">{{ $message }}</p>
                    @enderror

                    <form class="chat-form" method="POST" action="{{ route('messages.store', $transaction) }}" enctype="multipart/form-data">
                    @csrf
                        @php
                            $draftKey = "message_drafts.{$currentUserId}.{$transaction->id}";
                            $draftBody = session($draftKey, '');
                        @endphp

                        <textarea class="chat-form__text" name="body" placeholder="取引メッセージを記入してください">{{ old('body', $draftBody) }}</textarea>

                        <label class="chat-form__file">
                            画像を追加
                            <input class="img" type="file" name="image" accept="image/jpeg,image/png">
                        </label>

                        <button class="chat-form__button" type="submit">
                            <img src="{{ asset('images/送信アイコン.jpg') }}" alt="送信アイコン">
                        </button>
                    </form>
                </div>
            @endif

            <!-- 取引完了後・未評価の場合のみ表示 -->
            @if($transaction->isCompleted() && ! $hasReviewed)
                <div class="review-box">
                    <p class="review-box__title">取引が完了しました。</p>
                    <p class="review-box__subtitle">今回の取引相手はどうでしたか？</p>

                    <form method="POST" action="{{ route('reviews.store', $transaction) }}">
                        @csrf
                        <div class="review-stars">
                            @for($score = 5; $score >= 1; $score--)
                                <input class="review-stars__input" type="radio" name="rating" id="rating-{{ $score }}" value="{{ $score }}">
                                <label class="review-stars__label" for="rating-{{ $score }}">
                                    <span class="review-stars__icon">★</span>
                                </label>
                            @endfor
                        </div>
                        <div class="review-submit">
                            <button class="review-submit__button" type="submit">送信する</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const textarea = document.querySelector('.chat-form__text');
    if (!textarea) return;

    const saveUrl = "{{ route('message-drafts.store', $transaction) }}";
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    let timer = null;

    const saveDraft = async () => {
        try {
            await fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body: textarea.value ?? '' }),
                credentials: 'same-origin',
            });
        } catch (error) {
            // 失敗しても無視
        }
    };

    textarea.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(saveDraft, 800);
    });
});
</script>
@endsection