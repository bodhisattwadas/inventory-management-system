<?php

namespace App\Livewire\Vendors;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class VendorTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'vendor-table';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable('vendor_export_' . now()->format('Y_m_d'))->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage(perPage: 10, perPageValues: [10, 25, 50, 100])->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Vendor::query()
            ->with(['companies', 'contacts', 'taxDetails', 'bankAccounts'])
            ->leftJoin('vendor_tax_details', 'vendors.id', '=', 'vendor_tax_details.vendor_id')
            ->select('vendors.*')
            ->distinct();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('vendor_code')
            ->add('vendor_name')
            ->add('legal_name')
            ->add('companies_badges', fn (Vendor $vendor) => $vendor->companies->pluck('company_name')->take(3)->join(', '))
            ->add('primary_contact_person')
            ->add('primary_phone')
            ->add('primary_phone_formatted', fn($model) => format_indian_phone($model->primary_phone))
            ->add('primary_email')
            ->add('tax_id', fn (Vendor $vendor) => $vendor->taxDetails->first()?->gstin ?? $vendor->taxDetails->first()?->tax_registration_number)
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('Vendor ID', 'vendor_code')->sortable()->searchable(),
            Column::make('Vendor Name', 'vendor_name')->sortable()->searchable(),
            Column::make('Legal Name', 'legal_name')->sortable()->searchable(),
            Column::make('Companies Covered', 'companies_badges'),
            Column::make('Primary Contact', 'primary_contact_person')->searchable(),
            Column::make('Phone', 'primary_phone_formatted', 'primary_phone')->searchable(),
            Column::make('Email', 'primary_email')->searchable(),
            Column::make('GST / Tax ID', 'tax_id'),
            Column::make('Status', 'status')->sortable()->searchable(),
            Column::action('Action'),
        ];
    }

    public function actions(Vendor $row): array
    {
        return [
            Button::add('view')->slot('View')->class('bg-blue-600 text-white px-3 py-1 rounded-md')->dispatch('show-vendor', ['vendor' => $row->id]),
            Button::add('edit')->slot('Edit')->class('bg-amber-500 text-white px-3 py-1 rounded-md')->dispatch('edit-vendor', ['vendor' => $row->id]),
        ];
    }

    #[On('refresh-vendors')]
    public function refreshTable(): void
    {
    }
}
