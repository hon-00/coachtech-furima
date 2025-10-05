@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')

<div class="content">
    <h2 class="content-title">会員登録</h2>
    <form class="content-form" action="{{ route('register') }}" method="post">
        @csrf
        <div class="form-group">
            <label class="form-item" for="name">ユーザー名</label>
            <input class="form-input" id="name" type="text" name="name">
        </div>
        @if ($errors->has('name'))
        <div class="content-error">
            <ul>
                @foreach ($errors->get('name') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="form-group">
            <label class="form-item" for="email">メールアドレス</label>
            <input class="form-input" id="email" type="text" name="email" value="{{ old('email') }}">
        </div>
        @if ($errors->has('email'))
        <div class="content-error">
            <ul>
                @foreach ($errors->get('email') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="form-group">
            <label class="form-item" for="password">パスワード</label>
            <input class="form-input" type="password" name="password">
        </div>
        @if ($errors->has('password'))
        <div class="content-error">
            <ul>
                @foreach ($errors->get('password') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="form-group">
            <label class="form-item" for="password_confirmation">確認用パスワード</label>
            <input class="form-input" id="password_confirmation" type="password" name="password_confirmation">
        </div>
        @if ($errors->has('password_confirmation'))
        <div class="content-error">
            <ul>
                @foreach ($errors->get('password_confirmation') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <button class="form-button" type="submit">登録する</button>
    </form>
    <a class="content-link" href="{{ route('login') }}" >ログインはこちら</a>
</div>
@endsection