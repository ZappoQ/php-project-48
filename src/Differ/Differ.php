<?php

namespace Differ\Differ;

function genDiff($data1, $data2, string $format = 'stylish'): string
{
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
    $result = $formatter($ast);

    return rtrim($result);
}
