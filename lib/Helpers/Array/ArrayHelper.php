<?php

namespace Pink80\Core\Helpers\Array;

use Pink80\Core\Helpers\BaseHelper;

/**
 * Хелпер для работы с массивами
 */
class ArrayHelper extends BaseHelper
{
    /**
     * Получить значение из массива по ключу с дефолтным значением
     */
    public static function get(array $array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
    
    /**
     * Получить значение из вложенного массива по пути
     */
    public static function getNested(array $array, $path, $default = null)
    {
        $keys = explode('.', $path);
        $value = $array;
        
        foreach ($keys as $key) {
            if (!is_array($value) || !isset($value[$key])) {
                return $default;
            }
            $value = $value[$key];
        }
        
        return $value;
    }
    
    /**
     * Проверить наличие ключа в массиве
     */
    public static function has(array $array, $key)
    {
        return isset($array[$key]);
    }
    
    /**
     * Рекурсивное слияние массивов
     */
    public static function merge(array $array1, array $array2)
    {
        $merged = $array1;
        
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::merge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        
        return $merged;
    }
    
    /**
     * Получить первый элемент массива
     */
    public static function first(array $array)
    {
        return reset($array);
    }
    
    /**
     * Получить последний элемент массива
     */
    public static function last(array $array)
    {
        return end($array);
    }
    
    /**
     * Проверить является ли массив ассоциативным
     */
    public static function isAssociative(array $array)
    {
        if (empty($array)) {
            return false;
        }
        
        return array_keys($array) !== range(0, count($array) - 1);
    }
    
    /**
     * Переиндексация массива по указанному полю
     */
    public static function indexBy(array $array, $field)
    {
        $result = [];
        
        foreach ($array as $item) {
            if (isset($item[$field])) {
                $result[$item[$field]] = $item;
            }
        }
        
        return $result;
    }
    
    /**
     * Получить значения массива по указанным ключам
     */
    public static function only(array $array, array $keys)
    {
        return array_intersect_key($array, array_flip($keys));
    }
    
    /**
     * Исключить указанные ключи из массива
     */
    public static function except(array $array, array $keys)
    {
        return array_diff_key($array, array_flip($keys));
    }
}
