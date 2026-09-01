<?php

namespace Pink80\Core\Factories\Iblock;

/**
 * Фабрика для работы с инфоблоками (использует старый API)
 */
class IblockFactory
{
    /**
     * Загрузка модуля
     */
    protected static function loadModule()
    {
        if (!\CModule::IncludeModule('iblock')) {
            throw new \Exception('Модуль инфоблоков не установлен');
        }
    }
    
    /**
     * Создать тип инфоблока
     */
    public static function createIblockType(array $data)
    {
        static::loadModule();
        
        $iblockType = new \CIBlockType();
        
        // Установка обязательных полей
        if (!isset($data['ID'])) {
            throw new \Exception('ID обязателен для создания типа инфоблока');
        }
        
        if (!isset($data['SECTIONS']) || !isset($data['IN_RSS']) || !isset($data['SORT'])) {
            $data['SECTIONS'] = 'Y';
            $data['IN_RSS'] = 'N';
            $data['SORT'] = 100;
        }
        
        $result = $iblockType->Add($data);
        
        if (!$result) {
            throw new \Exception($iblockType->LAST_ERROR);
        }
        
        return $result;
    }
    
    /**
     * Создать новый инфоблок
     */
    public static function createIblock(array $data)
    {
        static::loadModule();
        
        $iblock = new \CIBlock();
        
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
        
        $iblockId = $iblock->Add($data);
        
        if (!$iblockId) {
            throw new \Exception($iblock->LAST_ERROR);
        }
        
        return self::getById($iblockId);
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
        
        $iblock = \CIBlock::GetList([], ['CODE' => $code])->Fetch();
        return $iblock ?: null;
    }
    
    /**
     * Получить инфоблок по ID
     */
    public static function getById($id)
    {
        static::loadModule();
        
        $iblock = \CIBlock::GetByID($id)->Fetch();
        return $iblock ?: null;
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
