<?php

/**
 * Основной файл модуля pink80.core
 * 
 * Модуль для упрощения разработки на Битрикс
 * Включает хелперы, фабрики, обработчики событий и крон задачи
 */

namespace Pink80\Core;

/**
 * Класс модуля
 */
class LocalCore
{
    const MODULE_ID = 'pink80.core';
    const MODULE_VERSION = '1.0.0';
    
    /**
     * Путь к модулю
     */
    private static $modulePath = null;
    
    /**
     * Получить путь к модулю
     */
    public static function getModulePath()
    {
        if (self::$modulePath === null) {
            self::$modulePath = dirname(__DIR__) . '/' . self::MODULE_ID;
        }
        return self::$modulePath;
    }
    
    /**
     * Автозагрузка классов модуля
     * Использует composer autoloader если доступен, иначе fallback на собственную реализацию
     */
    public static function autoload($className)
    {
        $className = ltrim($className, '\\');
        $namespace = 'Pink80\\Core\\';
        
        if (strpos($className, $namespace) !== 0) {
            return;
        }
        
        $relativeClass = substr($className, strlen($namespace));
        $filePath = self::getModulePath() . '/lib/' . str_replace('\\', '/', $relativeClass) . '.php';
        
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
    
    /**
     * Регистрация автозагрузки
     * Приоритет: composer autoloader > собственная реализация
     */
    public static function registerAutoload()
    {
        // Пытаемся загрузить composer autoloader
        $composerAutoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
        if (file_exists($composerAutoload)) {
            require_once $composerAutoload;
        } else {
            // Fallback на собственную автозагрузку
            spl_autoload_register([__CLASS__, 'autoload']);
        }
    }
    
    /**
     * Инициализация модуля
     */
    public static function init()
    {
        self::registerAutoload();
        
        // Регистрация обработчиков событий через отдельный класс
        if (class_exists('\Pink80\Core\Handlers\EventHandlerRegistrar')) {
            \Pink80\Core\Handlers\EventHandlerRegistrar::registerAll();
        }
    }
}

// Регистрация автозагрузки при подключении файла
LocalCore::registerAutoload();
