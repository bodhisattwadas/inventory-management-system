<x-app-layout title="Supplier / Vendor Details">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-foreground leading-tight">{{ $supplier->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $supplier->legal_name ?: __('Supplier / Vendor Details') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('suppliers.index') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    <x-heroicon-o-arrow-left class="h-4 w-4" />
                    {{ __('Back') }}
                </a>
                <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded bg-amber-500 px-3 text-xs font-semibold text-white hover:bg-amber-600">
                    <x-heroicon-o-pencil-square class="h-4 w-4" />
                    {{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-start justify-between gap-4 border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $supplier->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $supplier->supplier_type ?: '-' }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $supplier->status === 'active' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-700 ring-slate-200' }}">
                        {{ Str::title($supplier->status ?: 'active') }}
                    </span>
                </div>

                <div class="space-y-6">
                    <section>
                        <x-input-label value="{{ __('Brands / Companies Supplied') }}" class="mb-2 block" />
                        <div class="flex flex-wrap gap-2">
                            @forelse ($supplier->companies as $company)
                                <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-200 hover:bg-blue-100">
                                    {{ $company->short_name ?: $company->company_name }}
                                </a>
                            @empty
                                <span class="text-sm text-gray-500">{{ __('No brands/companies assigned.') }}</span>
                            @endforelse
                        </div>
                    </section>

                    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div><x-input-label value="{{ __('Contact Person') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ $supplier->contact_person ?: '-' }}</p></div>
                        <div><x-input-label value="{{ __('Email') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ $supplier->email ?: '-' }}</p></div>
                        <div><x-input-label value="{{ __('Phone') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ format_indian_phone($supplier->phone) }}</p></div>
                        <div><x-input-label value="{{ __('GST / Tax ID') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ $supplier->tax_id ?: '-' }}</p></div>
                        <div class="sm:col-span-2">
                            <x-input-label value="{{ __('Address') }}" class="text-muted-foreground" />
                            <p class="text-sm font-medium">{{ collect([$supplier->address_line_1, $supplier->address_line_2, $supplier->city, $supplier->state, $supplier->postal_code, $supplier->country])->filter()->join(', ') ?: ($supplier->address ?: '-') }}</p>
                        </div>
                    </section>

                    <section class="grid grid-cols-1 gap-4 border-t border-gray-200 pt-6 sm:grid-cols-2">
                        <div><x-input-label value="{{ __('Bank Name') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ $supplier->bank_name ?: '-' }}</p></div>
                        <div><x-input-label value="{{ __('Account Number') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ $supplier->full_account_number ?: '-' }}</p></div>
                        <div><x-input-label value="{{ __('IFSC') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ $supplier->ifsc_code ?: '-' }}</p></div>
                        <div><x-input-label value="{{ __('Beneficiary') }}" class="text-muted-foreground" /><p class="text-sm font-medium">{{ $supplier->beneficiary_name ?: '-' }}</p></div>
                    </section>

                    <section class="border-t border-gray-200 pt-6">
                        <x-input-label value="{{ __('Documents') }}" class="mb-2 block text-muted-foreground" />
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
