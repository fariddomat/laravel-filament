<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->searchable()
                    ->relationship('customer')
                    ->getOptionLabelFromRecordUsing(fn(Customer $record) => $record->first_name . ' ' . $record->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->required(),
                Select::make('employee_id') // Match the foreign key in your model (e.g., Customer)
                    ->relationship(
                        name: 'employee', // The relationship method name on your model
                        titleAttribute: 'name', // The attribute to display (e.g., user name)
                        modifyQueryUsing: fn($query) => $query->role('Employee') // Filter users with Employee role
                    )
                    ->preload()
                    ->searchable()
                    ->label('Employee'),
                RichEditor::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                DatePicker::make('due_date'),
                Toggle::make('is_completed')
                    ->required(),
            ]);
    }
}
