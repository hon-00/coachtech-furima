@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tab-area">
    <ul class="tab-list">
        <li class="tab-item">
            <a class="tab-link {{ request('tab') !== 'mylist' ? 'is-active' : '' }}" href="{{ url('/') }}">
                おすすめ
            </a>
        </li>
        <li class="tab-item">
            <a class="tab-link {{ request('tab') === 'mylist' ? 'is-active' : '' }}" href="{{ url('/?tab=mylist') }}" >
                マイリスト
            </a>
        </li>
    </ul>
</div>
@if(request('tab') === 'mylist')
            @auth
                <div class="mylist-container">
                    <ul class="mylist-items">
                        <li class="mylist-item">
                            <img src="" alt="">
                            <span></span>
                        </li>
                    </ul>
                </div>
            @endauth
@else
    <div class="product-list">
        @foreach ($items as $item)
        <div class="product-card">
            <a href="{{ route('items.show', $item->id) }}">
                <img class="img-item" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                <p class="card-title">{{ $item->name }}</p>
            </a>
        </div>
        @endforeach
    </div>
@endif
@endsection