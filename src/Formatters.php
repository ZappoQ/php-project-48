<?php

namespace ZappoQ;

require_once __DIR__ . '/Formatters/Stylish.php';
require_once __DIR__ . '/Formatters/Plain.php';
require_once __DIR__ . '/Formatters/Json.php';

use function ZappoQ\Formatters\stylish;
use function ZappoQ\Formatters\plain;
use function ZappoQ\Formatters\json as jsonFormatter;

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
