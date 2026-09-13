<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff;

class DifferTest extends TestCase
{
    private function getFixturePath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    public function testJsonFlat(): void
    {
        $expected = rtrim(file_get_contents($this->getFixturePath('diff.stylish')));
        $actual = rtrim(genDiff(
            $this->getFixturePath('file1.json'),
            $this->getFixturePath('file2.json')
        ));
        $this->assertEquals($expected, $actual);
    }

    public function testYamlFlat(): void
    {
        $expected = rtrim(file_get_contents($this->getFixturePath('diff.stylish')));
        $actual = rtrim(genDiff(
            $this->getFixturePath('file1.yml'),
            $this->getFixturePath('file2.yml')
        ));
        $this->assertEquals($expected, $actual);
    }

    public function testPlain(): void
    {
        $expected = rtrim(file_get_contents($this->getFixturePath('diff.plain')));
        $actual = rtrim(genDiff(
            $this->getFixturePath('file1.json'),
            $this->getFixturePath('file2.json'),
            'plain'
        ));
        $this->assertEquals($expected, $actual);
    }

    public function testJsonFormat(): void
    {
        $expected = json_decode(file_get_contents($this->getFixturePath('diff.json')), true);
        $actual = json_decode(genDiff(
            $this->getFixturePath('file1.json'),
            $this->getFixturePath('file2.json'),
            'json'
        ), true);
        $this->assertEquals($expected, $actual);
    }
}
