<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Purchases;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('mypage.profile');
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $user -> update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'profile_completed' => true,
        ]);

        if ($request->hasFile('avatar')) {
            
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)){
                \Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        return redirect()->route('home', ['tab' => 'mylist'])->with('status', 'プロフィールを更新しました。');
    }


}
