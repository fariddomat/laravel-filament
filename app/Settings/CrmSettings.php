<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class CrmSettings extends Settings
{
    use HasTranslations;

    public array $translatable = ['company_name', 'description', 'address'];

    public array $company_name;
    public ?string $logo;
    public array $description;
    public ?string $email;
    public ?string $phone;
    public array $address;
    public string $currency;
    public string $timezone;
    public array $menu_config;

    public static function group(): string
    {
        return 'crm';
    }

    public static function name(): string
    {
        return 'crm_settings';
    }
}
