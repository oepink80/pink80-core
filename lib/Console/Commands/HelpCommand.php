<?php

namespace Pink80\Core\Console\Commands;

use Pink80\Core\Console\BaseCommand;

/**
 * Команда для вывода справки по команде
 */
class HelpCommand extends BaseCommand
{
    /**
     * Получить имя команды
     */
    public static function getName()
    {
        return 'help';
    }
    
    /**
     * Получить описание команды
     */
    public static function getDescription()
    {
        return 'Показать справку по команде';
    }
    
    /**
     * Получить аргументы команды
     */
    public static function getArguments()
    {
        return [
            [
                'name' => 'command',
                'description' => 'Имя команды для справки',
                'required' => false,
                'default' => null
            ]
        ];
    }
    
    /**
     * Выполнить команду
     */
    public function execute(array $args = [], array $options = [])
    {
        $commandName = $this->getArgument('command', $args);
        
        if (!$commandName) {
            $this->output("Usage: php -d mbstring.func_overload=2 bin/console help <command>");
            $this->output("\nДля получения списка всех команд используйте: php -d mbstring.func_overload=2 bin/console list");
            return;
        }
        
        if (!\Pink80\Core\Console\CommandRegistry::hasCommand($commandName)) {
            $this->error("Команда '{$commandName}' не найдена");
            return;
        }
        
        $commandClass = \Pink80\Core\Console\CommandRegistry::getAllCommands()[$commandName];
        
        $this->output("Command: {$commandName}");
        $this->output("Description: {$commandClass::getDescription()}");
        
        $arguments = $commandClass::getArguments();
        if (!empty($arguments)) {
            $this->output("\nArguments:");
            foreach ($arguments as $arg) {
                $required = isset($arg['required']) && $arg['required'] ? ' (required)' : '';
                $default = isset($arg['default']) ? " [default: {$arg['default']}]" : '';
                $this->output("  {$arg['name']} - {$arg['description']}{$required}{$default}");
            }
        }
        
        $options = $commandClass::getOptions();
        if (!empty($options)) {
            $this->output("\nOptions:");
            foreach ($options as $opt) {
                $this->output("  --{$opt['name']} - {$opt['description']}");
            }
        }
    }
}
