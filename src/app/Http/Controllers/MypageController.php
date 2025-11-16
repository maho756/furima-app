<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item; 
use App\Models\Order; 


class MypageController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $page = $request->query('page', 'sell'); 

        if ($page === 'sell') {
            $items = $user->items()->latest()->get(); 
        } elseif ($page === 'buy') {
            $orders = $user->orders()->with('item')->latest()->get();
            $items = $orders->filter(function ($order) {
                return $order->item !== null;
            })->pluck('item');
        } else {
            $items = collect(); // 空コレクションなど
        }

        return view('mypage.index', compact('items', 'page'));
    }

}