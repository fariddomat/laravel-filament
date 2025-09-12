<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Not Started',
                'color' => '#6b7280', // Gray
                'position' => 1,
            ],
            [
                'name' => 'In Progress',
                'color' => '#3b82f6', // Blue
                'position' => 2,
            ],
            [
                'name' => 'Completed',
                'color' => '#22c55e', // Green
                'position' => 3,
            ],
            [
                'name' => 'On Hold',
                'color' => '#ef4444', // Red
                'position' => 4,
            ],
            [
                'name' => 'Cancelled',
                'color' => '#1f2937', // Dark Gray
                'position' => 5,
            ],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['name' => $status['name']],
                [
                    'color' => $status['color'],
                    'position' => $status['position'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
