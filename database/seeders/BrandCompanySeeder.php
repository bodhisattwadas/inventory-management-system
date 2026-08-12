<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class BrandCompanySeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['BRD-001', 'Lakme'],
            ['BRD-002', 'Loreal'],
            ['BRD-003', 'Maybelline'],
            ['BRD-004', 'Garnier'],
            ['BRD-005', 'Revlon'],
            ['BRD-006', 'Colorbar'],
            ['BRD-007', 'Sugar Cosmetics'],
            ['BRD-008', 'Nykaa Cosmetics'],
            ['BRD-009', 'Mamaearth'],
            ['BRD-010', 'Biotique'],
            ['BRD-011', 'Himalaya'],
            ['BRD-012', 'Lotus Herbals'],
            ['BRD-013', 'Ponds'],
            ['BRD-014', 'Dove'],
            ['BRD-015', 'Nivea'],
            ['BRD-016', 'The Body Shop'],
            ['BRD-017', 'Faces Canada'],
            ['BRD-018', 'MAC Cosmetics'],
            ['BRD-019', 'Estee Lauder'],
            ['BRD-020', 'Clinique'],
        ];

        foreach ($brands as [$code, $name]) {
            Company::updateOrCreate(
                ['company_code' => $code],
                [
                    'company_name' => $name,
                    'short_name' => $name,
                    'company_type' => 'Brand',
                    'status' => 'active',
                ]
            );
        }
    }
}
