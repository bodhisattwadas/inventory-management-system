<x-modal name="vendor-modal" :show="$errors->isNotEmpty()" focusable>
    <div class="p-6 max-h-[85vh] overflow-y-auto">
        <div class="mb-6 border-b border-gray-200 pb-4">
            <h3 class="text-lg font-semibold">{{ $isEditing ? __('Edit Supplier / Vendor') : __('Add Supplier / Vendor') }}</h3>
            <p class="text-sm text-muted-foreground">{{ __('Maintain supplier details, bank information, and supplied brands/companies.') }}</p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div>
                <h4 class="text-sm font-semibold mb-3">{{ __('Supplier Details') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-form-input name="vendor_name" label="Supplier / Vendor Name" wire:model="vendor_name" required hint="Display name used across vendor records. Example: Beauty World Distributors." />
                    <x-form-input name="legal_name" label="Legal Name" wire:model="legal_name" hint="Registered business name for invoices and tax records. Example: Beauty World Pvt Ltd." />
                    <x-form-input name="trade_name" label="Trade Name" wire:model="trade_name" hint="Public or shop name if different from legal name. Example: Beauty World." />
                    <x-form-input name="vendor_type" label="Supplier Type" wire:model="vendor_type" hint="Classification of the supplier relationship. Example: Distributor." />
                    <div class="space-y-2">
                        <x-input-label for="vendor_category_id" :value="__('Vendor Category')" hint="Grouping used for vendor reporting. Example: Cosmetics Distributor." />
                        <select id="vendor_category_id" wire:model="vendor_category_id" class="w-full rounded-md border-gray-300">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('vendor_category_id')" />
                    </div>
                    <x-form-input name="registration_number" label="GST / Tax / Registration No" wire:model="registration_number" hint="Government, GST, or business registration identifier. Example: 29ABCDE1234F1Z5." />
                    <x-form-input name="industry" label="Industry" wire:model="industry" hint="Business category this vendor belongs to. Example: Cosmetics." />
                    <x-form-input name="website" label="Website" wire:model="website" hint="Vendor website or catalog link. Example: https://supplier.com." />
                </div>
            </div>

            <div>
                <h4 class="text-sm font-semibold mb-3">{{ __('Contact Details') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-form-input name="primary_contact_person" label="Contact Person" wire:model="primary_contact_person" hint="Person to contact for orders or payments. Example: Jody Watsica." />
                    <x-form-input name="primary_email" label="Primary Email" type="email" wire:model="primary_email" hint="Main vendor communication email. Example: orders@supplier.com." />
                    <x-form-input name="accounts_email" label="Accounts Email" type="email" wire:model="accounts_email" hint="Email used for invoices and payment queries. Example: accounts@supplier.com." />
                    <x-form-input name="po_email" label="Purchase Email" type="email" wire:model="po_email" hint="Email used for purchase orders. Example: po@supplier.com." />
                    <x-form-input name="primary_phone" label="Primary Phone" wire:model="primary_phone" placeholder="+91 98765 43210" hint="Main vendor Indian phone number. Example: +91 98765 43210." />
                    <x-form-input name="alternate_phone" label="Alternate Phone" wire:model="alternate_phone" placeholder="+91 91234 56789" hint="Backup Indian phone number for vendor contact. Example: +91 91234 56789." />
                </div>
            </div>

            <div>
                <h4 class="text-sm font-semibold mb-3">{{ __('Address') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-form-input name="address_line_1" label="Address Line 1" wire:model="address_line_1" hint="Street address or building name. Example: 12 MG Road." />
                    <x-form-input name="address_line_2" label="Address Line 2" wire:model="address_line_2" hint="Additional address detail. Example: 2nd Floor." />
                    <x-form-input name="city" label="City" wire:model="city" hint="Vendor city. Example: Bengaluru." />
                    <x-form-input name="state" label="State" wire:model="state" hint="Vendor state or region. Example: Karnataka." />
                    <x-form-input name="postal_code" label="Postal Code" wire:model="postal_code" hint="ZIP or PIN code. Example: 560001." />
                    <x-form-input name="country" label="Country" wire:model="country" hint="Vendor country. Example: India." />
                </div>
            </div>

            <div>
                <h4 class="text-sm font-semibold mb-3">{{ __('Bank Details') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-form-input name="bank_name" label="Bank Name" wire:model="bank_name" hint="Name of the vendor bank. Example: HDFC Bank." />
                    <x-form-input name="bank_branch" label="Branch" wire:model="bank_branch" hint="Bank branch location. Example: Indiranagar." />
                    <x-form-input name="account_name" label="Account Name" wire:model="account_name" hint="Name registered on the bank account. Example: Beauty World Pvt Ltd." />
                    <x-form-input name="account_number" label="Account Number" wire:model="account_number" hint="Vendor bank account number. Example: 123456789012." />
                    <x-form-input name="account_type" label="Account Type" wire:model="account_type" hint="Type of bank account. Example: Current." />
                    <x-form-input name="ifsc_code" label="IFSC Code" wire:model="ifsc_code" hint="Indian bank routing code. Example: HDFC0001234." />
                    <x-form-input name="swift_bic" label="SWIFT / BIC" wire:model="swift_bic" hint="International bank routing code if needed. Example: HDFCINBB." />
                    <x-form-input name="beneficiary_name" label="Beneficiary Name" wire:model="beneficiary_name" hint="Name receiving payment. Example: Beauty World Pvt Ltd." />
                    <x-form-input name="bank_country" label="Bank Country" wire:model="bank_country" hint="Country where the bank account is held. Example: India." />
                </div>
                <p class="mt-2 text-xs text-gray-500">{{ __('Account numbers are encrypted and shown masked after saving.') }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold mb-3">{{ __('Brands / Companies Supplied') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 rounded-md border border-gray-200 p-3 max-h-56 overflow-y-auto">
                    @forelse ($companies as $company)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" wire:model="company_ids" value="{{ $company->id }}" class="rounded border-gray-300">
                            <span>{{ $company->company_code }} : {{ $company->company_name }}</span>
                        </label>
                    @empty
                        <span class="text-sm text-gray-500">{{ __('No active brands/companies found. Add one from Company Master first.') }}</span>
                    @endforelse
                </div>
                <x-input-error :messages="$errors->get('company_ids')" />
            </div>

            <div>
                <h4 class="text-sm font-semibold mb-3">{{ __('Status') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <x-input-label for="status" :value="__('Status')" hint="Whether this vendor can currently be used. Example: Active." />
                        <select id="status" wire:model="status" class="w-full rounded-md border-gray-300">
                            @foreach (['draft', 'pending approval', 'active', 'on hold', 'blocked', 'inactive'] as $option)
                                <option value="{{ $option }}">{{ Str::title($option) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="approval_status" :value="__('Approval Status')" hint="Internal approval state for using this vendor. Example: Approved." />
                        <select id="approval_status" wire:model="approval_status" class="w-full rounded-md border-gray-300">
                            @foreach (['draft', 'submitted', 'under review', 'approved', 'rejected'] as $option)
                                <option value="{{ $option }}">{{ Str::title($option) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="preferred_vendor" class="rounded"> Preferred Supplier</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="purchase_enabled" class="rounded"> Purchase Enabled</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="payment_enabled" class="rounded"> Payment Enabled</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model.live="blocked" class="rounded"> Blocked</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model.live="blacklisted" class="rounded"> Blacklisted</label>
                </div>
                @if ($blocked)
                    <div class="mt-3">
                        <x-form-input name="blocked_reason" label="Blocked Reason" wire:model="blocked_reason" required hint="Reason this vendor is temporarily blocked. Example: Pending tax documents." />
                    </div>
                @endif
                @if ($blacklisted)
                    <div class="mt-3">
                        <x-form-input name="blacklist_reason" label="Blacklist Reason" wire:model="blacklist_reason" required hint="Reason this vendor should not be used. Example: Contract violation." />
                    </div>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', { name: 'vendor-modal' })">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button type="submit">{{ $isEditing ? __('Save Changes') : __('Create Supplier') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
