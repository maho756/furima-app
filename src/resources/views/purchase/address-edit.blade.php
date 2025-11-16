@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address-edit.css') }}">
@endsection

@section('content')
<div class="form__content">
    <div class="form__heading">
        <h2>住所の変更</h2>
    </div>
    <form class="form" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST">
        @csrf

        <div class="form__group">
            <div class="form__group-title">
                <label class="form__label--item">郵便番号</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="postal_code"  value="{{ old('postal_code', $address->postal_code ?? '') }}">
                </div>
                @error('postal_code')
                <div class="form__error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <label class="form__label--item">住所</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address"  value="{{ old('address', $address->address ?? '') }}">
                </div>
                @error('address')
                <div class="form__error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <label class="form__label--item">建物名</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building"  value="{{ old('building', $address->building ?? '') }}">
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection
