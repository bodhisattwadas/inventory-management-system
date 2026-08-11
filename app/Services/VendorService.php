<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Vendor;
use App\Models\VendorAddress;
use App\Models\VendorBankAccount;
use App\Models\VendorCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendorService
{
    public function __construct(private readonly AuditLogService $auditLogService)
    {
    }

    public function create(array $data, array $companyIds = []): Vendor
    {
        return DB::transaction(function () use ($data, $companyIds) {
            $data['vendor_code'] = $data['vendor_code'] ?? $this->nextVendorCode();
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();

            $this->validateStatusReasons($data);

            $vendor = Vendor::create($data);

            foreach ($companyIds as $companyId) {
                $this->assignCompany($vendor, (int) $companyId);
            }

            $this->auditLogService->record($vendor, $vendor->id, 'vendor_created', [], $vendor->toArray());

            return $vendor->refresh();
        });
    }

    public function update(Vendor $vendor, array $data): Vendor
    {
        return DB::transaction(function () use ($vendor, $data) {
            unset($data['vendor_code']);
            $data['updated_by'] = Auth::id();
            $this->validateStatusReasons($data);

            $old = $vendor->getOriginal();
            $vendor->update($data);

            $this->auditLogService->record($vendor, $vendor->id, 'vendor_updated', $old, $vendor->fresh()->toArray());

            return $vendor->refresh();
        });
    }

    public function syncCompanies(Vendor $vendor, array $companyIds): void
    {
        DB::transaction(function () use ($vendor, $companyIds) {
            $existingCompanyIds = $vendor->vendorCompanies()->pluck('company_id')->all();
            $companyIds = array_values(array_unique(array_map('intval', $companyIds)));

            foreach (array_diff($companyIds, $existingCompanyIds) as $companyId) {
                $this->assignCompany($vendor, $companyId);
            }

            foreach (array_diff($existingCompanyIds, $companyIds) as $companyId) {
                $vendor->vendorCompanies()
                    ->where('company_id', $companyId)
                    ->update([
                        'status' => 'inactive',
                        'effective_to' => now()->toDateString(),
                        'updated_by' => Auth::id(),
                    ]);
            }
        });
    }

    public function assignCompany(Vendor $vendor, int $companyId, array $settings = []): VendorCompany
    {
        return DB::transaction(function () use ($vendor, $companyId, $settings) {
            $company = Company::findOrFail($companyId);

            if (! $company->isActive()) {
                throw ValidationException::withMessages([
                    'company_id' => 'Inactive companies cannot be assigned to a vendor.',
                ]);
            }

            if ($vendor->vendorCompanies()->where('company_id', $companyId)->exists()) {
                throw ValidationException::withMessages([
                    'company_id' => 'This vendor is already assigned to the selected company.',
                ]);
            }

            if (($settings['is_primary'] ?? false) === true) {
                $vendor->vendorCompanies()->update(['is_primary' => false]);
            }

            $mapping = $vendor->vendorCompanies()->create(array_merge($settings, [
                'company_id' => $companyId,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]));

            $this->auditLogService->record($vendor, $vendor->id, 'company_added', [], [
                'company_id' => $companyId,
                'company_code' => $company->company_code,
            ]);

            return $mapping;
        });
    }

    public function deactivate(Vendor $vendor, ?string $reason = null): Vendor
    {
        return $this->changeStatus($vendor, 'inactive', $reason);
    }

    public function approve(Vendor $vendor): Vendor
    {
        return DB::transaction(function () use ($vendor) {
            $oldStatus = $vendor->status;
            $vendor->update([
                'status' => 'active',
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            $vendor->statusHistory()->create([
                'from_status' => $oldStatus,
                'to_status' => 'active',
                'changed_by' => Auth::id(),
            ]);

            return $vendor->refresh();
        });
    }

    public function block(Vendor $vendor, string $reason, string $type = 'all'): Vendor
    {
        return DB::transaction(function () use ($vendor, $reason, $type) {
            $vendor->update([
                'blocked' => true,
                'status' => 'blocked',
                'block_type' => $type,
                'blocked_reason' => $reason,
                'blocked_by' => Auth::id(),
                'blocked_at' => now(),
            ]);
            $this->auditLogService->record($vendor, $vendor->id, 'vendor_blocked', [], compact('reason', 'type'));

            return $vendor->refresh();
        });
    }

    public function addBankAccount(Vendor $vendor, array $data): VendorBankAccount
    {
        return DB::transaction(function () use ($vendor, $data) {
            $accountNumber = $data['account_number'];
            unset($data['account_number']);

            if (($data['is_primary'] ?? false) === true) {
                VendorBankAccount::query()
                    ->where('vendor_id', $vendor->id)
                    ->where('company_id', $data['company_id'] ?? null)
                    ->where('currency_id', $data['currency_id'] ?? null)
                    ->where('active', true)
                    ->update(['is_primary' => false]);
            }

            $account = new VendorBankAccount($data);
            $account->vendor_id = $vendor->id;
            $account->created_by = Auth::id();
            $account->updated_by = Auth::id();
            $account->account_number = $accountNumber;
            $account->save();

            $this->auditLogService->record($account, $account->id, 'bank_account_added', [], [
                'account_number' => $accountNumber,
                'bank_name' => $account->bank_name,
            ]);

            return $account->refresh();
        });
    }

    public function addAddress(Vendor $vendor, array $data): VendorAddress
    {
        return DB::transaction(function () use ($vendor, $data) {
            if (($data['is_default'] ?? false) === true) {
                $vendor->addresses()
                    ->where('address_type', $data['address_type'] ?? null)
                    ->where('company_id', $data['company_id'] ?? null)
                    ->where('active', true)
                    ->update(['is_default' => false]);
            }

            $address = $vendor->addresses()->create($data);
            $this->auditLogService->record($address, $address->id, 'address_added', [], $address->toArray());

            return $address->refresh();
        });
    }

    public function nextVendorCode(): string
    {
        $latest = Vendor::withTrashed()
            ->where('vendor_code', 'like', 'VND-%')
            ->orderByDesc('id')
            ->value('vendor_code');

        $number = $latest ? ((int) substr($latest, 4)) + 1 : 1;

        return 'VND-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
    }

    private function changeStatus(Vendor $vendor, string $status, ?string $reason = null): Vendor
    {
        return DB::transaction(function () use ($vendor, $status, $reason) {
            $from = $vendor->status;
            $vendor->update(['status' => $status, 'updated_by' => Auth::id()]);
            $vendor->statusHistory()->create([
                'from_status' => $from,
                'to_status' => $status,
                'reason' => $reason,
                'changed_by' => Auth::id(),
            ]);
            $this->auditLogService->record($vendor, $vendor->id, 'vendor_status_changed', ['status' => $from], ['status' => $status]);

            return $vendor->refresh();
        });
    }

    private function validateStatusReasons(array $data): void
    {
        if (($data['blocked'] ?? false) && blank($data['blocked_reason'] ?? null)) {
            throw ValidationException::withMessages(['blocked_reason' => 'Blocked vendors require a reason.']);
        }

        if (($data['blacklisted'] ?? false) && blank($data['blacklist_reason'] ?? null)) {
            throw ValidationException::withMessages(['blacklist_reason' => 'Blacklisted vendors require a reason.']);
        }
    }
}
