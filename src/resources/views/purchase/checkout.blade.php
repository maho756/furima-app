<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Stripe決済テスト</title>
</head>
<body>
    <h1>商品購入ページ</h1>
    <form action="{{ route('purchase.checkout') }}" method="POST">
        @csrf
        <button type="submit">購入する</button>
    </form>
</body>
</html>
