<?php

namespace Differ\Differ;

use function Differ\Differ\buildTree;
use function Differ\Differ\getFormatter;
use function Differ\Differ\parseFile;

function genDiff($data1, $data2, string $format = 'stylish'): string
{
    if (is_string($data1) && file_exists($data1)) {
        $data1 = parseFile($data1);
    } elseif (is_string($data1)) {
        $data1 = json_decode($data1, true);
    }

    if (is_string($data2) && file_exists($data2)) {
        $data2 = parseFile($data2);
    } elseif (is_string($data2)) {
        $data2 = json_decode($data2, true);
    }

    if (!is_array($data1)) {
        $data1 = [];
    }
    if (!is_array($data2)) {
        $data2 = [];
    }

    $ast = buildTree($data1, $data2);
    $formatter = getFormatter($format);

    $result = $formatter($ast);

    if ($format === 'stylish') {
        $result = "{\n" . $result . "\n}";
    }

    return rtrim($result) . "\n";
}
