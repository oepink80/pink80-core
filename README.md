# Pink80 Local Core

Локальное ядро для упрощения разработки на Битрикс. Включает хелперы, фабрики, обработчики событий и CLI команды.

**Важно:** Модуль устанавливается только через Composer. Админ-установка не поддерживается с версии 2.0.0.

## Установка

### Требования
- PHP >= 7.4
- Composer (установленный глобально или локально)
- Битрикс (любая версия с поддержкой D7)

### Шаг 1: Подготовка проекта

#### 1.1. Создать composer.json в local/

Перейдите в папку local:
```bash
cd local
```

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
            "Pink80\\Core\\": "modules/pink80.core/lib/",
            "Project\\Core\\": "modules/project.core/lib/"
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

#### 1.2. Установить модуль
```bash
composer install
```

Результат:
- ✅ Модуль установится в `local/modules/pink80.core/`
- ✅ Composer зависимости в `local/vendor/`
- ✅ Autoload настроен

### Шаг 2: Создание project.core

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

### Шаг 3: Создание init.php

Создайте файл `local/php_interface/init.php`:

```php
<?php

/**
 * Файл автозагрузки модулей
 * Подключается автоматически при загрузке Битрикс
 */

// Подключение composer autoload
$composerAutoload = $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

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

### Шаг 4: Настройка .gitignore

Добавьте в `.gitignore` проекта:

```gitignore
bitrix/
local/vendor/
local/composer.lock
local/modules/pink80.core/
```

### Итоговая структура проекта

```
F:\www\uralgarnet/
├── local/
│   ├── composer.json          # Composer конфигурация для модулей
│   ├── composer.lock
│   ├── vendor/               # Composer зависимости
│   ├── modules/
│   │   ├── pink80.core/     # Установлен через composer
│   │   └── project.core/    # Проектные изменения
│   └── php_interface/
│       └── init.php         # Автозагрузка модулей
└── bitrix/                  # Битрикс core
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

Создайте файл в `local/modules/project.core/lib/Console/Commands/MyCommand.php`:

```php
<?php

namespace Project\Core\Console\Commands;

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

Зарегистрируйте команду в `project.core/include.php`:

```php
private static function registerProjectHandlers()
{
    \Pink80\Core\Console\CommandRegistry::register('Project\Core\Console\Commands\MyCommand');
}
```

После создания команды перегенерируйте autoload:
```bash
cd local
composer dump-autoload
```

Запустите команду:
```bash
php -d mbstring.func_overload=2 local/modules/pink80.core/bin/console my-command
```

### Конфликт detector

Для проверки дубликатов классов между `pink80.core` и `project.core`:

```bash
php local/modules/pink80.core/bin/conflict-detector.php
```

Детектор сканирует оба модуля и предупреждает о наличии дубликатов FQCN.

## Модификация модуля

### Вариант 1: Временные изменения (рекомендуется)

Используйте `project.core` для быстрых изменений:
- Создайте класс в `local/modules/project.core/lib/`
- Протестируйте в проекте
- Если функционал универсальный → перенесите в pink80.core (см. Promotion workflow ниже)

**Преимущества:**
- ✅ Изменения не потеряются при обновлении pink80.core
- ✅ Легко отменить
- ✅ Изолировано от общего модуля

### Вариант 2: Постоянные изменения в pink80.core (для владельца репозитория)

Если вы владелец репозитория `pink80-core`, самый простой способ:

#### Чек-лист для переноса функционала:

1. **Протестируйте в project.core**
   - Создайте функционал в `local/modules/project.core/lib/`
   - Тщательно протестируйте в проекте
   - Убедитесь, что функционал универсальный

2. **Подготовьте основной репозиторий**
   ```bash
   cd C:\temp\pink80-core  # или другая рабочая папка
   git pull origin master
   ```

3. **Добавьте функционал в основной модуль**
   - Откройте соответствующий файл в `lib/`
   - Добавьте метод/класс (НЕ копируйте файл целиком!)
   - Измените namespace на `Pink80\Core`
   - Сохраните файл

4. **Проверьте изменения**
   ```bash
   git diff lib/Helpers/Iblock/IblockHelper.php
   ```
   Убедитесь, что видите только добавление новых строк (+), без удаления существующих (-).

5. **Commit и push**
   ```bash
   git add lib/Helpers/Iblock/IblockHelper.php
   git commit -m "Add description of changes"
   git push origin master
   ```

6. **Обновите версию**
   - Измените версию в `composer.json` (например, 2.0.2 → 2.0.3)
   - Commit, push, tag

7. **Обновите модуль в проекте**
   ```bash
   cd local
   composer update pink80/core
   ```

8. **Удалите дубликаты из project.core**
   - Удалите перенесённый файл/метод из `local/modules/project.core/`
   - Проверьте конфликты: `php local/modules/pink80.core/bin/conflict-detector.php`
   - Commit изменений проекта

#### Пример правильного переноса метода:

**Что нужно перенести (из project.core):**
```php
// local/modules/project.core/lib/Helpers/Iblock/IblockHelper.php
public static function getPropertyEnumValues($iblockId, $propertyCode)
{
    // код метода
}
```

**Как добавить в основной модуль:**
```php
// C:\temp\pink80-core/lib/Helpers/Iblock/IblockHelper.php
class IblockHelper extends BaseHelper
{
    // ... существующие методы ...
    
    // Добавить в конец класса, перед закрывающей скобкой
    public static function getPropertyEnumValues($iblockId, $propertyCode)
    {
        // код метода
    }
}
```

**Проверка через git diff:**
```bash
git diff lib/Helpers/Iblock/IblockHelper.php
```
Должен показывать только добавление новых строк (+), без удаления существующих (-).

### Вариант 3: Постоянные изменения через Pull Request (для контрибьюторов)

Если вы не владелец репозитория и хотите внести вклад:

1. Форкните репозиторий: https://github.com/oepink80/pink80-core/fork
2. Клонируйте свой форк в любую рабочую папку:
```bash
cd C:\temp
git clone git@github.com:YOUR_USERNAME/pink80-core.git
cd pink80-core
```

3. Создайте ветку для изменений:
```bash
git checkout -b feature/имя-функционала
```

4. Внесите изменения (следуя чек-листу из Варианта 2)

5. Запушьте в свой форк:
```bash
git add .
git commit -m "Add feature: описание изменений"
git push origin feature/имя-функционала
```

6. Создайте Pull Request: https://github.com/oepink80/pink80-core/compare

7. После слияния PR обновите модуль в проекте:
```bash
cd local
composer update pink80/core
```

## Обновление

Для обновления модуля до новой версии:

```bash
cd local
composer update pink80/core
```

После обновления проверьте конфликты:
```bash
php local/modules/pink80.core/bin/conflict-detector.php
```

## Требования и лицензия

- PHP >= 7.4
- Composer
- Битрикс с поддержкой D7
- MIT License
