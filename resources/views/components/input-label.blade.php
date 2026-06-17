@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-medium tracking-[0.35em] text-tzel-bronze uppercase']) }}>
    {{ $value ?? $slot }}
</label>
