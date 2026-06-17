@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
  <div class="mb-8">
    <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Users</p>
    <h1 class="mt-2 font-serif text-3xl text-tzel-cream">Edit User</h1>
    <p class="mt-2 text-sm text-tzel-sand/80 font-mono">{{ $user->email }}</p>
  </div>

  <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-3xl">
    @csrf
    @method('PUT')

    <div class="grid gap-6 rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8">
      <div class="grid gap-6 sm:grid-cols-2">
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

      <div class="grid gap-6 sm:grid-cols-2">
        <div>
          <x-input-label for="password" value="New password (optional)" />
          <x-text-input id="password" name="password" type="password" class="mt-3" />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="password_confirmation" value="Confirm new password" />
          <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-3" />
        </div>
      </div>

      <label class="flex items-center gap-3 text-sm text-tzel-sand/80">
        <input type="checkbox" name="is_admin" value="1" class="rounded border-white/20 bg-tzel-ink/30 text-tzel-bronze focus:ring-tzel-bronze/30" @checked(old('is_admin', $user->is_admin)) />
        Admin user
      </label>

      <div class="flex flex-wrap gap-3">
        <x-primary-button>Save</x-primary-button>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-tzel-espresso/40 px-6 py-3 text-xs font-semibold uppercase tracking-[0.25em] text-tzel-sand hover:border-tzel-bronze/50 hover:text-tzel-gold">Back</a>
      </div>
    </div>
  </form>
@endsection

