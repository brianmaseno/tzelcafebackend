<x-guest-layout>
    <div class="mb-4 text-sm text-tzel-sand/80">
        Enter the 6-digit code we sent to your email, then choose a new password.
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.otp.store') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-3 block w-full" type="email" name="email" :value="old('email', $email)" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="otp" value="Verification code" />
            <x-text-input id="otp" class="mt-3 block w-full font-mono tracking-[0.5em]" type="text" name="otp" inputmode="numeric" maxlength="6" required placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="New password" />
            <x-text-input id="password" class="mt-3 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm password" />
            <x-text-input id="password_confirmation" class="mt-3 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('password.request') }}" class="text-sm text-tzel-bronze hover:text-tzel-gold">Resend code</a>
            <x-primary-button>Reset password</x-primary-button>
        </div>
    </form>
</x-guest-layout>
