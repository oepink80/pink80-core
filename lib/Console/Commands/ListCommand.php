<?php

namespace Pink80\Core\Console\Commands;

use Pink80\Core\Console\BaseCommand;

/**
 * Команда для вывода списка всех доступных команд
 */
class ListCommand extends BaseCommand
{
    /**
     * Получить имя команды
     */
    public static function getName()
    {
        return 'list';
    }
    
    /**
     * Получить описание команды
     */
    public static function getDescription()
    {
        return 'Показать список всех доступных команд';
    }
    
    /**
     * Выполнить команду
     */
    public function execute(array $args = [], array $options = [])
    {
        $this->output("Available commands:\n");
        
        $commands = \Pink80\Core\Console\CommandRegistry::getAllCommands();
        ksort($commands);
        
        foreach ($commands as $name => $class) {
            $description = $class::getDescription();
            $this->output("  {$name} - {$description}");
        }
        
        $this->output("\nUsage: php -d mbstring.func_overload=2 bin/console <command> [options] [arguments]");
    }
}
