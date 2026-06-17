<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PasswordOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->getKey()],
        ]);

        $user = $request->user();
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('status', 'Profile updated.');
    }

    public function sendOtp(Request $request, PasswordOtpService $otp): RedirectResponse
    {
        try {
            $otp->send($request->user()->email, 'profile');
        } catch (\Throwable) {
            return back()->withErrors(['otp' => 'Could not send verification code. Check email configuration.']);
        }

        return back()->with('status', 'Verification code sent to your email.');
    }

    public function updatePassword(Request $request, PasswordOtpService $otp): RedirectResponse
    {
        $data = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $request->user();

        if (! $otp->verify($user->email, $data['otp'], 'profile')) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        return redirect()
            ->route('admin.profile.edit')
            ->with('status', 'Password updated successfully.');
    }
}
