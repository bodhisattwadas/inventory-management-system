<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\DTOs\UserData;
use App\Services\UserService;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UserForm extends Component
{
    use WithFileUploads;

    public ?User $user = null;
    public bool $isEditing = false;

    public $name;
    public $username;
    public $email;
    public string $role = 'staff';
    public $profile_photo = null;
    public ?string $currentProfilePhotoPath = null;
    public $password;
    public $password_confirmation;

    public array $roles = [
        'admin' => 'Admin',
        'manager' => 'Manager',
        'staff' => 'Staff',
    ];

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'role' => ['required', Rule::in(array_keys($this->roles))],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'password' => [$this->isEditing ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    #[On('create-user')]
    public function create(): void
    {
        $this->reset(['user', 'isEditing', 'name', 'username', 'email', 'profile_photo', 'currentProfilePhotoPath', 'password', 'password_confirmation']);
        $this->role = 'staff';
        $this->dispatch('open-modal', name: 'user-form-modal');
    }

    #[On('edit-user')]
    public function edit(User $user): void
    {
        $this->user = $user;
        $this->isEditing = true;

        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role ?? 'staff';
        $this->profile_photo = null;
        $this->currentProfilePhotoPath = $user->profile_photo_path;
        $this->password = '';
        $this->password_confirmation = '';

        $this->dispatch('open-modal', name: 'user-form-modal');
    }

    public function save(UserService $service): void
    {
        $validated = $this->validate();
        $validated['profile_photo_path'] = $this->profile_photo
            ? $this->profile_photo->store('profile-photos', 'public')
            : null;

        $data = new UserData(
            name: $this->name,
            username: $this->username,
            email: $this->email,
            role: $this->role,
            profile_photo_path: $validated['profile_photo_path'],
            password: $this->password ?: null, // Pass null if empty in edit mode
        );

        try {
            if ($this->isEditing && $this->user) {
                $service->updateUser($this->user, $data);
                $message = 'User updated successfully.';
            } else {
                $service->createUser($data);
                $message = 'User created successfully.';
            }

            $this->dispatch('close-modal', name: 'user-form-modal');
            $this->dispatch('pg:eventRefresh-user-table');
            $this->dispatch('toast', message: $message, type: 'success');

            // Reset after save
            $this->reset(['user', 'isEditing', 'name', 'username', 'email', 'profile_photo', 'currentProfilePhotoPath', 'password', 'password_confirmation']);
            $this->role = 'staff';

        } catch (\Exception $e) {
            $this->deleteUploadedPhoto($validated['profile_photo_path']);
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    private function deleteUploadedPhoto(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
