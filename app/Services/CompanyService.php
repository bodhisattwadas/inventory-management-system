<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    public function create(array $data): Company
    {
        return DB::transaction(fn () => Company::create($data));
    }

    public function update(Company $company, array $data): Company
    {
        return DB::transaction(function () use ($company, $data) {
            $company->update($data);

            return $company->refresh();
        });
    }

    public function activate(Company $company): Company
    {
        $company->update(['status' => 'active']);

        return $company->refresh();
    }

    public function deactivate(Company $company): Company
    {
        $company->update(['status' => 'inactive']);

        return $company->refresh();
    }
}
