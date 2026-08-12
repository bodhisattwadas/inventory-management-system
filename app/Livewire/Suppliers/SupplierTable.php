<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use App\Services\SupplierService;
use App\Exceptions\SupplierException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class SupplierTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'supplier-table';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('supplier_export_' . now()->format('Y_m_d'))
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 25, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Supplier::query()->with('companies');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('contact_person')
            ->add('email')
            ->add('phone')
            ->add('companies_list', fn (Supplier $supplier) => $supplier->companies->pluck('company_name')->join(', '))
            ->add('bank_account', fn (Supplier $supplier) => $supplier->masked_account_number ?? '-')
            ->add('status')
            ->add('address')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Contact Person', 'contact_person')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Brands / Companies', 'companies_list'),

            Column::make('Bank Account', 'bank_account'),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            // Exports
            Column::make('Address', 'address')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Notes', 'notes')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Bank Name', 'bank_name')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Bank Account Last 4', 'account_number_last4')
                ->hidden()
                ->visibleInExport(true),

            Column::action('Action'),
        ];
    }

    public function actions(Supplier $row): array
    {
        return [
            Button::add('view')
                ->slot('View')
                ->class('bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-medium inline-flex items-center justify-center')
                ->dispatch('show-supplier', ['supplier' => $row->id])
                ->tooltip('View Supplier'),

            Button::add('edit')
                ->slot('Edit')
                ->class('bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-md text-xs font-medium inline-flex items-center justify-center')
                ->dispatch('edit-supplier', ['supplier' => $row->id])
                ->tooltip('Edit Supplier'),

            Button::add('delete')
                ->slot('Delete')
                ->class('bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-medium inline-flex items-center justify-center')
                ->dispatch('open-delete-modal', [
                    'component' => 'suppliers.supplier-table',
                    'method' => 'delete',
                    'params' => ['rowId' => $row->id],
                    'title' => 'Delete Supplier?',
                    'description' => "Are you sure you want to delete supplier '{$row->name}'? This action cannot be undone.",
                ])
                ->tooltip('Delete Supplier'),
        ];
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($rowId, SupplierService $supplierService): void
    {
        $supplier = Supplier::find($rowId);

        if ($supplier) {
            try {
                $supplierService->deleteSupplier($supplier);
                $this->dispatch('toast', message: 'Supplier deleted successfully.', type: 'success');
            } catch (\Exception $e) {
                $message = $e instanceof SupplierException
                    ? $e->getMessage()
                    : 'Failed to delete supplier: ' . $e->getMessage();

                $this->dispatch('toast', message: $message, type: 'error');
            }
        }
    }
}
