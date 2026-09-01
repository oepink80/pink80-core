<?php

namespace Pink80\Core\Helpers\Date;

use Pink80\Core\Helpers\BaseHelper;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Type\Date;

/**
 * Хелпер для работы с датами (D7 подход)
 */
class DateHelper extends BaseHelper
{
    /**
     * Создать объект DateTime из timestamp
     */
    public static function fromTimestamp($timestamp)
    {
        return DateTime::createFromTimestamp($timestamp);
    }
    
    /**
     * Создать объект DateTime из строки
     */
    public static function fromString($dateString)
    {
        return new DateTime($dateString);
    }
    
    /**
     * Создать объект DateTime из формата Битрикс
     */
    public static function fromBitrixFormat($dateString)
    {
        return new DateTime($dateString);
    }
    
    /**
     * Форматирование даты в формат Битрикс
     */
    public static function toBitrixFormat($dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        } elseif (!($dateTime instanceof DateTime)) {
            $dateTime = self::fromTimestamp($dateTime);
        }
        
        return $dateTime->format('d.m.Y H:i:s');
    }
    
    /**
     * Получить текущую дату и время как объект DateTime
     */
    public static function now()
    {
        return new DateTime();
    }
    
    /**
     * Получить начало дня
     */
    public static function startOfDay($dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        } elseif (!($dateTime instanceof DateTime)) {
            $dateTime = self::fromTimestamp($dateTime);
        }
        
        $clone = clone $dateTime;
        $clone->setTime(0, 0, 0);
        
        return $clone;
    }
    
    /**
     * Получить конец дня
     */
    public static function endOfDay($dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        } elseif (!($dateTime instanceof DateTime)) {
            $dateTime = self::fromTimestamp($dateTime);
        }
        
        $clone = clone $dateTime;
        $clone->setTime(23, 59, 59);
        
        return $clone;
    }
    
    /**
     * Получить начало месяца
     */
    public static function startOfMonth($dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        } elseif (!($dateTime instanceof DateTime)) {
            $dateTime = self::fromTimestamp($dateTime);
        }
        
        $clone = clone $dateTime;
        $clone->setDate((int)$clone->format('Y'), (int)$clone->format('m'), 1);
        $clone->setTime(0, 0, 0);
        
        return $clone;
    }
    
    /**
     * Получить конец месяца
     */
    public static function endOfMonth($dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        } elseif (!($dateTime instanceof DateTime)) {
            $dateTime = self::fromTimestamp($dateTime);
        }
        
        $clone = clone $dateTime;
        $clone->setDate((int)$clone->format('Y'), (int)$clone->format('m') + 1, 0);
        $clone->setTime(23, 59, 59);
        
        return $clone;
    }
    
    /**
     * Получить разницу между датами в человекочитаемом формате
     */
    public static function humanDiff($from, $to = null)
    {
        if ($to === null) {
            $to = new DateTime();
        }
        
        if (!($from instanceof DateTime)) {
            $from = self::fromTimestamp($from);
        }
        
        if (!($to instanceof DateTime)) {
            $to = self::fromTimestamp($to);
        }
        
        $diff = $to->getTimestamp() - $from->getTimestamp();
        $diff = abs($diff);
        
        $intervals = [
            31536000 => 'год',
            2592000 => 'месяц',
            604800 => 'неделю',
            86400 => 'день',
            3600 => 'час',
            60 => 'минуту',
            1 => 'секунду'
        ];
        
        foreach ($intervals as $seconds => $label) {
            if ($diff >= $seconds) {
                $count = floor($diff / $seconds);
                return $count . ' ' . $label . ($count > 1 ? (in_array($label, ['год', 'месяц']) ? 'а' : (in_array($label, ['неделю', 'день']) ? 'и' : 'ы')) : '') . ' назад';
            }
        }
        
        return 'только что';
    }
    
    /**
     * Проверка на валидность даты в формате Битрикс
     */
    public static function isValidBitrixDate($dateString)
    {
        try {
            new DateTime($dateString);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Добавить интервал к дате
     */
    public static function add($dateTime, $interval)
    {
        if (!($dateTime instanceof DateTime)) {
            $dateTime = self::fromTimestamp($dateTime);
        }
        
        $clone = clone $dateTime;
        $clone->add($interval);
        
        return $clone;
    }
    
    /**
     * Вычесть интервал из даты
     */
    public static function sub($dateTime, $interval)
    {
        if (!($dateTime instanceof DateTime)) {
            $dateTime = self::fromTimestamp($dateTime);
        }
        
        $clone = clone $dateTime;
        $clone->add($interval * -1);
        
        return $clone;
    }
}
