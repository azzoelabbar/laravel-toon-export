<?php

namespace Hashed\ToonExport\Exporters;


use Hashed\ToonExport\Services\ToonFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CollectionToonExporter
{
    public function __construct(
        protected ToonFormatter $formatter
    ) {}

    public function fromCollection(
        Collection|EloquentCollection $collection,
        string $name = 'items',
        ?array $columns = null
    ): string {
        return $this->formatter->format($collection, $name, $columns);
    }

    public function fromQuery(
        Builder $query,
        string $name = 'items',
        ?array $columns = null
    ): string {
        $collection = $query->get();
        return $this->fromCollection($collection, $name, $columns);
    }
}

