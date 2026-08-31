<?php

namespace Differ\Differ;

function genDiff($data1, $data2, string $format = 'stylish'): string
{
    // Если переданы строки - парсим их как JSON
    if (is_string($data1)) {
        $data1 = json_decode($data1, true);
    }
    if (is_string($data2)) {
        $data2 = json_decode($data2, true);
    }

    $ast = buildTree($data1, $data2);
    $formatter = getFormatter($format);
    return $formatter($ast);
}
