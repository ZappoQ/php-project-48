<?php

namespace Differ;

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
