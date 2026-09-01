<?php

namespace Pink80\Core\Helpers;

/**
 * Базовый абстрактный класс для всех хелперов
 */
abstract class BaseHelper
{
    /**
     * Получить ID модуля
     */
    protected static function getModuleId()
    {
        return 'pink80.core';
    }
    
    /**
     * Логирование для отладки
     */
    protected static function log($message, $context = [])
    {
        if (defined('BX_DEBUG') && BX_DEBUG === true) {
            AddMessage2Log($message, self::getModuleId());
        }
    }
    
    /**
     * Безопасное получение значения из массива
     */
    protected static function getArrayValue(array $array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
    
    /**
     * Проверка на пустоту значения
     */
    protected static function isEmpty($value)
    {
        return $value === null || $value === '' || $value === [];
    }
}
