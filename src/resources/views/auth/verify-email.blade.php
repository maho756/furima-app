@extends('layouts.app')

@section('content')
<div class="verify__content">
    <p class="verify__message">
        ご登録のメールアドレス宛に認証メールを送信しました。<br>
        メール内のリンクをクリックして認証を完了してください。
    </p>

    <div class="verify__actions">
        
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">
                認証メールを再送する
            </button>
        </form>

        <a href="{{ route('verification.notice') }}" onclick="alert('認証メールに記載のリンクをクリックしてください。'); return false;" class="btn">
        認証はこちらから
        </a>
    </div>
</div>
@endsection
