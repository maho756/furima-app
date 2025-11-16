document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.icon-like').forEach(function (likeBtn) {
        likeBtn.addEventListener('click', function () {
            const itemId = likeBtn.dataset.itemId;
            const icon = likeBtn.querySelector('img');
            const isLiked = icon.src.includes('liked.png'); // 現在の状態を判定

            const method = isLiked ? 'DELETE' : 'POST';

            fetch(`/items/${itemId}/like`, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    // アイコンの画像切り替え
                    icon.src = data.liked ? '/images/liked.png' : '/images/like.png';

                    // いいね数更新
                    likeBtn.querySelector('.icon-count').textContent = data.count;
                })
                .catch(error => {
                    console.error('通信エラー:', error);
                });
        });
    });
});

