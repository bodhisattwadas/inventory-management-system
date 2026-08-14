<x-modal name="user-form-modal" :title="''" maxWidth="2xl">
    <div class="p-6">
        <!-- Custom Header -->
        <div class="mb-6 flex items-start justify-between gap-4 border-b border-gray-200 pb-4">
            <div class="space-y-1.5 text-center sm:text-left">
                <h3 class="text-lg font-semibold leading-none tracking-tight text-foreground">
                    {{ $isEditing ? 'Edit User' : 'Create User' }}
                </h3>
                <p class="text-sm text-muted-foreground">
                    {{ $isEditing ? 'Update user information.' : 'Add a new user to the system.' }}
                </p>
            </div>
            <button
                type="button"
                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                x-on:click="$dispatch('close')"
                aria-label="{{ __('Close') }}"
            >
                <x-heroicon-o-x-mark class="h-4 w-4" />
            </button>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div class="space-y-2">
                <x-input-label for="profile_photo" :value="__('Profile Photo')" hint="User avatar image. Example: manager.jpg." />
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <x-avatar
                        :name="$name ?: 'User'"
                        :src="$profile_photo ? $profile_photo->temporaryUrl() : ($currentProfilePhotoPath ? Storage::url($currentProfilePhotoPath) : null)"
                        class="h-20 w-20"
                    />
                    <div class="flex-1 space-y-2">
                        <input
                            id="profile_photo"
                            type="file"
                            wire:model="profile_photo"
                            accept="image/png,image/jpeg,image/webp"
                            class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-foreground hover:file:bg-primary/90"
                        >
                        <p class="text-xs text-muted-foreground">PNG, JPG, or WEBP up to 2 MB.</p>
                        <div wire:loading wire:target="profile_photo" class="text-xs text-muted-foreground">Uploading photo...</div>
                        <x-input-error :messages="$errors->get('profile_photo')" />
                    </div>
                </div>
            </div>

            <!-- Name -->
            <x-form-input
                name="name"
                label="Name"
                type="text"
                wire:model="name"
                required
                placeholder="Full Name"
                hint="User full name. Example: Priya Sharma."
            />

            <!-- Username -->
            <x-form-input
                name="username"
                label="Username"
                type="text"
                wire:model="username"
                required
                placeholder="Unique username"
                hint="Login username. Example: priya."
            />

            <!-- Email -->
            <x-form-input
                name="email"
                label="Email"
                type="email"
                wire:model="email"
                required
                placeholder="email@example.com"
                hint="Login and notification email. Example: priya@example.com."
            />

            <div class="space-y-2">
                <x-input-label for="role" :value="__('Role')" required hint="Permission group for this user. Example: Admin." />
                <select
                    id="role"
                    wire:model="role"
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                    required
                >
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" />
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-input-label for="password" :value="__('Password')" hint="New login password. Example: Use a strong password." />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        wire:model="password"
                        :required="!$isEditing"
                        autocomplete="new-password"
                        placeholder="{{ $isEditing ? 'Leave blank to keep current' : 'Min 8 chars' }}"
                    />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" hint="Repeat password to avoid typing errors. Example: Same as password." />
                    <x-text-input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        wire:model="password_confirmation"
                        :required="!$isEditing"
                        autocomplete="new-password"
                    />
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button type="submit" wire:loading.attr="disabled">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <x-heroicon-o-check wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" />
                    {{ $isEditing ? __('Save Changes') : __('Create User') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
