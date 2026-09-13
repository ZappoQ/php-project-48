<?php

namespace Differ\Formatters\Plain;

function plain(array $tree): string
{
    $lines = renderPlain($tree, '');
    return implode("\n", array_filter($lines));
}

function renderPlain(array $tree, string $path): array
{
    return array_merge(...array_map(function ($node) use ($path) {
        $key = $node['key'];
        $currentPath = $path === '' ? $key : "{$path}.{$key}";

        if ($node['type'] === 'nested') {
            return renderPlain($node['children'], $currentPath);
        }

        if ($node['type'] === 'added') {
            return ["Property '{$currentPath}' was added with value: " . formatPlainValue($node['value'])];
        }

        if ($node['type'] === 'removed') {
            return ["Property '{$currentPath}' was removed"];
        }

        if ($node['type'] === 'unchanged') {
            return [];
        }

        $oldValue = formatPlainValue($node['oldValue']);
        $newValue = formatPlainValue($node['newValue']);
        return ["Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}"];
    }, $tree));
}

function formatPlainValue($value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    return (string) $value;
}
