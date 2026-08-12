<x-app-layout title="Create Supplier / Vendor">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-foreground leading-tight">
                    {{ __('Create Supplier / Vendor') }}
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ __('Add supplier details, bank information, and supplied brands/companies.') }}
                </p>
            </div>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('suppliers.supplier-form', ['asPage' => true])
        </div>
    </div>
</x-app-layout>
