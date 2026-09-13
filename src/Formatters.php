<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function getFormatter(string $format): callable
{
    return match ($format) {
        'stylish' => stylish(...),
        'plain' => plain(...),
        'json' => json(...),
        default => throw new \Exception("Unsupported format: {$format}"),
    };
}
