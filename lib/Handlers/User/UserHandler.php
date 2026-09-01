<?php

namespace Pink80\Core\Handlers\User;

use Pink80\Core\Handlers\BaseHandler;

/**
 * Обработчик событий пользователей
 */
class UserHandler extends BaseHandler
{
    /**
     * Обработчик события OnAfterUserAdd
     */
    public static function onAfterUserAdd(&$fields)
    {
        self::log('OnAfterUserAdd triggered', ['fields' => $fields]);
        
        // Логика после добавления пользователя
        // Например: отправка приветственного письма, добавление в группы и т.д.
    }
    
    /**
     * Обработчик события OnAfterUserUpdate
     */
    public static function onAfterUserUpdate(&$fields)
    {
        self::log('OnAfterUserUpdate triggered', ['fields' => $fields]);
        
        // Логика после обновления пользователя
    }
    
    /**
     * Обработчик события OnAfterUserDelete
     */
    public static function onAfterUserDelete($userId)
    {
        self::log('OnAfterUserDelete triggered', ['userId' => $userId]);
        
        // Логика после удаления пользователя
    }
    
    /**
     * Обработчик события OnBeforeUserAdd
     */
    public static function onBeforeUserAdd(&$fields)
    {
        self::log('OnBeforeUserAdd triggered', ['fields' => $fields]);
        
        // Логика перед добавлением пользователя
        // Можно модифицировать $fields
    }
    
    /**
     * Обработчик события OnBeforeUserUpdate
     */
    public static function onBeforeUserUpdate(&$fields)
    {
        self::log('OnBeforeUserUpdate triggered', ['fields' => $fields]);
        
        // Логика перед обновлением пользователя
    }
    
    /**
     * Обработчик события OnUserLogin
     */
    public static function onUserLogin(&$userId)
    {
        self::log('OnUserLogin triggered', ['userId' => $userId]);
        
        // Логика при авторизации пользователя
        // Например: запись последнего посещения, логирование и т.д.
    }
    
    /**
     * Обработчик события OnUserLogout
     */
    public static function onUserLogout($userId)
    {
        self::log('OnUserLogout triggered', ['userId' => $userId]);
        
        // Логика при выходе пользователя
    }
}
