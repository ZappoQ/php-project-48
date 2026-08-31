<?php

namespace Differ\Differ;

function buildTree(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    asort($keys);
    $keys = array_values($keys);

    $result = [];
    foreach ($keys as $key) {
        $hasInFirst = array_key_exists($key, $data1);
        $hasInSecond = array_key_exists($key, $data2);
        $value1 = $data1[$key] ?? null;
        $value2 = $data2[$key] ?? null;

        if ($hasInFirst && $hasInSecond && is_array($value1) && is_array($value2)) {
            $result[$key] = [
                'type' => 'nested',
                'children' => buildTree($value1, $value2)
            ];
        } elseif (!$hasInFirst) {
            $result[$key] = ['type' => 'added', 'value' => $value2];
        } elseif (!$hasInSecond) {
            $result[$key] = ['type' => 'removed', 'value' => $value1];
        } elseif ($value1 === $value2) {
            $result[$key] = ['type' => 'unchanged', 'value' => $value1];
        } else {
            $result[$key] = [
                'type' => 'changed',
                'oldValue' => $value1,
                'newValue' => $value2
            ];
        }
    }
    return $result;
}
