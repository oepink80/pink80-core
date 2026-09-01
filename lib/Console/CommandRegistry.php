<?php

namespace Pink80\Core\Console;

use Pink80\Core\Interfaces\CommandInterface;

/**
 * Реестр консольных команд
 */
class CommandRegistry
{
    /**
     * Зарегистрированные команды
     */
    private static $commands = [];
    
    /**
     * Зарегистрировать команду
     */
    public static function register($commandClass)
    {
        if (!class_exists($commandClass)) {
            throw new \Exception("Класс команды {$commandClass} не существует");
        }
        
        if (!is_subclass_of($commandClass, CommandInterface::class)) {
            throw new \Exception("Класс {$commandClass} должен реализовывать CommandInterface");
        }
        
        $commandName = $commandClass::getName();
        self::$commands[$commandName] = $commandClass;
    }
    
    /**
     * Получить команду по имени
     */
    public static function getCommand($name)
    {
        if (!isset(self::$commands[$name])) {
            return null;
        }
        
        $commandClass = self::$commands[$name];
        return new $commandClass();
    }
    
    /**
     * Получить все команды
     */
    public static function getAllCommands()
    {
        return self::$commands;
    }
    
    /**
     * Проверить существование команды
     */
    public static function hasCommand($name)
    {
        return isset(self::$commands[$name]);
    }
    
    /**
     * Автоматическая регистрация команд из папки Commands
     */
    public static function autoRegister()
    {
        $commandsPath = dirname(__DIR__) . '/Console/Commands';
        
        if (!is_dir($commandsPath)) {
            return;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($commandsPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $className = self::getClassNameFromFile($file->getPathname());
                
                if ($className && class_exists($className)) {
                    try {
                        self::register($className);
                    } catch (\Exception $e) {
                        // Игнорируем ошибки при регистрации
                    }
                }
            }
        }
    }
    
    /**
     * Получить имя класса из файла
     */
    private static function getClassNameFromFile($filePath)
    {
        $content = file_get_contents($filePath);
        
        if (preg_match('/namespace\s+([\w\\]+);/', $content, $matches)) {
            $namespace = $matches[1];
            
            if (preg_match('/class\s+(\w+)\s+/', $content, $matches)) {
                $className = $matches[1];
                return $namespace . '\\' . $className;
            }
        }
        
        return null;
    }
}
