@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/show.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="profile-group">
        <div class="profile-icon">
            <img src="{{ $user->profile_image_url }}" alt="アイコン">
        </div>
        <div class="profile-info">
            <p class="profile-name">{{ $user->name }}</p>
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
        </ul>
    </div>

    <div class="content-item">
        @if($page == 'sell')
            <div class="product-list">
                @forelse($listedItems as $item)
                    <div class="product-item">
                        <a href="{{ route('items.show', $item->id) }}">
                            <img class="product-img" src="{{ $item->image ? asset('storage/' . e($item->image)) : asset('images/no_image.png') }}" alt="{{ $item->name }}">
                            <p class="product-name">{{ $item->name }}</p>
                        </a>
                    </div>
                @empty
                    <p class="no-items">出品した商品はありません</p>
                @endforelse
            </div>
        @elseif($page == 'buy')
            <div class="product-list">
                @forelse($purchasedItems as $order)
                    @php $item = $order->item; @endphp
                    <div class="product-item">
                        <a href="{{ route('items.show', $item->id) }}">
                            <img class="product-img" src="{{ $item->image ? asset('storage/' . e($item->image)) : asset('images/no_image.png') }}" alt="{{ $item->name }}">
                            <p class="product-name">{{ $item->name }}</p>
                        </a>
                    </div>
                @empty
                    <p class="no-items">購入した商品はありません</p>
                @endforelse
            </div>
        @endif
    </div>

</div>
@endsection