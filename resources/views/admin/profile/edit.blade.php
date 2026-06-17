@extends('admin.layout')

@section('title', 'My Profile')

@section('content')
  <div class="mb-8">
    <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Account</p>
    <h1 class="mt-2 font-serif text-3xl text-tzel-cream">My Profile</h1>
    <p class="mt-2 text-sm text-tzel-sand/80">Update your name and password. Password changes require a one-time code sent to your email.</p>
  </div>

  <div class="grid w-full max-w-5xl gap-8 lg:grid-cols-2">
    <form method="POST" action="{{ route('admin.profile.update') }}" class="rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8">
      @csrf
      @method('PATCH')

      <h2 class="font-serif text-xl text-tzel-cream">Profile details</h2>
      <p class="mt-2 text-sm text-tzel-sand/80">Update your display name and email.</p>

      <div class="mt-6 space-y-5">
        <div>
          <x-input-label for="name" value="Name" />
          <x-text-input id="name" name="name" type="text" class="mt-3" required value="{{ old('name', $user->name) }}" />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="email" value="Email" />
          <x-text-input id="email" name="email" type="email" class="mt-3" required value="{{ old('email', $user->email) }}" />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
      </div>

      <div class="mt-8">
        <x-primary-button>Save profile</x-primary-button>
      </div>
    </form>

    <div class="rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8">
      <h2 class="font-serif text-xl text-tzel-cream">Change password</h2>
      <p class="mt-2 text-sm text-tzel-sand/80">
        We send a 6-digit OTP to <span class="text-tzel-gold">{{ $user->email }}</span> before updating your password.
      </p>

      <form method="POST" action="{{ route('admin.profile.send-otp') }}" class="mt-6">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-tzel-ink/30 px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.25em] text-tzel-sand transition hover:border-tzel-bronze/50 hover:text-tzel-gold">
          Send verification code
        </button>
      </form>

      <form method="POST" action="{{ route('admin.profile.password') }}" class="mt-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
          <x-input-label for="otp" value="Verification code" />
          <x-text-input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" class="mt-3 font-mono tracking-[0.5em]" required placeholder="000000" />
          <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="password" value="New password" />
          <x-text-input id="password" name="password" type="password" class="mt-3" required autocomplete="new-password" />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="password_confirmation" value="Confirm new password" />
          <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-3" required autocomplete="new-password" />
        </div>

        <x-primary-button>Update password</x-primary-button>
      </form>
    </div>
  </div>
@endsection
