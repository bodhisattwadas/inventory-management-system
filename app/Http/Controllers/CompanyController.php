<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;

class CompanyController extends Controller
{
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Brand / company deleted successfully.');
    }
}
