<?php

namespace ZappoQ\Tests;

use PHPUnit\Framework\TestCase;
use function ZappoQ\parseFile;
use function ZappoQ\genDiff;
use function ZappoQ\isJsonFile;
use function ZappoQ\isYamlFile;
use function ZappoQ\Formatters\stringify;

require_once __DIR__ . '/../src/Parsing.php';
require_once __DIR__ . '/../src/Builder.php';
require_once __DIR__ . '/../src/Formatters/Stylish.php';
require_once __DIR__ . '/../src/Differ.php';

class DifferTest extends TestCase
{
    private function getFixturePath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    public function testGenDiffFlatJson(): void
    {
        $data1 = parseFile($this->getFixturePath('flat1.json'));
        $data2 = parseFile($this->getFixturePath('flat2.json'));

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

    public function testGenDiffNestedJson(): void
    {
        $data1 = parseFile($this->getFixturePath('nested1.json'));
        $data2 = parseFile($this->getFixturePath('nested2.json'));

        $expected = '{
common: {
    + follow: false
      setting1: Value 1
    - setting2: 200
    - setting3: true
    + setting3: null
    + setting4: blah blah
    + setting5: { ... }
    setting6: {
        doge: {
            - wow: 
            + wow: so much
        }
          key: value
        + ops: vops
    }
}
group1: {
    - baz: bas
    + baz: bars
      foo: bar
    - nest: { ... }
    + nest: str
}
- group2: { ... }
+ group3: { ... }
}';

        $this->assertEquals($expected, genDiff($data1, $data2));
    }

    public function testGenDiffNestedYaml(): void
    {
        $data1 = parseFile($this->getFixturePath('nested1.yml'));
        $data2 = parseFile($this->getFixturePath('nested2.yml'));

        $expected = '{
common: {
    + follow: false
      setting1: Value 1
    - setting2: 200
    - setting3: true
    + setting3: null
    + setting4: blah blah
    + setting5: { ... }
    setting6: {
        doge: {
            - wow: 
            + wow: so much
        }
          key: value
        + ops: vops
    }
}
group1: {
    - baz: bas
    + baz: bars
      foo: bar
    - nest: { ... }
    + nest: str
}
- group2: { ... }
+ group3: { ... }
}';

        $this->assertEquals($expected, genDiff($data1, $data2));
    }

    public function testMixedFormats(): void
    {
        $data1 = parseFile($this->getFixturePath('nested1.json'));
        $data2 = parseFile($this->getFixturePath('nested2.yml'));

        $expected = '{
common: {
    + follow: false
      setting1: Value 1
    - setting2: 200
    - setting3: true
    + setting3: null
    + setting4: blah blah
    + setting5: { ... }
    setting6: {
        doge: {
            - wow: 
            + wow: so much
        }
          key: value
        + ops: vops
    }
}
group1: {
    - baz: bas
    + baz: bars
      foo: bar
    - nest: { ... }
    + nest: str
}
- group2: { ... }
+ group3: { ... }
}';

        $this->assertEquals($expected, genDiff($data1, $data2));
    }

    public function testIsJsonFile(): void
    {
        $this->assertTrue(isJsonFile('file.json'));
        $this->assertTrue(isJsonFile('file.JSON'));
        $this->assertFalse(isJsonFile('file.yml'));
        $this->assertFalse(isJsonFile('file.yaml'));
        $this->assertFalse(isJsonFile('file.txt'));
    }

    public function testIsYamlFile(): void
    {
        $this->assertTrue(isYamlFile('file.yml'));
        $this->assertTrue(isYamlFile('file.yaml'));
        $this->assertTrue(isYamlFile('file.YML'));
        $this->assertFalse(isYamlFile('file.json'));
        $this->assertFalse(isYamlFile('file.txt'));
    }

    public function testParseFileNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/File not found:/');
        parseFile('not_exists.json');
    }

    public function testParseInvalidJson(): void
    {
        $invalidJsonFile = $this->getFixturePath('invalid.json');
        file_put_contents($invalidJsonFile, '{invalid json}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Invalid JSON in file:/');
        parseFile($invalidJsonFile);

        unlink($invalidJsonFile);
    }

    public function testParseUnsupportedFormat(): void
    {
        $unsupportedFile = $this->getFixturePath('test.txt');
        file_put_contents($unsupportedFile, 'test content');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Unsupported file format:/');
        parseFile($unsupportedFile);

        unlink($unsupportedFile);
    }

    public function testStringify(): void
    {
        $this->assertEquals('true', stringify(true));
        $this->assertEquals('false', stringify(false));
        $this->assertEquals('null', stringify(null));
        $this->assertEquals('123', stringify(123));
        $this->assertEquals('test', stringify('test'));
        $this->assertEquals('{ ... }', stringify(['key' => 'value']));
        $this->assertEquals('{ ... }', stringify([]));
    }

    public function testGenDiffUnsupportedFormat(): void
    {
        $data1 = parseFile($this->getFixturePath('flat1.json'));
        $data2 = parseFile($this->getFixturePath('flat2.json'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unsupported format: invalid');
        genDiff($data1, $data2, 'invalid');
    }

    public function testGenDiffPlainFlatJson(): void
    {
        $data1 = parseFile($this->getFixturePath('flat1.json'));
        $data2 = parseFile($this->getFixturePath('flat2.json'));

        $expected = "Property 'follow' was removed\n"
            . "Property 'proxy' was removed\n"
            . "Property 'timeout' was updated. From 50 to 20\n"
            . "Property 'verbose' was added with value: true";

        $this->assertEquals($expected, genDiff($data1, $data2, 'plain'));
    }

    public function testGenDiffPlainNestedJson(): void
    {
        $data1 = parseFile($this->getFixturePath('nested1.json'));
        $data2 = parseFile($this->getFixturePath('nested2.json'));

        $expected = "Property 'common.follow' was added with value: false\n"
            . "Property 'common.setting2' was removed\n"
            . "Property 'common.setting3' was updated. From true to null\n"
            . "Property 'common.setting4' was added with value: 'blah blah'\n"
            . "Property 'common.setting5' was added with value: [complex value]\n"
            . "Property 'common.setting6.doge.wow' was updated. From '' to 'so much'\n"
            . "Property 'common.setting6.ops' was added with value: 'vops'\n"
            . "Property 'group1.baz' was updated. From 'bas' to 'bars'\n"
            . "Property 'group1.nest' was updated. From [complex value] to 'str'\n"
            . "Property 'group2' was removed\n"
            . "Property 'group3' was added with value: [complex value]";

        $this->assertEquals($expected, genDiff($data1, $data2, 'plain'));
    }
}

