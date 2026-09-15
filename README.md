[![hexlet-check](https://github.com/ZappoQ/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/ZappoQ/php-project-48/actions/workflows/hexlet-check.yml)
[![CI](https://github.com/ZappoQ/php-project-48/actions/workflows/ci.yml/badge.svg)](https://github.com/ZappoQ/php-project-48/actions/workflows/ci.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=ZappoQ_php-project-48&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=ZappoQ_php-project-48)

Программа для сравнения двух конфигурационных файлов (JSON/YAML) и вывода различий между ними.

## Демонстрация работы

[![asciicast](https://asciinema.org/a/CpAOdLr1D4sYEPEA.svg)](https://asciinema.org/a/CpAOdLr1D4sYEPEA)

## Возможности

- Поддержка форматов: **JSON** и **YAML**
- Сравнение плоских и вложенных структур
- Три формата вывода: **stylish** (по умолчанию), **plain**, **json**

## Установка

```bash
git clone git@github.com:ZappoQ/php-project-48.git
cd php-project-48
composer install
```

## Использование

./bin/gendiff <firstFile> <secondFile>

## Пример со вложенными структурами

./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json

## Вывод:
```
{
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
```

## Формат plain

./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json --format plain

## Вывод:
```
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From 'too much' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
Property 'group4.default' was updated. From null to ''
Property 'group4.foo' was updated. From 0 to null
Property 'group4.isNested' was updated. From false to 'none'
Property 'group4.key' was added with value: false
Property 'group4.nest.bar' was updated. From '' to 0
Property 'group4.nest.isNested' was removed
Property 'group4.someKey' was added with value: true
Property 'group4.type' was updated. From 'bas' to 'bar'
```

## Формат json

./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json --format json

## Вывод:
```
[
    {
        "key": "common",
        "type": "nested",
        "children": [
            {
                "key": "follow",
                "type": "added",
                "value": false
            },
            {
                "key": "setting1",
                "type": "unchanged",
                "value": "Value 1"
            },
            {
                "key": "setting2",
                "type": "removed",
                "value": 200
            },
            {
                "key": "setting3",
                "type": "changed",
                "oldValue": true,
                "newValue": null
            },
            {
                "key": "setting4",
                "type": "added",
                "value": "blah blah"
            },
            {
                "key": "setting5",
                "type": "added",
                "value": {
                    "key5": "value5"
                }
            },
            {
                "key": "setting6",
                "type": "nested",
                "children": [
                    {
                        "key": "doge",
                        "type": "nested",
                        "children": [
                            {
                                "key": "wow",
                                "type": "changed",
                                "oldValue": "too much",
                                "newValue": "so much"
                            }
                        ]
                    },
                    {
                        "key": "key",
                        "type": "unchanged",
                        "value": "value"
                    },
                    {
                        "key": "ops",
                        "type": "added",
                        "value": "vops"
                    }
                ]
            }
        ]
    },
    {
        "key": "group1",
        "type": "nested",
        "children": [
            {
                "key": "baz",
                "type": "changed",
                "oldValue": "bas",
                "newValue": "bars"
            },
            {
                "key": "foo",
                "type": "unchanged",
                "value": "bar"
            },
            {
                "key": "nest",
                "type": "changed",
                "oldValue": {
                    "key": "value"
                },
                "newValue": "str"
            }
        ]
    },
    {
        "key": "group2",
        "type": "removed",
        "value": {
            "abc": 12345,
            "deep": {
                "id": 45
            }
        }
    },
    {
        "key": "group3",
        "type": "added",
        "value": {
            "deep": {
                "id": {
                    "number": 45
                }
            },
            "fee": 100500
        }
    },
    {
        "key": "group4",
        "type": "nested",
        "children": [
            {
                "key": "default",
                "type": "changed",
                "oldValue": null,
                "newValue": ""
            },
            {
                "key": "foo",
                "type": "changed",
                "oldValue": 0,
                "newValue": null
            },
            {
                "key": "isNested",
                "type": "changed",
                "oldValue": false,
                "newValue": "none"
            },
            {
                "key": "key",
                "type": "added",
                "value": false
            },
            {
                "key": "nest",
                "type": "nested",
                "children": [
                    {
                        "key": "bar",
                        "type": "changed",
                        "oldValue": "",
                        "newValue": 0
                    },
                    {
                        "key": "isNested",
                        "type": "removed",
                        "value": true
                    }
                ]
            },
            {
                "key": "someKey",
                "type": "added",
                "value": true
            },
            {
                "key": "type",
                "type": "changed",
                "oldValue": "bas",
                "newValue": "bar"
            }
        ]
    }
]
```


## Справка

./bin/gendiff -h

## Тестирование

make test

## Покрытие кода

make test-coverage

