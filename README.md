# Pink80 Local Core

Локальное ядро для упрощения разработки на Битрикс. Включает хелперы, фабрики, обработчики событий и CLI команды.

**Важно:** Модуль устанавливается только через Composer. Админ-установка не поддерживается с версии 2.0.0.

## Установка

### Требования
- PHP >= 7.4
- Composer (установленный глобально или локально)
- Битрикс (любая версия с поддержкой D7)

### Установка через Composer
Модуль опубликован в Packagist для максимально простой установки.

#### Шаг 1: Создание composer.json (если не существует)

Для чистого проекта без composer.json:

```bash
cd /path/to/your/bitrix/project
composer init
```

Если composer не установлен, установите его:
```bash
# Windows
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer.phar

# Linux/Mac
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Затем отредактируйте созданный composer.json:

```json
{
    "name": "your-company/your-project",
    "description": "Your Bitrix project",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "pink80/core": "^2.0"
    },
    "autoload": {
        "psr-4": {
            "Pink80\\Core\\": "local/modules/pink80.core/lib/",
            "Project\\Core\\": "local/modules/project.core/lib/"
        }
    },
    "extra": {
        "installer-paths": {
            "local/modules/pink80.core": ["type:bitrix-module"]
        }
    },
    "require-dev": {
        "composer/installers": "^2.0"
    },
    "config": {
        "optimize-autoloader": true,
        "classmap-authoritative": false,
        "allow-plugins": {
            "composer/installers": true
        }
    }
}
```

**Обратите внимание:** Секции `extra`, `require-dev` и `config` обязательны для правильной установки модуля в `local/modules/pink80.core/` через composer/installers.

#### Шаг 2: Установка модуля
```bash
composer require pink80/core
```

При установке автоматически:
1. Модуль устанавливается напрямую в `local/modules/pink80.core`
2. Настраивается автозагрузка через composer
3. Модуль готов к использованию

#### Шаг 3: Создание init.php (если не существует)

Для чистого проекта без `local/php_interface/init.php`:

Создайте файл `local/php_interface/init.php`:

```php
<?php

/**
 * Файл автозагрузки модулей
 * Подключается автоматически при загрузке Битрикс
 */

// Подключение основного модуля pink80.core
$corePath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/pink80.core/include.php';
if (file_exists($corePath)) {
    require_once $corePath;
    if (class_exists('Pink80\Core\LocalCore')) {
        \Pink80\Core\LocalCore::init();
    }
}

// Подключение проектного модуля project.core (если создан)
$projectPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/project.core/include.php';
if (file_exists($projectPath)) {
    require_once $projectPath;
    if (class_exists('Project\Core\ProjectCore')) {
        \Project\Core\ProjectCore::init();
    }
}
```

**Важно для существующих проектов:**
- Если папка `local/modules/pink80.core` уже существует → composer перезапишет её
- Если файл `local/php_interface/init.php` уже существует → нужно добавить загрузку модуля вручную
- Рекомендуется проверить существующий `init.php` и добавить загрузку модуля

#### Обновление:
```bash
composer update pink80/core
```

### Установка в существующий проект

Если проект уже имеет структуру `local/` и файл `init.php`:

#### 1. Проверьте существующий init.php
```bash
# Проверьте, существует ли файл
cat local/php_interface/init.php
```

#### 2. Добавьте загрузку модуля
Вставьте этот код в начало или конец существующего `local/php_interface/init.php`:

```php
// Pink80 Core Module
$corePath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/pink80.core/include.php';
if (file_exists($corePath)) {
    require_once $corePath;
    if (class_exists('Pink80\Core\LocalCore')) {
        \Pink80\Core\LocalCore::init();
    }
}
```

#### 3. Установите модуль через composer
```bash
composer require pink80/core
```

#### 4. Проверьте конфликты
```bash
php local/modules/pink80.core/bin/conflict-detector.php
```

#### 5. Если существует project.core
Убедитесь, что нет дубликатов классов между `pink80.core` и `project.core`.

### Быстрая установка

Если composer.json уже существует, просто добавьте зависимость:

```bash
composer require pink80/core
```

Модуль автоматически установится в `local/modules/pink80.core/` через composer/installers.

**Важно:** Для работы installer-paths composer.json проекта должен содержать:

```json
{
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

### Создание project.core

Для проект-специфичных изменений создайте модуль `project.core`:

```bash
mkdir -p local/modules/project.core/lib
```

Создайте файл `local/modules/project.core/include.php`:

```php
<?php

namespace Project\Core;

class ProjectCore extends \Pink80\Core\LocalCore
{
    const MODULE_ID = 'project.core';
    
    public static function init()
    {
        parent::init();
        self::registerProjectHandlers();
    }
    
    private static function registerProjectHandlers()
    {
        // Проект-специфичные обработчики
    }
}

ProjectCore::registerAutoload();
```

Папка `local/modules/project.core/` попадает в git и содержит только проектный код.

### Модификация pink80.core

Если вы хотите внести изменения в сам модуль `pink80.core`:

#### Для временных изменений:
Используйте `project.core` - создайте дубликат класса с изменённым функционалом.

#### Для постоянных изменений:
1. Клонируйте репозиторий модуля:
```bash
git clone git@github.com:oepink80/pink80-core.git
cd pink80-core
```

2. Внесите изменения

3. Увеличьте версию в `composer.json` и `include.php`

4. Запушьте изменения:
```bash
git add .
git commit -m "Описание изменений"
git push origin master
git tag v2.0.1
git push origin master --tags
```

5. Обновите в проекте:
```bash
composer update pink80/core
```

**Важно:** Проверьте конфликты классов перед обновлением:
```bash
php local/modules/pink80.core/bin/conflict-detector.php
```

### Ручная установка
Если composer не используется:
1. Скопируйте папку `pink80.core` в `local/modules/`
2. Настройте автозагрузку:

**Если файл `local/php_interface/init.php` уже существует:**
```php
// Добавьте в начало или конец существующего файла
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/pink80.core/include.php';
if (class_exists('Pink80\Core\LocalCore')) {
    \Pink80\Core\LocalCore::init();
}
```

**Если файл `local/php_interface/init.php` не существует:**
Создайте файл `local/php_interface/init.php`:
```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/pink80.core/include.php';
if (class_exists('Pink80\Core\LocalCore')) {
    \Pink80\Core\LocalCore::init();
}
```

## Структура модуля

```
local/modules/pink80.core/
├── bin/                    # CLI команды
│   ├── console            # Точка входа для консольных команд
│   └── conflict-detector.php # Детектор конфликтов классов
├── handlers/               # Регистрация обработчиков событий
│   └── EventHandlerRegistrar.php
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
│   └── Interfaces/        # Интерфейсы
├── composer.json          # Composer конфигурация
├── README.md              # Документация
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
