@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'w-full rounded-2xl border border-white/10 bg-tzel-ink/30 px-4 py-3 text-sm text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze/60 focus:ring focus:ring-tzel-bronze/20']) }}
>
