<?php

namespace Database\Seeders;

use App\Enums\FinanceCategoryType;
use App\Enums\PaymentMethod;
use App\Enums\PurchaseStatus;
use App\Enums\SaleStatus;
use App\Models\Category;
use App\Models\Customer;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CosmeticsDemoPortalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->orderBy('id')->first();

        if (! $admin) {
            throw new \RuntimeException('Cannot seed cosmetics demo data without an existing user account.');
        }

        DB::transaction(function () use ($admin) {
            $categories = $this->seedCategories();
            $units = $this->seedUnits();
            $suppliers = $this->seedSuppliers();
            $customers = $this->seedCustomers();
            $financeCategories = $this->seedFinanceCategories();
            $products = $this->seedProducts($categories, $units);

            $this->seedPurchases($admin->id, $suppliers, $products);
            $this->seedSales($admin->id, $customers, $products);
            $this->seedManualFinanceTransactions($admin->id, $financeCategories);
        });
    }

    private function seedCategories(): array
    {
        $categories = [
            ['Face Makeup', 'Foundations, concealers, primers, powders, blush, and contour products.'],
            ['Eye Makeup', 'Mascara, eyeliner, eyebrow products, eyeshadow palettes, and eye primers.'],
            ['Lip Makeup', 'Lipstick, gloss, liner, balm, tint, and lip care products.'],
            ['Skin Care', 'Cleansers, toners, serums, moisturizers, sunscreen, masks, and treatments.'],
            ['Hair Care', 'Shampoo, conditioner, oils, masks, styling cream, and scalp care.'],
            ['Fragrance', 'Perfume, body mist, roll-ons, and travel fragrance.'],
            ['Tools & Accessories', 'Brushes, sponges, applicators, mirrors, pouches, and cosmetic tools.'],
            ['Body Care', 'Body lotion, scrubs, shower gel, hand cream, and deodorant.'],
        ];

        return collect($categories)
            ->mapWithKeys(fn ($category) => [
                $category[0] => Category::create([
                    'name' => $category[0],
                    'slug' => Str::slug($category[0]),
                    'description' => $category[1],
                ]),
            ])
            ->all();
    }

    private function seedUnits(): array
    {
        $units = [
            ['Piece', 'pcs'],
            ['Bottle', 'btl'],
            ['Tube', 'tube'],
            ['Jar', 'jar'],
            ['Palette', 'plt'],
            ['Box', 'box'],
            ['Set', 'set'],
            ['Sachet', 'sct'],
        ];

        return collect($units)
            ->mapWithKeys(fn ($unit) => [
                $unit[1] => Unit::create([
                    'name' => $unit[0],
                    'symbol' => $unit[1],
                ]),
            ])
            ->all();
    }

    private function seedSuppliers(): array
    {
        $suppliers = [
            ['LuxeGlow Cosmetics', 'Priya Nair', 'orders@luxeglow.test', '+91 98765 11001'],
            ['Velvet Rose Beauty', 'Aarav Mehta', 'supply@velvetrose.test', '+91 98765 11002'],
            ['DermaPure Labs', 'Kavya Rao', 'trade@dermapure.test', '+91 98765 11003'],
            ['ColorMuse Studio', 'Rhea Kapoor', 'wholesale@colormuse.test', '+91 98765 11004'],
            ['Herbelle Naturals', 'Neha Shah', 'partners@herbelle.test', '+91 98765 11005'],
            ['Urban Aura Fragrance', 'Kabir Khan', 'sales@urbanaura.test', '+91 98765 11006'],
            ['SilkStrand Haircare', 'Meera Iyer', 'orders@silkstrand.test', '+91 98765 11007'],
            ['ProBlend Tools', 'Vikram Sethi', 'distribution@problend.test', '+91 98765 11008'],
        ];

        return collect($suppliers)
            ->map(fn ($supplier) => Supplier::create([
                'name' => $supplier[0],
                'contact_person' => $supplier[1],
                'email' => $supplier[2],
                'phone' => $supplier[3],
                'address' => 'Demo cosmetics supplier warehouse, Mumbai, India',
                'notes' => 'Test brand supplier for cosmetics portal data.',
            ]))
            ->values()
            ->all();
    }

    private function seedCustomers(): array
    {
        $names = [
            'Glow House Retail', 'Beauty Bay Counter', 'The Vanity Store', 'Radiant Skin Clinic',
            'Makeup Maven Studio', 'Blush Basket', 'Urban Salon Supplies', 'Nexa Beauty Lounge',
            'Skin Rituals Spa', 'Palette Pro Academy', 'Fresh Face Boutique', 'Aura Wellness Store',
        ];

        return collect($names)
            ->map(fn ($name, $index) => Customer::create([
                'name' => $name,
                'email' => Str::slug($name).'.demo@example.test',
                'phone' => '+91 90000 '.str_pad((string) ($index + 2001), 4, '0', STR_PAD_LEFT),
                'address' => 'Demo customer address, Bengaluru, India',
                'notes' => 'Seeded test customer for cosmetics sales.',
            ]))
            ->values()
            ->all();
    }

    private function seedFinanceCategories(): array
    {
        $categories = [
            ['Product Sales', FinanceCategoryType::Income, 'Income from cosmetics product sales.'],
            ['Product Purchases', FinanceCategoryType::Expense, 'Cost of cosmetics inventory purchases.'],
            ['Salon Demo Income', FinanceCategoryType::Income, 'Income from beauty demos and sampling events.'],
            ['Influencer Marketing', FinanceCategoryType::Expense, 'Campaigns, creator samples, and brand promotion.'],
            ['Store Utilities', FinanceCategoryType::Expense, 'Electricity, water, and store operating utilities.'],
            ['Packaging Supplies', FinanceCategoryType::Expense, 'Gift bags, wraps, cartons, and labels.'],
        ];

        return collect($categories)
            ->mapWithKeys(fn ($category) => [
                $category[0] => FinanceCategory::create([
                    'name' => $category[0],
                    'slug' => Str::slug($category[0]),
                    'type' => $category[1],
                    'description' => $category[2],
                ]),
            ])
            ->all();
    }

    private function seedProducts(array $categories, array $units): array
    {
        $products = [
            ['LuxeGlow', 'Face Makeup', 'tube', 'LuxeGlow HD Matte Foundation - Warm Beige 30ml', 520, 899, 76],
            ['LuxeGlow', 'Face Makeup', 'pcs', 'LuxeGlow Soft Blur Compact Powder - Natural 9g', 260, 499, 95],
            ['ColorMuse', 'Face Makeup', 'pcs', 'ColorMuse Cream Blush Stick - Coral Pop 8g', 310, 649, 63],
            ['Velvet Rose', 'Face Makeup', 'tube', 'Velvet Rose Hydrating Primer Gel 25ml', 360, 749, 48],
            ['DermaPure', 'Face Makeup', 'tube', 'DermaPure Bright Cover Concealer - Medium 10ml', 240, 529, 88],
            ['ColorMuse', 'Eye Makeup', 'plt', 'ColorMuse 12 Shade Eyeshadow Palette - Sunset Nude', 620, 1299, 42],
            ['LuxeGlow', 'Eye Makeup', 'pcs', 'LuxeGlow Waterproof Mascara - Black 12ml', 330, 699, 72],
            ['Velvet Rose', 'Eye Makeup', 'pcs', 'Velvet Rose Precision Liquid Eyeliner 2.5ml', 180, 399, 120],
            ['ColorMuse', 'Eye Makeup', 'pcs', 'ColorMuse Brow Sculpt Pencil - Dark Brown', 150, 349, 110],
            ['Velvet Rose', 'Lip Makeup', 'pcs', 'Velvet Rose Satin Lipstick - Ruby Muse 4g', 220, 549, 84],
            ['LuxeGlow', 'Lip Makeup', 'tube', 'LuxeGlow Plump Shine Lip Gloss - Peach 6ml', 190, 449, 91],
            ['Herbelle', 'Lip Makeup', 'pcs', 'Herbelle Tinted Lip Balm - Berry 5g', 120, 299, 140],
            ['ColorMuse', 'Lip Makeup', 'pcs', 'ColorMuse Transfer-Proof Liquid Lip - Mocha', 240, 599, 77],
            ['DermaPure', 'Skin Care', 'btl', 'DermaPure Vitamin C Brightening Serum 30ml', 540, 1199, 58],
            ['DermaPure', 'Skin Care', 'jar', 'DermaPure Ceramide Repair Moisturizer 50g', 430, 949, 66],
            ['Herbelle', 'Skin Care', 'btl', 'Herbelle Green Tea Gel Cleanser 100ml', 210, 499, 102],
            ['Herbelle', 'Skin Care', 'tube', 'Herbelle Mineral Sunscreen SPF 50 PA++++ 50g', 390, 899, 80],
            ['DermaPure', 'Skin Care', 'sct', 'DermaPure AHA BHA Peeling Sachet 10ml', 55, 149, 250],
            ['SilkStrand', 'Hair Care', 'btl', 'SilkStrand Keratin Smooth Shampoo 250ml', 260, 599, 74],
            ['SilkStrand', 'Hair Care', 'btl', 'SilkStrand Argan Repair Conditioner 250ml', 280, 649, 69],
            ['SilkStrand', 'Hair Care', 'jar', 'SilkStrand Deep Nourish Hair Mask 200g', 360, 799, 44],
            ['Herbelle', 'Hair Care', 'btl', 'Herbelle Rosemary Scalp Oil 100ml', 230, 549, 83],
            ['Urban Aura', 'Fragrance', 'btl', 'Urban Aura Eau De Parfum - Bloom 50ml', 780, 1699, 37],
            ['Urban Aura', 'Fragrance', 'btl', 'Urban Aura Body Mist - Citrus Day 150ml', 260, 649, 89],
            ['Urban Aura', 'Fragrance', 'pcs', 'Urban Aura Travel Roll-On - Musk 10ml', 180, 399, 115],
            ['ProBlend', 'Tools & Accessories', 'set', 'ProBlend 10 Piece Makeup Brush Set', 520, 1199, 46],
            ['ProBlend', 'Tools & Accessories', 'pcs', 'ProBlend Latex-Free Beauty Sponge', 95, 249, 160],
            ['ProBlend', 'Tools & Accessories', 'box', 'ProBlend Cotton Rounds Box - 100 Count', 80, 199, 210],
            ['Herbelle', 'Body Care', 'btl', 'Herbelle Cocoa Body Lotion 250ml', 220, 499, 92],
            ['Velvet Rose', 'Body Care', 'jar', 'Velvet Rose Sugar Body Scrub 200g', 300, 699, 54],
            ['Urban Aura', 'Body Care', 'tube', 'Urban Aura Hand Cream - Rose 50g', 120, 299, 130],
            ['DermaPure', 'Body Care', 'btl', 'DermaPure Niacinamide Body Wash 300ml', 250, 599, 68],
        ];

        return collect($products)
            ->map(fn ($product, $index) => Product::create([
                'category_id' => $categories[$product[1]]->id,
                'unit_id' => $units[$product[2]]->id,
                'sku' => 'COS.'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'name' => $product[0].' '.$product[3],
                'purchase_price' => $product[4],
                'selling_price' => $product[5],
                'quantity' => $product[6],
                'min_stock' => max(8, (int) floor($product[6] * 0.18)),
                'is_active' => true,
                'description' => 'Demo cosmetics inventory item from '.$product[0].'.',
                'notes' => 'Seeded by cosmetics demo portal agent.',
                'image_path' => null,
            ]))
            ->values()
            ->all();
    }

    private function seedPurchases(int $adminId, array $suppliers, array $products): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $items = Arr::random($products, 4);
            $date = now()->subDays(45 - ($i * 3))->toDateString();
            $purchase = Purchase::create([
                'invoice_number' => 'PUR-COS-'.now()->format('ymd').'-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'supplier_id' => $suppliers[($i - 1) % count($suppliers)]->id,
                'purchase_date' => $date,
                'due_date' => now()->subDays(45 - ($i * 3))->addDays(15)->toDateString(),
                'total' => 0,
                'status' => $i <= 8 ? PurchaseStatus::PAID : PurchaseStatus::RECEIVED,
                'notes' => 'Demo cosmetics stock purchase.',
                'created_by' => $adminId,
            ]);

            $total = 0;
            foreach ($items as $product) {
                $quantity = random_int(8, 26);
                $subtotal = $quantity * $product->purchase_price;
                $total += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->purchase_price,
                    'selling_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ]);
            }

            $purchase->update(['total' => $total]);
        }
    }

    private function seedSales(int $adminId, array $customers, array $products): void
    {
        for ($i = 1; $i <= 18; $i++) {
            $items = Arr::random($products, random_int(2, 5));
            $sale = Sale::create([
                'invoice_number' => 'SAL-COS-'.now()->format('ymd').'-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'customer_id' => $customers[($i - 1) % count($customers)]->id,
                'created_by' => $adminId,
                'sale_date' => now()->subDays(30 - $i)->setTime(random_int(10, 18), random_int(0, 59)),
                'status' => $i <= 16 ? SaleStatus::COMPLETED : SaleStatus::PENDING,
                'subtotal' => 0,
                'global_discount' => $i % 4 === 0 ? 150 : 0,
                'total_discount' => 0,
                'total' => 0,
                'cash_received' => 0,
                'change' => 0,
                'payment_method' => $i % 3 === 0 ? PaymentMethod::TRANSFER : PaymentMethod::CASH,
                'notes' => 'Demo cosmetics sale.',
            ]);

            $subtotal = 0;
            $itemDiscount = 0;
            foreach ($items as $product) {
                $quantity = random_int(1, 4);
                $discount = $product->selling_price > 800 ? 50 : 0;
                $finalPrice = $product->selling_price - $discount;
                $lineSubtotal = $quantity * $finalPrice;
                $subtotal += $lineSubtotal;
                $itemDiscount += $quantity * $discount;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'cost_price' => $product->purchase_price,
                    'unit_price' => $product->selling_price,
                    'discount' => $discount,
                    'final_price' => $finalPrice,
                    'subtotal' => $lineSubtotal,
                ]);
            }

            $totalDiscount = $itemDiscount + $sale->global_discount;
            $total = max(0, $subtotal - $sale->global_discount);
            $cashReceived = $sale->payment_method === PaymentMethod::CASH ? (int) ceil($total / 100) * 100 : $total;

            $sale->update([
                'subtotal' => $subtotal,
                'total_discount' => $totalDiscount,
                'total' => $total,
                'cash_received' => $cashReceived,
                'change' => max(0, $cashReceived - $total),
            ]);
        }
    }

    private function seedManualFinanceTransactions(int $adminId, array $financeCategories): void
    {
        $transactions = [
            ['Influencer Marketing', 18500, 'Creator kit dispatch and short-form video campaign.'],
            ['Store Utilities', 9200, 'Monthly electricity and water for cosmetics store.'],
            ['Packaging Supplies', 6400, 'Gift bags, cosmetic pouches, labels, and parcel boxes.'],
            ['Salon Demo Income', 12500, 'Weekend skin-care demo counter booking.'],
        ];

        foreach ($transactions as $index => $transaction) {
            FinanceTransaction::create([
                'code' => 'FTX-COS-'.now()->format('ymd').'-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'transaction_date' => now()->subDays(12 - ($index * 3))->toDateString(),
                'finance_category_id' => $financeCategories[$transaction[0]]->id,
                'amount' => $transaction[1],
                'description' => $transaction[2],
                'external_reference' => 'COS-DEMO-'.($index + 1),
                'created_by' => $adminId,
            ]);
        }
    }
}
