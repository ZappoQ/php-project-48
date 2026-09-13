<?php

namespace Differ;

use function Differ\Parsing\parseFile;
use function Differ\Builder\buildTree;
use function Differ\Formatters\getFormatter;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $data1 = parseFile($pathToFile1);
    $data2 = parseFile($pathToFile2);
    $tree = buildTree($data1, $data2);
    $formatter = getFormatter($format);

    return sprintf('%s%s', $formatter($tree), PHP_EOL);
}
