<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;

class CustomRegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

       
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

       
        if (is_null($user->postal_code) || is_null($user->address)) {
            return redirect('/mypage/profile');
        }

        
        return redirect()->route('home', ['tab' => 'mylist']);
    }

}
