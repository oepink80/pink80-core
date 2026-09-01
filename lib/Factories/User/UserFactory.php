<?php

namespace Pink80\Core\Factories\User;

use Pink80\Core\Factories\BaseFactory;
use Bitrix\Main\UserTable;

/**
 * Фабрика для работы с пользователями (D7 подход)
 */
class UserFactory extends BaseFactory
{
    /**
     * Получить имя сущности ORM
     */
    protected static function getEntityClass()
    {
        return UserTable::class;
    }
    
    /**
     * Получить модуль для загрузки
     */
    protected static function getModuleId()
    {
        return 'main';
    }
    
    /**
     * Создать нового пользователя
     */
    public static function createUser(array $data)
    {
        static::loadModule();
        
        $user = new \CUser();
        
        // Установка обязательных полей
        if (!isset($data['LOGIN'])) {
            throw new \Exception('LOGIN обязателен для создания пользователя');
        }
        
        if (!isset($data['EMAIL'])) {
            throw new \Exception('EMAIL обязателен для создания пользователя');
        }
        
        if (!isset($data['PASSWORD'])) {
            throw new \Exception('PASSWORD обязателен для создания пользователя');
        }
        
        if (!isset($data['ACTIVE'])) {
            $data['ACTIVE'] = 'Y';
        }
        
        $userId = $user->Add($data);
        
        if (!$userId) {
            throw new \Exception($user->LAST_ERROR);
        }
        
        return self::getById($userId);
    }
    
    /**
     * Получить пользователя по логину
     */
    public static function getByLogin($login)
    {
        static::loadModule();
        
        return UserTable::getList([
            'filter' => ['LOGIN' => $login],
            'limit' => 1
        ])->fetchObject();
    }
    
    /**
     * Получить пользователя по email
     */
    public static function getByEmail($email)
    {
        static::loadModule();
        
        return UserTable::getList([
            'filter' => ['EMAIL' => $email],
            'limit' => 1
        ])->fetchObject();
    }
    
    /**
     * Получить администраторов
     */
    public static function getAdmins()
    {
        static::loadModule();
        
        $adminGroupId = 1; // ID группы администраторов по умолчанию
        
        return UserTable::getList([
            'filter' => [
                'USER.GROUP.GROUP_ID' => $adminGroupId
            ],
            'select' => ['*', 'USER_GROUP'],
            'runtime' => [
                'USER_GROUP' => [
                    'data_type' => \Bitrix\Main\UserGroupTable::class,
                    'reference' => [
                        'this.ID' => 'ref.USER_ID'
                    ]
                ]
            ]
        ])->fetchAll();
    }
}
