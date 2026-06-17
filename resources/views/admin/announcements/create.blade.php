@extends('admin.layout')

@section('title', 'New Announcement')

@section('content')
  <div class="mb-8">
    <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Announcements</p>
    <h1 class="mt-2 font-serif text-3xl text-tzel-cream">Create Announcement</h1>
  </div>

  <form method="POST" action="{{ route('admin.announcements.store') }}" class="max-w-3xl">
    @csrf

    <div class="grid gap-6 rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8">
      <div>
        <x-input-label for="subject" value="Subject" />
        <x-text-input id="subject" name="subject" type="text" class="mt-3" required value="{{ old('subject') }}" />
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
      </div>

      <div>
        <x-input-label for="audience" value="Audience" />
        <select id="audience" name="audience" class="mt-3 w-full rounded-2xl border border-white/10 bg-tzel-ink/30 px-4 py-3 text-sm text-tzel-cream focus:border-tzel-bronze/60 focus:ring focus:ring-tzel-bronze/20">
          <option value="all" @selected(old('audience') === 'all')>all</option>
          <option value="customers" @selected(old('audience') === 'customers')>customers</option>
          <option value="admins" @selected(old('audience') === 'admins')>admins</option>
        </select>
        <x-input-error :messages="$errors->get('audience')" class="mt-2" />
      </div>

      <div>
        <x-input-label for="body" value="Message" />
        <textarea id="body" name="body" rows="8" class="mt-3 w-full rounded-2xl border border-white/10 bg-tzel-ink/30 px-4 py-3 text-sm text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze/60 focus:ring focus:ring-tzel-bronze/20" required>{{ old('body') }}</textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
      </div>

      <label class="flex items-center gap-3 text-sm text-tzel-sand/80">
        <input type="checkbox" name="is_active" value="1" class="rounded border-white/20 bg-tzel-ink/30 text-tzel-bronze focus:ring-tzel-bronze/30" @checked(old('is_active')) />
        Active
      </label>

      <div class="flex flex-wrap gap-3">
        <x-primary-button>Create</x-primary-button>
        <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-tzel-espresso/40 px-6 py-3 text-xs font-semibold uppercase tracking-[0.25em] text-tzel-sand hover:border-tzel-bronze/50 hover:text-tzel-gold">Cancel</a>
      </div>
    </div>
  </form>
@endsection

