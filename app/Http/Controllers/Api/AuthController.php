<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PasswordOtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        $token = $user->createToken('tzel-web')->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => $this->userPayload($user),
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 422);
        }

        $token = $user->createToken('tzel-web')->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => $this->userPayload($user),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['data' => ['ok' => true]]);
    }

    public function sendPasswordOtp(Request $request, PasswordOtpService $otp): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (! User::query()->where('email', $data['email'])->exists()) {
            return response()->json(['message' => 'We could not find an account with that email.'], 422);
        }

        try {
            $otp->send($data['email'], 'reset');
        } catch (\Throwable) {
            return response()->json(['message' => 'Could not send verification code.'], 500);
        }

        return response()->json(['data' => ['message' => 'Verification code sent.']]);
    }

    public function resetPasswordWithOtp(Request $request, PasswordOtpService $otp): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (! $otp->verify($data['email'], $data['otp'], 'reset')) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 422);
        }

        User::query()
            ->where('email', $data['email'])
            ->update(['password' => Hash::make($data['password'])]);

        return response()->json(['data' => ['message' => 'Password reset successfully.']]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->getKey()],
        ]);

        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        return response()->json(['data' => $this->userPayload($user->fresh())]);
    }

    public function sendProfileOtp(Request $request, PasswordOtpService $otp): JsonResponse
    {
        try {
            $otp->send($request->user()->email, 'profile');
        } catch (\Throwable) {
            return response()->json(['message' => 'Could not send verification code.'], 500);
        }

        return response()->json(['data' => ['message' => 'Verification code sent.']]);
    }

    public function updatePassword(Request $request, PasswordOtpService $otp): JsonResponse
    {
        $data = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        if (! $otp->verify($user->email, $data['otp'], 'profile')) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 422);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        return response()->json(['data' => ['message' => 'Password updated.']]);
    }

    public static function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'reward_points' => (int) ($user->reward_points ?? 0),
        ];
    }
}
