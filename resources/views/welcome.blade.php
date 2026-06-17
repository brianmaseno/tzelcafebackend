<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TZEL CAFÉ') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-tzel-ink text-tzel-cream font-sans antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute -left-40 -top-40 h-[520px] w-[520px] rounded-full bg-tzel-bronze/10 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-32 top-32 h-[420px] w-[420px] rounded-full bg-tzel-roast/60 blur-3xl" aria-hidden="true"></div>

            <main class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid w-full gap-12 lg:grid-cols-2 lg:items-center">
                    <section>
                        <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">
                            TZEL CAFÉ
                        </p>
                        <h1 class="mt-3 font-serif text-4xl leading-tight text-tzel-cream sm:text-5xl">
                            EAT. SIP. CONNECT.
                        </h1>
                        <p class="mt-5 text-tzel-sand/85">
                            Where great taste meets great purpose. Freshly prepared meals, premium beverages, and a warm space for meaningful connections.
                        </p>

                        <div class="mt-10 flex flex-wrap gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-8 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold">
                                    Continue to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-8 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-transparent px-8 py-3 text-sm font-medium text-tzel-cream transition hover:border-tzel-bronze/50 hover:text-tzel-gold">
                                        Create Account
                                    </a>
                                @endif
                            @endauth

                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-tzel-espresso/40 px-8 py-3 text-sm font-medium text-tzel-sand transition hover:border-tzel-bronze/50 hover:text-tzel-gold">
                                Admin Dashboard
                            </a>
                        </div>
                    </section>

                    <section class="overflow-hidden rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8 shadow-2xl shadow-black/30">
                        <div class="flex items-center gap-3">
                            <img src="/logo.png" alt="TZEL CAFÉ logo" class="h-12 w-12 rounded-full object-cover ring-1 ring-tzel-bronze/40" />
                            <div>
                                <div class="font-serif text-lg tracking-[0.2em]">TZEL</div>
                                <div class="text-[10px] font-medium tracking-[0.35em] text-tzel-bronze">CAFÉ</div>
                            </div>
                        </div>
                        <p class="mt-6 text-sm text-tzel-sand/80">
                            Unified admin + user authentication styling. After sign-in, manage menu, orders, and customer experience from one premium dashboard.
                        </p>
                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/5 bg-tzel-ink/30 p-5">
                                <div class="font-serif text-2xl text-tzel-gold">Premium UI</div>
                                <div class="mt-2 text-sm text-tzel-muted">TZEL fonts, palette, and layout everywhere.</div>
                            </div>
                            <div class="rounded-2xl border border-white/5 bg-tzel-ink/30 p-5">
                                <div class="font-serif text-2xl text-tzel-gold">Operational Ready</div>
                                <div class="mt-2 text-sm text-tzel-muted">Orders, statuses, and reporting.</div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </body>
</html>

