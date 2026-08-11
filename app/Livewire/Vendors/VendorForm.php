<?php

namespace App\Livewire\Vendors;

use App\Models\Company;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Services\VendorService;
use Livewire\Attributes\On;
use Livewire\Component;

class VendorForm extends Component
{
    public ?Vendor $vendor = null;
    public bool $isEditing = false;
    public string $vendor_name = '';
    public string $legal_name = '';
    public string $trade_name = '';
    public string $vendor_type = '';
    public ?int $vendor_category_id = null;
    public string $primary_contact_person = '';
    public string $primary_email = '';
    public string $primary_phone = '';
    public string $alternate_phone = '';
    public string $accounts_email = '';
    public string $po_email = '';
    public string $registration_number = '';
    public string $website = '';
    public string $industry = '';
    public string $status = 'draft';
    public string $approval_status = 'draft';
    public bool $preferred_vendor = false;
    public bool $purchase_enabled = true;
    public bool $payment_enabled = true;
    public bool $blocked = false;
    public string $blocked_reason = '';
    public bool $blacklisted = false;
    public string $blacklist_reason = '';
    public array $company_ids = [];
    public string $bank_name = '';
    public string $bank_branch = '';
    public string $account_name = '';
    public string $account_number = '';
    public string $account_type = '';
    public string $ifsc_code = '';
    public string $swift_bic = '';
    public string $beneficiary_name = '';
    public string $bank_country = '';
    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $city = '';
    public string $state = '';
    public string $postal_code = '';
    public string $country = '';

    protected function rules(): array
    {
        return [
            'vendor_name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'vendor_type' => ['nullable', 'string', 'max:100'],
            'vendor_category_id' => ['nullable', 'exists:vendor_categories,id'],
            'primary_contact_person' => ['nullable', 'string', 'max:255'],
            'primary_email' => ['nullable', 'email', 'max:255'],
            'primary_phone' => ['nullable', 'string', 'max:50'],
            'alternate_phone' => ['nullable', 'string', 'max:50'],
            'accounts_email' => ['nullable', 'email', 'max:255'],
            'po_email' => ['nullable', 'email', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'approval_status' => ['required', 'string', 'max:50'],
            'preferred_vendor' => ['boolean'],
            'purchase_enabled' => ['boolean'],
            'payment_enabled' => ['boolean'],
            'blocked' => ['boolean'],
            'blocked_reason' => [$this->blocked ? 'required' : 'nullable', 'string', 'max:1000'],
            'blacklisted' => ['boolean'],
            'blacklist_reason' => [$this->blacklisted ? 'required' : 'nullable', 'string', 'max:1000'],
            'company_ids' => ['array'],
            'company_ids.*' => ['exists:companies,id'],
            'bank_name' => ['nullable', 'required_with:account_number', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_type' => ['nullable', 'string', 'max:100'],
            'ifsc_code' => ['nullable', 'string', 'max:50'],
            'swift_bic' => ['nullable', 'string', 'max:50'],
            'beneficiary_name' => ['nullable', 'string', 'max:255'],
            'bank_country' => ['nullable', 'string', 'max:100'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function render()
    {
        return view('livewire.vendors.vendor-form', [
            'companies' => Company::active()->orderBy('company_name')->limit(200)->get(),
            'categories' => VendorCategory::where('active', true)->orderBy('category_name')->get(),
        ]);
    }

    #[On('create-vendor')]
    public function create(): void
    {
        $this->reset();
        $this->status = 'draft';
        $this->approval_status = 'draft';
        $this->purchase_enabled = true;
        $this->payment_enabled = true;
        $this->dispatch('open-modal', name: 'vendor-modal');
    }

    #[On('edit-vendor')]
    public function edit(Vendor $vendor): void
    {
        $this->vendor = $vendor->load('companies');
        $this->isEditing = true;
        foreach (['vendor_name', 'legal_name', 'trade_name', 'vendor_type', 'primary_contact_person', 'primary_email', 'primary_phone', 'alternate_phone', 'accounts_email', 'po_email', 'registration_number', 'website', 'industry', 'status', 'approval_status', 'blocked_reason', 'blacklist_reason'] as $field) {
            $this->{$field} = (string) ($vendor->{$field} ?? '');
        }
        foreach (['vendor_category_id', 'preferred_vendor', 'purchase_enabled', 'payment_enabled', 'blocked', 'blacklisted'] as $field) {
            $this->{$field} = $vendor->{$field};
        }
        $this->company_ids = $vendor->companies->pluck('id')->all();
        $this->dispatch('open-modal', name: 'vendor-modal');
    }

    public function save(VendorService $service): void
    {
        $validated = $this->validate();
        $companyIds = $validated['company_ids'] ?? [];
        $bankData = collect($validated)->only([
            'bank_name',
            'bank_branch',
            'account_name',
            'account_number',
            'account_type',
            'ifsc_code',
            'swift_bic',
            'beneficiary_name',
        ])->filter(fn ($value) => filled($value))->all();
        $addressData = collect($validated)->only([
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'postal_code',
            'country',
        ])->filter(fn ($value) => filled($value))->all();
        if (isset($validated['bank_country'])) {
            $bankData['country'] = $validated['bank_country'];
        }
        unset($validated['company_ids']);
        unset(
            $validated['bank_name'],
            $validated['bank_branch'],
            $validated['account_name'],
            $validated['account_number'],
            $validated['account_type'],
            $validated['ifsc_code'],
            $validated['swift_bic'],
            $validated['beneficiary_name'],
            $validated['bank_country'],
            $validated['address_line_1'],
            $validated['address_line_2'],
            $validated['city'],
            $validated['state'],
            $validated['postal_code'],
            $validated['country'],
        );

        if ($this->isEditing && $this->vendor) {
            $vendor = $service->update($this->vendor, $validated);
            $service->syncCompanies($vendor, $companyIds);
        } else {
            $vendor = $service->create($validated, $companyIds);
        }

        if (! empty($bankData['account_number'])) {
            $bankData['is_primary'] = true;
            $service->addBankAccount($vendor, $bankData);
        }

        if (! empty($addressData)) {
            $addressData['address_type'] = 'Registered Office';
            $addressData['is_default'] = true;
            $service->addAddress($vendor, $addressData);
        }

        $this->dispatch('close-modal', name: 'vendor-modal');
        $this->dispatch('pg:eventRefresh-vendor-table');
        $this->dispatch('toast', message: 'Vendor saved successfully.', type: 'success');
        $this->reset();
    }
}
