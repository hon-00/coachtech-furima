@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="item-container">
    <div class="item-img">
        <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/no_image.png') }}" alt="{{ $item->name }}">
    </div>
    <div class="item-content">
        <div class="item-info">
            <h2 class="item-title">{{ $item->name }}</h2>
            <p class="brand-title">{{ $item->description }}</p>
            <p class="item-price">
                <span>¥</span>{{ number_format($item->price) }}<span>(税込)</span>
            </p>
            <div class="icon-group">
                <label class="favorite-icon">
                    <input type="checkbox" class="favorite-toggle">
                    <img src="{{ asset('images/星アイコン8.png') }}" alt="お気に入りアイコン">
                    <span class="favorite-count">3</span>
                </label>
                <a href="#comments" class="comment-icon">
                    <img src="{{ asset('images/ふきだしのアイコン (1).png') }}" alt="コメントアイコン">
                    <span class="comment-count">1</span>
                </a>
            </div>
            <button class="purchase-button">購入手続きへ</button>
        </div>
        <div class="item-detail">
            <h3 class="detail-title">商品説明</h3>
            <p class="description">{{ $item->description }}</p>
            <h3 class="detail-title">商品の情報</h3>
            <p class="category">
                カテゴリー
                <span class="category-name"><!--{{ $item->category }}--></span>
            </p>
            <p class="condition">
                商品の状態
                <span class="condition-name"><!--{{ $item->condition }}--></span>
            </p>
        </div>
        <div class="comment">
            <h3 class="comment-title" id="comments">コメント</h3>
            <div class="comment-list">
                @foreach($item->comments as $comment)
                <div class="comment-item">
                    <img class="user-img" src="{{ asset('images/default_icon.png') }}" alt="" >
                    <p class="user-name">{{ $comment->user->name }}:</p>
                    <p class="comment-text">{{ $comment->content }}</p>
                </div>
                @endforeach
                <!--レイアウト用-->
                <div class="comment-item">
                    <div class="comment-wrapper">
                <img class="user-img" src="{{ asset('images/default_icon.png') }}" alt="" >
                        <p class="user-name">admin</p>
                    </div>
                    <p class="comment-text">こちらにコメントが入ります。</p>
                    <!--ここまで-->
                </div>
            </div>
            <h4 class="form-title">商品へのコメント</h4>
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