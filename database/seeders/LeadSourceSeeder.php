<?php

namespace Database\Seeders;

use App\Models\LeadSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leadSources = [
            'Website',
            'Online AD',
            'whatsapp',
            'LinkedIn',
            'Phone',
            'Other',
        ];
        foreach ($leadSources as $leadSource) {
            LeadSource::create(['name' => $leadSource]);
        }
    }
}
