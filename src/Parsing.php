<?php

namespace Differ\Parsing;

use Symfony\Component\Yaml\Yaml;

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
    }

    if (isYamlFile($filePath)) {
        return Yaml::parse($content);
    }

    throw new \Exception("Unsupported file format: {$filePath}");
}

function isJsonFile(string $filePath): bool
{
    return strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'json';
}

function isYamlFile(string $filePath): bool
{
    return in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['yml', 'yaml']);
}
