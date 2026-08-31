<?php

namespace Differ\Differ;

use function Differ\Differ\Formatters\stylish;
use function Differ\Differ\Formatters\plain;
use function Differ\Differ\Formatters\json as jsonFormatter;

function getFormatter(string $format): callable
{
    switch ($format) {
        case 'stylish':
            return function ($ast) {
                return "{\n" . Formatters\stylish($ast) . "\n}";
            };
        case 'plain':
            return function ($ast) {
                return Formatters\plain($ast);
            };
        case 'json':
            return function ($ast) {
                return Formatters\json($ast);
            };
        default:
            throw new \Exception("Unsupported format: {$format}");
    }
}
