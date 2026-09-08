<?php

namespace Differ\Differ\Formatters;

function stylish(array $ast, int $depth = 0): string
{
    $lines = [];
    $indent = str_repeat('  ', $depth);
    $nextIndent = str_repeat('  ', $depth + 1);

    foreach ($ast as $key => $node) {
        switch ($node['type']) {
            case 'nested':
                $lines[] = $indent . $key . ': {';
                $lines[] = stylish($node['children'], $depth + 1);
                $lines[] = $indent . '}';
                break;
            case 'added':
                $lines[] = $nextIndent . '+ ' . $key . ': ' . formatValue($node['value'], $depth + 1);
                break;
            case 'removed':
                $lines[] = $nextIndent . '- ' . $key . ': ' . formatValue($node['value'], $depth + 1);
                break;
            case 'unchanged':
                $lines[] = $nextIndent . '  ' . $key . ': ' . formatValue($node['value'], $depth + 1);
                break;
            case 'changed':
                $lines[] = $nextIndent . '- ' . $key . ': ' . formatValue($node['oldValue'], $depth + 1);
                $lines[] = $nextIndent . '+ ' . $key . ': ' . formatValue($node['newValue'], $depth + 1);
                break;
        }
    }

    return implode("\n", $lines);
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
        return '{ ... }';
    }
    if ($value === '') {
        return '';
    }
    return (string) $value;
}
