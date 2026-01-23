@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
<div class="item-container">
    <div class="item-img">
        @php
            $img = $item->image;
            $src = $img
                ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset('storage/' . $img))
                : asset('images/no_image.png');
        @endphp
        <img src="{{ $src }}" alt="{{ $item->name }}">

        @if($item->isSold())
            <span class="sold-label">Sold</span>
        @endif
    </div>
    <div class="item-content">
        <div class="item-info">
            <h1 class="item-title">{{ $item->name }}</h1>
            <p class="brand-title">{{ $item->brand }}</p>
            <p class="item-price">
                <span>¥</span>{{ number_format($item->price) }}<span>(税込)</span>
            </p>
            <div class="icon-group">
                @php
                    $isLiked = $item->likes->contains('user_id', auth()->id());
                @endphp
                <form action="{{ route('like.toggle', $item) }}" method="POST">
                    @csrf
                    <label class="favorite-icon">
                        <input class="favorite-toggle" type="checkbox"
                        {{ $isLiked ? 'checked' : '' }}
                        onchange="this.form.submit()">
                        <img src="{{ asset('images/星アイコン8.png') }}" alt="お気に入りアイコン">
                        <span class="favorite-count">{{ $item->likes->count() }}</span>
                    </label>
                </form>
                <a href="#comments" class="comment-icon">
                    <img src="{{ asset('images/ふきだしのアイコン (1).png') }}" alt="コメントアイコン">
                    <span class="comment-count">{{ $item->comments->count() }}</span>
                </a>
            </div>
            @if(!$item->isSold())
                <form class="purchase-form" action="{{ route('transactions.store', $item) }}" method="POST">
                    @csrf
                    <button class="purchase-button" type="submit">購入手続きへ</button>
                </form>
            @endif
        </div>
        <div class="item-detail">
            <h2 class="detail-title">商品説明</h2>
            <p class="description">{{ $item->description }}</p>
            <h2 class="detail-title">商品の情報</h2>
            <p class="category">
                カテゴリー
                @foreach($item->categories as $category)
                <span class="category-name">{{ $category->name }}</span>
                @endforeach
            </p>
            <p class="condition">
                商品の状態
                <span class="condition-name">{{ $item->condition }}</span>
            </p>
        </div>
        <div class="comment">
            <h2 class="comment-title" id="comments">コメント({{ $item->comments_count }})</h2>
            <div class="comment-list">
                @foreach($item->comments as $comment)
                <div class="comment-item">
                    <img class="user-img" src="{{ $comment->user->profile_image_url ?? asset('images/default_icon.png') }}" alt="{{ $comment->user->name }}">
                    <p class="user-name">{{ $comment->user->name }}:</p>
                    <p class="comment-text">{{ $comment->content }}</p>
                </div>
                @endforeach
            </div>
            <h3 class="form-title">商品へのコメント</h3>
            <form class="comment-form" action="{{ route('comment.store', $item->id) }}" method="post">
                @csrf
                <textarea class="form-content" name="content">{{ old('content') }}</textarea>
                @error('content')
                    <p class="error">{{ $message }}</p>
                @enderror
                <button class="form-submit" type="submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection