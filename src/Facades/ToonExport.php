<?php

namespace Hashed\ToonExport\Facades;

use Hashed\ToonExport\Exporters\CollectionToonExporter;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string fromCollection(\Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $collection, string $name = 'items', ?array $columns = null)
 * @method static string fromQuery(\Illuminate\Database\Eloquent\Builder $query, string $name = 'items', ?array $columns = null)
 *
 * @see \Hashed\ToonExport\Exporters\CollectionToonExporter
 */
class ToonExport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CollectionToonExporter::class;
    }
}

