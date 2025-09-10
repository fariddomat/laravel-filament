<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use App\Models\Task;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

      public function getTabs(): array
    {
        $tabs = [];

        if (auth()->user()->hasRole('employee')) {
            $tabs[] = Tab::make('My Tasks')
                ->badge(Task::where('user_id', auth()->id())->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('user_id', auth()->id());
                });
        }

        $tabs[] = Tab::make('All Tasks')
            ->badge(Task::count());

        $tabs[] = Tab::make('Completed Tasks')
            ->badge(Task::where('is_completed', true)->count())
            ->modifyQueryUsing(function ($query) {
                return $query->where('is_completed', true);
            });

        $tabs[] = Tab::make('Incomplete Tasks')
            ->badge(Task::where('is_completed', false)->count())
            ->modifyQueryUsing(function ($query) {
                return $query->where('is_completed', false);
            });

        return $tabs;
    }
}
