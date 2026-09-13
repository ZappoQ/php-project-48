<?php

namespace Differ\Differ;

use function Differ\genDiff as differGenDiff;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    return differGenDiff($pathToFile1, $pathToFile2, $format);
}
