@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
  <div class="mb-8">
    <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Admin</p>
    <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">Overview</h1>
    <p class="mt-2 text-sm text-tzel-sand/80">Orders, revenue, and performance at a glance.</p>
  </div>

  <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/40 p-5">
      <div class="text-sm text-tzel-muted">Total Orders</div>
      <div class="mt-2 font-serif text-3xl font-semibold text-tzel-cream">{{ $totalOrders }}</div>
    </div>
    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/40 p-5">
      <div class="text-sm text-tzel-muted">Pending / Active</div>
      <div class="mt-2 font-serif text-3xl font-semibold text-tzel-cream">{{ $pendingOrders }}</div>
    </div>
    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/40 p-5">
      <div class="text-sm text-tzel-muted">Delivered</div>
      <div class="mt-2 font-serif text-3xl font-semibold text-tzel-cream">{{ $deliveredOrders }}</div>
    </div>
    <div class="rounded-2xl border border-tzel-bronze/20 bg-gradient-to-r from-tzel-espresso to-tzel-roast p-5">
      <div class="text-sm text-tzel-sand/80">Revenue (Paid)</div>
      <div class="mt-2 font-serif text-3xl font-semibold text-tzel-gold">KES {{ number_format($revenueKes, 0) }}</div>
    </div>
  </section>

  <section class="mt-6 grid gap-4 sm:grid-cols-2">
    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/40 p-5">
      <div class="text-sm text-tzel-muted">Newsletter Subscribers</div>
      <div class="mt-2 font-serif text-3xl font-semibold text-tzel-cream">{{ $newsletterSubscribers }}</div>
    </div>
    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/40 p-5">
      <div class="text-sm text-tzel-muted">Registered Customers</div>
      <div class="mt-2 font-serif text-3xl font-semibold text-tzel-cream">{{ $registeredCustomers }}</div>
    </div>
  </section>

  <section class="mt-8 grid gap-6 lg:grid-cols-2">
    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/40 p-6">
      <div class="flex items-center justify-between">
        <h2 class="font-serif text-lg font-semibold text-tzel-cream">Orders by Status</h2>
        <div class="text-sm text-tzel-muted">Pie chart</div>
      </div>
      <div class="mt-4">
        <canvas id="ordersByStatusChart" height="220"></canvas>
      </div>
    </div>

    <div class="rounded-2xl border border-white/5 bg-tzel-espresso/40 p-6">
      <div class="flex items-center justify-between">
        <h2 class="font-serif text-lg font-semibold text-tzel-cream">Daily Revenue (last 14 days)</h2>
        <div class="text-sm text-tzel-muted">Line chart</div>
      </div>
      <div class="mt-4">
        <canvas id="dailyRevenueChart" height="220"></canvas>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const statusData = @json($ordersByStatus);
      const statusLabels = Object.keys(statusData);
      const statusCounts = Object.values(statusData);

      const pieCtx = document.getElementById('ordersByStatusChart');
      if (pieCtx) {
        new Chart(pieCtx, {
          type: 'pie',
          data: {
            labels: statusLabels,
            datasets: [{
              data: statusCounts,
              backgroundColor: [
                'rgba(197, 160, 89, 0.75)',   // bronze
                'rgba(212, 176, 106, 0.75)',  // gold
                'rgba(232, 220, 200, 0.65)',  // sand
                'rgba(61, 42, 26, 0.85)',     // roast
                'rgba(44, 31, 20, 0.85)',     // espresso
                'rgba(154, 139, 122, 0.65)',  // muted
              ],
              borderColor: 'rgba(255,255,255,0.06)',
              borderWidth: 1,
            }],
          },
          options: {
            plugins: {
              legend: { position: 'bottom', labels: { color: '#f5efe6' } },
            },
          },
        });
      }

      const revenueRows = @json($dailyRevenue);
      const days = revenueRows.map(r => r.day);
      const totals = revenueRows.map(r => (Number(r.total) || 0) / 100);

      const lineCtx = document.getElementById('dailyRevenueChart');
      if (lineCtx) {
        new Chart(lineCtx, {
          type: 'line',
          data: {
            labels: days,
            datasets: [{
              label: 'KES',
              data: totals,
              tension: 0.35,
              borderColor: 'rgba(197, 160, 89, 0.85)',
              backgroundColor: 'rgba(197, 160, 89, 0.14)',
              fill: true,
              pointRadius: 2,
            }],
          },
          options: {
            scales: {
              x: { ticks: { color: '#9a8b7a' }, grid: { color: 'rgba(255,255,255,0.06)' } },
              y: { ticks: { color: '#9a8b7a' }, grid: { color: 'rgba(255,255,255,0.06)' } },
            },
            plugins: {
              legend: { labels: { color: '#f5efe6' } },
            },
          },
        });
      }
    });
  </script>
@endsection

