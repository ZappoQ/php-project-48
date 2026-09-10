<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\parseFile;
use function Differ\Differ\genDiff;
use function Differ\Differ\isJsonFile;
use function Differ\Differ\isYamlFile;
use function Differ\Differ\Formatters\formatValue;

class DifferTest extends TestCase
{
    private function getFixturePath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    public function testGenDiffFlatJson(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $expected = '{
common: {
    + follow: false
      setting1: Value 1
    - setting2: 200
    - setting3: true
    + setting3: null
    + setting4: blah blah
    + setting5: {
      key5: value5
    }
  setting6: {
    doge: {
        - wow: too much
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
    - nest: {
      key: value
    }
    + nest: str
}
  - group2: {
    abc: 12345
    deep: {
      id: 45
    }
  }
  + group3: {
    deep: {
      id: {
        number: 45
      }
    }
    fee: 100500
  }
group4: {
    - default: null
    + default: 
    - foo: 0
    + foo: null
    - isNested: false
    + isNested: none
    + key: false
  nest: {
      - bar: 
      + bar: 0
      - isNested: true
  }
    + someKey: true
    - type: bas
    + type: bar
}
}
';

        $this->assertEquals($expected, genDiff($data1, $data2));
    }

    public function testGenDiffNestedJson(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $expected = '{
common: {
    + follow: false
      setting1: Value 1
    - setting2: 200
    - setting3: true
    + setting3: null
    + setting4: blah blah
    + setting5: {
      key5: value5
    }
  setting6: {
    doge: {
        - wow: too much
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
    - nest: {
      key: value
    }
    + nest: str
}
  - group2: {
    abc: 12345
    deep: {
      id: 45
    }
  }
  + group3: {
    deep: {
      id: {
        number: 45
      }
    }
    fee: 100500
  }
group4: {
    - default: null
    + default: 
    - foo: 0
    + foo: null
    - isNested: false
    + isNested: none
    + key: false
  nest: {
      - bar: 
      + bar: 0
      - isNested: true
  }
    + someKey: true
    - type: bas
    + type: bar
}
}
';

        $this->assertEquals($expected, genDiff($data1, $data2));
    }

    public function testGenDiffNestedYaml(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.yml'));
        $data2 = parseFile($this->getFixturePath('file2.yml'));

        $expected = '{
common: {
    + follow: false
      setting1: Value 1
    - setting2: 200
    - setting3: true
    + setting3: null
    + setting4: blah blah
    + setting5: {
      key5: value5
    }
  setting6: {
    doge: {
        - wow: too much
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
    - nest: {
      key: value
    }
    + nest: str
}
  - group2: {
    abc: 12345
    deep: {
      id: 45
    }
  }
  + group3: {
    deep: {
      id: {
        number: 45
      }
    }
    fee: 100500
  }
group4: {
    - default: null
    + default: 
    - foo: 0
    + foo: null
    - isNested: false
    + isNested: none
    + key: false
  nest: {
      - bar: 
      + bar: 0
      - isNested: true
  }
    + someKey: true
    - type: bas
    + type: bar
}
}
';

        $this->assertEquals($expected, genDiff($data1, $data2));
    }

    public function testMixedFormats(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.yml'));

        $expected = '{
common: {
    + follow: false
      setting1: Value 1
    - setting2: 200
    - setting3: true
    + setting3: null
    + setting4: blah blah
    + setting5: {
      key5: value5
    }
  setting6: {
    doge: {
        - wow: too much
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
    - nest: {
      key: value
    }
    + nest: str
}
  - group2: {
    abc: 12345
    deep: {
      id: 45
    }
  }
  + group3: {
    deep: {
      id: {
        number: 45
      }
    }
    fee: 100500
  }
group4: {
    - default: null
    + default: 
    - foo: 0
    + foo: null
    - isNested: false
    + isNested: none
    + key: false
  nest: {
      - bar: 
      + bar: 0
      - isNested: true
  }
    + someKey: true
    - type: bas
    + type: bar
}
}
';

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
        parseFile('not_exists.json');
    }

    public function testParseInvalidJson(): void
    {
        $invalidJsonFile = $this->getFixturePath('invalid.json');
        file_put_contents($invalidJsonFile, '{invalid json}');

        $this->expectException(\Exception::class);
        parseFile($invalidJsonFile);

        unlink($invalidJsonFile);
    }

    public function testParseUnsupportedFormat(): void
    {
        $unsupportedFile = $this->getFixturePath('test.txt');
        file_put_contents($unsupportedFile, 'test content');

        $this->expectException(\Exception::class);
        parseFile($unsupportedFile);

        unlink($unsupportedFile);
    }

    public function testStringify(): void
    {
        $this->assertEquals('true', formatValue(true));
        $this->assertEquals('false', formatValue(false));
        $this->assertEquals('null', formatValue(null));
        $this->assertEquals('123', formatValue(123));
        $this->assertEquals('test', formatValue('test'));
        $this->assertEquals("{\n  key: value\n}", formatValue(['key' => 'value']));
        $this->assertEquals('', formatValue(''));
    }

    public function testGenDiffUnsupportedFormat(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $this->expectException(\Exception::class);
        genDiff($data1, $data2, 'invalid');
    }

    public function testGenDiffPlainFlatJson(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $expected = "Property 'common.follow' was added with value: false\n"
            . "Property 'common.setting2' was removed\n"
            . "Property 'common.setting3' was updated. From true to null\n"
            . "Property 'common.setting4' was added with value: 'blah blah'\n"
            . "Property 'common.setting5' was added with value: [complex value]\n"
            . "Property 'common.setting6.doge.wow' was updated. From 'too much' to 'so much'\n"
            . "Property 'common.setting6.ops' was added with value: 'vops'\n"
            . "Property 'group1.baz' was updated. From 'bas' to 'bars'\n"
            . "Property 'group1.nest' was updated. From [complex value] to 'str'\n"
            . "Property 'group2' was removed\n"
            . "Property 'group3' was added with value: [complex value]\n"
            . "Property 'group4.default' was updated. From null to ''\n"
            . "Property 'group4.foo' was updated. From 0 to null\n"
            . "Property 'group4.isNested' was updated. From false to 'none'\n"
            . "Property 'group4.key' was added with value: false\n"
            . "Property 'group4.nest.bar' was updated. From '' to 0\n"
            . "Property 'group4.nest.isNested' was removed\n"
            . "Property 'group4.someKey' was added with value: true\n"
            . "Property 'group4.type' was updated. From 'bas' to 'bar'";

        $this->assertEquals($expected, genDiff($data1, $data2, 'plain'));
    }

    public function testGenDiffPlainNestedJson(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $expected = "Property 'common.follow' was added with value: false\n"
            . "Property 'common.setting2' was removed\n"
            . "Property 'common.setting3' was updated. From true to null\n"
            . "Property 'common.setting4' was added with value: 'blah blah'\n"
            . "Property 'common.setting5' was added with value: [complex value]\n"
            . "Property 'common.setting6.doge.wow' was updated. From 'too much' to 'so much'\n"
            . "Property 'common.setting6.ops' was added with value: 'vops'\n"
            . "Property 'group1.baz' was updated. From 'bas' to 'bars'\n"
            . "Property 'group1.nest' was updated. From [complex value] to 'str'\n"
            . "Property 'group2' was removed\n"
            . "Property 'group3' was added with value: [complex value]\n"
            . "Property 'group4.default' was updated. From null to ''\n"
            . "Property 'group4.foo' was updated. From 0 to null\n"
            . "Property 'group4.isNested' was updated. From false to 'none'\n"
            . "Property 'group4.key' was added with value: false\n"
            . "Property 'group4.nest.bar' was updated. From '' to 0\n"
            . "Property 'group4.nest.isNested' was removed\n"
            . "Property 'group4.someKey' was added with value: true\n"
            . "Property 'group4.type' was updated. From 'bas' to 'bar'";

        $this->assertEquals($expected, genDiff($data1, $data2, 'plain'));
    }

    public function testGenDiffJsonFlat(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $expected = json_encode([
            'common' => [
                'type' => 'nested',
                'children' => [
                    'follow' => ['type' => 'added', 'value' => false],
                    'setting1' => ['type' => 'unchanged', 'value' => 'Value 1'],
                    'setting2' => ['type' => 'removed', 'value' => 200],
                    'setting3' => ['type' => 'changed', 'oldValue' => true, 'newValue' => null],
                    'setting4' => ['type' => 'added', 'value' => 'blah blah'],
                    'setting5' => ['type' => 'added', 'value' => ['key5' => 'value5']],
                    'setting6' => [
                        'type' => 'nested',
                        'children' => [
                            'doge' => [
                                'type' => 'nested',
                                'children' => [
                                    'wow' => ['type' => 'changed', 'oldValue' => 'too much', 'newValue' => 'so much'],
                                ],
                            ],
                            'key' => ['type' => 'unchanged', 'value' => 'value'],
                            'ops' => ['type' => 'added', 'value' => 'vops'],
                        ],
                    ],
                ],
            ],
            'group1' => [
                'type' => 'nested',
                'children' => [
                    'baz' => ['type' => 'changed', 'oldValue' => 'bas', 'newValue' => 'bars'],
                    'foo' => ['type' => 'unchanged', 'value' => 'bar'],
                    'nest' => ['type' => 'changed', 'oldValue' => ['key' => 'value'], 'newValue' => 'str'],
                ],
            ],
            'group2' => ['type' => 'removed', 'value' => ['abc' => 12345, 'deep' => ['id' => 45]]],
            'group3' => ['type' => 'added', 'value' => ['deep' => ['id' => ['number' => 45]], 'fee' => 100500]],
            'group4' => [
                'type' => 'nested',
                'children' => [
                    'default' => ['type' => 'changed', 'oldValue' => null, 'newValue' => ''],
                    'foo' => ['type' => 'changed', 'oldValue' => 0, 'newValue' => null],
                    'isNested' => ['type' => 'changed', 'oldValue' => false, 'newValue' => 'none'],
                    'key' => ['type' => 'added', 'value' => false],
                    'nest' => [
                        'type' => 'nested',
                        'children' => [
                            'bar' => ['type' => 'changed', 'oldValue' => '', 'newValue' => 0],
                            'isNested' => ['type' => 'removed', 'value' => true],
                        ],
                    ],
                    'someKey' => ['type' => 'added', 'value' => true],
                    'type' => ['type' => 'changed', 'oldValue' => 'bas', 'newValue' => 'bar'],
                ],
            ],
        ], JSON_PRETTY_PRINT);

        $this->assertEquals($expected, genDiff($data1, $data2, 'json'));
    }

    public function testGenDiffJsonNested(): void
    {
        $data1 = parseFile($this->getFixturePath('file1.json'));
        $data2 = parseFile($this->getFixturePath('file2.json'));

        $expected = json_encode([
            'common' => [
                'type' => 'nested',
                'children' => [
                    'follow' => ['type' => 'added', 'value' => false],
                    'setting1' => ['type' => 'unchanged', 'value' => 'Value 1'],
                    'setting2' => ['type' => 'removed', 'value' => 200],
                    'setting3' => ['type' => 'changed', 'oldValue' => true, 'newValue' => null],
                    'setting4' => ['type' => 'added', 'value' => 'blah blah'],
                    'setting5' => ['type' => 'added', 'value' => ['key5' => 'value5']],
                    'setting6' => [
                        'type' => 'nested',
                        'children' => [
                            'doge' => [
                                'type' => 'nested',
                                'children' => [
                                    'wow' => ['type' => 'changed', 'oldValue' => 'too much', 'newValue' => 'so much'],
                                ],
                            ],
                            'key' => ['type' => 'unchanged', 'value' => 'value'],
                            'ops' => ['type' => 'added', 'value' => 'vops'],
                        ],
                    ],
                ],
            ],
            'group1' => [
                'type' => 'nested',
                'children' => [
                    'baz' => ['type' => 'changed', 'oldValue' => 'bas', 'newValue' => 'bars'],
                    'foo' => ['type' => 'unchanged', 'value' => 'bar'],
                    'nest' => ['type' => 'changed', 'oldValue' => ['key' => 'value'], 'newValue' => 'str'],
                ],
            ],
            'group2' => ['type' => 'removed', 'value' => ['abc' => 12345, 'deep' => ['id' => 45]]],
            'group3' => ['type' => 'added', 'value' => ['deep' => ['id' => ['number' => 45]], 'fee' => 100500]],
            'group4' => [
                'type' => 'nested',
                'children' => [
                    'default' => ['type' => 'changed', 'oldValue' => null, 'newValue' => ''],
                    'foo' => ['type' => 'changed', 'oldValue' => 0, 'newValue' => null],
                    'isNested' => ['type' => 'changed', 'oldValue' => false, 'newValue' => 'none'],
                    'key' => ['type' => 'added', 'value' => false],
                    'nest' => [
                        'type' => 'nested',
                        'children' => [
                            'bar' => ['type' => 'changed', 'oldValue' => '', 'newValue' => 0],
                            'isNested' => ['type' => 'removed', 'value' => true],
                        ],
                    ],
                    'someKey' => ['type' => 'added', 'value' => true],
                    'type' => ['type' => 'changed', 'oldValue' => 'bas', 'newValue' => 'bar'],
                ],
            ],
        ], JSON_PRETTY_PRINT);

        $this->assertEquals($expected, genDiff($data1, $data2, 'json'));
    }
}
