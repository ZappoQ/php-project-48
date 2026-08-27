<?php

namespace ZappoQ;

use function ZappoQ\buildTree;
use function ZappoQ\Formatters\stylish;

function genDiff(array $data1, array $data2, string $format = 'stylish'): string
{
    $ast = buildTree($data1, $data2);

    if ($format === 'stylish') {
        return "{\n" . stylish($ast) . "\n}";
    }
    
    throw new \Exception("Unsupported format: {$format}");
}
