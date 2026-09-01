# Pink80 Local Core

Локальное ядро для упрощения разработки на Битрикс. Включает хелперы, фабрики, обработчики событий и CLI команды.

**Важно:** Модуль устанавливается только через Composer. Админ-установка не поддерживается с версии 2.0.0.

## Установка

### Требования
- PHP >= 7.4
- Composer (установленный глобально или локально)
- Битрикс (любая версия с поддержкой D7)

### Установка через Composer (рекомендуемый метод)

Модуль устанавливается через composer **внутри папки local/** для изоляции от основного проекта.

#### Шаг 1: Перейти в папку local
```bash
cd local
```

#### Шаг 2: Создать composer.json в local/

Создайте файл `local/composer.json`:

```json
{
    "name": "your-company/local",
    "description": "Local modules for Bitrix project",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "pink80/core": "^2.0"
    },
    "autoload": {
        "psr-4": {
            "Pink80\\Core\\": "pink80.core/lib/",
            "Project\\Core\\": "project.core/lib/"
        }
    },
    "extra": {
        "installer-paths": {
            "modules/pink80.core": ["type:bitrix-module"]
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

#### Шаг 3: Установить модуль
```bash
composer install
```

Результат:
- ✅ Модуль установится в `local/modules/pink80.core/`
- ✅ Composer зависимости в `local/vendor/`
- ✅ Autoload настроен

#### Шаг 4: Создать project.core

Создайте структуру для проектных изменений:

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

#### Шаг 5: Создать init.php

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

// Подключение проектного модуля project.core
$projectPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/project.core/include.php';
if (file_exists($projectPath)) {
    require_once $projectPath;
    if (class_exists('Project\Core\ProjectCore')) {
        \Project\Core\ProjectCore::init();
    }
}
```

#### Обновление
```bash
cd local
composer update pink80/core
```

### Структура проекта после установки

```
F:\www\uralgarnet/
├── local/
│   ├── composer.json          # Composer конфигурация для модулей
│   ├── composer.lock
│   ├── vendor/               # Composer зависимости
│   ├── modules/
│   │   ├── pink80.core/     # Установлен через composer
│   │   └── project.core/    # Проектные изменения (создаётся вручную)
│   └── php_interface/
│       └── init.php         # Автозагрузка модулей
└── bitrix/                  # Битрикс core
```

### .gitignore

Добавьте в `.gitignore` проекта:

```gitignore
bitrix/
local/vendor/
local/composer.lock
local/modules/pink80.core/
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
```

### Фабрики (Factories)

#### Фабрика для инфоблоков
```php
use Pink80\Core\Factories\Iblock\IblockFactory;

// Создать инфоблок
$iblock = IblockFactory::createIblock([
    'NAME' => 'Продукты',
    'CODE' => 'products',
    'IBLOCK_TYPE_ID' => 'catalog'
]);

// Создать элемент
$element = IblockFactory::createElement($iblockId, [
    'NAME' => 'Товар 1',
    'ACTIVE' => 'Y'
]);

// Получить инфоблок по коду
$iblock = IblockFactory::getByCode('products');
```

#### Фабрика для пользователей
```php
use Pink80\Core\Factories\User\UserFactory;

// Создать пользователя
$user = UserFactory::createUser([
    'LOGIN' => 'testuser',
    'EMAIL' => 'test@example.com',
    'PASSWORD' => 'password123'
]);

// Получить пользователя по логину
$user = UserFactory::getByLogin('testuser');
```

### Обработчики событий (Event Handlers)

Модуль автоматически регистрирует следующие обработчики:

#### Основные события
- OnBeforeProlog
- OnProlog
- OnAfterProlog
- OnPageStart
- OnEpilog

#### События инфоблоков
- OnAfterIBlockElementAdd
- OnAfterIBlockElementUpdate
- OnAfterIBlockElementDelete
- OnBeforeIBlockElementAdd
- OnBeforeIBlockElementUpdate

#### События пользователей
- OnAfterUserAdd
- OnAfterUserUpdate
- OnAfterUserDelete
- OnBeforeUserAdd
- OnBeforeUserUpdate
- OnUserLogin
- OnUserLogout

### CLI команды

#### Запуск консольных команд
```bash
php -d mbstring.func_overload=2 local/modules/pink80.core/bin/console <command>
```

#### Доступные команды
- `list` - Показать список всех команд
- `help` - Показать справку по команде

#### Создание собственной команды
```php
namespace Pink80\Core\Console\Commands;

use Pink80\Core\Console\BaseCommand;

class MyCommand extends BaseCommand
{
    public static function getName()
    {
        return 'my-command';
    }
    
    public static function getDescription()
    {
        return 'Моя команда';
    }
    
    public function execute(array $args = [], array $options = [])
    {
        $this->output('Hello from my command!');
    }
}
```

## Конфликт detector

Для проверки дубликатов классов между `pink80.core` и `project.core`:

```bash
php local/modules/pink80.core/bin/conflict-detector.php
```

Детектор сканирует оба модуля и предупреждает о наличии дубликатов FQCN.

## Модификация pink80.core

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

3. Увеличьте версию в `composer.json`

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
cd local
composer update pink80/core
```

**Важно:** Проверьте конфликты классов перед обновлением:
```bash
php local/modules/pink80.core/bin/conflict-detector.php
```

## Promotion workflow: Перенос функционала из project.core в pink80.core

Если функционал, разработанный в `project.core`, стал универсальным и нужен в других проектах:

#### Шаг 1: Подготовка к переносу
```bash
# Убедитесь, что код протестирован в проекте
php local/modules/pink80.core/bin/conflict-detector.php
```

#### Шаг 2: Клонирование основного репозитория
```bash
cd /path/to/work/
git clone git@github.com:oepink80/pink80-core.git
cd pink80-core
```

#### Шаг 3: Создание ветки для изменений
```bash
git checkout -b feature/имя-функционала
```

#### Шаг 4: Перенос кода
Скопируйте файлы из `local/modules/project.core/` в соответствующие папки `pink80.core/`
Измените namespace с `Project\Core` на `Pink80\Core`.

#### Шаг 5: Тестирование изменений
```bash
cd local
composer update pink80/core
# Протестируйте функционал
```

#### Шаг 6: Создание Pull Request
```bash
git add .
git commit -m "Add feature: описание функционала"
git push origin feature/имя-функционала
```

Создайте Pull Request на GitHub: https://github.com/oepink80/pink80-core/compare

#### Шаг 7: После слияния PR
1. Обновите версию в основном репозитории
2. Обновите проект: `cd local && composer update pink80/core`
3. Удалите дубликаты из `project.core`
4. Проверьте конфликты: `php local/modules/pink80.core/bin/conflict-detector.php`

#### Шаг 8: Commit изменений в проекте
```bash
git add local/modules/project.core
git commit -m "Remove duplicated code, now in pink80.core"
git push
```

**Важно:** Не переносите проект-специфичный код (business logic) в общий модуль. Переносите только универсальные утилиты, хелперы и обработчики.

## Требования и лицензия

- PHP >= 7.4
- Composer
- Битрикс с поддержкой D7
- MIT License
