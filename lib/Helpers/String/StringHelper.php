<?php

namespace Pink80\Core\Helpers\String;

use Pink80\Core\Helpers\BaseHelper;

/**
 * Хелпер для работы со строками
 */
class StringHelper extends BaseHelper
{
    /**
     * Генерация случайной строки
     */
    public static function random($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }
    
    /**
     * Обрезка строки с добавлением суффикса
     */
    public static function truncate($string, $length, $suffix = '...')
    {
        if (mb_strlen($string) <= $length) {
            return $string;
        }
        
        return mb_substr($string, 0, $length) . $suffix;
    }
    
    /**
     * Конвертация в camelCase
     */
    public static function toCamelCase($string)
    {
        $string = str_replace(['-', '_'], ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        
        return lcfirst($string);
    }
    
    /**
     * Конвертация в snake_case
     */
    public static function toSnakeCase($string)
    {
        $string = preg_replace('/([A-Z])/', '_$1', $string);
        $string = strtolower($string);
        $string = ltrim($string, '_');
        
        return $string;
    }
    
    /**
     * Конвертация в kebab-case
     */
    public static function toKebabCase($string)
    {
        $string = preg_replace('/([A-Z])/', '-$1', $string);
        $string = strtolower($string);
        $string = ltrim($string, '-');
        
        return $string;
    }
    
    /**
     * Очистка строки от спецсимволов
     */
    public static function sanitize($string)
    {
        return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Генерация slug
     */
    public static function slug($string)
    {
        $string = mb_strtolower($string);
        $string = preg_replace('/[^a-z0-9а-яё]+/u', '-', $string);
        $string = trim($string, '-');
        
        return $string;
    }
}
