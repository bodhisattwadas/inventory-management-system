<x-app-layout title="Brands / Companies">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">{{ __('Brands / Companies') }}</h2>
            <x-primary-button x-data x-on:click="$dispatch('create-company')">
                <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                {{ __('Add Brand / Company') }}
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:companies.company-table />
        </div>
    </div>

    <livewire:companies.company-form />
</x-app-layout>
