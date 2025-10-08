@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase.css') }}">
@endsection

@section('content')
<div class="purchase__content">
    <div class="purchase__group">
        <div class="purchase__item">
            <div class="purchase__item-img">
                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/no_image.png') }}" alt="{{ $item->name }}">
            </div>
            <div class="purchase__item-info">
                <h2 class="purchase__item-title">{{ $item->name }}</h2>
                <p class="purchase__item-price"><span>¥</span>{{ number_format($item->price) }}</p>
            </div>
        </div>

        <!-- 支払い方法を選んだ瞬間にGET送信 -->
        <form action="{{ route('purchase.create', $item->id) }}" method="GET" id="paymentForm">
            <div class="purchase__payment">
                <label class="purchase__payment-title">支払い方法</label>
                <div class="purchase__payment-select">
                    <select name="payment" onchange="this.form.submit()">
                        <option value="" hidden {{ $payment ? '' : 'selected' }}>選択してください</option>
                        <option value="convenience_store" {{ $payment=='convenience_store' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="credit_card" {{ $payment=='credit_card' ? 'selected' : '' }}>カード払い</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="purchase__delivery">
            <div class="purchase__delivery-row">
                <h3 class="purchase__delivery-title">配送先</h3>
                <a class="purchase__delivery-link" href="{{ route('purchase.editAddress', ['item' => $item->id]) }}">変更する</a>
            </div>
            <div class="purchase__delivery-content">
                <p class="purchase__delivery-text"><span>〒</span>{{ $user->postal_code }}</p>
                <p class="purchase__delivery-address">{{ $user->address }}</p>
                <p class="purchase__delivery-text">{{ $user->building }}</p>
            </div>
        </div>
    </div>

    <div class="purchase__summary">
        <div class="purchase__summary-item">
            <p class="purchase__summary-label">商品代金</p>
            <p class="purchase__summary-value">
                <span class="purchase__summary-currency">¥</span>
                <span class="purchase__summary-number">{{ number_format($item->price) }}</span>
            </p>
        </div>
        <div class="purchase__summary-item">
            <p class="purchase__summary-label">支払い方法</p>
            <p class="purchase__summary-value">
                @if($payment === 'convenience_store')
                    コンビニ払い
                @elseif($payment === 'credit_card')
                    カード払い
                @else
                    ―
                @endif
            </p>
        </div>

        <form action="{{ route('purchase.store', $item->id) }}" method="POST">
            @csrf
            <input type="hidden" name="payment" value="{{ $payment }}">
            <button class="purchase__summary-button" type="submit">購入する</button>
        </form>
    </div>
</div>
@endsection