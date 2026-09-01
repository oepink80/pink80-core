<?php

namespace Pink80\Core\Handlers;

/**
 * Базовый класс для обработчиков событий
 */
abstract class BaseHandler
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
}
