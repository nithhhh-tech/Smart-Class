@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
