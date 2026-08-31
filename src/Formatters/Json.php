<?php

namespace Differ\Formatters;

function json(array $ast): string
{
    return \json_encode($ast, JSON_PRETTY_PRINT);
}
