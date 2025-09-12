<?php

namespace App\Filament\Resources\CrmSettings;

use App\Filament\Resources\CrmSettings\Pages\CreateCrmSettings;
use App\Filament\Resources\CrmSettings\Pages\EditCrmSettings;
use App\Filament\Resources\CrmSettings\Pages\ListCrmSettings;
use App\Filament\Resources\CrmSettings\Schemas\CrmSettingsForm;
use App\Filament\Resources\CrmSettings\Tables\CrmSettingsTable;
use App\Models\CrmSettings;
use App\Models\Setting;
use App\Settings\CrmSettings as SettingsCrmSettings;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use UnitEnum;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class CrmSettingsResource extends Resource
{
    use InteractsWithForms, InteractsWithTable;
    use Translatable;

protected static array $locales = ['en', 'ar'];  // Add your locales here

    protected static ?string $model = Setting::class;
    // protected static ?string $model = SettingsCrmSettings::class;

    // No model needed for singleton settings
    // protected static ?string $model = null;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'CRM Settings';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('super_admin') || auth()->user()->hasPermissionTo('manage_crm_settings');
    }

    public static function form(Schema $schema): Schema
    {
        return CrmSettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CrmSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('group', 'crm')
            ->where('name', 'crm_settings');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCrmSettings::route('/'),
            // 'create' => CreateCrmSettings::route('/create'),
            'edit' => EditCrmSettings::route('/{record}/edit'),
        ];
    }
    // At the top of the class, after imports
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
