<?php

namespace ZappoQ;

require_once __DIR__ . '/Formatters/Stylish.php';
require_once __DIR__ . '/Formatters/Plain.php';

use function ZappoQ\Formatters\stylish;
use function ZappoQ\Formatters\plain;

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
        default:
            throw new \Exception("Unsupported format: {$format}");
    }
}
