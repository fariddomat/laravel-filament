<?php

namespace App\Filament\Resources\CrmSettings\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class CrmSettingsForm
{
    use Translatable;  // Enables translatable features in the resource
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company Information')
                    ->schema([
                        // Custom translatable TextInput for company_name
                        static::translatableField('company_name', 'Company Name', function ($name, $label) {
                            return TextInput::make($name)
                                ->label($label)
                                ->maxLength(255);
                        }),
                        FileUpload::make('payload.logo')
                            ->label('Logo')
                            ->image()
                            ->directory('crm/logos')
                            ->preserveFilenames()
                            ->maxSize(2048),
                        // Custom translatable Textarea for description
                        static::translatableField('description', 'Description', function ($name, $label) {
                            return Textarea::make($name)
                                ->label($label)
                                ->maxLength(1000)
                                ->rows(4);
                        }),
                        TextInput::make('payload.email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('payload.phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(20),
                        // Custom translatable Textarea for address
                        static::translatableField('address', 'Address', function ($name, $label) {
                            return Textarea::make($name)
                                ->label($label)
                                ->maxLength(500)
                                ->rows(3);
                        }),
                    ]),
                Section::make('General')
                    ->schema([
                        Select::make('payload.currency')
                            ->label('Currency')
                            ->options([
                                'USD' => 'US Dollar',
                                'EUR' => 'Euro',
                                'GBP' => 'British Pound',
                            ])
                            ->default('USD')
                            ->required(),
                        Select::make('payload.timezone')
                            ->label('Timezone')
                            ->options(timezone_identifiers_list())
                            ->default('UTC')
                            ->required(),
                    ]),
                Section::make('Menu Configuration')
                    ->schema([
                        Repeater::make('payload.menu_config.groups')
                            ->label('Menu Groups')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Group Name')
                                    ->required()
                                    ->maxLength(100),
                            ])
                            ->default([['name' => 'CRM'], ['name' => 'Settings']]),
                        Repeater::make('payload.menu_config.custom_items')
                            ->label('Custom Menu Items')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Item Label')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('url')
                                    ->label('Item URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('icon')
                                    ->label('Item Icon')
                                    ->hint('e.g., heroicon-o-home')
                                    ->maxLength(100),
                            ])
                            ->default([]),
                    ]),
                // Locale switcher action (optional, for global switching)
                Action::make('switchLocale')
                    ->label('Switch Locale')
                    ->icon('heroicon-m-language')
                    ->color('gray')
                    ->action(function () {
                        // Custom logic for locale switching (e.g., redirect with locale param)
                        // Or integrate with a session-based locale switcher
                    }),
            ]);
    }

    // At the top of the class, after imports
protected static array $locales = ['en', 'ar'];  // Add your locales here

// Custom method to create translatable fields as tabs
    protected static function translatableField(string $fieldName, string $label, callable $fieldBuilder): Tabs
    {
        return Tabs::make($fieldName)
            ->tabs(
                collect(static::$locales)->map(function ($locale) use ($fieldName, $label, $fieldBuilder) {
                    return Tab::make($locale)
                        ->label(strtoupper($locale))
                        ->schema([
                            $fieldBuilder("payload.{$fieldName}.{$locale}", $label)
                                ->required(),
                        ]);
                })->toArray()
            );
    }
}
