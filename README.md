# Pink80 Local Core

Локальное ядро для упрощения разработки на Битрикс. Включает хелперы, фабрики, обработчики событий и CLI команды.

## Установка

### Установка через Composer (рекомендуется)
Модуль распространяется через composer для легкого обновления и переиспользования в разных проектах.

#### В composer.json проекта:
```json
{
    "require": {
        "pink80/core": "^1.0"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:oepink80/pink80-core.git"
        }
    ],
    "extra": {
        "installer-paths": {
            "local/modules/pink80.core": ["type:bitrix-module"]
        }
    },
    "require-dev": {
        "composer/installers": "^2.0"
    },
    "config": {
        "allow-plugins": {
            "composer/installers": true
        }
    }
}
```

#### Установка:
```bash
composer require pink80/core
```

При установке автоматически:
1. Модуль устанавливается напрямую в `local/modules/pink80.core`
2. Настраивается автозагрузка через composer
3. Модуль готов к использованию

#### Обновление:
```bash
composer update pink80/core
```

### Ручная установка
Если composer не используется:
1. Скопируйте папку `pink80.core` в `local/modules/`
2. Добавьте в `local/php_interface/init.php`:
```php
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/pink80.core/include.php';
\Pink80\Core\LocalCore::init();
```

### Установка через админку (опционально)
Если нужно отобразить модуль в списке модулей:
1. Перейдите в админку: Настройки → Модули → Установка
2. Найдите "Pink80 Local Core" и нажмите "Установить"
3. Это добавит модуль в список установленных (но не обязательно для работы)

## Структура модуля

```
local/modules/pink80.core/
├── bin/                    # CLI команды
│   └── console            # Точка входа для консольных команд
├── cron/                   # Крон задачи (будущее)
├── handlers/               # Регистрация обработчиков событий
│   └── EventHandlerRegistrar.php
├── install/                # Файлы установки
│   ├── index.php
│   ├── version.php
│   ├── description.php
│   └── lang/
├── lang/                   # Языковые файлы
├── lib/                    # Основные классы
│   ├── Console/           # CLI система
│   │   ├── Commands/      # Консольные команды
│   │   ├── ConsoleApplication.php
│   │   ├── CommandRegistry.php
│   │   └── BaseCommand.php
│   ├── Factories/         # Фабрики сущностей
│   │   ├── Iblock/
│   │   ├── User/
│   │   ├── Highloadblock/
│   │   └── Main/
│   ├── Handlers/          # Обработчики событий
│   │   ├── Main/
│   │   ├── Iblock/
│   │   ├── User/
│   │   └── Highloadblock/
│   ├── Helpers/           # Хелперы
│   │   ├── Iblock/
│   │   ├── User/
│   │   ├── String/
│   │   ├── Array/
│   │   ├── Date/
│   │   └── Highloadblock/
│   ├── Interfaces/        # Интерфейсы
│   └── Cron/              # Крон задачи (будущее)
└── include.php            # Основной файл модуля
```

## Использование

### Хелперы (Helpers)

#### Хелперы для инфоблоков
```php
use Pink80\Core\Helpers\Iblock\IblockHelper;

// Получить инфоблок по коду
$iblock = IblockHelper::getByCode('products');

// Получить ID инфоблока по коду
$iblockId = IblockHelper::getIdByCode('products');

// Получить элементы инфоблока
$elements = IblockHelper::getElements($iblockId, ['ACTIVE' => 'Y']);

// Получить элемент по ID
$element = IblockHelper::getElementById(123);

// Получить свойство по коду
$property = IblockHelper::getPropertyByCode($iblockId, 'PRICE');
```

#### Хелперы для пользователей
```php
use Pink80\Core\Helpers\User\UserHelper;

// Получить текущего пользователя
$user = UserHelper::getCurrent();
$userId = UserHelper::getCurrentId();

// Проверить авторизацию
if (UserHelper::isAuthorized()) {
    // Пользователь авторизован
}

// Проверить админ права
if (UserHelper::isAdmin()) {
    // Пользователь - администратор
}

// Получить пользователя по ID
$user = UserHelper::getById(1);

// Получить пользователя по email
$user = UserHelper::getByEmail('user@example.com');

// Проверить принадлежность к группе
if (UserHelper::isInGroup($userId, 1)) {
    // Пользователь в группе администраторов
}
```

#### Хелперы для строк
```php
use Pink80\Core\Helpers\String\StringHelper;

// Генерация случайной строки
$random = StringHelper::random(10);

// Обрезка строки
$truncated = StringHelper::truncate('Длинная строка текста', 10, '...');

// Конвертация форматов
$camelCase = StringHelper::toCamelCase('my_string');
$snakeCase = StringHelper::toSnakeCase('MyString');
$kebabCase = StringHelper::toKebabCase('MyString');

// Генерация slug
$slug = StringHelper::slug('Название статьи');
```

