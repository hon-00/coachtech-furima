@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tab-group">
    <ul class="tab-list">
        <li class="tab-item">
            <a class="tab-link {{ $tab === 'recommend' ? 'active' : '' }}" href="{{ url('/') }}{{ $keyword ? '?keyword=' . $keyword : '' }}">
                おすすめ
            </a>
        </li>
        <li class="tab-item">
            <a class="tab-link {{ $tab === 'mylist' ? 'active' : '' }}" href="{{ url('/?tab=mylist') }}{{ $keyword ? '&keyword=' . $keyword : '' }}">
                マイリスト
            </a>
        </li>
    </ul>
</div>

<div class="content-item">
    @if($tab === 'mylist')
        @auth
            @if($items->isEmpty())
                <p>お気に入り登録した商品はありません。</p>
            @else
                <div class="product-list">
                    @foreach($items as $item)
                        <div class="product-item">
                            <a href="{{ route('items.show', $item->id) }}">
                                <img class="product-img" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                <p class="product-name">{{ $item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <p>マイリストを見るにはログインが必要です。</p>
        @endauth
    @else
        <div class="product-list">
            @foreach ($items as $item)
                <div class="product-item">
                    <a href="{{ route('items.show', $item->id) }}">
                        <img class="product-img" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                        <p class="product-name">{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection