@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-transparent border border-[#4B5563] text-[#E0E0E0] rounded-md']) }}>
