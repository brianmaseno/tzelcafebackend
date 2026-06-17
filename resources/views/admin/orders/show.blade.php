@extends('admin.layout')

@section('title', 'Order #'.$order->id)

@section('content')
  <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Order</p>
      <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">#{{ $order->id }}</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">
        {{ $order->user?->name }} — {{ $order->user?->email }}
      </p>
    </div>

    <a
      href="{{ route('admin.orders.index') }}"
      class="inline-flex items-center justify-center rounded-full border border-white/10 px-5 py-2.5 text-sm text-tzel-cream hover:border-tzel-bronze/40 hover:text-tzel-gold"
    >
      Back to Orders
    </a>
  </div>

  <div class="grid gap-6 lg:grid-cols-3">
    <section class="rounded-2xl border border-white/5 bg-tzel-espresso/30 p-6 lg:col-span-2">
      <h2 class="font-serif text-lg text-tzel-cream">Items</h2>

      <div class="mt-4 space-y-3">
        @foreach ($order->items as $item)
          <div class="flex items-center justify-between rounded-xl border border-white/5 bg-tzel-ink/30 px-4 py-3">
            <div class="min-w-0">
              <div class="truncate font-medium text-tzel-cream">{{ $item->name }}</div>
              <div class="mt-1 text-xs text-tzel-muted">
                {{ $item->quantity }} × KES {{ number_format(($item->unit_price_cents ?? 0) / 100, 0) }}
              </div>
            </div>
            <div class="shrink-0 font-semibold text-tzel-bronze">
              KES {{ number_format(($item->line_total_cents ?? 0) / 100, 0) }}
            </div>
          </div>
        @endforeach
      </div>

      <dl class="mt-6 space-y-2 border-t border-white/5 pt-4 text-sm">
        <div class="flex justify-between text-tzel-sand">
          <dt>Subtotal</dt>
          <dd>KES {{ number_format(($order->subtotal_cents ?? 0) / 100, 0) }}</dd>
        </div>
        <div class="flex justify-between text-tzel-sand">
          <dt>Discount</dt>
          <dd>- KES {{ number_format(($order->discount_cents ?? 0) / 100, 0) }}</dd>
        </div>
        <div class="flex justify-between text-tzel-sand">
          <dt>Delivery fee</dt>
          <dd>KES {{ number_format(($order->delivery_fee_cents ?? 0) / 100, 0) }}</dd>
        </div>
        <div class="flex justify-between font-semibold text-tzel-cream">
          <dt>Total</dt>
          <dd class="text-tzel-gold">KES {{ number_format(($order->total_cents ?? 0) / 100, 0) }}</dd>
        </div>
      </dl>
    </section>

    <aside class="space-y-6">
      <section class="rounded-2xl border border-white/5 bg-tzel-espresso/30 p-6">
        <h2 class="font-serif text-lg text-tzel-cream">Status</h2>
        <p class="mt-2 text-sm text-tzel-muted">Update fulfillment state.</p>

        <form class="mt-4 space-y-4" method="POST" action="{{ route('admin.orders.update', $order) }}">
          @csrf
          @method('PATCH')

          <div>
            <label class="mb-1.5 block text-sm text-tzel-sand" for="status">Order status</label>
            <select
              id="status"
              name="status"
              class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze"
            >
              @php
                $statuses = ['pending', 'paid', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'];
              @endphp
              @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected($order->status === $status)>
                  {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
              @endforeach
            </select>
          </div>

          <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-full bg-tzel-bronze px-5 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold"
          >
            Save
          </button>
        </form>
      </section>

      <section class="rounded-2xl border border-white/5 bg-tzel-espresso/30 p-6">
        <h2 class="font-serif text-lg text-tzel-cream">Delivery</h2>

        <dl class="mt-4 space-y-3 text-sm">
          <div class="flex justify-between gap-4">
            <dt class="text-tzel-muted">Type</dt>
            <dd class="text-right text-tzel-cream">{{ $order->order_type }}</dd>
          </div>
          <div class="space-y-1">
            <dt class="text-tzel-muted">Drop-off location</dt>
            <dd class="text-tzel-cream">{{ $order->dropoff_location ?? '—' }}</dd>
          </div>
          <div class="space-y-1">
            <dt class="text-tzel-muted">Notes</dt>
            <dd class="text-tzel-cream">{{ $order->notes ?? '—' }}</dd>
          </div>
          <div class="flex justify-between gap-4">
            <dt class="text-tzel-muted">Payment</dt>
            <dd class="text-right text-tzel-cream">{{ $order->payment_status }}</dd>
          </div>
          <div class="space-y-1">
            <dt class="text-tzel-muted">Paystack reference</dt>
            <dd class="break-all font-mono text-xs text-tzel-sand">{{ $order->paystack_reference ?? '—' }}</dd>
          </div>
        </dl>
      </section>
    </aside>
  </div>
@endsection

