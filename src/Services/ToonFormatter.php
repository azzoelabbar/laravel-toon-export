<?php

namespace Egate\ToonExport\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ToonFormatter
{
    public function format(
        iterable $rows,
        ?string $name = 'items',
        ?array $columns = null
    ): string {
        if ($rows instanceof Collection) {
            $rows = $rows->values()->all();
        } else {
            $rows = is_array($rows) ? array_values($rows) : iterator_to_array($rows);
        }

        if (empty($rows)) {
            return $name . "[0]{}:\n";
        }

        if ($columns === null) {
            $first = $rows[0];
            $firstArray = is_object($first) && method_exists($first, 'toArray') 
                ? $first->toArray() 
                : (is_array($first) ? $first : (array) $first);
            $columns = array_keys($firstArray);
        }

        $count = count($rows);

        $header = sprintf(
            "%s[%d]{%s}:",
            $name,
            $count,
            implode(',', $columns)
        );

        $lines = [$header];

        foreach ($rows as $row) {
            $rowArray = is_object($row) && method_exists($row, 'toArray') 
                ? $row->toArray() 
                : (is_array($row) ? $row : (array) $row);
            $rowArray = Arr::only($rowArray, $columns);

            $escaped = array_map(function ($value) {
                if ($value === null) {
                    return '';
                }

                $value = (string) $value;

                // Very basic escaping: if comma or newline exists, wrap in quotes
                if (str_contains($value, ',') || str_contains($value, "\n")) {
                    $value = '"' . str_replace('"', '\"', $value) . '"';
                }

                return $value;
            }, $rowArray);

            $lines[] = implode(',', $escaped);
        }

        return implode("\n", $lines) . "\n";
    }
}