#### Хелперы для массивов
```php
use Pink80\Core\Helpers\Array\ArrayHelper;

// Безопасное получение значения
$value = ArrayHelper::get($array, 'key', 'default');

// Получение вложенного значения
$value = ArrayHelper::getNested($array, 'user.profile.name');

// Слияние массивов
$merged = ArrayHelper::merge($array1, $array2);

// Проверка наличия ключа
if (ArrayHelper::has($array, 'key')) {
    // Ключ существует
}

// Переиндексация массива
$indexed = ArrayHelper::indexBy($array, 'id');

// Получить только определенные ключи
$filtered = ArrayHelper::only($array, ['id', 'name']);

// Исключить ключи
$filtered = ArrayHelper::except($array, ['password']);
```

#### Хелперы для дат
```php
use Pink80\Core\Helpers\Date\DateHelper;

// Получить текущую дату
$now = DateHelper::now();

// Форматирование в формат Битрикс
$bitrixDate = DateHelper::toBitrixFormat();

// Получить начало дня
$startOfDay = DateHelper::startOfDay();

// Получить конец дня
$endOfDay = DateHelper::endOfDay();

// Получить начало месяца
$startOfMonth = DateHelper::startOfMonth();

// Получить конец месяца
$endOfMonth = DateHelper::endOfMonth();

// Человеческое различие дат
$diff = DateHelper::humanDiff($timestamp);
```

#### Хелперы для HL блоков
```php
use Pink80\Core\Helpers\Highloadblock\HighloadblockHelper;

// Получить HL блок по названию
$hlblock = HighloadblockHelper::getByName('MyHLBlock');

// Получить сущность данных HL блока
$dataClass = HighloadblockHelper::getEntityDataClassByName('MyHLBlock');

// Получить записи из HL блока
$result = HighloadblockHelper::getDataList($hlblockId, [
    'filter' => ['UF_ACTIVE' => 1],
    'order' => ['UF_ID' => 'ASC']
]);

// Получить запись по ID
$record = HighloadblockHelper::getDataById($hlblockId, 1);
```

### Фабрики (Factories)

#### Фабрика для инфоблоков
```php
use Pink80\Core\Factories\Iblock\IblockFactory;

// Создать новый инфоблок
$iblock = IblockFactory::createIblock([
    'NAME' => 'Каталог',
    'CODE' => 'catalog',
    'IBLOCK_TYPE_ID' => 'catalog',
    'LID' => 's1'
]);

// Создать элемент инфоблока
$element = IblockFactory::createElement($iblockId, [
    'NAME' => 'Товар 1',
    'ACTIVE' => 'Y',
    'CODE' => 'product-1'
]);

// Получить инфоблок по коду
$iblock = IblockFactory::getByCode('catalog');
```

#### Фабрика для пользователей
```php
use Pink80\Core\Factories\User\UserFactory;

// Создать нового пользователя
$user = UserFactory::createUser([
    'LOGIN' => 'newuser',
    'EMAIL' => 'newuser@example.com',
    'PASSWORD' => 'password123',
    'NAME' => 'Иван',
    'LAST_NAME' => 'Иванов'
]);

// Получить пользователя по логину
$user = UserFactory::getByLogin('newuser');

// Получить администраторов
$admins = UserFactory::getAdmins();
```

#### Фабрика для HL блоков
```php
use Pink80\Core\Factories\Highloadblock\HighloadblockFactory;

// Создать новый HL блок
$hlblock = HighloadblockFactory::createHighloadblock([
    'NAME' => 'MyHLBlock',
    'TABLE_NAME' => 'my_hl_block'
]);

// Добавить запись в HL блок
$recordId = HighloadblockFactory::addData($hlblockId, [
    'UF_NAME' => 'Запись 1',
    'UF_ACTIVE' => 1
]);

// Обновить запись в HL блоке
HighloadblockFactory::updateData($hlblockId, $recordId, [
    'UF_NAME' => 'Обновленная запись'
]);

// Удалить запись из HL блока
HighloadblockFactory::deleteData($hlblockId, $recordId);

// Получить записи из HL блока
$result = HighloadblockFactory::getDataList($hlblockId, [
    'filter' => ['UF_ACTIVE' => 1]
]);
```

### Обработчики событий (EventHandlers)

