<?php

namespace Pink80\Core\Console;

use Pink80\Core\Console\CommandRegistry;

/**
 * Консольное приложение для выполнения команд
 */
class ConsoleApplication
{
    /**
     * Запустить приложение
     */
    public function run()
    {
        // Автоматическая регистрация команд
        CommandRegistry::autoRegister();
        
        // Регистрация базовых команд
        CommandRegistry::register('Pink80\Core\Console\Commands\ListCommand');
        CommandRegistry::register('Pink80\Core\Console\Commands\HelpCommand');
        
        // Получаем аргументы командной строки
        $argv = $_SERVER['argv'];
        array_shift($argv); // Удаляем имя скрипта
        
        if (empty($argv)) {
            $this->showUsage();
            return;
        }
        
        $commandName = array_shift($argv);
        
        if (!CommandRegistry::hasCommand($commandName)) {
            $this->error("Команда '{$commandName}' не найдена");
            $this->showAvailableCommands();
            return;
        }
        
        try {
            $command = CommandRegistry::getCommand($commandName);
            $this->parseAndExecute($command, $argv);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            exit(1);
        }
    }
    
    /**
     * Показать справку по использованию
     */
    private function showUsage()
    {
        echo "Pink80 Core Console Application\n";
        echo "Usage: php -d mbstring.func_overload=2 bin/console <command> [options] [arguments]\n\n";
        $this->showAvailableCommands();
    }
    
    /**
     * Показать доступные команды
     */
    private function showAvailableCommands()
    {
        echo "Available commands:\n";
        
        $commands = CommandRegistry::getAllCommands();
        ksort($commands);
        
        foreach ($commands as $name => $class) {
            $description = $class::getDescription();
            echo "  {$name} - {$description}\n";
        }
        
        echo "\n";
    }
    
    /**
     * Парсинг и выполнение команды
     */
    private function parseAndExecute($command, $argv)
    {
        $args = [];
        $options = [];
        
        foreach ($argv as $arg) {
            if (strpos($arg, '--') === 0) {
                // Опция
                $option = substr($arg, 2);
                if (strpos($option, '=') !== false) {
                    list($name, $value) = explode('=', $option, 2);
                    $options[$name] = $value;
                } else {
                    $options[$option] = true;
                }
            } elseif (strpos($arg, '-') === 0) {
                // Короткая опция
                $option = substr($arg, 1);
                $options[$option] = true;
            } else {
                // Аргумент
                $args[] = $arg;
            }
        }
        
        // Нормализация аргументов согласно определению команды
        $commandArgs = $this->normalizeArguments($command, $args);
        
        $command->execute($commandArgs, $options);
    }
    
    /**
     * Нормализация аргументов
     */
    private function normalizeArguments($command, $args)
    {
        $definedArgs = $command::getArguments();
        $normalized = [];
        
        foreach ($definedArgs as $index => $arg) {
            if (isset($args[$index])) {
                $normalized[$arg['name']] = $args[$index];
            } elseif (isset($arg['required']) && $arg['required']) {
                throw new \Exception("Аргумент '{$arg['name']}' обязателен");
            } elseif (isset($arg['default'])) {
                $normalized[$arg['name']] = $arg['default'];
            }
        }
        
        return $normalized;
    }
}
