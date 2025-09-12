<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Settings\CrmSettings;
use Illuminate\Support\Facades\DB;

class CrmSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing settings for the 'crm' group to avoid conflicts
        DB::table('settings')->where('group', 'crm')->delete();

        // Manually insert settings to debug
        DB::table('settings')->insert([
            'group' => 'crm',
            'name' => 'crm_settings',
            'locked' => false,
            'payload' => json_encode([
                'company_name' => ['en' => 'My CRM', 'ar' => 'نظام إدارة العملاء'],
                'logo' => null,
                'description' => ['en' => 'A powerful CRM system', 'ar' => 'نظام إدارة عملاء قوي'],
                'email' => 'contact@mycrm.com',
                'phone' => '+1234567890',
                'address' => ['en' => '123 CRM Street, City', 'ar' => '123 شارع إدارة العملاء، المدينة'],
                'currency' => 'USD',
                'timezone' => 'UTC',
                'menu_config' => [
                    'groups' => ['CRM', 'Settings'],
                    'custom_items' => [],
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verify settings can be loaded
        try {
            $settings = app(CrmSettings::class);
            $settings->toArray();
            \Log::info('CrmSettings seeded successfully: ' . json_encode($settings->toArray()));
        } catch (\Exception $e) {
            \Log::error('Failed to load CrmSettings: ' . $e->getMessage());
            throw $e;
        }
    }
}
