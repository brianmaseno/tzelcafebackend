<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordOtpService
{
    public function __construct(private BrevoService $brevo) {}

    public function send(string $email, string $purpose = 'reset'): void
    {
        $code = (string) random_int(100000, 999999);

        DB::table('password_otps')
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->delete();

        DB::table('password_otps')->insert([
            'email' => $email,
            'code' => Hash::make($code),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(15),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::query()->where('email', $email)->first();
        $name = $user?->name ?? 'there';

        $subject = 'TZEL CAFÉ — Your verification code';
        $html = view('emails.password-otp', [
            'name' => $name,
            'code' => $code,
            'purpose' => $purpose,
        ])->render();

        $this->brevo->sendTransactional(
            [['email' => $email, 'name' => $name]],
            $subject,
            $html,
            "Your TZEL CAFÉ verification code is: {$code}. It expires in 15 minutes."
        );
    }

    public function verify(string $email, string $code, string $purpose = 'reset'): bool
    {
        $row = DB::table('password_otps')
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->where('expires_at', '>', now())
            ->orderByDesc('id')
            ->first();

        if (! $row || ! Hash::check($code, $row->code)) {
            return false;
        }

        DB::table('password_otps')
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->delete();

        return true;
    }
}
