@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
<div class="content">
    <h2 class="content-title">商品の出品</h2>

    <form class="content-form" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-item">商品画像</label>
            <div class="image-upload">
                <label class="image-file" for="image">画像を選択する</label>
                <input class="image-input" id="image" type="file" name="image" accept="image/jpeg,image/png" style="display:none;">
            </div>
            @error('image')
                <div class="content-error">{{ $message }}</div>
            @enderror
        </div>

        <h3 class="form-subtitle">商品の詳細</h3>
        <div class="form-group">
            <label class="form-item">カテゴリー</label>
            <div class="category-buttons">
                @foreach(\App\Models\Category::orderBy('id')->get() as $category)
                    <input class="category-input"
                        type="checkbox"
                        id="category{{ $category->id }}"
                        name="category_id[]"
                        value="{{ $category->id }}"
                        {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}
                        style="display:none;"
                    >
                    <label class="category-button" for="category{{ $category->id }}" >
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
            @error('category_id')
                <div class="content-error">{{ $message }}</div>
            @enderror
            @error('category_id.*')
                <div class="content-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-item" for="condition">商品の状態</label>
            <div  class="form-select" >
                <select name="condition" id="condition">
                    <option value="" hidden selected>選択してください</option>
                    @foreach(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'] as $condition)
                        <option value="{{ $condition }}" {{ old('condition') == $condition ? 'selected' : '' }}>
                            {{ $condition }}
                        </option>
                    @endforeach
                </select>
                @error('condition')
                    <div class="content-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h3 class="form-subtitle">商品名と説明</h3>
        <div class="form-group">
            <label class="form-item" for="name">商品名</label>
            <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="content-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-item" for="brand">ブランド名</label>
            <input class="form-input" id="brand" type="text" name="brand" value="{{ old('brand') }}">
            @error('brand')
                <div class="content-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-item" for="description">商品の説明</label>
            <textarea class="form-input" id="description" name="description">{{ old('description') }}</textarea>
            @error('description')
                <div class="content-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-item" for="price">販売価格</label>
            <div class="price-input">
                <input class="form-input" id="price" type="text" name="price" value="{{ old('price') }}" min="0">
            </div>
            @error('price')
                <div class="content-error">{{ $message }}</div>
            @enderror
        </div>

        <button class="form-button" type="submit">出品する</button>
    </form>
</div>
@endsection