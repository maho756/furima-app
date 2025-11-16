<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Illuminate\Http\RedirectResponse;

class CustomVerifiedRedirectResponse implements VerifyEmailResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect('/mypage/profile');
    }
}
