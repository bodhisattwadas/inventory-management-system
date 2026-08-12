<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug')->toArray();
        $units = Unit::pluck('id', 'symbol')->toArray();
        $brandIds = Company::query()->active()->pluck('id')->values();

        $categoryId = fn (string $name) => $categories[Str::slug($name)] ?? reset($categories);
        $unitId = fn (string $symbol) => $units[$symbol] ?? reset($units);

        $products = [
            ['Face Makeup', 'tube', 'LuxeGlow HD Matte Foundation - Warm Beige 30ml', 520, 899, 76],
            ['Face Makeup', 'pcs', 'LuxeGlow Soft Blur Compact Powder - Natural 9g', 260, 499, 95],
            ['Face Makeup', 'pcs', 'ColorMuse Cream Blush Stick - Coral Pop 8g', 310, 649, 63],
            ['Eye Makeup', 'plt', 'ColorMuse 12 Shade Eyeshadow Palette - Sunset Nude', 620, 1299, 42],
            ['Eye Makeup', 'pcs', 'LuxeGlow Waterproof Mascara - Black 12ml', 330, 699, 72],
            ['Eye Makeup', 'pcs', 'Velvet Rose Precision Liquid Eyeliner 2.5ml', 180, 399, 120],
            ['Lip Makeup', 'pcs', 'Velvet Rose Satin Lipstick - Ruby Muse 4g', 220, 549, 84],
            ['Lip Makeup', 'tube', 'LuxeGlow Plump Shine Lip Gloss - Peach 6ml', 190, 449, 91],
            ['Skin Care', 'btl', 'DermaPure Vitamin C Brightening Serum 30ml', 540, 1199, 58],
            ['Skin Care', 'jar', 'DermaPure Ceramide Repair Moisturizer 50g', 430, 949, 66],
            ['Skin Care', 'tube', 'Herbelle Mineral Sunscreen SPF 50 PA++++ 50g', 390, 899, 80],
            ['Hair Care', 'btl', 'SilkStrand Keratin Smooth Shampoo 250ml', 260, 599, 74],
            ['Fragrance', 'btl', 'Urban Aura Eau De Parfum - Bloom 50ml', 780, 1699, 37],
            ['Tools & Accessories', 'set', 'ProBlend 10 Piece Makeup Brush Set', 520, 1199, 46],
            ['Tools & Accessories', 'pcs', 'ProBlend Latex-Free Beauty Sponge', 95, 249, 160],
            ['Body Care', 'btl', 'Herbelle Cocoa Body Lotion 250ml', 220, 499, 92],
        ];

        foreach ($products as $index => $product) {
            Product::create([
                'category_id' => $categoryId($product[0]),
                'unit_id' => $unitId($product[1]),
                'company_id' => $brandIds->isNotEmpty() ? $brandIds[$index % $brandIds->count()] : null,
                'sku' => 'COS.'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'name' => $product[2],
                'mrp' => $product[4],
                'purchase_price' => $product[4],
                'selling_price' => $product[4],
                'quantity' => $product[5],
                'min_stock' => max(8, (int) floor($product[5] * 0.18)),
                'is_active' => true,
                'description' => 'Seeded cosmetics inventory item.',
                'notes' => 'English demo data for cosmetics portal exports and reports.',
                'image_path' => null,
            ]);
        }
    }
}
