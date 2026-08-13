<x-modal name="supplier-detail-modal" focusable>
    @if($supplier)
        <div class="p-6 max-h-[85vh] overflow-y-auto">
            <div class="mb-6 flex items-start justify-between gap-4 border-b border-gray-200 pb-4">
                <div class="space-y-1.5">
                    <h3 class="text-lg font-semibold leading-none tracking-tight text-foreground">{{ $supplier->name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ $supplier->legal_name ?: __('Supplier / Vendor Details') }}</p>
                </div>
                <a href="{{ route('suppliers.profile.pdf', $supplier) }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                    <x-heroicon-o-arrow-down-tray class="mr-1.5 h-4 w-4" />
                    Download Profile PDF
                </a>
            </div>

            <div class="space-y-6">
                <section>
                    <h4 class="text-sm font-semibold mb-2">{{ __('Brands / Companies Supplied') }}</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($supplier->companies as $company)
                            <span class="rounded bg-blue-50 px-2 py-1 text-xs text-blue-800">{{ $company->company_name }}</span>
                        @empty
                            <span class="text-sm text-gray-500">{{ __('No brands/companies assigned.') }}</span>
                        @endforelse
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Contact Person') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->contact_person ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Supplier Type') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->supplier_type ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Email') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->email ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Phone') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->phone ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('GST / Tax ID') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->tax_id ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Status') }}</label>
                        <p class="text-sm font-medium">{{ Str::title($supplier->status ?: 'active') }}</p>
                    </div>
                </section>

                <section>
                    <h4 class="text-sm font-semibold mb-2">{{ __('Address') }}</h4>
                    <p class="text-sm font-medium">
                        {{ collect([$supplier->address_line_1, $supplier->address_line_2, $supplier->city, $supplier->state, $supplier->postal_code, $supplier->country])->filter()->join(', ') ?: ($supplier->address ?: '-') }}
                    </p>
                </section>

                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Bank Name') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->bank_name ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Account Number') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->masked_account_number ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('IFSC') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->ifsc_code ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">{{ __('Beneficiary') }}</label>
                        <p class="text-sm font-medium">{{ $supplier->beneficiary_name ?: '-' }}</p>
                    </div>
                </section>

                <section>
                    <h4 class="text-sm font-semibold mb-2">{{ __('Notes') }}</h4>
                    <p class="text-sm font-medium whitespace-pre-line">{{ $supplier->notes ?: '-' }}</p>
                </section>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-2 pt-4 border-t border-border">
                <x-secondary-button x-on:click="$dispatch('close-modal', { name: 'supplier-detail-modal' })">
                    {{ __('Close') }}
                </x-secondary-button>

                <x-primary-button href="{{ route('suppliers.edit', $supplier) }}" class="bg-amber-500 hover:bg-amber-600 focus:ring-amber-500">
                    <x-heroicon-o-pencil-square class="w-4 h-4 mr-2" />
                    {{ __('Edit Supplier') }}
                </x-primary-button>
            </div>
        </div>
    @endif
</x-modal>
