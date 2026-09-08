<?php

namespace Differ\Differ\Formatters;

function plain(array $ast, string $path = ''): string
{
    $lines = [];

    foreach ($ast as $key => $node) {
        $currentPath = $path ? $path . '.' . $key : $key;

        switch ($node['type']) {
            case 'nested':
                $lines[] = plain($node['children'], $currentPath);
                break;
            case 'added':
                $lines[] = "Property '{$currentPath}' was added with value: " . formatPlainValue($node['value']);
                break;
            case 'removed':
                $lines[] = "Property '{$currentPath}' was removed";
                break;
            case 'changed':
                $lines[] = "Property '{$currentPath}' was updated. From "
                    . formatPlainValue($node['oldValue']) . " to "
                    . formatPlainValue($node['newValue']);
                break;
            case 'unchanged':
                break;
        }
    }

    return implode("\n", array_filter($lines));
}

function formatPlainValue($value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }
    if (is_string($value)) {
        return "'" . $value . "'";
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    return (string) $value;
}
