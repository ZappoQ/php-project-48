<?php

namespace Differ\Differ;

use function Differ\Differ\buildTree;
use function Differ\Differ\getFormatter;

function genDiff(array $data1, array $data2, string $format = 'stylish'): string
{
    $ast = buildTree($data1, $data2);
    $formatter = getFormatter($format);
    return $formatter($ast);
}
