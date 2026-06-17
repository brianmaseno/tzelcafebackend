<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PasswordOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class OtpPasswordResetController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password-otp', [
            'email' => $request->query('email', old('email', '')),
        ]);
    }

    public function store(Request $request, PasswordOtpService $otp): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (! $otp->verify($data['email'], $data['otp'], 'reset')) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        User::query()
            ->where('email', $data['email'])
            ->update(['password' => Hash::make($data['password'])]);

        return redirect()
            ->route('login')
            ->with('status', 'Password reset successfully. You can now sign in.');
    }
}
