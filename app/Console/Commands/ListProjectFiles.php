<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListProjectFiles extends Command
{
    protected $signature = 'project:list-files';
    protected $description = 'List Models and Filament-related files';

    public function handle()
    {
        $directories = [
            'Models' => base_path('app/Models'),
            'Filament' => base_path('app/Filament'),
            'Blade Views' => base_path('resources/views/filament'),
            'Translations' => base_path('lang'),
            'Panel Provider' => base_path('app/Providers/Filament'),
            'Custom CSS' => base_path('resources/css/filament/dashboard'),
        ];

        foreach ($directories as $name => $path) {
            $this->info("$name:");
            if (File::exists($path)) {
                $this->listDirectory($path, '');
            } else {
                $this->warn("Directory $path does not exist.");
            }
            $this->newLine();
        }
    }

    protected function listDirectory($path, $indent = '')
    {
        $files = File::allFiles($path);
        $directories = File::directories($path);

        foreach ($directories as $dir) {
            $relativeDir = str_replace(base_path(), '', $dir);
            $this->info("$indent- $relativeDir");
            $this->listDirectory($dir, $indent . '  ');
        }

        foreach ($files as $file) {
            $relativePath = str_replace(base_path(), '', $file->getPathname());
            $this->info("$indent- $relativePath");
        }
    }
}
