@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form id="purchaseForm" action="{{ route('purchase.store', $item->id) }}"  method="POST" class="purchase__form">
    @csrf
    <input type="hidden" name="item_id" value="{{ $item->id }}">
    <div class="purchase__content">
        <div class="purchase__left">
            <div class="purchase__product">
               
                <div class="purchase__image">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                </div>

                <div class="purchase__info">
                    <h2 class="purchase__name">{{ $item->name }}</h2>
                    <p class="purchase__price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>
            <div class="purchase__section">
                <label for="payment_method" class="purchase__label">支払い方法</label>
                <select id="payment_method" name="payment_method" class="purchase__select">
                    <option value="" selected disabled>選択してください</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="クレジットカード">カード支払い</option>
                </select>
            </div>

            @if ($errors->has('payment_method'))
                <p class="form__error">{{ $errors->first('payment_method') }}</p>
            @endif
            
            <div class="purchase__section">
                <div class="purchase__label-wrapper">
                    <label class="purchase__label">配送先</label>
                    <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="purchase__edit-link">変更する</a>
                </div> 
                <p class="purchase__address">
                    〒{{ $address->postal_code ?? $user->postal_code }}<br>
                    {{ $address->address ?? $user->address }} {{ $address->building ?? '' }}
                </p>
            </div>
        </div>

        <div class="purchase__right">
            <p class="purchase__summary-row">
                <span class="purchase__summary-label">商品代金</span>
                <span class="purchase__summary-value">¥{{ number_format($item->price) }}</span>
            </p>
            <p class="purchase__summary-row">
                <span class="purchase__summary-label">支払い方法</span>
                <span class="purchase__summary-value" id="payment_display">未選択</span>
            </p>

            <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">
            <input type="hidden" name="address" value="{{ $address->address }}">
            <input type="hidden" name="building" value="{{ $address->building }}">

        

            <button type="submit" class="btn btn-purchase-submit">購入する</button>
        </div>
        

    </div>
</form>
@endsection

    
@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('payment_method');
    const display = document.getElementById('payment_display');
    const form = document.getElementById('purchaseForm');
    const submitBtn = document.querySelector('.btn-purchase-submit');

    select.addEventListener('change', function() {
        display.textContent = this.value || '未選択';
    });

    submitBtn.addEventListener('click', function(e) {
        const selected = select.value;

        
        if (!selected) {
            e.preventDefault();
            alert('支払い方法を選択してください。');
            return;
        }

        
        if (selected === 'クレジットカード') {
            e.preventDefault();

            let hidden = form.querySelector('input[name="item_id"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'item_id';
                hidden.value = "{{ $item->id }}";
                form.appendChild(hidden);
            }

            form.action = "{{ route('purchase.checkout') }}";
            form.method = 'POST';
            form.submit();
        }    

 
    });
});
</script>
@endsection

