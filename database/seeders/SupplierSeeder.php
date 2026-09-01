<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        $suppliers = [
            'Builder Ware',
            'YY Home Builder Depot',
            'City Hardware',
            'Wilcon Depot',
            'Three A Builder',
            'DN Steel Corporation',
            'Puyat Steel Corporation',
            'Steel Tech Corporation',
            'Triumph Hardware',
            'Saver Depot',
            'Cogon Commercial',
            'Val Anthony Construction',
            'PT Construction Supply',
            'Fixrites Hardware',
            'Alturas Glass Service',
            'Limson Hardware',
        ];

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->updateOrInsert(
                ['name' => $supplier],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
