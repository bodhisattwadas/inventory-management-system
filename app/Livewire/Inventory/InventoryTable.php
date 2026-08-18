<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryStock;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class InventoryTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'inventory-table';
    public string $sortField = 'updated_at';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable('inventory_export_' . now()->format('Y_m_d'))
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return InventoryStock::query()
            ->with(['product.company', 'product.unit', 'product.category']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('sku', fn(InventoryStock $model) => $model->product?->sku ?: '-')
            ->add('product_name', fn(InventoryStock $model) => $model->product?->name ?: '-')
            ->add('brand_name', fn(InventoryStock $model) => $model->product?->company?->short_name ?: $model->product?->company?->company_name ?: '-')
            ->add('category_name', fn(InventoryStock $model) => $model->product?->category?->name ?: '-')
            ->add('quantity')
            ->add('unit', fn(InventoryStock $model) => $model->product?->unit?->symbol ?: $model->product?->unit?->name ?: '-')
            ->add('min_stock', fn(InventoryStock $model) => $model->product?->min_stock ?? 0)
            ->add('stock_badge', fn(InventoryStock $model) => $this->stockBadge($model))
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::make('SKU', 'sku')->searchable()->sortable(),
            Column::make('Product', 'product_name')->searchable()->sortable(),
            Column::make('Brand / Company', 'brand_name')->searchable()->sortable(),
            Column::make('Category', 'category_name')->searchable()->sortable(),
            Column::make('Quantity', 'quantity')->sortable()->headerAttribute('text-center')->bodyAttribute('text-center'),
            Column::make('Unit', 'unit'),
            Column::make('Min Quantity', 'min_stock')->headerAttribute('text-center')->bodyAttribute('text-center'),
            Column::make('Status', 'stock_badge')->headerAttribute('text-center')->bodyAttribute('text-center'),
        ];
    }

    private function stockBadge(InventoryStock $stock): string
    {
        $minStock = (int) ($stock->product?->min_stock ?? 0);
        $low = $stock->quantity <= $minStock;
        $class = $low
            ? 'border-red-200 bg-red-50 text-red-700'
            : 'border-emerald-200 bg-emerald-50 text-emerald-700';
        $label = $low ? 'Low Stock' : 'In Stock';

        return '<span class="inline-flex rounded-full border px-2 py-0.5 text-xs font-semibold '.$class.'">'.$label.'</span>';
    }
}
