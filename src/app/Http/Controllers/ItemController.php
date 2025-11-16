<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {

    
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }

        if (!Auth::user()->profile_completed) {
            return redirect('/mypage/profile');
        }

    
        $likedItems = Auth::user()
            ->likes()
            ->with('item')
            ->get()
            ->pluck('item');

   
        if ($request->filled('query')) {
                $items = $likedItems->filter(function ($item) use ($request) {
                    return mb_strpos($item->name, $request->query('query')) !== false;
                })->values();
            } else {
                $items = $likedItems;
            }
        }

        else {

    
            $query = Item::query();

            if ($request->filled('query')) {
                $query->where('name', 'like', '%' . $request->query('query') . '%');
            }

        
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            $items = $query->latest()->get();          
        }

    

        return view('index', [
            'items' => $items,
            'tab' => $tab,
            'query' => $request->query('query') 
        ]);

    }


    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->load(['categories', 'comments.user', 'likes']);


        $user = Auth::user();

        $isPurchased = false;

        if ($user) {
            $isPurchased = $user->orders()->where('item_id', $item->id)->exists();
        }

        return view('items.show', compact('item', 'isPurchased'));
    }


    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $path = $request->file('image')->store('public/item_images');

        $item = new Item();
        $item->user_id = auth()->id();
        $item->image_url = str_replace('public/', '', $path); 
        $item->condition = $validated['condition'];
        $item->name = $validated['name'];
        $item->brand = $validated['brand'] ?? null;
        $item->description = $validated['description'];
        $item->price = $validated['price'];
        $item->save();

        $item->categories()->sync($validated['categories']);

        return redirect()->route('home', ['tab' => 'mylist'])
        ->with('success', '商品を出品しました！');
    }

    public function create()
    {
        $categories = \App\Models\Category::all();

        return view('items.create', compact('categories'));
    }
}
