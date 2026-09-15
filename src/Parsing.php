<?php

namespace Differ\Differ\Parsing;

use Symfony\Component\Yaml\Yaml;

function readFile(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new \Exception("File not found: {$filePath}");
    }

    $content = file_get_contents($filePath);

    if ($content === false) {
        throw new \Exception("Cannot read file: {$filePath}");
    }

    return $content;
}

function parse(string $content, string $filePath): array
{
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if ($extension === 'json') {
        return parseJson($content, $filePath);
    }

    if (in_array($extension, ['yml', 'yaml'])) {
        return parseYaml($content);
    }

    throw new \Exception("Unsupported file format: {$filePath}");
}

function parseJson(string $content, string $filePath): array
{
    $data = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Invalid JSON in file: {$filePath}");
    }

    return $data ?? [];
}

function parseYaml(string $content): array
{
    $data = Yaml::parse($content);
    return $data ?? [];
}
