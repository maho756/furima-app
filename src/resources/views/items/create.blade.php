@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="item-form__heading">
    <h2>
        商品の出品
    </h2>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="item-form__form">
        @csrf

        <div class="item-form__group">
            <label class="item-form__label">商品画像</label>
            <div class="item-form__file-wrapper">
                <label for="image" class="item-form__file-label">画像を選択する</label>
                <input type="file" name="image" id="image" class="item-form__file-input">
            </div>
            
            @error('image')
            <div class="error-message">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="item-form__section">
            <h3 class="item-form__subheading">商品の詳細</h3>

            <div class="item-form__group">
                <label class="item-form__label">カテゴリー</label>
                <div class="item-form__categories">
                    @foreach($categories as $category)
                    <input         type="checkbox" id="category-{{ $category->id }}"  name="categories[]" value="{{ $category->id }}"  class="item-form__category-checkbox" hidden>
                   <label for="category-{{ $category->id }}" class="item-form__category-tag">
                   {{ $category->name }}
                   </label>
                   @endforeach


                    @error('categories')
                    <div class="error-message">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="item-form__group">
                <label  class="item-form__label">
                    商品の状態
                </label>
               <select name="condition" class="item-form__select">
                    <option value=""    disabled selected>選択してください</option>
                    <option value="良好"  {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                @error('condition')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="item-form__section">
            <h3 class="item-form__subheading">商品名と説明</h3>

            <div class="item-form__group">
                <label  class="item-form__label">商品名</label>
                <input type="text" name="name" class="item-form__input" value="{{ old('name') }}">
                @error('name')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="item-form__group">
                <label  class="item-form__label">ブランド名</label>
                <input type="text" name="brand" class="item-form__input" value="{{ old('brand') }}"> 
                @error('brand')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="item-form__group">
                <label  class="item-form__label">商品の説明</label>
                <textarea name="description" class="item-form__textarea" rows="5">{{ old('description') }}</textarea>
                @error('description')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="item-form__group">
                <label  class="item-form__label">販売価格</label>
                <input type="number" name="price" class="item-form__input" placeholder="￥" value="{{ old('price') }}">
                @error('price')
                <div class="error-message">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="item-form__submit">
            <button type="submit" class="item-form__button">出品する</button>
        </div>        
    </form>
</div>
@endsection