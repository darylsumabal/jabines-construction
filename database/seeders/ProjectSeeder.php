<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $projects = [
            ['project_code' => 'PRJ-001', 'project_name' => 'Peremeter Fence', 'client' => 'Rustica Varuez-Palapar', 'budget' => 1682197.78, 'status' => 'in_progress'],
            // ['project_code' => 'PRJ-002', 'project_name' => 'Dau Residential Subdivision', 'client' => 'Dau Housing Development Corp.', 'budget' => 45000000.00, 'status' => 'Ongoing'],
            // ['project_code' => 'PRJ-003', 'project_name' => 'Mabalacat Public Market Renovation', 'client' => 'Mabalacat City Government', 'budget' => 8500000.00, 'status' => 'Completed'],
            // ['project_code' => 'PRJ-004', 'project_name' => 'Porac Warehouse Facility', 'client' => 'Porac Industrial Holdings', 'budget' => 22000000.00, 'status' => 'Ongoing'],
            // ['project_code' => 'PRJ-005', 'project_name' => 'Clark View Condominium', 'client' => 'Clark View Realty Inc.', 'budget' => 65000000.00, 'status' => 'Planning'],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['project_code' => $project['project_code']],
                $project
            );
        }
    }
}
