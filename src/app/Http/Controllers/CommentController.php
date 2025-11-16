<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'item_id' => $item->id,
        ]);

        return redirect()->route('item.show', $item->id)->with('success', 'コメントを投稿しました！');
    }
}
