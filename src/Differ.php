<?php

namespace ZappoQ;

function genDiff(array $data1, array $data2): string
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    asort($keys);
    $keys = array_values($keys);

    $lines = [];
    foreach ($keys as $key) {
        $hasInFirst = array_key_exists($key, $data1);
        $hasInSecond = array_key_exists($key, $data2);

        if ($hasInFirst && !$hasInSecond) {
            $lines[] = "  - {$key}: " . stringify($data1[$key]);
        } elseif (!$hasInFirst && $hasInSecond) {
            $lines[] = "  + {$key}: " . stringify($data2[$key]);
        } elseif ($data1[$key] === $data2[$key]) {
            $lines[] = "    {$key}: " . stringify($data1[$key]);
        } else {
            $lines[] = "  - {$key}: " . stringify($data1[$key]);
            $lines[] = "  + {$key}: " . stringify($data2[$key]);
        }
    }

    return "{\n" . implode("\n", $lines) . "\n}";
}

function stringify($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    return (string) $value;
}