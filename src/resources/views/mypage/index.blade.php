@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage__content">
    <div class="mypage__header">
        <div class="mypage__icon">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" class="profile-avatar">
        </div>
        <div class="mypage__name">{{ Auth::user()->name }}</div>
        <a href="{{ route('mypage.profile') }}" class="mypage__edit-btn">プロフィールを編集</a>
    </div>

    {{-- タブ切り替え --}}
    <div class="mypage__tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="mypage__tab {{ $page === 'sell' ? 'mypage__tab--active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="mypage__tab {{ $page === 'buy' ? 'mypage__tab--active' : '' }}">購入した商品</a>
    </div>

    {{-- 商品一覧 --}}
    <div class="mypage__grid">
        @foreach($items as $item)
            @php
                $product = $item;
                $imagePath = Str::startsWith($product->image_url, 'http')
                    ? $product->image_url
                    : asset('storage/' . $product->image_url);
            @endphp

            @if($product)
            <a href="{{ route('item.show', ['item_id' => $product->id]) }}" class="item-card">
                <div class="item-card__image-wrapper">
                    <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="item-card__image">
                
                    @if($product->sold_out)
                        <img src="{{ asset('images/sold_out.png') }}" alt="SOLD OUT" class="item-card__sold-overlay">
                    @endif
                </div>
                <div class="item-card__name">
                    {{ $product->name }}
                </div>
            </a>
            @endif
        @endforeach
    </div>
</div>
@endsection
