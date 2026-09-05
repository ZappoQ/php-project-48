<?php

namespace Differ\Differ;

function getFormatter(string $format): callable
{
    switch ($format) {
        case 'stylish':
            return function ($ast) {
                return Formatters\stylish($ast);
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
