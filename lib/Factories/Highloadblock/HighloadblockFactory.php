<?php

namespace Pink80\Core\Factories\Highloadblock;

use Pink80\Core\Factories\BaseFactory;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

/**
 * Фабрика для работы с HL блоками (D7 подход)
 */
class HighloadblockFactory extends BaseFactory
{
    /**
     * Получить имя сущности ORM
     */
    protected static function getEntityClass()
    {
        return HighloadBlockTable::class;
    }
    
    /**
     * Получить модуль для загрузки
     */
    protected static function getModuleId()
    {
        return 'highloadblock';
    }
    
    /**
     * Создать новый HL блок
     */
    public static function createHighloadblock(array $data)
    {
        static::loadModule();
        
        if (!Loader::includeModule('highloadblock')) {
            throw new \Exception('Модуль highloadblock не установлен');
        }
        
        // Установка обязательных полей
        if (!isset($data['NAME'])) {
            throw new \Exception('NAME обязателен для создания HL блока');
        }
        
        if (!isset($data['TABLE_NAME'])) {
            throw new \Exception('TABLE_NAME обязателен для создания HL блока');
        }
        
        $result = HighloadBlockTable::add($data);
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return self::getById($result->getId());
    }
    
    /**
     * Получить HL блок по названию
     */
    public static function getByName($name)
    {
        static::loadModule();
        
        return HighloadBlockTable::getList([
            'filter' => ['NAME' => $name],
            'limit' => 1
        ])->fetchObject();
    }
    
    /**
     * Получить HL блок по названию таблицы
     */
    public static function getByTableName($tableName)
    {
        static::loadModule();
        
        return HighloadBlockTable::getList([
            'filter' => ['TABLE_NAME' => $tableName],
            'limit' => 1
        ])->fetchObject();
    }
    
    /**
     * Получить сущность данных HL блока
     */
    public static function getEntityDataClass($hlblockId)
    {
        static::loadModule();
        
        $hlblock = self::getById($hlblockId);
        
        if (!$hlblock) {
            throw new \Exception("HL блок с ID {$hlblockId} не найден");
        }
        
        $entity = HighloadBlockTable::compileEntity($hlblock);
        return $entity->getDataClass();
    }
    
    /**
     * Получить сущность данных HL блока по названию
     */
    public static function getEntityDataClassByName($name)
    {
        static::loadModule();
        
        $hlblock = self::getByName($name);
        
        if (!$hlblock) {
            throw new \Exception("HL блок с названием {$name} не найден");
        }
        
        $entity = HighloadBlockTable::compileEntity($hlblock);
        return $entity->getDataClass();
    }
    
    /**
     * Добавить запись в HL блок
     */
    public static function addData($hlblockId, array $data)
    {
        $dataClass = self::getEntityDataClass($hlblockId);
        $result = $dataClass::add($data);
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return $result->getId();
    }
    
    /**
     * Обновить запись в HL блоке
     */
    public static function updateData($hlblockId, $id, array $data)
    {
        $dataClass = self::getEntityDataClass($hlblockId);
        $result = $dataClass::update($id, $data);
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return true;
    }
    
    /**
     * Удалить запись из HL блока
     */
    public static function deleteData($hlblockId, $id)
    {
        $dataClass = self::getEntityDataClass($hlblockId);
        $result = $dataClass::delete($id);
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return true;
    }
    
    /**
     * Получить записи из HL блока
     */
    public static function getDataList($hlblockId, array $parameters = [])
    {
        $dataClass = self::getEntityDataClass($hlblockId);
        return $dataClass::getList($parameters);
    }
    
    /**
     * Получить запись из HL блока по ID
     */
    public static function getDataById($hlblockId, $id)
    {
        $dataClass = self::getEntityDataClass($hlblockId);
        return $dataClass::getById($id)->fetch();
    }
}
