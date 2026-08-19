<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryStock;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
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
            ->with(['product.company', 'product.unit', 'product.category', 'product.purchaseItems']);
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
            ->add('quantity_with_unit', fn(InventoryStock $model) => $this->numberWithUnit((int) $model->quantity, $model))
            ->add('batch_quantities', fn(InventoryStock $model) => $this->batchQuantities($model))
            ->add('unit', fn(InventoryStock $model) => $model->product?->unit?->symbol ?: $model->product?->unit?->name ?: '-')
            ->add('min_stock', fn(InventoryStock $model) => $model->product?->min_stock ?? 0)
            ->add('min_stock_with_unit', fn(InventoryStock $model) => $this->numberWithUnit((int) ($model->product?->min_stock ?? 0), $model))
            ->add('stock_badge', fn(InventoryStock $model) => $this->stockBadge($model))
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('')->visibleInExport(false),
            Column::make('SKU', 'sku')->searchable()->sortable(),
            Column::make('Product', 'product_name')->searchable()->sortable(),
            Column::make('Brand / Company', 'brand_name')->searchable()->sortable(),
            Column::make('Category', 'category_name')->searchable()->sortable(),
            Column::make('Quantity', 'quantity_with_unit', 'quantity')->sortable()->headerAttribute('text-center')->bodyAttribute('text-center'),
            Column::make('Batch Quantity', 'batch_quantities')->headerAttribute('text-center')->bodyAttribute('text-center')->visibleInExport(false),
            Column::make('Min Quantity', 'min_stock_with_unit', 'min_stock')->headerAttribute('text-center')->bodyAttribute('text-center'),
            Column::make('Status', 'stock_badge')->headerAttribute('text-center')->bodyAttribute('text-center'),
        ];
    }

    public function actions(InventoryStock $row): array
    {
        if (! $row->product_id) {
            return [];
        }

        return [
            Button::add('view-product')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>')
                ->class('inline-flex h-8 w-8 items-center justify-center rounded-md bg-blue-500 text-white hover:bg-blue-600')
                ->route('inventory.show', ['inventoryStock' => $row->id])
                ->tooltip('View Product'),
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

    private function batchQuantities(InventoryStock $stock): string
    {
        $batches = $stock->product?->purchaseItems
            ?->filter(fn ($item) => (int) ($item->received_quantity ?? 0) > 0)
            ->sortBy(fn ($item) => $item->expiry_date?->timestamp ?? PHP_INT_MAX)
            ->values() ?? collect();

        if ($batches->isEmpty()) {
            return '<span class="text-xs text-gray-400">-</span>';
        }

        $colors = [
            'border-blue-200 bg-blue-50 text-blue-700',
            'border-emerald-200 bg-emerald-50 text-emerald-700',
            'border-amber-200 bg-amber-50 text-amber-700',
            'border-fuchsia-200 bg-fuchsia-50 text-fuchsia-700',
            'border-cyan-200 bg-cyan-50 text-cyan-700',
        ];

        $html = '<div class="flex max-w-72 flex-wrap justify-center gap-1">';

        foreach ($batches as $index => $batch) {
            $label = ($batch->batch_number ?: 'Batch').' : '.(int) $batch->received_quantity;
            $class = $colors[$index % count($colors)];
            $html .= '<span class="inline-flex w-44 shrink-0 justify-between rounded-full border px-2 py-0.5 font-mono text-[10px] font-semibold leading-tight '.$class.'" title="'.e($label).'"><span>'.e($batch->batch_number ?: 'Batch').'</span><span class="pl-2">'.e((string) (int) $batch->received_quantity).'</span></span>';
        }

        $html .= '</div>';

        return $html;
    }

    private function numberWithUnit(int $number, InventoryStock $stock): string
    {
        $unit = $stock->product?->unit?->symbol ?: $stock->product?->unit?->name ?: '';

        $html = '<div class="flex flex-col items-center leading-tight">';
        $html .= '<span class="text-sm font-semibold text-gray-900">'.e((string) $number).'</span>';

        if ($unit !== '') {
            $html .= '<span class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide text-gray-500">'.e($unit).'</span>';
        }

        $html .= '</div>';

        return $html;
    }
}
