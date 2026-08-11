<?php

namespace App\Livewire\Companies;

use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class CompanyTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'company-table';

    public string $sortField = 'company_name';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable('company_export_' . now()->format('Y_m_d'))->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage(perPage: 10, perPageValues: [10, 25, 50, 100])->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Company::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('company_code')
            ->add('company_name')
            ->add('legal_name')
            ->add('company_type')
            ->add('gstin')
            ->add('pan')
            ->add('primary_email')
            ->add('phone')
            ->add('city')
            ->add('state')
            ->add('country')
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'company_code')->sortable()->searchable(),
            Column::make('Company', 'company_name')->sortable()->searchable(),
            Column::make('Legal Name', 'legal_name')->sortable()->searchable(),
            Column::make('Type', 'company_type')->sortable()->searchable(),
            Column::make('GSTIN / Tax ID', 'gstin')->searchable(),
            Column::make('PAN / Reg No', 'pan')->searchable(),
            Column::make('Email', 'primary_email')->searchable(),
            Column::make('Phone', 'phone')->searchable(),
            Column::make('City', 'city')->sortable()->searchable(),
            Column::make('State', 'state')->sortable()->searchable(),
            Column::make('Country', 'country')->sortable()->searchable(),
            Column::make('Status', 'status')->sortable()->searchable(),
            Column::action('Action'),
        ];
    }

    public function actions(Company $row): array
    {
        return [
            Button::add('edit')->slot('Edit')->class('bg-amber-500 text-white px-3 py-1 rounded-md')->dispatch('edit-company', ['company' => $row->id]),
            Button::add('toggle')->slot($row->isActive() ? 'Deactivate' : 'Activate')->class('bg-slate-700 text-white px-3 py-1 rounded-md')->dispatch('toggle-company', ['company' => $row->id]),
        ];
    }

    #[On('toggle-company')]
    public function toggle(Company $company, CompanyService $service): void
    {
        $company->isActive() ? $service->deactivate($company) : $service->activate($company);
        $this->dispatch('pg:eventRefresh-company-table');
    }
}
