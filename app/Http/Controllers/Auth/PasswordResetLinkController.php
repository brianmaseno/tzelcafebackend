<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PasswordOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request, PasswordOtpService $otp): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->string('email')->toString();

        if (! User::query()->where('email', $email)->exists()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not find an account with that email address.']);
        }

        try {
            $otp->send($email, 'reset');
        } catch (\Throwable) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Could not send verification code. Please try again later.']);
        }

        return redirect()
            ->route('password.otp.reset', ['email' => $email])
            ->with('status', 'We emailed you a 6-digit verification code.');
    }
}
