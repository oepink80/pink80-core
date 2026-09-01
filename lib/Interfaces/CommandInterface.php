<?php

namespace Pink80\Core\Interfaces;

/**
 * Интерфейс для консольных команд
 */
interface CommandInterface
{
    /**
     * Получить имя команды
     */
    public static function getName();
    
    /**
     * Получить описание команды
     */
    public static function getDescription();
    
    /**
     * Получить аргументы команды
     */
    public static function getArguments();
    
    /**
     * Получить опции команды
     */
    public static function getOptions();
    
    /**
     * Выполнить команду
     */
    public function execute(array $args = [], array $options = []);
}
