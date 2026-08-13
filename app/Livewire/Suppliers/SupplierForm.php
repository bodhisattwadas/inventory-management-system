<?php

namespace App\Livewire\Suppliers;

use Exception;
use Livewire\Component;
use App\Models\Company;
use App\Models\Supplier;
use App\DTOs\SupplierData;
use Livewire\Attributes\On;
use App\Services\SupplierService;

class SupplierForm extends Component
{
    public ?Supplier $supplier = null;

    public string $name = '';
    public string $legal_name = '';
    public string $trade_name = '';
    public string $supplier_type = '';
    public string $registration_number = '';
    public string $tax_id = '';
    public string $website = '';
    public string $industry = '';

    public string $contact_person = '';

    public string $email = '';
    public string $accounts_email = '';
    public string $purchase_email = '';

    public string $phone = '';
    public string $alternate_phone = '';

    public string $address = '';
    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $city = '';
    public string $state = '';
    public string $postal_code = '';
    public string $country = '';
    public string $bank_name = '';
    public string $bank_branch = '';
    public string $account_name = '';
    public string $account_number = '';
    public string $account_type = '';
    public string $ifsc_code = '';
    public string $swift_bic = '';
    public string $beneficiary_name = '';
    public string $bank_country = '';
    public string $status = 'active';
    public array $company_ids = [];

    public string $notes = '';

    public bool $isEditing = false;
    public bool $asPage = false;

    public function mount(bool $asPage = false): void
    {
        $this->asPage = $asPage;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'supplier_type' => ['nullable', 'string', 'max:100'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:suppliers,email,' . ($this->supplier?->id)],
            'accounts_email' => ['nullable', 'email', 'max:255'],
            'purchase_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'required_with:account_number', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_type' => ['nullable', 'in:Savings,Current'],
            'ifsc_code' => ['nullable', 'string', 'max:50'],
            'swift_bic' => ['nullable', 'string', 'max:50'],
            'beneficiary_name' => ['nullable', 'string', 'max:255'],
            'bank_country' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'company_ids' => ['array'],
            'company_ids.*' => ['exists:companies,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render()
    {
        return view('livewire.suppliers.supplier-form', [
            'companies' => Company::active()->orderBy('company_name')->get(),
        ]);
    }

    #[On('create-supplier')]
    public function create(): void
    {
        $this->reset();
        $this->isEditing = false;
        $this->dispatch('open-modal', name: 'supplier-modal');
    }

    #[On('edit-supplier')]
    public function edit(Supplier $supplier): void
    {
        $this->resetValidation();
        $this->supplier = $supplier;
        $supplier->load('companies');

        foreach (array_keys($this->rules()) as $field) {
            if ($field === 'company_ids' || str_contains($field, '.')) {
                continue;
            }

            $this->{$field} = (string) ($supplier->{$field} ?? '');
        }

        $this->account_number = '';
        $this->company_ids = $supplier->companies->pluck('id')->all();

        $this->isEditing = true;
        $this->dispatch('open-modal', name: 'supplier-modal');
    }

    public function save(SupplierService $service): void
    {
        $validated = $this->validate($this->rules());

        try {
            $supplierData = SupplierData::fromArray($validated);

            if ($this->isEditing && $this->supplier) {
                $service->updateSupplier($this->supplier, $supplierData);
                $message = 'Supplier updated successfully.';
            } else {
                $service->createSupplier($supplierData);
                $message = 'Supplier created successfully.';
            }

            $this->dispatch('close-modal', name: 'supplier-modal');
            $this->dispatch('pg:eventRefresh-default');
            $this->dispatch('pg:eventRefresh-supplier-table');
            $this->dispatch('toast', message: $message, type: 'success');

            if ($this->asPage) {
                $this->redirectRoute('suppliers.index', navigate: true);

                return;
            }

            $this->reset();

        } catch (Exception $e) {
            $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }
}
