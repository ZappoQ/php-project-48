<?php

namespace ZappoQ\Formatters;

function stylish(array $ast, int $depth = 0): string
{
    $lines = [];
    $indent = str_repeat('    ', $depth);
    $childIndent = str_repeat('    ', $depth + 1);

    foreach ($ast as $key => $node) {
        switch ($node['type']) {
            case 'nested':
                $lines[] = $indent . $key . ': {';
                $lines[] = stylish($node['children'], $depth + 1);
                $lines[] = $indent . '}';
                break;
            case 'added':
                $lines[] = $indent . '+ ' . $key . ': ' . stringify($node['value']);
                break;
            case 'removed':
                $lines[] = $indent . '- ' . $key . ': ' . stringify($node['value']);
                break;
            case 'unchanged':
                $lines[] = $indent . '  ' . $key . ': ' . stringify($node['value']);
                break;
            case 'changed':
                $lines[] = $indent . '- ' . $key . ': ' . stringify($node['oldValue']);
                $lines[] = $indent . '+ ' . $key . ': ' . stringify($node['newValue']);
                break;
        }
    }

    return implode("\n", $lines);
}

function stringify($value): string
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
    return (string) $value;
}
