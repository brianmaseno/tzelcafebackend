@extends('admin.layout')

@section('title', 'New Promotion')

@section('content')
  <div class="mb-8">
    <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Promotions</p>
    <h1 class="mt-2 font-serif text-3xl text-tzel-cream">Create Promotion</h1>
  </div>

  <form method="POST" action="{{ route('admin.promotions.store') }}" class="max-w-3xl">
    @csrf

    <div class="grid gap-6 rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8">
      <div class="grid gap-6 sm:grid-cols-2">
        <div>
          <x-input-label for="name" value="Name" />
          <x-text-input id="name" name="name" type="text" class="mt-3" required value="{{ old('name') }}" />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="code" value="Code" />
          <x-text-input id="code" name="code" type="text" class="mt-3" required value="{{ old('code') }}" />
          <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>
      </div>

      <div class="grid gap-6 sm:grid-cols-2">
        <div>
          <x-input-label for="type" value="Type" />
          <select id="type" name="type" class="mt-3 w-full rounded-2xl border border-white/10 bg-tzel-ink/30 px-4 py-3 text-sm text-tzel-cream focus:border-tzel-bronze/60 focus:ring focus:ring-tzel-bronze/20">
            <option value="percent" @selected(old('type') === 'percent')>percent</option>
            <option value="fixed" @selected(old('type') === 'fixed')>fixed (cents)</option>
          </select>
          <x-input-error :messages="$errors->get('type')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="value" value="Value" />
          <x-text-input id="value" name="value" type="number" class="mt-3" required value="{{ old('value') }}" />
          <p class="mt-2 text-xs text-tzel-muted">Percent = 10 for 10%. Fixed = amount in cents.</p>
          <x-input-error :messages="$errors->get('value')" class="mt-2" />
        </div>
      </div>

      <div class="grid gap-6 sm:grid-cols-3">
        <div>
          <x-input-label for="usage_limit" value="Usage limit (optional)" />
          <x-text-input id="usage_limit" name="usage_limit" type="number" class="mt-3" value="{{ old('usage_limit') }}" />
          <x-input-error :messages="$errors->get('usage_limit')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="starts_at" value="Starts at (optional)" />
          <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-3" value="{{ old('starts_at') }}" />
          <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="ends_at" value="Ends at (optional)" />
          <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-3" value="{{ old('ends_at') }}" />
          <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
        </div>
      </div>

      <label class="flex items-center gap-3 text-sm text-tzel-sand/80">
        <input type="checkbox" name="is_active" value="1" class="rounded border-white/20 bg-tzel-ink/30 text-tzel-bronze focus:ring-tzel-bronze/30" @checked(old('is_active')) />
        Active
      </label>

      <div class="flex flex-wrap gap-3">
        <x-primary-button>Create</x-primary-button>
        <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-tzel-espresso/40 px-6 py-3 text-xs font-semibold uppercase tracking-[0.25em] text-tzel-sand hover:border-tzel-bronze/50 hover:text-tzel-gold">Cancel</a>
      </div>
    </div>
  </form>
@endsection

