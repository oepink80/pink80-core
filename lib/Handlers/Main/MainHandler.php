<?php

namespace Pink80\Core\Handlers\Main;

use Pink80\Core\Handlers\BaseHandler;

/**
 * Обработчик основных событий Битрикс
 */
class MainHandler extends BaseHandler
{
    /**
     * Обработчик события OnBeforeProlog
     */
    public static function onBeforeProlog()
    {
        // Логика перед прологом
        self::log('OnBeforeProlog triggered');
    }
    
    /**
     * Обработчик события OnProlog
     */
    public static function onProlog()
    {
        // Логика в прологе
        self::log('OnProlog triggered');
    }
    
    /**
     * Обработчик события OnAfterProlog
     */
    public static function onAfterProlog()
    {
        // Логика после пролога
        self::log('OnAfterProlog triggered');
    }
    
    /**
     * Обработчик события OnPageStart
     */
    public static function onPageStart()
    {
        // Логика при старте страницы
        self::log('OnPageStart triggered');
    }
    
    /**
     * Обработчик события OnEpilog
     */
    public static function onEpilog()
    {
        // Логика в эпилоге
        self::log('OnEpilog triggered');
    }
}
