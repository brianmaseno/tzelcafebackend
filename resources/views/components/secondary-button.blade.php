<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border border-white/10 bg-tzel-espresso/40 px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.25em] text-tzel-sand transition hover:border-tzel-bronze/50 hover:text-tzel-gold focus:outline-none focus:ring-2 focus:ring-tzel-bronze/30 focus:ring-offset-0 disabled:opacity-40']) }}>
    {{ $slot }}
</button>