Обработчики событий регистрируются автоматически при инициализации модуля. Для добавления новой логики редактируйте соответствующие файлы в `lib/Handlers/`:

#### Основные события
```php
// lib/Handlers/Main/MainHandler.php
class MainHandler extends BaseHandler
{
    public static function onBeforeProlog()
    {
        // Логика перед прологом
    }
}
```

#### События инфоблоков
```php
// lib/Handlers/Iblock/IblockHandler.php
class IblockHandler extends BaseHandler
{
    public static function onAfterElementAdd(&$fields)
    {
        // Логика после добавления элемента
    }
}
```

#### События пользователей
```php
// lib/Handlers/User/UserHandler.php
class UserHandler extends BaseHandler
{
    public static function onAfterUserAdd(&$fields)
    {
        // Логика после добавления пользователя
    }
}
```

### CLI команды

#### Использование консоли
```bash
# Показать список всех команд
php -d mbstring.func_overload=2 local/modules/pink80.core/bin/console list

# Показать справку по команде
php -d mbstring.func_overload=2 local/modules/pink80.core/bin/console help <command>

# Выполнить команду
php -d mbstring.func_overload=2 local/modules/pink80.core/bin/console <command> [options] [arguments]
```

#### Создание собственной команды

1. Создайте файл в `lib/Console/Commands/`:
```php
<?php

namespace Pink80\Core\Console\Commands;

use Pink80\Core\Console\BaseCommand;

class MyCustomCommand extends BaseCommand
{
    public static function getName()
    {
        return 'my:custom';
    }
    
    public static function getDescription()
    {
        return 'Моя кастомная команда';
    }
    
    public static function getArguments()
    {
        return [
            [
                'name' => 'argument1',
                'description' => 'Первый аргумент',
                'required' => true
            ]
        ];
    }
    
    public static function getOptions()
    {
        return [
            [
                'name' => 'verbose',
                'description' => 'Подробный вывод'
            ]
        ];
    }
    
    public function execute(array $args = [], array $options = [])
    {
        $this->output('Выполнение команды...');
        
        $arg1 = $this->getArgument('argument1', $args);
        $verbose = $this->hasOption('verbose', $options);
        
        if ($verbose) {
            $this->info('Аргумент: ' . $arg1);
        }
        
        $this->success('Команда выполнена успешно!');
    }
}
```

2. Команда автоматически зарегистрируется и будет доступна через консоль.

## Правила разработки

- **ВСЕГДА** использовать современный подход D7 и ORM, если это возможно
- Использовать устаревшие методы только если нет аналогов в D7
- Приоритет использования:
  1. D7 ORM (Bitrix\Main\*)
  2. Новые API D7
  3. Устаревшие методы (только при отсутствии альтернатив)

## Проект-специфичные изменения

Для проект-специфичных изменений используйте модуль `project.core`:

```
local/modules/
├── pink80.core/          # Основной модуль (устанавливается через composer)
└── project.core/         # Проектные изменения (локальный код)
```

### Структура project.core:
```
local/modules/project.core/
├── lib/
│   ├── Helpers/       # Проектные хелперы
│   ├── Factories/     # Проектные фабрики
│   ├── Handlers/      # Проектные обработчики
│   └── Console/       # Проектные CLI команды
└── include.php        # Файл инициализации
```

### Правила для project.core:
1. **Никогда не создавайте классы с теми же именами, что в pink80.core**
2. **Перед созданием нового класса проверьте его наличие в основном модуле**
3. **Если создали полезный класс → рассмотрите мердж в основной модуль**
4. **Для временных решений используйте пространство имен Temp**

## Система конфликтов

Для проверки дубликатов классов между pink80.core и project.core используйте детектор конфликтов:

```bash
php local/modules/pink80.core/bin/conflict-detector.php
```

Скрипт проверяет наличие дубликатов FQCN (Fully Qualified Class Names) в обоих модулях и выводит отчет о конфликтах.

### Как работает:
1. Сканирует `local/modules/pink80.core/lib/`
2. Сканирует `local/modules/project.core/lib/`
3. Извлекает namespace и class name из PHP файлов
4. Сравнивает FQCN и находит дубликаты
5. Выводит список конфликтующих файлов

### Разрешение конфликтов:
Если обнаружены дубликаты:
1. Удалите дубликаты из `local/modules/project.core/`
2. Если функция полезна → мерджите её в основной модуль
3. Обновите версию основного модуля
4. Повторите установку через composer

## Требования

- PHP >= 7.4
- Битрикс (любая версия с поддержкой D7)
- Composer (для автозагрузки)

## Лицензия

MIT

## Поддержка

GitHub: https://github.com/oepink80/uralgarnet
