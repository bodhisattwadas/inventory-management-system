<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class InventoryService
{
    public function receivePurchaseItem(Purchase $purchase, PurchaseItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        $stock = InventoryStock::query()
            ->where('product_id', $item->product_id)
            ->lockForUpdate()
            ->first();

        if (! $stock) {
            $stock = InventoryStock::create([
                'product_id' => $item->product_id,
                'quantity' => 0,
            ]);
        }

        $stock->increment('quantity', $quantity);
        $stock->refresh();

        InventoryMovement::create([
            'product_id' => $item->product_id,
            'purchase_id' => $purchase->id,
            'purchase_item_id' => $item->id,
            'type' => 'purchase_receive',
            'quantity' => $quantity,
            'balance_after' => $stock->quantity,
            'reference' => $purchase->invoice_number,
            'notes' => 'Received from purchase order.',
        ]);
    }
}
