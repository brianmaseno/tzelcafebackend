<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-red-500/90 px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:ring-offset-0']) }}>
    {{ $slot }}
</button>
