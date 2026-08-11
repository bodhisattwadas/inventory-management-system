<x-app-layout title="Suppliers / Vendors">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">{{ __('Suppliers / Vendors') }}</h2>
            <x-primary-button x-data x-on:click="$dispatch('create-vendor')">
                <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                {{ __('Add Supplier') }}
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:vendors.vendor-table />
        </div>
    </div>

    <livewire:vendors.vendor-form />
    <livewire:vendors.vendor-detail />
</x-app-layout>
