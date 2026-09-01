<?php

namespace Pink80\Core\Factories\Main;

use Pink80\Core\Factories\BaseFactory;

/**
 * Базовая фабрика для общих операций
 */
class MainFactory extends BaseFactory
{
    /**
     * Получить имя сущности ORM
     */
    protected static function getEntityClass()
    {
        // Базовая фабрика не имеет конкретной сущности
        return null;
    }
    
    /**
     * Получить модуль для загрузки
     */
    protected static function getModuleId()
    {
        return 'main';
    }
    
    /**
     * Создать сущность (переопределено для базовой фабрики)
     */
    public static function create(array $data = [])
    {
        throw new \Exception('Базовая фабрика не поддерживает создание сущностей');
    }
    
    /**
     * Обновить сущность (переопределено для базовой фабрики)
     */
    public static function update($id, array $data)
    {
        throw new \Exception('Базовая фабрика не поддерживает обновление сущностей');
    }
    
    /**
     * Удалить сущность (переопределено для базовой фабрики)
     */
    public static function delete($id)
    {
        throw new \Exception('Базовая фабрика не поддерживает удаление сущностей');
    }
    
    /**
     * Получить сущность по ID (переопределено для базовой фабрики)
     */
    public static function getById($id)
    {
        throw new \Exception('Базовая фабрика не поддерживает получение сущностей');
    }
    
    /**
     * Получить список сущностей (переопределено для базовой фабрики)
     */
    public static function getList(array $parameters = [])
    {
        throw new \Exception('Базовая фабрика не поддерживает получение списков сущностей');
    }
}
