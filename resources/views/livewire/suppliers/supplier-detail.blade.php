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
                    <x-input-label value="{{ __('Brands / Companies Supplied') }}" hint="Brands this supplier is allowed to supply. Example: Colorbar." class="mb-2 block" />
                    <div class="flex flex-wrap gap-2">
                        @forelse ($supplier->companies as $company)
                            @php
                                $badgeClasses = [
                                    'bg-blue-50 text-blue-700 ring-blue-200',
                                    'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    'bg-amber-50 text-amber-700 ring-amber-200',
                                    'bg-rose-50 text-rose-700 ring-rose-200',
                                    'bg-violet-50 text-violet-700 ring-violet-200',
                                    'bg-cyan-50 text-cyan-700 ring-cyan-200',
                                ];
                                $badgeClass = $badgeClasses[$loop->index % count($badgeClasses)];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold shadow-sm ring-1 ring-inset {{ $badgeClass }}">
                                {{ $company->company_name }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500">{{ __('No brands/companies assigned.') }}</span>
                        @endforelse
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label value="{{ __('Contact Person') }}" hint="Person to contact for orders or payments. Example: Jody Watsica." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->contact_person ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('Supplier Type') }}" hint="Classification of the supplier relationship. Example: Distributor." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->supplier_type ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('Email') }}" hint="Primary email for supplier communication. Example: orders@supplier.com." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->email ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('Phone') }}" hint="Main supplier phone number. Example: +91 98765 43210." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->phone ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('GST / Tax ID') }}" hint="Tax identifier used for compliant billing. Example: 29ABCDE1234F1Z5." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->tax_id ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('Status') }}" hint="Whether this supplier can currently be used in records. Example: Active." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ Str::title($supplier->status ?: 'active') }}</p>
                    </div>
                </section>

                <section>
                    <x-input-label value="{{ __('Address') }}" hint="Registered or delivery-related supplier location. Example: 12 MG Road, Bengaluru." class="mb-2 block" />
                    <p class="text-sm font-medium">
                        {{ collect([$supplier->address_line_1, $supplier->address_line_2, $supplier->city, $supplier->state, $supplier->postal_code, $supplier->country])->filter()->join(', ') ?: ($supplier->address ?: '-') }}
                    </p>
                </section>

                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label value="{{ __('Bank Name') }}" hint="Name of the supplier bank. Example: HDFC Bank." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->bank_name ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('Account Number') }}" hint="Masked supplier bank account number. Example: XXXXXXXX9012." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->masked_account_number ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('IFSC') }}" hint="Indian bank routing code. Example: HDFC0001234." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->ifsc_code ?: '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="{{ __('Beneficiary') }}" hint="Name registered on the bank account. Example: Beauty World Pvt Ltd." class="text-muted-foreground" />
                        <p class="text-sm font-medium">{{ $supplier->beneficiary_name ?: '-' }}</p>
                    </div>
                </section>

                <section>
                    <x-input-label value="{{ __('Notes') }}" hint="Internal remarks not printed on normal documents. Example: Payment due in 30 days." class="mb-2 block" />
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
