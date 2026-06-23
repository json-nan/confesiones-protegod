@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-1.5 font-medium text-sm text-success']) }}>
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        {{ $status }}
    </div>
@endif
