<?php

namespace App\Services;

use Exception;
use App\Models\Product;
use Illuminate\Support\Str;
use App\DTOs\ProductData;
use App\Exceptions\ProductException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Create a new product.
     */
    public function createProduct(ProductData $data): Product
    {
        return DB::transaction(function () use ($data) {
            try {
                $sku = $data->sku ?? $this->generateUniqueSku();

                return Product::create([
                    'category_id' => $data->category_id,
                    'unit_id' => $data->unit_id,
                    'company_id' => $data->company_id,
                    'sku' => $sku,
                    'name' => $data->name,
                    'mrp' => $data->mrp,
                    'purchase_price' => $data->mrp,
                    'selling_price' => $data->mrp,
                    'quantity' => 0,
                    'min_stock' => $data->min_stock,
                    'is_active' => $data->is_active,
                    'description' => $data->description,
                    'notes' => $data->notes,
                    'image_path' => $data->image_path,
                ]);

            } catch (Exception $e) {
                throw ProductException::creationFailed($e->getMessage(), [
                    'data' => (array) $data,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, ProductData $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            try {
                $oldImagePath = $product->image_path;

                $product->update([
                    'category_id' => $data->category_id,
                    'unit_id' => $data->unit_id,
                    'company_id' => $data->company_id,
                    'sku' => $data->sku ?? $product->sku,
                    'name' => $data->name,
                    'mrp' => $data->mrp,
                    'purchase_price' => $data->mrp,
                    'selling_price' => $data->mrp,
                    'min_stock' => $data->min_stock,
                    'is_active' => $data->is_active,
                    'description' => $data->description,
                    'notes' => $data->notes,
                    'image_path' => $data->image_path ?? $product->image_path,
                ]);

                if ($data->image_path && $oldImagePath && $oldImagePath !== $data->image_path) {
                    Storage::disk('public')->delete($oldImagePath);
                }

                return $product->refresh();

            } catch (Exception $e) {
                throw ProductException::updateFailed($e->getMessage(), [
                    'id'   => $product->id,
                    'data' => (array) $data
                ]);
            }
        });
    }

    /**
     * Delete a product.
     */
    public function deleteProduct(Product $product): void
    {
        DB::transaction(function () use ($product) {
            try {
                if ($product->purchaseItems()->exists() || $product->saleItems()->exists()) {
                    throw new Exception('Cannot delete product because it is associated with purchase or sale records.');
                }

                $imagePath = $product->image_path;

                $product->delete();

                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }

            } catch (Exception $e) {
                throw ProductException::deletionFailed($e->getMessage(), ['id' => $product->id]);
            }
        });
    }

    /**
     * Generate a unique SKU in format P.YYMMDD.XXXX.
     */
    private function generateUniqueSku(): string
    {
        $prefix = 'P.' . date('ymd') . '.';

        do {
            $sku = $prefix . strtoupper(Str::random(4));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
