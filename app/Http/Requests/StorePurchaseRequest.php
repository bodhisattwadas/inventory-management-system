<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $items = collect($this->input('items', []))->map(function ($item) {
            if (! is_array($item)) {
                return $item;
            }

            $mrp = Product::query()->whereKey($item['product_id'] ?? null)->value('mrp');
            $discount = max(0, min(100, (float) ($item['discount_percent'] ?? 0)));

            if ($mrp !== null) {
                $item['unit_price'] = (int) round($mrp * (1 - $discount / 100));
                $item['selling_price'] = (int) $mrp;
            }

            return $item;
        })->all();

        $this->merge(['items' => $items]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'company_id' => [
                'required',
                Rule::exists('supplier_companies', 'company_id')
                    ->where('supplier_id', $this->input('supplier_id')),
            ],
            'invoice_number' => ['nullable', 'string', 'max:255', 'unique:purchases,invoice_number'],
            'purchase_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:purchase_date'],
            'notes' => ['nullable', 'string'],
            // 'status' is handled by controller (default: draft)
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                Rule::exists('products', 'id')->where('company_id', $this->input('company_id')),
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.discount_percent' => ['required', 'numeric', 'between:0,100'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.selling_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Please add at least one item.',
            'items.*.product_id.required' => 'Product is required.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.discount_percent.between' => 'MRP discount must be between 0% and 100%.',
            'company_id.exists' => 'The selected brand is not supplied by this vendor.',
            'items.*.product_id.exists' => 'Every product must belong to the selected brand.',
        ];
    }
}
