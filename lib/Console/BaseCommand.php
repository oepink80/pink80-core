<?php

namespace Pink80\Core\Console;

use Pink80\Core\Interfaces\CommandInterface;
use Pink80\Core\Helpers\BaseHelper;

/**
 * Базовый класс для консольных команд
 */
abstract class BaseCommand extends BaseHelper implements CommandInterface
{
    /**
     * Получить имя команды
     */
    abstract public static function getName();
    
    /**
     * Получить описание команды
     */
    abstract public static function getDescription();
    
    /**
     * Получить аргументы команды
     */
    public static function getArguments()
    {
        return [];
    }
    
    /**
     * Получить опции команды
     */
    public static function getOptions()
    {
        return [];
    }
    
    /**
     * Выполнить команду
     */
    abstract public function execute(array $args = [], array $options = []);
    
    /**
     * Вывести сообщение в консоль
     */
    protected function output($message)
    {
        echo $message . "\n";
    }
    
    /**
     * Вывести ошибку в консоль
     */
    protected function error($message)
    {
        echo "ERROR: " . $message . "\n";
    }
    
    /**
     * Вывести информацию в консоль
     */
    protected function info($message)
    {
        echo "INFO: " . $message . "\n";
    }
    
    /**
     * Вывести предупреждение в консоль
     */
    protected function warning($message)
    {
        echo "WARNING: " . $message . "\n";
    }
    
    /**
     * Вывести успешное сообщение в консоль
     */
    protected function success($message)
    {
        echo "SUCCESS: " . $message . "\n";
    }
    
    /**
     * Получить аргумент по имени
     */
    protected function getArgument($name, array $args, $default = null)
    {
        return isset($args[$name]) ? $args[$name] : $default;
    }
    
    /**
     * Получить опцию по имени
     */
    protected function getOption($name, array $options, $default = null)
    {
        return isset($options[$name]) ? $options[$name] : $default;
    }
    
    /**
     * Проверить наличие опции
     */
    protected function hasOption($name, array $options)
    {
        return isset($options[$name]);
    }
}
