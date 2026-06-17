<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin') — TZEL CAFÉ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>
    <script>
      function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden', !sidebar.classList.contains('-translate-x-full'));
      }
    </script>
  </head>
  <body class="bg-tzel-ink text-tzel-cream font-sans antialiased">
    <div id="admin-overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden" onclick="toggleAdminSidebar()" aria-hidden="true"></div>

    <div class="admin-shell flex min-h-screen w-full">
      <aside
        id="admin-sidebar"
        class="admin-sidebar fixed inset-y-0 left-0 z-40 flex w-64 shrink-0 -translate-x-full flex-col border-r border-white/5 bg-tzel-espresso transition-transform duration-200 lg:static lg:z-auto lg:translate-x-0"
      >
        <div class="border-b border-white/5 px-6 py-6">
          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <img src="/logo.png" alt="TZEL CAFÉ" class="h-10 w-10 rounded-full object-cover ring-1 ring-tzel-bronze/40" width="40" height="40" />
            <div>
              <div class="font-serif text-sm tracking-[0.2em] text-tzel-cream">TZEL</div>
              <div class="text-[10px] font-medium tracking-[0.35em] text-tzel-bronze">CAFÉ • ADMIN</div>
            </div>
          </a>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6" aria-label="Admin navigation">
          @php
            $navLink = function (string $pattern) {
                return request()->routeIs($pattern)
                    ? 'bg-tzel-bronze/10 text-tzel-gold border-tzel-bronze/30'
                    : 'text-tzel-sand/80 border-transparent hover:border-white/10 hover:bg-white/5 hover:text-tzel-gold';
            };
          @endphp

          <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.dashboard') }}">Dashboard</a>
          <a href="{{ route('admin.orders.index') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.orders.*') }}">Orders</a>
          <a href="{{ route('admin.categories.index') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.categories.*') }}">Categories</a>
          <a href="{{ route('admin.menu-items.index') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.menu-items.*') }}">Menu Items</a>
          <a href="{{ route('admin.promotions.index') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.promotions.*') }}">Promotions</a>
          <a href="{{ route('admin.announcements.index') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.announcements.*') }}">Announcements</a>
          <a href="{{ route('admin.contacts.index') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.contacts.*') }}">Contact Messages</a>
          <a href="{{ route('admin.users.index') }}" class="flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.users.*') }}">Users</a>
        </nav>

        <div class="border-t border-white/5 px-4 py-5">
          <div class="mb-4 rounded-2xl border border-white/5 bg-tzel-ink/30 px-4 py-3">
            <p class="text-xs text-tzel-muted">Signed in as</p>
            <p class="mt-1 truncate text-sm font-medium text-tzel-cream">{{ auth()->user()->name }}</p>
            <p class="truncate text-xs text-tzel-sand/70">{{ auth()->user()->email }}</p>
          </div>
          <a href="{{ route('admin.profile.edit') }}" class="mb-2 flex items-center rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $navLink('admin.profile.*') }}">My Profile</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full rounded-2xl border border-white/10 bg-tzel-ink/30 px-4 py-3 text-sm text-tzel-sand transition hover:border-tzel-bronze/50 hover:text-tzel-gold">Logout</button>
          </form>
        </div>
      </aside>

      <div class="admin-main flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 border-b border-white/5 bg-tzel-ink/80 px-4 py-4 backdrop-blur-md sm:px-8">
          <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <button type="button" class="rounded-lg border border-white/10 p-2 text-tzel-sand lg:hidden" onclick="toggleAdminSidebar()" aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
              </button>
              <p class="text-xs font-medium tracking-[0.35em] text-tzel-bronze uppercase">@yield('title', 'Admin')</p>
            </div>
            <a href="{{ route('admin.profile.edit') }}" class="truncate text-sm text-tzel-sand/80 hover:text-tzel-gold">{{ auth()->user()->name }}</a>
          </div>
        </header>

        <main class="flex-1 px-4 py-8 sm:px-8 sm:py-10">
          @if (session('status'))
            <div class="mb-6 rounded-2xl border border-tzel-bronze/30 bg-tzel-espresso/40 px-4 py-3 text-sm text-tzel-sand">{{ session('status') }}</div>
          @endif
          @yield('content')
        </main>
      </div>
    </div>
  </body>
</html>
