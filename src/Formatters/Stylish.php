<?php

namespace Differ\Formatters\Stylish;

function stylish(array $tree): string
{
    $lines = renderTree($tree, 0);

    return implode("\n", array_merge(['{'], $lines, ['}']));
}

function renderTree(array $tree, int $depth): array
{
    return array_merge(...array_map(function ($node) use ($depth) {
        $levelIndent = str_repeat('    ', $depth);       // отступ уровня
        $signIndent = $levelIndent . '  ';               // уровень + 2 пробела
        $nestedIndent = str_repeat('    ', $depth + 1);  // вложенный уровень

        $key = $node['key'];

        if ($node['type'] === 'nested') {
            return array_merge(
                ["{$nestedIndent}{$key}: {"],
                renderTree($node['children'], $depth + 1),
                ["{$nestedIndent}}"]
            );
        }

        if ($node['type'] === 'added') {
            return [sprintf('%s+ %s: %s', $signIndent, $key, formatValue($node['value'], $depth + 1))];
        }

        if ($node['type'] === 'removed') {
            return ["{$signIndent}- {$key}: " . formatValue($node['value'], $depth + 1)];
        }

        if ($node['type'] === 'unchanged') {
            return ["{$signIndent}  {$key}: " . formatValue($node['value'], $depth + 1)];
        }

        return [
            "{$signIndent}- {$key}: " . formatValue($node['oldValue'], $depth + 1),
            "{$signIndent}+ {$key}: " . formatValue($node['newValue'], $depth + 1),
        ];
    }, $tree));
}

function formatValue($value, int $depth = 0): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_array($value)) {
        $nestedIndent = str_repeat('    ', $depth + 1);
        $lines = array_map(
            fn ($k, $v) => "{$nestedIndent}{$k}: " . formatValue($v, $depth + 1),
            array_keys($value),
            array_values($value)
        );
        return implode("\n", array_merge(['{'], $lines, [str_repeat('    ', $depth) . '}']));
    }

    return (string) $value;
}
