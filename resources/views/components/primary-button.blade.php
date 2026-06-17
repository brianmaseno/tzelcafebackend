<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-tzel-bronze px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.25em] text-tzel-ink transition hover:bg-tzel-gold focus:outline-none focus:ring-2 focus:ring-tzel-bronze/60 focus:ring-offset-0']) }}>
    {{ $slot }}
</button>
