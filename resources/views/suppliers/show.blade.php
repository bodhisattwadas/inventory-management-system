@php
    $display = fn ($value) => filled($value) ? $value : '-';
    $valueClass = 'mt-1 text-sm font-medium text-gray-900';
@endphp

<x-app-layout title="Supplier / Vendor Details">
    <x-slot name="header">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="font-semibold text-xl text-foreground leading-tight">{{ __('View Supplier / Vendor') }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ __('Review supplier details, bank information, and supplied brands/companies.') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('suppliers.index') }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <x-heroicon-o-arrow-left class="h-4 w-4" />
                    {{ __('Back') }}
                </a>
                <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md bg-amber-500 px-3 text-sm font-semibold text-white hover:bg-amber-600">
                    <x-heroicon-o-pencil-square class="h-4 w-4" />
                    {{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-md border border-gray-200 bg-white px-6 py-5 shadow-sm sm:px-8">
                <section class="pb-6">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Supplier Details') }}" hint="Basic identity and tax information for this supplier. Example: GST ID and active status." />
                        <p class="text-xs text-muted-foreground">{{ __('Identity, tax, registration, and operating status.') }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div><x-input-label value="{{ __('Supplier / Vendor Name') }}" required /><p class="{{ $valueClass }}">{{ $display($supplier->name) }}</p></div>
                        <div><x-input-label value="{{ __('Legal Name') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->legal_name) }}</p></div>
                        <div><x-input-label value="{{ __('Trade Name') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->trade_name) }}</p></div>
                        <div><x-input-label value="{{ __('Supplier Type') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->supplier_type) }}</p></div>
                        <div><x-input-label value="{{ __('Registration Number') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->registration_number) }}</p></div>
                        <div><x-input-label value="{{ __('GST / Tax ID') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->tax_id) }}</p></div>
                        <div><x-input-label value="{{ __('Website') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->website) }}</p></div>
                        <div><x-input-label value="{{ __('Industry') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->industry) }}</p></div>
                        <div><x-input-label value="{{ __('Status') }}" /><p class="{{ $valueClass }}">{{ Str::title($supplier->status ?: 'active') }}</p></div>
                    </div>
                </section>

                <section class="border-t border-gray-200 py-6">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Contact Details') }}" hint="Main contact details for supplier communication. Example: purchase contact email and phone." />
                        <p class="text-xs text-muted-foreground">{{ __('Primary contact, one email, and two phone numbers.') }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div><x-input-label value="{{ __('Contact Person') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->contact_person) }}</p></div>
                        <div><x-input-label value="{{ __('Email') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->email) }}</p></div>
                        <div><x-input-label value="{{ __('Phone 1') }}" required /><p class="{{ $valueClass }}">{{ format_indian_phone($supplier->phone) }}</p></div>
                        <div><x-input-label value="{{ __('Phone 2') }}" /><p class="{{ $valueClass }}">{{ format_indian_phone($supplier->alternate_phone) }}</p></div>
                    </div>
                </section>

                <section class="border-t border-gray-200 py-6">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Address') }}" hint="Registered or delivery-related supplier location. Example: city, state, and postal code." />
                        <p class="text-xs text-muted-foreground">{{ __('Registered or primary supplier location.') }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div><x-input-label value="{{ __('Address Line 1') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->address_line_1) }}</p></div>
                        <div><x-input-label value="{{ __('Address Line 2') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->address_line_2) }}</p></div>
                        <div><x-input-label value="{{ __('City') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->city) }}</p></div>
                        <div><x-input-label value="{{ __('State') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->state) }}</p></div>
                        <div><x-input-label value="{{ __('Postal Code') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->postal_code) }}</p></div>
                        <div><x-input-label value="{{ __('Country') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->country) }}</p></div>
                    </div>
                    <div class="mt-4">
                        <x-input-label value="{{ __('Full Address / Notes For Address') }}" />
                        <p class="{{ $valueClass }}">{{ $display($supplier->address) }}</p>
                    </div>
                </section>

                <section class="border-t border-gray-200 py-6">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Bank Details') }}" hint="Payment account information for supplier payouts. Example: HDFC Bank current account." />
                        <p class="text-xs text-muted-foreground">{{ __('Payment account details. Account number is encrypted when saved.') }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div><x-input-label value="{{ __('Bank Name') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->bank_name) }}</p></div>
                        <div><x-input-label value="{{ __('Branch') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->bank_branch) }}</p></div>
                        <div><x-input-label value="{{ __('Account Number') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->full_account_number) }}</p></div>
                        <div><x-input-label value="{{ __('Account Type') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->account_type) }}</p></div>
                        <div><x-input-label value="{{ __('IFSC Code') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->ifsc_code) }}</p></div>
                        <div><x-input-label value="{{ __('SWIFT / BIC') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->swift_bic) }}</p></div>
                        <div><x-input-label value="{{ __('Beneficiary Name') }}" /><p class="{{ $valueClass }}">{{ $display($supplier->beneficiary_name) }}</p></div>
                    </div>
                </section>

                <section class="border-t border-gray-200 py-6">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Supplier Documents') }}" hint="Supplier verification documents. Example: blank cheque and GST certificate." />
                        <p class="text-xs text-muted-foreground">{{ __('Uploaded supplier verification documents.') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($supplier->blank_cheque_path)
                            <a href="{{ public_storage_url($supplier->blank_cheque_path) }}" target="_blank" class="inline-flex items-center gap-1 rounded-md border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100">
                                <x-heroicon-o-paper-clip class="h-4 w-4" />
                                {{ __('Blank Cheque') }}
                            </a>
                        @endif
                        @if($supplier->gst_document_path)
                            <a href="{{ public_storage_url($supplier->gst_document_path) }}" target="_blank" class="inline-flex items-center gap-1 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-100">
                                <x-heroicon-o-paper-clip class="h-4 w-4" />
                                {{ __('GST Document') }}
                            </a>
                        @endif
                        @unless($supplier->blank_cheque_path || $supplier->gst_document_path)
                            <span class="text-sm text-gray-500">{{ __('No documents uploaded.') }}</span>
                        @endunless
                    </div>
                </section>

                <section class="border-t border-gray-200 py-6">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Brands / Companies Supplied') }}" hint="Brands this supplier is allowed to supply. Example: Colorbar." />
                        <p class="text-xs text-muted-foreground">{{ __('Every brand/company this supplier can provide goods or services for.') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 rounded-md border border-gray-200 bg-gray-50 p-3">
                        @forelse ($supplier->companies as $company)
                            <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-200 hover:bg-blue-100">
                                {{ $company->short_name ?: $company->company_name }}
                            </a>
                        @empty
                            <span class="text-sm text-gray-500">{{ __('No active brands/companies found.') }}</span>
                        @endforelse
                    </div>
                </section>

                <section class="border-t border-gray-200 py-6">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Notes') }}" hint="Internal remarks not printed on normal documents. Example: Payment due in 30 days." />
                        <p class="text-xs text-muted-foreground">{{ __('Internal remarks, payment reminders, or supplier-specific instructions.') }}</p>
                    </div>
                    <p class="{{ $valueClass }} whitespace-pre-line">{{ $display($supplier->notes) }}</p>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
