<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TZEL CAFÉ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-tzel-ink text-tzel-cream font-sans antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute -left-40 -top-40 h-[520px] w-[520px] rounded-full bg-tzel-bronze/10 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-32 top-32 h-[420px] w-[420px] rounded-full bg-tzel-roast/60 blur-3xl" aria-hidden="true"></div>

            <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
                <div class="w-full max-w-md overflow-hidden rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8 shadow-2xl shadow-black/30">
                    <a href="/" class="group mx-auto flex items-center justify-center gap-3" aria-label="TZEL CAFÉ">
                        <img src="/logo.png" alt="TZEL CAFÉ logo" class="h-12 w-12 rounded-full object-cover ring-1 ring-tzel-bronze/40 transition group-hover:ring-tzel-bronze" width="48" height="48" />
                        <div class="text-left">
                            <div class="font-serif text-lg tracking-[0.2em] text-tzel-cream">TZEL</div>
                            <div class="text-[10px] font-medium tracking-[0.35em] text-tzel-bronze">CAFÉ</div>
                        </div>
                    </a>

                    <div class="mt-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
