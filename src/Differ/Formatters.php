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
                return stylish($ast);
            };
        case 'plain':
            return function ($ast) {
                return plain($ast);
            };
        case 'json':
            return function ($ast) {
                return jsonFormatter($ast);
            };
        default:
            throw new \Exception("Unsupported format: {$format}");
    }
}
