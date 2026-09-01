<?php

namespace Pink80\Core\Factories;

use Pink80\Core\Interfaces\FactoryInterface;

/**
 * Базовый абстрактный класс для фабрик
 */
abstract class BaseFactory implements FactoryInterface
{
    /**
     * Получить имя сущности ORM
     */
    abstract protected static function getEntityClass();
    
    /**
     * Получить модуль для загрузки
     */
    abstract protected static function getModuleId();
    
    /**
     * Загрузка модуля
     */
    protected static function loadModule()
    {
        $moduleId = static::getModuleId();
        if (!\Bitrix\Main\Loader::includeModule($moduleId)) {
            throw new \Exception("Модуль {$moduleId} не установлен");
        }
    }
    
    /**
     * Создать новую сущность
     */
    public static function create(array $data = [])
    {
        static::loadModule();
        
        $entityClass = static::getEntityClass();
        $entity = new $entityClass();
        
        foreach ($data as $field => $value) {
            $entity->set($field, $value);
        }
        
        return $entity;
    }
    
    /**
     * Создать сущность и сохранить в БД
     */
    public static function createAndSave(array $data)
    {
        $entity = static::create($data);
        $result = $entity->save();
        
        if ($result->isSuccess()) {
            return $entity;
        }
        
        throw new \Exception(implode(', ', $result->getErrorMessages()));
    }
    
    /**
     * Обновить сущность
     */
    public static function update($id, array $data)
    {
        static::loadModule();
        
        $entityClass = static::getEntityClass();
        $entity = $entityClass::getByPrimary($id)->fetchObject();
        
        if (!$entity) {
            throw new \Exception("Сущность с ID {$id} не найдена");
        }
        
        foreach ($data as $field => $value) {
            $entity->set($field, $value);
        }
        
        $result = $entity->save();
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return $entity;
    }
    
    /**
     * Удалить сущность
     */
    public static function delete($id)
    {
        static::loadModule();
        
        $entityClass = static::getEntityClass();
        $result = $entityClass::delete($id);
        
        if (!$result->isSuccess()) {
            throw new \Exception(implode(', ', $result->getErrorMessages()));
        }
        
        return true;
    }
    
    /**
     * Получить сущность по ID
     */
    public static function getById($id)
    {
        static::loadModule();
        
        $entityClass = static::getEntityClass();
        return $entityClass::getByPrimary($id)->fetchObject();
    }
    
    /**
     * Получить список сущностей
     */
    public static function getList(array $parameters = [])
    {
        static::loadModule();
        
        $entityClass = static::getEntityClass();
        return $entityClass::getList($parameters);
    }
}
