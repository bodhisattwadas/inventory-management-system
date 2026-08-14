<?php

namespace App\Livewire\Companies;

use App\Models\Company;
use Livewire\Attributes\On;
use Livewire\Component;

class CompanyDetail extends Component
{
    public ?Company $company = null;

    public function render()
    {
        return view('livewire.companies.company-detail');
    }

    #[On('show-company')]
    public function show(Company $company): void
    {
        $this->company = $company->loadCount(['suppliers', 'vendors']);
        $this->dispatch('open-modal', name: 'company-detail-modal');
    }
}
