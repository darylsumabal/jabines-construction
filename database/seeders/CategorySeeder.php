<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['ref_code' => 'CAT-001', 'name' => 'Structural', 'description' => 'Structural construction materials'],
            ['ref_code' => 'CAT-002', 'name' => 'Electrical', 'description' => 'Electrical materials'],
            ['ref_code' => 'CAT-003', 'name' => 'Plumbing', 'description' => 'Plumbing materials'],
            ['ref_code' => 'CAT-004', 'name' => 'Architectural', 'description' => 'Architectural materials'],
            ['ref_code' => 'CAT-005', 'name' => 'General Materials', 'description' => 'General construction materials'],
            ['ref_code' => 'CAT-006', 'name' => 'Roofing', 'description' => 'Roofing materials'],
            ['ref_code' => 'CAT-007', 'name' => 'Finishing', 'description' => 'Finishing materials'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['ref_code' => $category['ref_code']],
                $category
            );
        }
    }
}
