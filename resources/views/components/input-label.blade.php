@props(['value', 'required' => false, 'hint' => null])

<label {{ $attributes->merge(['class' => 'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-destructive">*</span>
    @endif
    @if($hint)
        <sup title="{{ $hint }}" class="ml-1 inline-flex h-3.5 w-3.5 cursor-help items-center justify-center rounded-full bg-blue-50 text-[9px] font-bold leading-none text-blue-700 ring-1 ring-blue-300">?</sup>
    @endif
</label>
