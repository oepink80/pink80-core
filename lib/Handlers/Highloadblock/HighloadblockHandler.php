<?php

namespace Pink80\Core\Handlers\Highloadblock;

use Pink80\Core\Handlers\BaseHandler;

/**
 * Обработчик событий HL блоков
 */
class HighloadblockHandler extends BaseHandler
{
    /**
     * Обработчик события OnAfterHighloadBlockAdd
     */
    public static function onAfterAdd(&$fields)
    {
        self::log('OnAfterHighloadBlockAdd triggered', ['fields' => $fields]);
        
        // Логика после добавления записи в HL блок
    }
    
    /**
     * Обработчик события OnAfterHighloadBlockUpdate
     */
    public static function onAfterUpdate(&$fields)
    {
        self::log('OnAfterHighloadBlockUpdate triggered', ['fields' => $fields]);
        
        // Логика после обновления записи в HL блок
    }
    
    /**
     * Обработчик события OnAfterHighloadBlockDelete
     */
    public static function onAfterDelete($id)
    {
        self::log('OnAfterHighloadBlockDelete triggered', ['id' => $id]);
        
        // Логика после удаления записи из HL блока
    }
    
    /**
     * Обработчик события OnBeforeHighloadBlockAdd
     */
    public static function onBeforeAdd(&$fields)
    {
        self::log('OnBeforeHighloadBlockAdd triggered', ['fields' => $fields]);
        
        // Логика перед добавлением записи в HL блок
        // Можно модифицировать $fields
    }
    
    /**
     * Обработчик события OnBeforeHighloadBlockUpdate
     */
    public static function onBeforeUpdate(&$fields)
    {
        self::log('OnBeforeHighloadBlockUpdate triggered', ['fields' => $fields]);
        
        // Логика перед обновлением записи в HL блок
    }
}
