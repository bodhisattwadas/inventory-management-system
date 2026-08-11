<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBankAccount;
use App\Services\VendorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class VendorManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_be_created_with_generated_stable_code(): void
    {
        $this->actingAs(User::factory()->create());

        $vendor = app(VendorService::class)->create([
            'vendor_name' => 'Acme Industrial Supplies',
            'legal_name' => 'Acme Industrial Supplies Pvt Ltd',
        ]);

        $this->assertSame('VND-000001', $vendor->vendor_code);

        app(VendorService::class)->update($vendor, [
            'vendor_code' => 'VND-999999',
            'vendor_name' => 'Acme Industrial Supplies Updated',
        ]);

        $this->assertSame('VND-000001', $vendor->refresh()->vendor_code);
    }

    public function test_vendor_can_link_to_multiple_active_companies_without_duplicates(): void
    {
        $this->actingAs(User::factory()->create());
        $vendor = Vendor::create(['vendor_code' => 'VND-000001', 'vendor_name' => 'Shared Vendor']);
        $first = Company::create(['company_code' => 'CMP-001', 'company_name' => 'First Company', 'status' => 'active']);
        $second = Company::create(['company_code' => 'CMP-002', 'company_name' => 'Second Company', 'status' => 'active']);

        app(VendorService::class)->assignCompany($vendor, $first->id);
        app(VendorService::class)->assignCompany($vendor, $second->id);

        $this->assertCount(2, $vendor->refresh()->companies);

        $this->expectException(ValidationException::class);
        app(VendorService::class)->assignCompany($vendor, $first->id);
    }

    public function test_inactive_company_cannot_be_assigned_to_new_vendor_mapping(): void
    {
        $this->actingAs(User::factory()->create());
        $vendor = Vendor::create(['vendor_code' => 'VND-000001', 'vendor_name' => 'Vendor']);
        $company = Company::create(['company_code' => 'CMP-001', 'company_name' => 'Inactive Company', 'status' => 'inactive']);

        $this->expectException(ValidationException::class);
        app(VendorService::class)->assignCompany($vendor, $company->id);
    }

    public function test_bank_account_number_is_encrypted_masked_and_primary_is_unique_per_scope(): void
    {
        $this->actingAs(User::factory()->create());
        $vendor = Vendor::create(['vendor_code' => 'VND-000001', 'vendor_name' => 'Banked Vendor']);

        $first = app(VendorService::class)->addBankAccount($vendor, [
            'bank_name' => 'First Bank',
            'account_number' => '123456789012',
            'is_primary' => true,
        ]);

        $second = app(VendorService::class)->addBankAccount($vendor, [
            'bank_name' => 'Second Bank',
            'account_number' => '987654321098',
            'is_primary' => true,
        ]);

        $this->assertNotSame('123456789012', $first->account_number_encrypted);
        $this->assertSame('XXXXXXXX9012', $first->masked_account_number);
        $this->assertSame('123456789012', $first->revealAccountNumber());
        $this->assertFalse($first->refresh()->is_primary);
        $this->assertTrue($second->refresh()->is_primary);
        $this->assertSame(2, VendorBankAccount::count());
    }

    public function test_blocked_vendor_requires_reason_and_cannot_be_purchasable(): void
    {
        $this->actingAs(User::factory()->create());

        $this->expectException(ValidationException::class);
        app(VendorService::class)->create([
            'vendor_name' => 'Blocked Vendor',
            'blocked' => true,
        ]);
    }

    public function test_purchasable_scope_excludes_blocked_or_inactive_vendors(): void
    {
        Vendor::create([
            'vendor_code' => 'VND-000001',
            'vendor_name' => 'Allowed',
            'status' => 'active',
            'approval_status' => 'approved',
            'purchase_enabled' => true,
            'blocked' => false,
        ]);
        Vendor::create([
            'vendor_code' => 'VND-000002',
            'vendor_name' => 'Blocked',
            'status' => 'blocked',
            'approval_status' => 'approved',
            'purchase_enabled' => true,
            'blocked' => true,
        ]);

        $this->assertSame(['Allowed'], Vendor::purchasable()->pluck('vendor_name')->all());
    }
}
