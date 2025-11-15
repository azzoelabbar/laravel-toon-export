<?php

namespace Egate\ToonExport\Contracts;

interface ToonExportable
{
    /**
     * Return the list of columns to include in TOON export.
     *
     * @return array
     */
    public static function toonColumns(): array;
}

