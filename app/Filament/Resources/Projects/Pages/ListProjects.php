<?php

namespace App\Filament\Resources\Projects\Pages;


use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\Status;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        // Adding `all` as our first tab
        $tabs['all'] = Tab::make('All Projects')
            // We will add a badge to show how many projects are in this tab
            ->badge(Project::count());

        // Add 'My Projects' tab for the authenticated user's projects
        if (auth()->user()) {
            $tabs['my'] = Tab::make('My Projects')
                ->badge(Project::where('created_by', auth()->id())->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('created_by', auth()->id());
                });
        }

        // Load all Statuses
        $statuses = Status::orderBy('position')->withCount('projects')->get();

        // Loop through each Status
        foreach ($statuses as $status) {
            // Add a tab for each Status
            // Array index is going to be used in the URL as a slug, so we transform the name into a slug
            $tabs[str($status->name)->slug()->toString()] = Tab::make($status->name)
                // We will add a badge to show how many projects are in this tab
                ->badge($status->projects_count)
                // We will modify the query to only show projects in this Status
                ->modifyQueryUsing(function ($query) use ($status) {
                    return $query->where('status_id', $status->id);
                });
        }

        // Add 'Archived' tab for soft-deleted projects
        $tabs['archived'] = Tab::make('Archived')
            ->badge(Project::onlyTrashed()->count())
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            });

        return $tabs;
    }
}
