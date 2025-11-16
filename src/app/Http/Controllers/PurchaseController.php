<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function index(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

    
        if ($request->has('postal_code')) {
            $address = (object)[
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ];
        } else {
        
            $address = (object)[
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building,
            ];
        }

        return view('purchase.index', compact('item', 'user', 'address'));
    }


    public function store(PurchaseRequest $request, $item_id)
    {
        $validated = $request->validated();

        $user = Auth::user();
    
        $item = Item::findOrFail($item_id);
        $paymentMethod = $validated['payment_method'];

        
        if ($item->sold_out) {
            return redirect()->back()->with('error', 'この商品はすでに購入されています。');
        }

     
        if ($user->orders()->where('item_id', $item_id)->exists()) {
            return redirect()->back()->with('error', 'この商品はすでに購入済みです。');
        }

       
        $order = Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $validated['payment_method'],
            'shipping_postal_code' => $request->postal_code ?? $user->postal_code,
            'shipping_address' => $request->address ?? $user->address,
            'shipping_building' => $request->building ?? $user->building,
        ]);

      

        $item->sold_out = true;
        $item->save();

        return redirect()->route('home', ['tab' => 'mylist'])->with('success', '購入が完了しました');

    }

    public function checkout(Request $request)
    {
        session(['item_id' => $request->item_id]);
       
        if (!$request->has('item_id')) {
            abort(404, '商品情報が見つかりません。');
        }

        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        
        $item = \App\Models\Item::find($request->item_id);
        if (!$item) {
            abort(404, '指定された商品が存在しません。');
        }

   
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success') . '?item_id=' . $item->id,

            'cancel_url' => route('purchase.cancel'),
        ]);

        // ✅ Stripeの決済ページへリダイレクト
        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        $item_id = $request->query('item_id');

        if (!$item_id) {
            return redirect()->route('home')->with('error', '商品情報が見つかりません。');
        }

        $item = Item::find($item_id);
        if (!$item) {
            return redirect()->route('home')->with('error', '指定された商品が存在しません。');
        }

    
        if (!$item->sold_out) {
            $item->sold_out = true;
            $item->save();
        }

        if ($item) {
            Order::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'payment_method' => 'クレジットカード',
                'shipping_postal_code' => Auth::user()->postal_code,
                'shipping_address' => Auth::user()->address,
                'shipping_building' => Auth::user()->building,
            ]);
        }
        return view('purchase.success');
    }


    public function cancel()
    {
        return view('purchase.cancel');
    }



}
