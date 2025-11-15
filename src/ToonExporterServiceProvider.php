<?php

namespace Egate\ToonExport;

use Egate\ToonExport\Console\ToonExportCommand;
use Egate\ToonExport\Exporters\CollectionToonExporter;
use Egate\ToonExport\Services\ToonFormatter;
use Illuminate\Support\ServiceProvider;

class ToonExporterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ToonFormatter::class, function () {
            return new ToonFormatter();
        });

        $this->app->singleton(CollectionToonExporter::class, function ($app) {
            return new CollectionToonExporter(
                $app->make(ToonFormatter::class)
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ToonExportCommand::class,
            ]);
        }
    }
}

