<?php

namespace ZappoQ\Tests;

use PHPUnit\Framework\TestCase;
use ZappoQ\parseFile;
use ZappoQ\genDiff;

class DifferTest extends TestCase
{
    private function getFixturePath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    public function testGenDiff(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $expected = '{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}';

        $this->assertEquals($expected, genDiff($data1, $data2));
    }

    public function testGenDiffWithIdenticalFiles(): void
    {
        $data = parseFile($this->getFixturePath('file1.json'));

        $expected = '{
    follow: false
    host: hexlet.io
    proxy: 123.234.53.22
    timeout: 50
}';

        $this->assertEquals($expected, genDiff($data, $data));
    }
}
