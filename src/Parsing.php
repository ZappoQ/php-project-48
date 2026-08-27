<?php

namespace ZappoQ;

function parseFile(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new \Exception("File not found: {$filePath}");
    }

    $content = file_get_contents($filePath);
    $data = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Invalid JSON in file: {$filePath}");
    }

    return $data;
}