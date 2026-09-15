<?php

namespace Differ\Differ;

use function Differ\Differ\Parsing\readFile;
use function Differ\Differ\Parsing\parse;
use function Differ\Differ\Builder\buildTree;
use function Differ\Differ\Formatters\getFormatter;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $content1 = readFile($pathToFile1);
    $data1 = parse($content1, $pathToFile1);

    $content2 = readFile($pathToFile2);
    $data2 = parse($content2, $pathToFile2);

    $tree = buildTree($data1, $data2);
    $formatter = getFormatter($format);

    return sprintf('%s%s', $formatter($tree), PHP_EOL);
}
