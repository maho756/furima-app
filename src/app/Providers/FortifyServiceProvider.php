<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Responses\CustomRegisterResponse;
use Laravel\Fortify\Features;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use App\Http\Responses\CustomVerifiedRedirectResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->app->singleton(VerifyEmailResponse::class, CustomVerifiedRedirectResponse::class);

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);

        Fortify::createUsersUsing(CreateNewUser::class);
        
        Fortify::registerView(function (){
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        Fortify::authenticateUsing(function (Request $request){

            $formRequest = app(LoginRequest::class);
            $formRequest->setContainer(app())->merge($request->only(['email', 'password']));
            $validated = $formRequest->validate($formRequest->rules(), $formRequest->messages());

            // $formRequest->setContainer(app())->validateResolved(); 
            // $validated = $formRequest->validated();


            if (Auth::attempt([
                'email' => $validated['email'],
                'password' => $validated['password'],
            ])) {
                return Auth::user();
            }

            throw ValidationException::withMessages([
                'login' => ['ログイン情報が登録されていません'],
            ]);
        });

    }

    protected function loggedOut(Request $request)
    {
        return redirect('/login');
    }

}
