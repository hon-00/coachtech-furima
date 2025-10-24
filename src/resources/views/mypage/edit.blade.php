@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/edit.css') }}">
@endsection

@section('content')
<div class="content">
    <h2 class="content-title">プロフィール設定</h2>

    <form class="content-form" action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="icon-group">
            <img class="profile-icon" src="{{ $user->profile_image_url }}" alt="プロフィール画像">
            <div class="icon-upload">
                <label class="icon-file" for="profile_image">画像を選択する</label>
                <input class="icon-input" id="profile_image" type="file" name="profile_image" accept="image/*" style="display:none;">
            </div>

            @if ($errors->has('profile_image'))
            <div class="content-error">
                <ul>
                    @foreach ($errors->get('profile_image') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-item" for="name">ユーザー名</label>
            <input class="form-input" id="name" type="text" name="name" value="{{ old('name', $user->name) }}">
            @if ($errors->has('name'))
            <div class="content-error">
                <ul>
                    @foreach ($errors->get('name') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-item" for="postal_code">郵便番号</label>
            <input class="form-input" id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            @if ($errors->has('postal_code'))
            <div class="content-error">
                <ul>
                    @foreach ($errors->get('postal_code') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-item" for="address">住所</label>
            <input class="form-input" id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
            @if ($errors->has('address'))
            <div class="content-error">
                <ul>
                    @foreach ($errors->get('address') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-item" for="building">建物名</label>
            <input class="form-input" id="building" type="text" name="building" value="{{ old('building', $user->building) }}">
            @if ($errors->has('building'))
            <div class="content-error">
                <ul>
                    @foreach ($errors->get('building') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <button class="form-button" type="submit">更新する</button>
    </form>
</div>
@endsection