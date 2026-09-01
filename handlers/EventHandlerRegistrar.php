<?php

namespace Pink80\Core\Handlers;

/**
 * Класс для регистрации обработчиков событий
 */
class EventHandlerRegistrar
{
    /**
     * Регистрация всех обработчиков событий модуля
     */
    public static function registerAll()
    {
        // Регистрация обработчиков основных событий
        AddEventHandler('main', 'OnBeforeProlog', ['Pink80\Core\Handlers\Main\MainHandler', 'onBeforeProlog']);
        AddEventHandler('main', 'OnProlog', ['Pink80\Core\Handlers\Main\MainHandler', 'onProlog']);
        AddEventHandler('main', 'OnAfterProlog', ['Pink80\Core\Handlers\Main\MainHandler', 'onAfterProlog']);
        AddEventHandler('main', 'OnPageStart', ['Pink80\Core\Handlers\Main\MainHandler', 'onPageStart']);
        AddEventHandler('main', 'OnEpilog', ['Pink80\Core\Handlers\Main\MainHandler', 'onEpilog']);
        
        // Регистрация обработчиков событий инфоблоков
        AddEventHandler('iblock', 'OnAfterIBlockElementAdd', ['Pink80\Core\Handlers\Iblock\IblockHandler', 'onAfterElementAdd']);
        AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['Pink80\Core\Handlers\Iblock\IblockHandler', 'onAfterElementUpdate']);
        AddEventHandler('iblock', 'OnAfterIBlockElementDelete', ['Pink80\Core\Handlers\Iblock\IblockHandler', 'onAfterElementDelete']);
        AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', ['Pink80\Core\Handlers\Iblock\IblockHandler', 'onBeforeElementAdd']);
        AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['Pink80\Core\Handlers\Iblock\IblockHandler', 'onBeforeElementUpdate']);
        
        // Регистрация обработчиков событий пользователей
        AddEventHandler('main', 'OnAfterUserAdd', ['Pink80\Core\Handlers\User\UserHandler', 'onAfterUserAdd']);
        AddEventHandler('main', 'OnAfterUserUpdate', ['Pink80\Core\Handlers\User\UserHandler', 'onAfterUserUpdate']);
        AddEventHandler('main', 'OnAfterUserDelete', ['Pink80\Core\Handlers\User\UserHandler', 'onAfterUserDelete']);
        AddEventHandler('main', 'OnBeforeUserAdd', ['Pink80\Core\Handlers\User\UserHandler', 'onBeforeUserAdd']);
        AddEventHandler('main', 'OnBeforeUserUpdate', ['Pink80\Core\Handlers\User\UserHandler', 'onBeforeUserUpdate']);
        AddEventHandler('main', 'OnUserLogin', ['Pink80\Core\Handlers\User\UserHandler', 'onUserLogin']);
        AddEventHandler('main', 'OnUserLogout', ['Pink80\Core\Handlers\User\UserHandler', 'onUserLogout']);
        
        // Регистрация обработчиков событий HL блоков
        // Для HL блоков события могут отличаться в зависимости от версии Битрикс
        // Здесь добавлена базовая структура
    }
}
