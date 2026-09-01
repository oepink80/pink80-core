<?php

namespace Pink80\Core\Helpers\Highloadblock;

use Pink80\Core\Helpers\BaseHelper;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

/**
 * Хелпер для работы с HL блоками (D7 подход)
 */
class HighloadblockHelper extends BaseHelper
{
    /**
     * Проверка загрузки модуля HL блоков
     */
    private static function loadHighloadblockModule()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new \Exception('Модуль highloadblock не установлен');
        }
    }
    
    /**
     * Получить HL блок по ID
     */
    public static function getById($id)
    {
        self::loadHighloadblockModule();
        
        $hlblock = HighloadBlockTable::getByPrimary($id)->fetch();
        return $hlblock ?: null;
    }
    
    /**
     * Получить HL блок по названию
     */
    public static function getByName($name)
    {
        self::loadHighloadblockModule();
        
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['NAME' => $name],
            'limit' => 1
        ])->fetch();
        
        return $hlblock ?: null;
    }
    
    /**
     * Получить HL блок по названию таблицы
     */
    public static function getByTableName($tableName)
    {
        self::loadHighloadblockModule();
        
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['TABLE_NAME' => $tableName],
            'limit' => 1
        ])->fetch();
        
        return $hlblock ?: null;
    }
    
    /**
     * Получить все HL блоки
     */
    public static function getAll()
    {
        self::loadHighloadblockModule();
        
        return HighloadBlockTable::getList([
            'order' => ['ID' => 'ASC']
        ])->fetchAll();
    }
    
    /**
     * Получить сущность данных HL блока
     */
    public static function getEntityDataClass($hlblockId)
    {
        self::loadHighloadblockModule();
        
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
        self::loadHighloadblockModule();
        
        $hlblock = self::getByName($name);
        
        if (!$hlblock) {
            throw new \Exception("HL блок с названием {$name} не найден");
        }
        
        $entity = HighloadBlockTable::compileEntity($hlblock);
        return $entity->getDataClass();
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
