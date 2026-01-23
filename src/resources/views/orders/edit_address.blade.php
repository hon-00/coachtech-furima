@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/orders/edit_address.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="content-title">住所の変更</h1>

    <form class="content-form" action="{{ route('purchase.updateAddress', ['transaction' => $transaction->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-item" for="postal_code">郵便番号</label>
            <input class="form-input" id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
        </div>
        @if ($errors->has('postal_code'))
        <div class="content-error">
            <ul>
                @foreach ($errors->get('postal_code') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-group">
            <label class="form-item" for="address">住所</label>
            <input class="form-input" id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>
        @if ($errors->has('address'))
        <div class="content-error">
            <ul>
                @foreach ($errors->get('address') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-group">
            <label class="form-item" for="building">建物名</label>
            <input class="form-input" id="building" type="text" name="building" value="{{ old('building', $user->building) }}">
        </div>
        @if ($errors->has('building'))
        <div class="content-error">
            <ul>
                @foreach ($errors->get('building') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <button class="form-button" type="submit">変更する</button>
    </form>
</div>
@endsection