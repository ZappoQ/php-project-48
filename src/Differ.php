<?php

namespace ZappoQ;

require_once __DIR__ . '/Builder.php';
require_once __DIR__ . '/Formatters.php';

function genDiff(array $data1, array $data2, string $format = 'stylish'): string
{
    $ast = buildTree($data1, $data2);
    $formatter = getFormatter($format);
    return $formatter($ast);
}
