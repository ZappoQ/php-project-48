<?php

namespace Differ\Differ;

use function Differ\Differ\buildTree;
use function Differ\Differ\getFormatter;

function genDiff($data1, $data2, string $format = 'stylish'): string
{
    // Если переданы строки — парсим их как JSON
    if (is_string($data1)) {
        $data1 = json_decode($data1, true);
    }
    if (is_string($data2)) {
        $data2 = json_decode($data2, true);
    }

    if ($data1 === null) {
        $data1 = [];
    }
    if ($data2 === null) {
        $data2 = [];
    }

    $ast = buildTree($data1, $data2);
    $formatter = getFormatter($format);

    if ($format === 'stylish') {
        return "{\n" . $formatter($ast) . "\n}";
    }

    return $formatter($ast);
}
