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

<form wire:submit="save" enctype="multipart/form-data" class="{{ $formClass }}">
    <section class="{{ $asPage ? 'pt-4 ' : '' }}pb-6">
        <div class="mb-4">
            <x-input-label value="{{ __('Supplier Details') }}" hint="Basic identity and tax information for this supplier. Example: GST ID and active status." />
            <p class="text-xs text-muted-foreground">{{ __('Identity, tax, registration, and operating status.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="name" label="Supplier / Vendor Name" wire:model="name" required hint="Display name used across purchase orders and reports. Example: Beauty World Distributors." />
            <x-form-input name="legal_name" label="Legal Name" wire:model="legal_name" hint="Registered business name for invoices and tax records. Example: Beauty World Pvt Ltd." />
            <x-form-input name="trade_name" label="Trade Name" wire:model="trade_name" hint="Public or shop name if different from legal name. Example: Beauty World." />
            <x-form-input name="supplier_type" label="Supplier Type" wire:model="supplier_type" hint="Classification of the supplier relationship. Example: Distributor." />
            <x-form-input name="registration_number" label="Registration Number" wire:model="registration_number" hint="Government or business registration identifier. Example: UDYAM-KR-123456." />
            <x-form-input name="tax_id" label="GST / Tax ID" wire:model="tax_id" hint="Tax identifier used for compliant billing. Example: 29ABCDE1234F1Z5." />
            <x-form-input name="website" label="Website" wire:model="website" hint="Supplier website or catalog link. Example: https://supplier.com." />
            <x-form-input name="industry" label="Industry" wire:model="industry" hint="Business category this supplier belongs to. Example: Cosmetics." />
            <div class="space-y-2">
                <x-input-label for="status" :value="__('Status')" hint="Whether this supplier can currently be used in records. Example: Active." />
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
            <x-input-label value="{{ __('Contact Details') }}" hint="Main contact details for supplier communication. Example: purchase contact email and phone." />
            <p class="text-xs text-muted-foreground">{{ __('Primary contact, one email, and two phone numbers.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="contact_person" label="Contact Person" wire:model="contact_person" hint="Person to contact for orders or payments. Example: Jody Watsica." />
            <x-form-input name="email" label="Email" type="email" wire:model="email" hint="Primary email for supplier communication. Example: orders@supplier.com." />
            <x-form-input name="phone" label="Phone 1" wire:model="phone" required placeholder="+91 98765 43210" hint="Main supplier Indian phone number. Example: +91 98765 43210." />
            <x-form-input name="alternate_phone" label="Phone 2" wire:model="alternate_phone" placeholder="+91 91234 56789" hint="Alternate Indian phone number for backup contact. Example: +91 91234 56789." />
        </div>
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <x-input-label value="{{ __('Address') }}" hint="Registered or delivery-related supplier location. Example: city, state, and postal code." />
            <p class="text-xs text-muted-foreground">{{ __('Registered or primary supplier location.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="address_line_1" label="Address Line 1" wire:model="address_line_1" hint="Street address or building name. Example: 12 MG Road." />
            <x-form-input name="address_line_2" label="Address Line 2" wire:model="address_line_2" hint="Additional address detail. Example: 2nd Floor." />
            <x-form-input name="city" label="City" wire:model="city" hint="Supplier city. Example: Bengaluru." />
            <x-form-input name="state" label="State" wire:model="state" hint="Supplier state or region. Example: Karnataka." />
            <x-form-input name="postal_code" label="Postal Code" wire:model="postal_code" hint="ZIP or PIN code. Example: 560001." />
            <x-form-input name="country" label="Country" wire:model="country" hint="Supplier country. Example: India." />
        </div>

        <div class="mt-4 space-y-2">
            <x-input-label for="address" :value="__('Full Address / Notes For Address')" hint="Complete address or delivery notes. Example: Use rear gate for deliveries." />
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
            <x-input-label value="{{ __('Bank Details') }}" hint="Payment account information for supplier payouts. Example: HDFC Bank current account." />
            <p class="text-xs text-muted-foreground">{{ __('Payment account details. Account number is encrypted when saved.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input name="bank_name" label="Bank Name" wire:model="bank_name" hint="Name of the supplier bank. Example: HDFC Bank." />
            <x-form-input name="bank_branch" label="Branch" wire:model="bank_branch" hint="Bank branch location. Example: Indiranagar." />
            <x-form-input name="account_number" label="Account Number" wire:model="account_number" hint="Supplier bank account number. Example: 123456789012." />
            <div class="space-y-2">
                <x-input-label for="account_type" :value="__('Account Type')" hint="Type of bank account. Example: Current." />
                <select id="account_type" name="account_type" wire:model="account_type" class="w-full rounded-md border-gray-300">
                    <option value="">Select Account Type</option>
                    <option value="Savings">Savings</option>
                    <option value="Current">Current</option>
                </select>
                <x-input-error :messages="$errors->get('account_type')" />
            </div>
            <x-form-input name="ifsc_code" label="IFSC Code" wire:model="ifsc_code" hint="Indian bank routing code. Example: HDFC0001234." />
            <x-form-input name="swift_bic" label="SWIFT / BIC" wire:model="swift_bic" hint="International bank routing code if needed. Example: HDFCINBB." />
            <x-form-input name="beneficiary_name" label="Beneficiary Name" wire:model="beneficiary_name" hint="Name registered on the bank account. Example: Beauty World Pvt Ltd." />
        </div>
        <p class="mt-2 text-xs text-gray-500">{{ __('Saved account numbers are encrypted and only the last four digits are kept searchable.') }}</p>
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <x-input-label value="{{ __('Supplier Documents') }}" hint="Upload supplier verification documents. Example: blank cheque image and GST certificate PDF." />
            <p class="text-xs text-muted-foreground">{{ __('Accepted formats: PDF, JPG, PNG, or WEBP up to 10 MB each.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2 rounded-md border border-gray-200 p-4">
                <x-input-label for="blank_cheque" :value="__('Blank Cheque')" hint="Cancelled or blank cheque used to verify supplier bank account details. Example: blank-cheque.jpg." />
                <input
                    id="blank_cheque"
                    type="file"
                    wire:model="blank_cheque"
                    accept="application/pdf,image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-foreground hover:file:bg-primary/90"
                >
                <div wire:loading wire:target="blank_cheque" class="text-xs text-muted-foreground">{{ __('Uploading blank cheque...') }}</div>
                @if($blank_cheque)
                    <p class="text-xs font-medium text-emerald-700">{{ __('Selected') }}: {{ $blank_cheque->getClientOriginalName() }}</p>
                @endif
                @if($current_blank_cheque_path)
                    <a href="{{ public_storage_url($current_blank_cheque_path) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline">
                        <x-heroicon-o-paper-clip class="h-4 w-4" />
                        {{ __('View current blank cheque') }}
                    </a>
                @endif
                <x-input-error :messages="$errors->get('blank_cheque')" />
            </div>

            <div class="space-y-2 rounded-md border border-gray-200 p-4">
                <x-input-label for="gst_document" :value="__('GST Document')" hint="Supplier GST certificate or tax registration document. Example: gst-certificate.pdf." />
                <input
                    id="gst_document"
                    type="file"
                    wire:model="gst_document"
                    accept="application/pdf,image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-foreground hover:file:bg-primary/90"
                >
                <div wire:loading wire:target="gst_document" class="text-xs text-muted-foreground">{{ __('Uploading GST document...') }}</div>
                @if($gst_document)
                    <p class="text-xs font-medium text-emerald-700">{{ __('Selected') }}: {{ $gst_document->getClientOriginalName() }}</p>
                @endif
                @if($current_gst_document_path)
                    <a href="{{ public_storage_url($current_gst_document_path) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline">
                        <x-heroicon-o-paper-clip class="h-4 w-4" />
                        {{ __('View current GST document') }}
                    </a>
                @endif
                <x-input-error :messages="$errors->get('gst_document')" />
            </div>
        </div>
    </section>

    <section class="border-t border-gray-200 py-6">
        <div class="mb-4">
            <x-input-label value="{{ __('Brands / Companies Supplied') }}" hint="Brands this supplier is allowed to supply. Example: Colorbar." />
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
            <x-input-label value="{{ __('Notes') }}" hint="Internal remarks not printed on normal documents. Example: Payment due in 30 days." />
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
