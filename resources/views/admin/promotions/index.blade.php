@extends('admin.layout')

@section('title', 'Promotions')

@section('content')
  <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Promotions</p>
      <h1 class="mt-2 font-serif text-3xl text-tzel-cream">Discounts &amp; Offers</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">Create promo codes for percent or fixed discounts.</p>
    </div>
    <a
      href="{{ route('admin.promotions.create') }}"
      class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink hover:bg-tzel-gold"
    >
      New Promotion
    </a>
  </div>

  <div class="mt-8 overflow-hidden rounded-3xl border border-white/5 bg-tzel-espresso/40">
    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="border-b border-white/5 text-xs tracking-[0.35em] text-tzel-bronze uppercase">
          <tr>
            <th class="px-6 py-4">Code</th>
            <th class="px-6 py-4">Name</th>
            <th class="px-6 py-4">Type</th>
            <th class="px-6 py-4">Value</th>
            <th class="px-6 py-4">Usage</th>
            <th class="px-6 py-4">Active</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($promotions as $promo)
            <tr class="text-tzel-sand/90">
              <td class="px-6 py-4 font-mono text-xs text-tzel-cream">{{ $promo->code }}</td>
              <td class="px-6 py-4">{{ $promo->name }}</td>
              <td class="px-6 py-4">{{ $promo->type }}</td>
              <td class="px-6 py-4">
                @if ($promo->type === 'percent')
                  {{ $promo->value }}%
                @else
                  {{ number_format($promo->value / 100, 2) }}
                @endif
              </td>
              <td class="px-6 py-4">
                {{ $promo->used_count ?? 0 }}
                @if ($promo->usage_limit)
                  / {{ $promo->usage_limit }}
                @endif
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex rounded-full border px-3 py-1 text-xs {{ $promo->is_active ? 'border-tzel-bronze/30 bg-tzel-bronze/10 text-tzel-gold' : 'border-white/10 bg-white/5 text-tzel-muted' }}">
                  {{ $promo->is_active ? 'Yes' : 'No' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <a class="text-tzel-gold hover:text-tzel-bronze" href="{{ route('admin.promotions.edit', $promo) }}">Edit</a>
                <span class="mx-2 text-white/10">|</span>
                <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}" class="inline">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-200 hover:text-red-100" onclick="return confirm('Delete this promotion?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td class="px-6 py-10 text-center text-tzel-muted" colspan="7">No promotions yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-white/5 px-6 py-4">
      {{ $promotions->links() }}
    </div>
  </div>
@endsection

