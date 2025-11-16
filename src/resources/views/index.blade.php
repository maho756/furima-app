@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item-list__content">
    <div class="item-list__tabs">
        <a href="{{ url('/') }}" class="item-list__tab {{ request('tab', 'recommend') === 'recommend' ? 'item-list__tab--active' : '' }}">
            おすすめ
        </a>
        <a href="/?tab=mylist&query={{ request('query') }}" class="item-list__tab {{ request('tab') === 'mylist' ? 'item-list__tab--active' : '' }}">
            マイリスト
        </a>

    </div>

    <div class="item-list__grid">
        @forelse($items as $item)
        <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card">      
            <div class="item-card__image-wrapper">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-card__image">

                @if($item->sold_out)
                <img src="{{ asset('images/sold_out.png') }}" alt="SOLD OUT" class="item-card__sold-overlay">
                @endif
            </div>
            <div class="item-card__name">
                    {{ $item->name }}
            </div>
        </a>
        @empty
            <p>まだ{{ request('tab') === 'mylist' ? 'マイリストに商品が登録されていません。' : '商品がありません。' }}</p>
        @endforelse
    </div>
</div>
@endsection