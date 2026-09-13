<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function getFormatter(string $format): callable
{
    $formatters = [
        'stylish' => fn ($tree) => stylish($tree),
        'plain' => fn ($tree) => plain($tree),
        'json' => fn ($tree) => json($tree),
    ];

    if (!isset($formatters[$format])) {
        throw new \Exception("Unsupported format: {$format}");
    }

    return $formatters[$format];
}
