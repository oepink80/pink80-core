<?php

namespace Pink80\Core\Helpers\Iblock;

use Pink80\Core\Helpers\BaseHelper;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Loader;

/**
 * Хелпер для работы с инфоблоками (D7 подход)
 */
class IblockHelper extends BaseHelper
{
    /**
     * Проверка загрузки модуля инфоблоков
     */
    private static function loadIblockModule()
    {
        if (!Loader::includeModule('iblock')) {
            throw new \Exception('Модуль инфоблоков не установлен');
        }
    }
    
    /**
     * Получить инфоблок по коду
     */
    public static function getByCode($code)
    {
        self::loadIblockModule();
        
        $iblock = IblockTable::getList([
            'filter' => ['CODE' => $code],
            'limit' => 1
        ])->fetch();
        
        return $iblock ?: null;
    }
    
    /**
     * Получить ID инфоблока по коду
     */
    public static function getIdByCode($code)
    {
        $iblock = self::getByCode($code);
        return $iblock ? $iblock['ID'] : null;
    }
    
    /**
     * Получить элементы инфоблока
     */
    public static function getElements($iblockId, $filter = [], $select = [], $order = ['SORT' => 'ASC'])
    {
        self::loadIblockModule();
        
        $filter['IBLOCK_ID'] = $iblockId;
        
        $query = ElementTable::getList([
            'filter' => $filter,
            'select' => $select ?: ['*'],
            'order' => $order
        ]);
        
        return $query->fetchAll();
    }
    
    /**
     * Получить элемент по ID
     */
    public static function getElementById($elementId)
    {
        self::loadIblockModule();
        
        $element = ElementTable::getByPrimary($elementId)->fetch();
        return $element ?: null;
    }
    
    /**
     * Получить свойство инфоблока по коду
     */
    public static function getPropertyByCode($iblockId, $code)
    {
        self::loadIblockModule();
        
        $property = PropertyTable::getList([
            'filter' => [
                'IBLOCK_ID' => $iblockId,
                'CODE' => $code
            ],
            'limit' => 1
        ])->fetch();
        
        return $property ?: null;
    }
    
    /**
     * Получить ID свойства по коду
     */
    public static function getPropertyId($iblockId, $code)
    {
        $property = self::getPropertyByCode($iblockId, $code);
        return $property ? $property['ID'] : null;
    }
    
    /**
     * Получить свойства инфоблока
     */
    public static function getProperties($iblockId)
    {
        self::loadIblockModule();
        
        return PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $iblockId],
            'order' => ['SORT' => 'ASC']
        ])->fetchAll();
    }
    
    /**
     * Получить значения свойства типа список в формате id => name
     * 
     * @param int $iblockId ID инфоблока
     * @param string $propertyCode Код свойства
     * @return array Массив значений в формате [id => name]
     */
    public static function getPropertyEnumValues($iblockId, $propertyCode)
    {
        self::loadIblockModule();
        
        $property = \CIBlockProperty::GetList([], [
            'IBLOCK_ID' => $iblockId,
            'CODE' => $propertyCode
        ])->Fetch();
        
        if (!$property) {
            return [];
        }
        
        if ($property['PROPERTY_TYPE'] !== 'L') {
            return [];
        }
        
        $enumValues = [];
        $enumList = \CIBlockPropertyEnum::GetList([], [
            'PROPERTY_ID' => $property['ID']
        ]);
        
        while ($enum = $enumList->Fetch()) {
            $enumValues[$enum['ID']] = $enum['VALUE'];
        }
        
        return $enumValues;
    }
}
