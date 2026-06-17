<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'TZEL Customer',
                'password' => Str::password(32),
                'is_admin' => false,
            ]
        );

        $token = $user->createToken('tzel-google')->plainTextToken;
        $frontend = rtrim((string) env('FRONTEND_URL', 'http://localhost:5173'), '/');

        return redirect("{$frontend}/auth/callback?token=" . urlencode($token));
    }

    /** Legacy web session login for Breeze (redirects admins to admin). */
    public function webCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: 'TZEL Customer',
                'password' => Str::password(32),
            ]
        );

        Auth::login($user, true);

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
