@props(['disabled' => false])

<textarea
    style="resize: none"
    @if($disabled) disabled @endif {{ $attributes->merge(['class' => 'bg-transparent border-[#4B5563] text-[#E0E0E0] rounded-md']) }}></textarea>
