@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/show.css') }}">
@endsection

@section('content')
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<div class="content">
    <div class="profile-group">
        <div class="profile-icon">
            <img src="{{ $user->profile_image_url }}" alt="アイコン">
        </div>
        <div class="profile-info">
            <div class="profile-text">
                <p class="profile-name">{{ $user->name }}</p>

                @if ($averageRating !== null)
                    <div class="profile-rating">
                        <span class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="rating-star {{ $i <= $averageRating ? 'is-filled' : 'is-empty' }}">★</span>
                            @endfor
                        </span>
                    </div>
                @endif
            </div>
            <a class="profile-edit__button" href="{{ route('mypage.edit') }}">プロフィールを編集</a>
        </div>
    </div>

    @php
        $page = request()->get('page', 'sell');
    @endphp

    <div class="tab-group">
        <ul class="tab-list">
            <li class="tab-item">
                <a class="tab-link {{ $page == 'sell' ? 'active' : '' }}" href="{{ route('mypage.show', ['page' => 'sell']) }}">出品した商品</a>
            </li>
            <li class="tab-item">
                <a class="tab-link {{ $page == 'buy' ? 'active' : '' }}" href="{{ route('mypage.show', ['page' => 'buy']) }}" >購入した商品</a>
            </li>
            <li class="tab-item">
                <a class="tab-link {{ $page == 'trading' ? 'active' : '' }}" href="{{ route('mypage.show' , ['page' => 'trading']) }}" >取引中の商品
                @if ($unreadTotal > 0)
                    <span class="tab-item__unread-count">{{ $unreadTotal }}</span>
                @endif
                </a>
            </li>
        </ul>
    </div>

    <div class="content-item">
        @php
            $imageSrc = function ($img) {
                if (! $img) return asset('images/no_image.png');
                return filter_var($img, FILTER_VALIDATE_URL)
                    ? $img : asset('storage/' . $img);
            };
        @endphp

        @if($page == 'sell')
            <div class="product-list">
                @forelse($listedItems as $item)
                    @php
                        $transaction = $item->transaction;
                    @endphp
                    <div class="product-item">
                        @if($transaction)
                            <!-- 取引あり：取引詳細へ -->
                            <a href="{{ route('transactions.show', $transaction->id) }}">
                                <img class="product-img" src="{{ $imageSrc($item->image) }}" alt="{{ $item->name }}">
                                <p class="product-name">{{ $item->name }}</p>
                            </a>

                            @if($transaction->isTrading())
                                <span class="trading-label">取引中</span>
                            @elseif($transaction->isCompleted())
                                <span class="sold-label">Sold</span>
                            @endif
                        @else
                            <!-- 取引なし：商品詳細へ -->
                            <a href="{{ route('items.show', $item->id) }}">
                                <img class="product-img" src="{{ $imageSrc($item->image) }}" alt="{{ $item->name }}">
                                <p class="product-name">{{ $item->name }}</p>
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="no-items">出品した商品はありません</p>
                @endforelse
            </div>
        @elseif($page == 'buy')
            <div class="product-list">
                @forelse($purchasedTransactions as $transaction)
                @php $item = $transaction->item; @endphp
                    <div class="product-item">
                        <a href="{{ route('transactions.show', $transaction->id) }}">
                            <img class="product-img" src="{{ $imageSrc($item?->image) }}" alt="{{ $item?->name ?? '（商品なし）' }}">
                            <p class="product-name">{{ $item?->name ?? '（商品なし）' }}</p>
                        </a>
                        <span class="sold-label">Sold</span>
                    </div>
                @empty
                    <p class="no-items">購入した商品はありません</p>
                @endforelse
            </div>
        @elseif($page == 'trading')
            <div class="product-list">
                @forelse($tradingTransactions as $transaction)
                    @php $item = $transaction->item; @endphp
                    <div class="product-item">
                        <a href="{{ route('transactions.show', $transaction->id) }}">
                            <img class="product-img" src="{{ $imageSrc($item?->image) }}" alt="{{ $item?->name ?? '（商品なし）' }}">
                            <p class="product-name">{{ $item?->name ?? '（商品なし）' }}</p>
                            @if(($transaction->unread_count ?? 0) > 0)
                                <span class="unread-badge">{{ $transaction->unread_count }}</span>
                            @endif
                        </a>
                    </div>
                @empty
                    <p class="no-items">
                        取引中の商品はありません
                    </p>
                @endforelse
            </div>
        @endif
    </div>
</div>
@endsection