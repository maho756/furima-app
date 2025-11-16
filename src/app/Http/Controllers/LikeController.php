<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class LikeController extends Controller
{
    public function store($item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->likes()->create([
            'user_id' => auth()->id(),
        ]);
        return response()->json(['liked' => true, 'count' => $item->likes()->count()]);
    }

    public function destroy($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->likes()->where('user_id', auth()->id())->delete();
        return response()->json(['liked' => false, 'count' => $item->likes()->count()]);
    }

}
