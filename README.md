[![CI](https://github.com/ZappoQ/php-project-48/actions/workflows/ci.yml/badge.svg)](https://github.com/ZappoQ/php-project-48/actions/workflows/ci.yml)

Программа для сравнения двух конфигурационных файлов (JSON/YAML) и вывода различий между ними.

## Демонстрация работы

[![asciicast](https://asciinema.org/a/Zul96yLVMZJ16sGL.svg)](https://asciinema.org/a/Zul96yLVMZJ16sGL)

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

./bin/gendiff tests/fixtures/nested1.json tests/fixtures/nested2.json

## Вывод:
```{
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
}
```

## Формат plain

./bin/gendiff tests/fixtures/nested1.json tests/fixtures/nested2.json --format plain

## Вывод:
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]

## Формат json

./bin/gendiff tests/fixtures/nested1.json tests/fixtures/nested2.json --format json

## Вывод:
```{
    "common": {
        "type": "nested",
        "children": {
            "follow": {
                "type": "added",
                "value": false
            },
            "setting1": {
                "type": "unchanged",
                "value": "Value 1"
            },
            "setting2": {
                "type": "removed",
                "value": 200
            },
            "setting3": {
                "type": "changed",
                "oldValue": true,
                "newValue": null
            },
            "setting4": {
                "type": "added",
                "value": "blah blah"
            },
            "setting5": {
                "type": "added",
                "value": {
                    "key5": "value5"
                }
            },
            "setting6": {
                "type": "nested",
                "children": {
                    "doge": {
                        "type": "nested",
                        "children": {
                            "wow": {
                                "type": "changed",
                                "oldValue": "",
                                "newValue": "so much"
                            }
                        }
                    },
                    "key": {
                        "type": "unchanged",
                        "value": "value"
                    },
                    "ops": {
                        "type": "added",
                        "value": "vops"
                    }
                }
            }
        }
    },
    "group1": {
        "type": "nested",
        "children": {
            "baz": {
                "type": "changed",
                "oldValue": "bas",
                "newValue": "bars"
            },
            "foo": {
                "type": "unchanged",
                "value": "bar"
            },
            "nest": {
                "type": "changed",
                "oldValue": {
                    "key": "value"
                },
                "newValue": "str"
            }
        }
    },
    "group2": {
        "type": "removed",
        "value": {
            "abc": 12345,
            "deep": {
                "id": 45
            }
        }
    },
    "group3": {
        "type": "added",
        "value": {
            "deep": {
                "id": {
                    "number": 45
                }
            },
            "fee": 100500
        }
    }
}
```


## Справка

./bin/gendiff -h

## Тестирование

make test

## Покрытие кода

make test-coverage

