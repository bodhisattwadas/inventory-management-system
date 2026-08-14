<x-modal name="company-detail-modal" focusable>
    @if($company)
        <div class="p-6">
            <div class="mb-6 flex items-start justify-between gap-4 border-b border-gray-200 pb-4">
                <div class="space-y-1.5">
                    <h3 class="text-lg font-semibold leading-none text-foreground">{{ $company->company_name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ $company->short_name ?: __('Brand / Company Details') }}</p>
                </div>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $company->status === 'active' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-700 ring-slate-200' }}">
                    {{ Str::title($company->status ?: 'active') }}
                </span>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label value="{{ __('Code') }}" hint="Unique short code used to identify this brand/company. Example: BRD-001." class="text-muted-foreground" />
                    <p class="text-sm font-medium">{{ $company->company_code ?: '-' }}</p>
                </div>
                <div>
                    <x-input-label value="{{ __('Brand Name') }}" hint="Short display name shown in product and supplier records. Example: L'Oreal." class="text-muted-foreground" />
                    <p class="text-sm font-medium">{{ $company->short_name ?: '-' }}</p>
                </div>
                <div>
                    <x-input-label value="{{ __('Company Type') }}" hint="Classification for this record. Example: Brand." class="text-muted-foreground" />
                    <p class="text-sm font-medium">{{ $company->company_type ?: '-' }}</p>
                </div>
                <div>
                    <x-input-label value="{{ __('Suppliers Linked') }}" hint="Number of suppliers assigned to this brand/company. Example: 3." class="text-muted-foreground" />
                    <p class="text-sm font-medium">{{ $company->suppliers_count ?? 0 }}</p>
                </div>
                <div>
                    <x-input-label value="{{ __('Vendors Linked') }}" hint="Number of vendor records linked to this brand/company. Example: 2." class="text-muted-foreground" />
                    <p class="text-sm font-medium">{{ $company->vendors_count ?? 0 }}</p>
                </div>
                <div>
                    <x-input-label value="{{ __('Created') }}" hint="Date this brand/company record was added. Example: 14 Aug 2026." class="text-muted-foreground" />
                    <p class="text-sm font-medium">{{ $company->created_at?->format('d M Y, H:i') ?: '-' }}</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-2 border-t border-border pt-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', { name: 'company-detail-modal' })">
                    {{ __('Close') }}
                </x-secondary-button>
                <x-primary-button type="button" class="bg-amber-500 hover:bg-amber-600 focus:ring-amber-500" x-on:click="$dispatch('close-modal', { name: 'company-detail-modal' }); $dispatch('edit-company', { company: {{ $company->id }} })">
                    <x-heroicon-o-pencil-square class="w-4 h-4 mr-2" />
                    {{ __('Edit') }}
                </x-primary-button>
            </div>
        </div>
    @endif
</x-modal>
