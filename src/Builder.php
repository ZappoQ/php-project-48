<?php

namespace Differ\Differ\Builder;

function buildTree(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortedKeys = array_values($keys);
    sort($sortedKeys);

    return array_map(function ($key) use ($data1, $data2) {
        $hasInFirst = array_key_exists($key, $data1);
        $hasInSecond = array_key_exists($key, $data2);
        $value1 = $data1[$key] ?? null;
        $value2 = $data2[$key] ?? null;

        if ($hasInFirst && $hasInSecond && is_array($value1) && is_array($value2)) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => buildTree($value1, $value2),
            ];
        }

        if (!$hasInFirst) {
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $value2,
            ];
        }

        if (!$hasInSecond) {
            return [
                'key' => $key,
                'type' => 'removed',
                'value' => $value1,
            ];
        }

        if ($value1 === $value2) {
            return [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $value1,
            ];
        }

        return [
            'key' => $key,
            'type' => 'changed',
            'oldValue' => $value1,
            'newValue' => $value2,
        ];
    }, $sortedKeys);
}
