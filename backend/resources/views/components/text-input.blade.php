@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800/80 text-slate-900 dark:text-slate-100 rounded-xl shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/20 transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none']) }}>
