<?php

namespace ZappoQ\Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Parsing.php';
require_once __DIR__ . '/../src/Differ.php';

class DifferTest extends TestCase
{
    private function getFixturePath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    public function testGenDiff(): void
    {
        $data1 = \ZappoQ\parseFile($this->getFixturePath('file1.json'));
        $data2 = \ZappoQ\parseFile($this->getFixturePath('file2.json'));

        $expected = '{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}';

        $this->assertEquals($expected, \ZappoQ\genDiff($data1, $data2));
    }

    public function testGenDiffWithIdenticalFiles(): void
    {
        $data = \ZappoQ\parseFile($this->getFixturePath('file1.json'));

        $expected = '{
    follow: false
    host: hexlet.io
    proxy: 123.234.53.22
    timeout: 50
}';

        $this->assertEquals($expected, \ZappoQ\genDiff($data, $data));
    }
}
