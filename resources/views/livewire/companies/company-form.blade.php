<x-modal name="company-modal" :show="$errors->isNotEmpty()" focusable>
    <div class="p-6">
        <div class="mb-6 border-b border-gray-200 pb-4">
            <h3 class="text-lg font-semibold">{{ $isEditing ? __('Edit Brand / Company') : __('Add Brand / Company') }}</h3>
            <p class="text-sm text-muted-foreground">{{ __('Maintain the brands or companies that suppliers can be linked to.') }}</p>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-input name="company_code" label="Company Code" wire:model="company_code" required />
                <x-form-input name="company_name" label="Brand / Company Name" wire:model="company_name" required />
                <x-form-input name="short_name" label="Short Name" wire:model="short_name" />
                <x-form-input name="company_type" label="Type" wire:model="company_type" />
            </div>

            <div class="space-y-2">
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" wire:model="status" class="w-full rounded-md border-gray-300">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" />
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', { name: 'company-modal' })">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button type="submit">{{ $isEditing ? __('Save Changes') : __('Create Company') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
