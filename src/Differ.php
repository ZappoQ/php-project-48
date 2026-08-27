<?php

namespace ZappoQ;

require_once __DIR__ . '/Builder.php';
require_once __DIR__ . '/Formatters.php';

function genDiff(array $data1, array $data2, string $format = 'stylish'): string
{
    // Строим AST
    $ast = buildTree($data1, $data2);

    // Получаем нужный форматер через фабрику
    $formatter = getFormatter($format);

    // Возвращаем отформатированный результат
    return $formatter($ast);
}
