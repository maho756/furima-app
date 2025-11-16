@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="item-detail__content">
    <!-- 商品画像 -->
    <div class="item-card__image-wrapper">
        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-card__image">

        @if($item->sold_out)
        <img src="{{ asset('images/sold_out.png') }}" alt="SOLD OUT" class="item-card__sold-overlay">
        @endif
    </div>

    <!-- 商品情報 -->
    <div class="item-detail__info">
        <h2 class="item-detail__name">{{ $item->name }}</h2>

        @if($item->brand)
        <p class="item-detail__brand">{{ $item->brand }}</p>
        @else
        <p class="item-detail__brand">ブランド情報なし</p>
        @endif


        <p class="item-detail__price">¥{{ number_format($item->price) }} <span>(税込)</span></p>

        <!-- アイコン：いいね & コメント -->
        <div class="item-detail__icons">
            <span class="icon-like" data-item-id="{{ $item->id }}">
                <img src="{{ $item->likes->contains('user_id', Auth::id()) ? asset('images/liked.png') : asset('images/like.png') }}" alt="いいね数" class="like-icon"> <div class="icon-count"> 
                    {{ $item->likes->count() }}
                </div>
            </span>
            <span class="icon-comment">
                <img src="{{ asset('images/comment.png') }}" alt="コメント数">
                <div class="icon-count">
                    {{ $item->comments->count() }}
                </div>
            </span>
        </div>


        <!-- 購入ボタン -->
        <div class="item-detail__purchase">
            @if ($item->sold_out || $isPurchased)
                <p class="btn-purchase">この商品は購入済みです</p>
            @else
                <a href="{{ route('purchase.index', $item->id) }}" class="btn-purchase">購入手続きへ</a>
            @endif

           
        </div>

        <!-- 商品説明 -->
        <div class="item-detail__description">
            <h3>商品説明</h3>
            <p>{{ $item->description }}</p>
        </div>

        <!-- 商品の情報 -->
        <div class="item-detail__extra">
            <h3>商品の情報</h3>
            <p>
                <strong>カテゴリー</strong>
                @foreach($item->categories as $category)
                    <span class="tag">{{ $category->name }}</span>
                @endforeach
            </p>
            <p>
                <strong>商品の状態</strong> <span class="condition">{{ $item->condition }}</span>
            </p>
        </div>

        <!-- コメント一覧 -->
        <div class="item-detail__comments">
            <h3>コメント ({{ $item->comments->count() }})</h3>
            @forelse($item->comments as $comment)
            <div class="comment">
                <div class="comment__user">
                    <strong>{{ $comment->user->name }}</strong>
                </div>
                <div class="comment__content">
                    {{ $comment->content }}
                </div>
            </div>
            @empty
            <p>コメントはまだありません。</p>
            @endforelse
        </div>

        <!-- コメント投稿フォーム -->
        @auth
        <div class="item-detail__comment-form">
            <form action="{{ route('comments.store', $item->id) }}" method="POST">
                @csrf
                <textarea name="content" rows="3"></textarea>
                @error('content')
                <div class="error-message">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-comment">コメントを送信する</button>
            </form>
        </div>
        @endauth
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const likeIcon = document.querySelector('.icon-like');
    if (!likeIcon) return;

    const itemId = likeIcon.dataset.itemId;
    const img = likeIcon.querySelector('img');
    const countEl = likeIcon.querySelector('.icon-count');

    likeIcon.addEventListener('click', async () => {
        const isLiked = img.src.includes('liked.png');
        const method = isLiked ? 'DELETE' : 'POST';
        const url = `/item/${itemId}/like`;

        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });

        if (response.ok) {
            const data = await response.json();

          
            img.src = isLiked
                ? "{{ asset('images/like.png') }}"
                : "{{ asset('images/liked.png') }}";

            
            countEl.textContent = data.count;
        }
    });
});
</script>

@endsection
