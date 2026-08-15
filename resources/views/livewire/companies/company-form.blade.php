<x-modal name="company-modal" :show="$errors->isNotEmpty()" focusable>
    <div class="p-6">
        <div class="mb-6 border-b border-gray-200 pb-4">
            <h3 class="text-lg font-semibold">{{ $isEditing ? __('Edit Brand / Company') : __('Add Brand / Company') }}</h3>
            <p class="text-sm text-muted-foreground">{{ __('Maintain the brands or companies that suppliers can be linked to.') }}</p>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-input-label for="company_code" :value="__('Code')" required hint="Unique short code used to identify this brand/company in lists and records. Example: BRD-001." />
                    <x-text-input id="company_code" name="company_code" wire:model="company_code" class="block w-full" />
                    <x-input-error :messages="$errors->get('company_code')" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="company_name" :value="__('Company Name')" required hint="The full legal or official company name. Example: L'Oreal India Pvt Ltd." />
                    <x-text-input id="company_name" name="company_name" wire:model="company_name" class="block w-full" />
                    <x-input-error :messages="$errors->get('company_name')" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="short_name" :value="__('Brand Name')" required hint="The shorter brand/display name shown in products, suppliers, and purchase orders. Example: L'Oreal." />
                    <x-text-input id="short_name" name="short_name" wire:model="short_name" class="block w-full" />
                    <x-input-error :messages="$errors->get('short_name')" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="phone" :value="__('Phone')" hint="Indian contact number. Example: +91 98765 43210." />
                    <x-text-input id="phone" name="phone" wire:model="phone" class="block w-full" placeholder="+91 98765 43210" />
                    <x-input-error :messages="$errors->get('phone')" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', { name: 'company-modal' })">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button type="submit">{{ $isEditing ? __('Save Changes') : __('Create Company') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
