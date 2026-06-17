@extends('admin.layout')

@section('title', 'Orders')

@section('content')
  <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Orders</p>
      <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">All Orders</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">Track and update fulfillment status.</p>
    </div>
  </div>

  <div class="overflow-hidden rounded-2xl border border-white/5 bg-tzel-espresso/30">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-white/5">
        <thead class="bg-tzel-ink/30">
          <tr class="text-left text-xs font-medium tracking-wider text-tzel-muted uppercase">
            <th class="px-6 py-4">Order</th>
            <th class="px-6 py-4">Customer</th>
            <th class="px-6 py-4">Total</th>
            <th class="px-6 py-4">Payment</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Placed</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($orders as $order)
            <tr class="hover:bg-white/5">
              <td class="px-6 py-4">
                <div class="font-medium text-tzel-cream">#{{ $order->id }}</div>
                <div class="mt-1 text-xs text-tzel-muted">{{ $order->order_type }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-tzel-cream">{{ $order->user?->name }}</div>
                <div class="mt-1 text-xs text-tzel-muted">{{ $order->user?->email }}</div>
              </td>
              <td class="px-6 py-4 text-sm text-tzel-cream">
                KES {{ number_format(($order->total_cents ?? 0) / 100, 0) }}
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-tzel-ink/40 px-3 py-1 text-xs text-tzel-sand">
                  {{ $order->payment_status }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center rounded-full border border-tzel-bronze/20 bg-tzel-bronze/10 px-3 py-1 text-xs text-tzel-sand">
                  {{ $order->status }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-tzel-muted">
                {{ optional($order->placed_at ?? $order->created_at)->format('Y-m-d H:i') }}
              </td>
              <td class="px-6 py-4 text-right">
                <a
                  href="{{ route('admin.orders.show', $order) }}"
                  class="inline-flex items-center justify-center rounded-full border border-white/10 px-4 py-2 text-sm text-tzel-cream hover:border-tzel-bronze/40 hover:text-tzel-gold"
                >
                  View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-10 text-center text-sm text-tzel-muted">
                No orders yet.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-white/5 px-6 py-4">
      {{ $orders->links() }}
    </div>
  </div>
@endsection

