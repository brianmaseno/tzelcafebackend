@extends('admin.layout')

@section('title', 'Menu Items')

@section('content')
  <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Menu</p>
      <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">Menu Items</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">Edit prices, images, and availability. Changes reflect on the website.</p>
    </div>
    <a
      href="{{ route('admin.menu-items.create') }}"
      class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold"
    >
      Add Item
    </a>
  </div>

  <div class="overflow-hidden rounded-2xl border border-white/5 bg-tzel-espresso/30">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-white/5">
        <thead class="bg-tzel-ink/30">
          <tr class="text-left text-xs font-medium tracking-wider text-tzel-muted uppercase">
            <th class="px-6 py-4">Item</th>
            <th class="px-6 py-4">Category</th>
            <th class="px-6 py-4">Price</th>
            <th class="px-6 py-4">Featured</th>
            <th class="px-6 py-4">Active</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($items as $item)
            <tr class="hover:bg-white/5">
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="h-12 w-12 overflow-hidden rounded-xl border border-white/10 bg-tzel-ink/40">
                    @if ($item->imageUrl())
                      <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy" />
                    @endif
                  </div>
                  <div class="min-w-0">
                    <div class="truncate font-medium text-tzel-cream">{{ $item->name }}</div>
                    <div class="mt-1 text-xs text-tzel-muted">{{ $item->slug }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-tzel-sand">{{ $item->category?->name }}</td>
              <td class="px-6 py-4 text-sm text-tzel-cream">KES {{ number_format(($item->price_cents ?? 0) / 100, 0) }}</td>
              <td class="px-6 py-4 text-sm text-tzel-sand">{{ $item->is_featured ? 'Yes' : 'No' }}</td>
              <td class="px-6 py-4 text-sm text-tzel-sand">{{ $item->is_active ? 'Yes' : 'No' }}</td>
              <td class="px-6 py-4 text-right">
                <a
                  href="{{ route('admin.menu-items.edit', $item) }}"
                  class="inline-flex items-center justify-center rounded-full border border-white/10 px-4 py-2 text-sm text-tzel-cream hover:border-tzel-bronze/40 hover:text-tzel-gold"
                >
                  Edit
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-sm text-tzel-muted">No menu items yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-white/5 px-6 py-4">
      {{ $items->links() }}
    </div>
  </div>
@endsection

