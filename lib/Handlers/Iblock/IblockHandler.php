<?php

namespace Pink80\Core\Handlers\Iblock;

use Pink80\Core\Handlers\BaseHandler;

/**
 * Обработчик событий инфоблоков
 */
class IblockHandler extends BaseHandler
{
    /**
     * Обработчик события OnAfterIBlockElementAdd
     */
    public static function onAfterElementAdd(&$fields)
    {
        self::log('OnAfterIBlockElementAdd triggered', ['fields' => $fields]);
        
        // Логика после добавления элемента инфоблока
        // Например: отправка уведомлений, обновление кэша и т.д.
    }
    
    /**
     * Обработчик события OnAfterIBlockElementUpdate
     */
    public static function onAfterElementUpdate(&$fields)
    {
        self::log('OnAfterIBlockElementUpdate triggered', ['fields' => $fields]);
        
        // Логика после обновления элемента инфоблока
    }
    
    /**
     * Обработчик события OnAfterIBlockElementDelete
     */
    public static function onAfterElementDelete($elementId)
    {
        self::log('OnAfterIBlockElementDelete triggered', ['elementId' => $elementId]);
        
        // Логика после удаления элемента инфоблока
    }
    
    /**
     * Обработчик события OnBeforeIBlockElementAdd
     */
    public static function onBeforeElementAdd(&$fields)
    {
        self::log('OnBeforeIBlockElementAdd triggered', ['fields' => $fields]);
        
        // Логика перед добавлением элемента инфоблока
        // Можно модифицировать $fields
    }
    
    /**
     * Обработчик события OnBeforeIBlockElementUpdate
     */
    public static function onBeforeElementUpdate(&$fields)
    {
        self::log('OnBeforeIBlockElementUpdate triggered', ['fields' => $fields]);
        
        // Логика перед обновлением элемента инфоблока
    }
}
