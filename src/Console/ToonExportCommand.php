<?php

namespace Egate\ToonExport\Console;

use Egate\ToonExport\Exporters\CollectionToonExporter;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ToonExportCommand extends Command
{
    protected $signature = 'toon:export
                            {model : Fully qualified model class, e.g. App\\Models\\User}
                            {--name= : Root TOON name, e.g. users}
                            {--columns= : Comma-separated list of columns}
                            {--path= : Subdirectory under storage/app (default: toon)}';

    protected $description = 'Export an Eloquent model to TOON format';

    public function __construct(
        protected CollectionToonExporter $exporter
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $modelClass = ltrim($this->argument('model'), '\\');

        if (! class_exists($modelClass)) {
            $this->error("Model class [{$modelClass}] does not exist.");
            return self::FAILURE;
        }

        if (! is_subclass_of($modelClass, Model::class)) {
            $this->error("[$modelClass] is not an Eloquent model.");
            return self::FAILURE;
        }

        $name = $this->option('name') ?: Str::snake(class_basename($modelClass));

        $columns = null;
        $columnsOption = $this->option('columns');
        if ($columnsOption) {
            $columns = array_filter(
                array_map('trim', explode(',', $columnsOption))
            );
        }

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $modelClass::query();

        if ($columns) {
            $query->select($columns);
        }

        $this->info("Exporting [{$modelClass}] to TOON...");
        $toon = $this->exporter->fromQuery($query, $name, $columns);

        $pathOption = $this->option('path') ?: 'toon';
        $fileName = $name . '.toon';
        $fullPath = storage_path('app/' . trim($pathOption, '/'). '/' . $fileName);

        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0775, true);
        }

        file_put_contents($fullPath, $toon);

        $this->info('Done.');
        $this->line('Saved to: ' . $fullPath);

        return self::SUCCESS;
    }
}

