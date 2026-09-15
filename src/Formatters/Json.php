<?php

namespace Differ\Differ\Formatters\Json;

function json(array $tree): string
{
    return json_encode($tree, JSON_PRETTY_PRINT);
}
