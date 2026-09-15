<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private function getFixturePath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    public static function formatProvider(): array
    {
        return [
            'json stylish' => ['file1.json', 'file2.json', 'stylish', 'diff.stylish'],
            'json plain' => ['file1.json', 'file2.json', 'plain', 'diff.plain'],
            'json json' => ['file1.json', 'file2.json', 'json', 'diff.json'],
            'yaml stylish' => ['file1.yml', 'file2.yml', 'stylish', 'diff.stylish'],
            'yaml plain' => ['file1.yml', 'file2.yml', 'plain', 'diff.plain'],
            'yaml json' => ['file1.yml', 'file2.yml', 'json', 'diff.json'],
            'json default' => ['file1.json', 'file2.json', null, 'diff.stylish'],
            'yaml default' => ['file1.yml', 'file2.yml', null, 'diff.stylish'],
        ];
    }

    /**
     * @dataProvider formatProvider
     */
    public function testGenDiff(
        string $file1,
        string $file2,
        ?string $format,
        string $expectedFile
    ): void {
        $expectedPath = $this->getFixturePath($expectedFile);

        if ($format === null) {
            $actual = genDiff(
                $this->getFixturePath($file1),
                $this->getFixturePath($file2)
            );
        } else {
            $actual = genDiff(
                $this->getFixturePath($file1),
                $this->getFixturePath($file2),
                $format
            );
        }

        if ($expectedFile === 'diff.json') {
            $this->assertEquals(
                json_decode(file_get_contents($expectedPath), true),
                json_decode($actual, true)
            );
        } else {
            $this->assertStringEqualsFile($expectedPath, $actual);
        }
    }

    public function testUnsupportedFormat(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unsupported format: invalid');

        genDiff(
            $this->getFixturePath('file1.json'),
            $this->getFixturePath('file2.json'),
            'invalid'
        );
    }

    public function testFileNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File not found');

        genDiff(
            $this->getFixturePath('not_exists.json'),
            $this->getFixturePath('file2.json')
        );
    }
}
