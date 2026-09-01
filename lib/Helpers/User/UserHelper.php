<?php

namespace Pink80\Core\Helpers\User;

use Pink80\Core\Helpers\BaseHelper;
use Bitrix\Main\UserTable;
use Bitrix\Main\Loader;

/**
 * Хелпер для работы с пользователями (D7 подход)
 */
class UserHelper extends BaseHelper
{
    /**
     * Проверка загрузки модуля пользователей
     */
    private static function loadUserModule()
    {
        if (!Loader::includeModule('main')) {
            throw new \Exception('Модуль пользователей не установлен');
        }
    }
    
    /**
     * Получить текущего пользователя
     */
    public static function getCurrent()
    {
        global $USER;
        return $USER;
    }
    
    /**
     * Получить ID текущего пользователя
     */
    public static function getCurrentId()
    {
        $user = self::getCurrent();
        return $user->GetID();
    }
    
    /**
     * Проверить авторизацию пользователя
     */
    public static function isAuthorized()
    {
        $user = self::getCurrent();
        return $user->IsAuthorized();
    }
    
    /**
     * Проверить является ли пользователь администратором
     */
    public static function isAdmin()
    {
        $user = self::getCurrent();
        return $user->IsAdmin();
    }
    
    /**
     * Получить данные пользователя по ID (D7 подход)
     */
    public static function getById($userId)
    {
        self::loadUserModule();
        
        $user = UserTable::getByPrimary($userId)->fetch();
        return $user ?: null;
    }
    
    /**
     * Получить пользователя по логину (D7 подход)
     */
    public static function getByLogin($login)
    {
        self::loadUserModule();
        
        $user = UserTable::getList([
            'filter' => ['LOGIN' => $login],
            'limit' => 1
        ])->fetch();
        
        return $user ?: null;
    }
    
    /**
     * Получить пользователя по email (D7 подход)
     */
    public static function getByEmail($email)
    {
        self::loadUserModule();
        
        $user = UserTable::getList([
            'filter' => ['EMAIL' => $email],
            'limit' => 1
        ])->fetch();
        
        return $user ?: null;
    }
    
    /**
     * Получить группы пользователя (используем старый метод, так как аналога нет в D7)
     */
    public static function getUserGroups($userId)
    {
        return \CUser::GetUserGroup($userId);
    }
    
    /**
     * Проверить принадлежность к группе
     */
    public static function isInGroup($userId, $groupId)
    {
        $groups = self::getUserGroups($userId);
        return in_array($groupId, $groups);
    }
    
    /**
     * Получить пользователей по фильтру (D7 подход)
     */
    public static function getList($filter = [], $select = [], $order = ['ID' => 'ASC'])
    {
        self::loadUserModule();
        
        return UserTable::getList([
            'filter' => $filter,
            'select' => $select ?: ['*'],
            'order' => $order
        ])->fetchAll();
    }
}
