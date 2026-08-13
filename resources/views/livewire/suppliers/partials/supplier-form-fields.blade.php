@unless($asPage)
    <div class="{{ $headerClass }}">
        <h3 class="text-lg font-semibold leading-none tracking-tight text-foreground">
            {{ $isEditing ? __('Edit Supplier / Vendor') : __('Create Supplier / Vendor') }}
        </h3>
        <p class="text-sm text-muted-foreground">
            {{ __('Supplier is the vendor master. Add contact, bank details, and supplied brands/companies here.') }}
        </p>
    </div>
@endunless

<form wire:submit="save" class="{{ $formClass }}">
    <section class="{{ $asPage ? 'pt-4 ' : '' }}pb-6">
        <div class="mb-4">
            <h4 class="text-sm font-semibold text-foreground">{{ __('Supplier Details') }}</h4>
            <p class="text-xs text-muted-foreground">{{ __('Identity, tax, registration, and operating status.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="name" label="Supplier / Vendor Name" wire:model="name" required />
            <x-form-input name="legal_name" label="Legal Name" wire:model="legal_name" />
            <x-form-input name="trade_name" label="Trade Name" wire:model="trade_name" />
            <x-form-input name="supplier_type" label="Supplier Type" wire:model="supplier_type" />
            <x-form-input name="registration_number" label="Registration Number" wire:model="registration_number" />
            <x-form-input name="tax_id" label="GST / Tax ID" wire:model="tax_id" />
            <x-form-input name="website" label="Website" wire:model="website" />
            <x-form-input name="industry" label="Industry" wire:model="industry" />
            <div class="space-y-2">
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" wire:model="status" class="w-full rounded-md border-gray-300">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" />
            </div>
        </div>
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <h4 class="text-sm font-semibold text-foreground">{{ __('Contact Details') }}</h4>
            <p class="text-xs text-muted-foreground">{{ __('Primary contact, one email, and two phone numbers.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="contact_person" label="Contact Person" wire:model="contact_person" />
            <x-form-input name="email" label="Email" type="email" wire:model="email" />
            <x-form-input name="phone" label="Phone 1" wire:model="phone" required />
            <x-form-input name="alternate_phone" label="Phone 2" wire:model="alternate_phone" />
        </div>
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <h4 class="text-sm font-semibold text-foreground">{{ __('Address') }}</h4>
            <p class="text-xs text-muted-foreground">{{ __('Registered or primary supplier location.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="address_line_1" label="Address Line 1" wire:model="address_line_1" />
            <x-form-input name="address_line_2" label="Address Line 2" wire:model="address_line_2" />
            <x-form-input name="city" label="City" wire:model="city" />
            <x-form-input name="state" label="State" wire:model="state" />
            <x-form-input name="postal_code" label="Postal Code" wire:model="postal_code" />
            <x-form-input name="country" label="Country" wire:model="country" />
        </div>

        <div class="mt-4 space-y-2">
            <x-input-label for="address" :value="__('Full Address / Notes For Address')" />
            <textarea
                id="address"
                wire:model="address"
                rows="2"
                class="flex min-h-[70px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
            ></textarea>
            <x-input-error :messages="$errors->get('address')" />
        </div>
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <h4 class="text-sm font-semibold text-foreground">{{ __('Bank Details') }}</h4>
            <p class="text-xs text-muted-foreground">{{ __('Payment account details. Account number is encrypted when saved.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="bank_name" label="Bank Name" wire:model="bank_name" />
            <x-form-input name="bank_branch" label="Branch" wire:model="bank_branch" />
            <x-form-input name="account_number" label="Account Number" wire:model="account_number" />
            <div class="space-y-2">
                <x-input-label for="account_type" :value="__('Account Type')" />
                <select id="account_type" name="account_type" wire:model="account_type" class="w-full rounded-md border-gray-300">
                    <option value="">Select Account Type</option>
                    <option value="Savings">Savings</option>
                    <option value="Current">Current</option>
                </select>
                <x-input-error :messages="$errors->get('account_type')" />
            </div>
            <x-form-input name="ifsc_code" label="IFSC Code" wire:model="ifsc_code" />
            <x-form-input name="swift_bic" label="SWIFT / BIC" wire:model="swift_bic" />
            <x-form-input name="beneficiary_name" label="Beneficiary Name" wire:model="beneficiary_name" />
        </div>
        <p class="mt-2 text-xs text-gray-500">{{ __('Saved account numbers are encrypted and only the last four digits are kept searchable.') }}</p>
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <h4 class="text-sm font-semibold text-foreground">{{ __('Brands / Companies Supplied') }}</h4>
            <p class="text-xs text-muted-foreground">{{ __('Select every brand/company this supplier can provide goods or services for.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 rounded-md border border-gray-200 p-3 max-h-56 overflow-y-auto">
            @forelse ($companies as $company)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="company_ids" value="{{ $company->id }}" class="rounded border-gray-300">
                    <span>{{ $company->company_code }} : {{ $company->company_name }}</span>
                </label>
            @empty
                <span class="text-sm text-gray-500">{{ __('No active brands/companies found. Add one from Master Data first.') }}</span>
            @endforelse
        </div>
        <x-input-error :messages="$errors->get('company_ids')" />
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <h4 class="text-sm font-semibold text-foreground">{{ __('Notes') }}</h4>
            <p class="text-xs text-muted-foreground">{{ __('Internal remarks, payment reminders, or supplier-specific instructions.') }}</p>
        </div>
        <textarea
            id="notes"
            wire:model="notes"
            rows="3"
            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
            placeholder="Additional supplier notes..."
        ></textarea>
        <x-input-error :messages="$errors->get('notes')" />
    </section>

    <div class="{{ $footerClass }}">
        @if($asPage)
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                {{ __('Cancel') }}
            </a>
        @else
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', { name: 'supplier-modal' })">
                {{ __('Cancel') }}
            </x-secondary-button>
        @endif

        <x-primary-button type="submit" wire:loading.attr="disabled">
            <x-heroicon-o-check wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" />
            {{ $isEditing ? __('Save Changes') : __('Create Supplier') }}
        </x-primary-button>
    </div>
</form>
