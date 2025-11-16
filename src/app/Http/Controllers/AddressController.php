<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit($itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        $address = (object)[
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building,
        ];
        

        return view('purchase.address-edit', compact('item', 'address'));
    }

    public function update(AddressRequest $request, $itemId)
    {
        
    $data = $request->validated();

   
    return redirect()->route('purchase.index', [
    'item_id' => $itemId,
    'postal_code' => $data['postal_code'],
    'address' => $data['address'],
    'building' => $data['building'] ?? ''
])->with('success', '配送先住所を更新しました。');

    }
}
