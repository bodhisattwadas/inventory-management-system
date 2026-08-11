<?php

namespace App\Livewire\Companies;

use App\Models\Company;
use App\Services\CompanyService;
use Livewire\Attributes\On;
use Livewire\Component;

class CompanyForm extends Component
{
    public ?Company $company = null;
    public bool $isEditing = false;
    public string $company_code = '';
    public string $company_name = '';
    public string $legal_name = '';
    public string $short_name = '';
    public string $company_type = '';
    public string $gstin = '';
    public string $pan = '';
    public string $primary_email = '';
    public string $phone = '';
    public string $city = '';
    public string $state = '';
    public string $country = '';
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'company_code' => ['required', 'string', 'max:50', 'unique:companies,company_code,' . ($this->company?->id)],
            'company_name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:100'],
            'company_type' => ['nullable', 'string', 'max:100'],
            'gstin' => ['nullable', 'string', 'max:50'],
            'pan' => ['nullable', 'string', 'max:50'],
            'primary_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function render()
    {
        return view('livewire.companies.company-form');
    }

    #[On('create-company')]
    public function create(): void
    {
        $this->reset();
        $this->status = 'active';
        $this->dispatch('open-modal', name: 'company-modal');
    }

    #[On('edit-company')]
    public function edit(Company $company): void
    {
        $this->company = $company;
        $this->isEditing = true;
        foreach (array_keys($this->rules()) as $field) {
            $this->{$field} = (string) ($company->{$field} ?? '');
        }
        $this->dispatch('open-modal', name: 'company-modal');
    }

    public function save(CompanyService $service): void
    {
        $validated = $this->validate();
        $this->isEditing && $this->company
            ? $service->update($this->company, $validated)
            : $service->create($validated);

        $this->dispatch('close-modal', name: 'company-modal');
        $this->dispatch('pg:eventRefresh-company-table');
        $this->dispatch('toast', message: 'Company saved successfully.', type: 'success');
        $this->reset();
    }
}
