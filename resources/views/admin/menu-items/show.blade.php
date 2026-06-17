@extends('admin.layout')

@section('title', 'Menu Item')

@section('content')
  <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Menu Item</p>
      <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">{{ $item->name }}</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">{{ $item->category?->name }}</p>
    </div>
    <a
      href="{{ route('admin.menu-items.edit', $item) }}"
      class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold"
    >
      Edit
    </a>
  </div>

  <div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/30 p-6 lg:col-span-2">
      <h2 class="font-serif text-lg text-tzel-cream">Details</h2>

      <dl class="mt-4 space-y-3 text-sm">
        <div class="flex justify-between gap-4">
          <dt class="text-tzel-muted">Slug</dt>
          <dd class="text-right text-tzel-sand">{{ $item->slug }}</dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-tzel-muted">Price</dt>
          <dd class="text-right text-tzel-cream">KES {{ number_format(($item->price_cents ?? 0) / 100, 0) }}</dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-tzel-muted">Active</dt>
          <dd class="text-right text-tzel-sand">{{ $item->is_active ? 'Yes' : 'No' }}</dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-tzel-muted">Featured</dt>
          <dd class="text-right text-tzel-sand">{{ $item->is_featured ? 'Yes' : 'No' }}</dd>
        </div>
      </dl>

      @if ($item->description)
        <div class="mt-6 border-t border-white/5 pt-4">
          <h3 class="text-sm font-medium text-tzel-cream">Description</h3>
          <p class="mt-2 text-sm leading-relaxed text-tzel-sand/85">{{ $item->description }}</p>
        </div>
      @endif
    </div>

    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/30 p-6">
      <h2 class="font-serif text-lg text-tzel-cream">Image</h2>
      <div class="mt-4 overflow-hidden rounded-2xl border border-white/10 bg-tzel-ink/30">
        @if ($item->image_path)
          <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="aspect-[4/3] w-full object-cover" />
        @else
          <div class="flex aspect-[4/3] items-center justify-center text-sm text-tzel-muted">No image</div>
        @endif
      </div>
    </div>
  </div>
@endsection

