@php
    $formShellClass = $asPage
        ? 'rounded-md border border-gray-200 bg-card shadow-sm'
        : 'h-[80vh] max-h-[80vh] overflow-auto';
    $formClass = $asPage
        ? 'px-6 py-5'
        : 'min-w-[900px] px-6 py-5 sm:min-w-[900px] md:min-w-[980px]';
    $headerClass = $asPage
        ? 'px-6 py-5 border-b border-gray-200'
        : 'sticky top-0 z-10 bg-card px-6 py-5 border-b border-gray-200';
    $footerClass = $asPage
        ? 'flex justify-end gap-3 border-t border-gray-200 bg-card px-6 py-4'
        : 'sticky bottom-0 -mx-6 flex justify-end gap-3 border-t border-gray-200 bg-card px-6 py-4';
@endphp

<div>
    @if($asPage)
        <div class="{{ $formShellClass }}">
            @include('livewire.suppliers.partials.supplier-form-fields')
        </div>
    @else
        <x-modal name="supplier-modal" maxWidth="inset-workspace" :show="$errors->isNotEmpty()" focusable>
            <div class="{{ $formShellClass }}">
                @include('livewire.suppliers.partials.supplier-form-fields')
            </div>
        </x-modal>
    @endif
</div>
