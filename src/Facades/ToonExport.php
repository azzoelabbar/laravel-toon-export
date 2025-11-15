<?php

namespace Egate\ToonExport\Facades;

use Egate\ToonExport\Exporters\CollectionToonExporter;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string fromCollection(\Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $collection, string $name = 'items', ?array $columns = null)
 * @method static string fromQuery(\Illuminate\Database\Eloquent\Builder $query, string $name = 'items', ?array $columns = null)
 *
 * @see \Egate\ToonExport\Exporters\CollectionToonExporter
 */
class ToonExport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CollectionToonExporter::class;
    }
}

