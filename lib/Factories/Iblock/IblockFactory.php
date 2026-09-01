<?php

namespace Pink80\Core\Factories\Iblock;

use Pink80\Core\Factories\BaseFactory;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\ElementTable;

/**
 * Фабрика для работы с инфоблоками (D7 подход)
 */
class IblockFactory extends BaseFactory
{
    /**
     * Получить имя сущности ORM
     */
    protected static function getEntityClass()
    {
        return IblockTable::class;
    }
    
    /**
     * Получить модуль для загрузки
     */
    protected static function getModuleId()
    {
        return 'iblock';
    }
    
    /**
     * Создать новый инфоблок
     */
    public static function createIblock(array $data)
    {
        static::loadModule();
        
        $iblock = new IblockTable();
        
        // Установка обязательных полей
        if (!isset($data['IBLOCK_TYPE_ID'])) {
            $data['IBLOCK_TYPE_ID'] = 'content';
        }
        
        if (!isset($data['LID'])) {
            $data['LID'] = 's1';
        }
        
        if (!isset($data['CODE'])) {
            throw new \Exception('CODE обязателен для создания инфоблока');
        }
        
        if (!isset($data['NAME'])) {
            throw new \Exception('NAME обязателен для создания инфоблока');
        }
        
        foreach ($data as $field => $value) {
            $iblock->set($field, $value);
        }
        
        $result = $iblock->save();
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return $iblock;
    }
    
    /**
     * Создать элемент инфоблока
     */
    public static function createElement($iblockId, array $data)
    {
        static::loadModule();
        
        $element = new ElementTable();
        $element->setIblockId($iblockId);
        
        // Установка обязательных полей
        if (!isset($data['NAME'])) {
            throw new \Exception('NAME обязателен для создания элемента');
        }
        
        if (!isset($data['ACTIVE'])) {
            $data['ACTIVE'] = 'Y';
        }
        
        foreach ($data as $field => $value) {
            $element->set($field, $value);
        }
        
        $result = $element->save();
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return $element;
    }
    
    /**
     * Получить инфоблок по коду
     */
    public static function getByCode($code)
    {
        static::loadModule();
        
        return IblockTable::getList([
            'filter' => ['CODE' => $code],
            'limit' => 1
        ])->fetchObject();
    }
    
    /**
     * Получить элементы инфоблока
     */
    public static function getElements($iblockId, array $filter = [], array $select = [], array $order = ['SORT' => 'ASC'])
    {
        static::loadModule();
        
        $filter['IBLOCK_ID'] = $iblockId;
        
        return ElementTable::getList([
            'filter' => $filter,
            'select' => $select ?: ['*'],
            'order' => $order
        ])->fetchAll();
    }
}
