<?php

namespace ZappoQ;

use Symfony\Component\Yaml\Yaml;

// ============================================
// 1. ПАРСИНГ ФАЙЛОВ
// ============================================

function parseFile(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new \Exception("File not found: {$filePath}");
    }

    $content = file_get_contents($filePath);
    
    if (isJsonFile($filePath)) {
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON in file: {$filePath}");
        }
        return $data;
    } elseif (isYamlFile($filePath)) {
        return Yaml::parse($content);
    } else {
        throw new \Exception("Unsupported file format: {$filePath}");
    }
}

function isJsonFile(string $filePath): bool
{
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    return strtolower($extension) === 'json';
}

function isYamlFile(string $filePath): bool
{
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    return in_array($extension, ['yml', 'yaml']);
}

// ============================================
// 2. ПОСТРОЕНИЕ ДЕРЕВА РАЗЛИЧИЙ
// ============================================

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

// ============================================
// 3. ФОРМАТТЕР STYLISH
// ============================================

function stylish(array $ast, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);
    $valueIndent = str_repeat('    ', $depth + 1);
    $lines = [];

    foreach ($ast as $key => $node) {
        switch ($node['type']) {
            case 'nested':
                $lines[] = $indent . '    ' . $key . ': {';
                $lines[] = stylish($node['children'], $depth + 1);
                $lines[] = $indent . '    }';
                break;
            case 'added':
                $lines[] = $valueIndent . '+ ' . $key . ': ' . stringify($node['value'], $depth);
                break;
            case 'removed':
                $lines[] = $valueIndent . '- ' . $key . ': ' . stringify($node['value'], $depth);
                break;
            case 'unchanged':
                $lines[] = $valueIndent . '  ' . $key . ': ' . stringify($node['value'], $depth);
                break;
            case 'changed':
                $lines[] = $valueIndent . '- ' . $key . ': ' . stringify($node['oldValue'], $depth);
                $lines[] = $valueIndent . '+ ' . $key . ': ' . stringify($node['newValue'], $depth);
                break;
        }
    }

    return implode("\n", $lines);
}

function stringify($value, int $depth = 0): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_array($value)) {
        // Если массив пустой
        if (empty($value)) {
            return '{}';
        }
        // Если это ассоциативный массив (вложенная структура)
        if (array_keys($value) !== range(0, count($value) - 1)) {
            $indent = str_repeat('    ', $depth + 1);
            $lines = [];
            foreach ($value as $k => $v) {
                if (is_array($v)) {
                    $lines[] = $indent . $k . ': ' . stringify($v, $depth + 1);
                } else {
                    $lines[] = $indent . $k . ': ' . stringify($v, $depth + 1);
                }
            }
            return "{\n" . implode("\n", $lines) . "\n" . str_repeat('    ', $depth) . '}';
        }
        // Обычный массив
        return '{ ... }';
    }
    return (string) $value;
}

// ============================================
// 4. ОСНОВНАЯ ФУНКЦИЯ
// ============================================

function genDiff(array $data1, array $data2, string $format = 'stylish'): string
{
    $ast = buildTree($data1, $data2);

    if ($format === 'stylish') {
        return "{\n" . stylish($ast) . "\n}";
    }
    
    throw new \Exception("Unsupported format: {$format}");
}
