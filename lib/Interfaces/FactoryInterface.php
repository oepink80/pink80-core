<?php

namespace Pink80\Core\Interfaces;

/**
 * Интерфейс для фабрик сущностей
 */
interface FactoryInterface
{
    /**
     * Создать новую сущность
     */
    public static function create(array $data = []);
    
    /**
     * Создать сущность и сохранить в БД
     */
    public static function createAndSave(array $data);
    
    /**
     * Обновить сущность
     */
    public static function update($id, array $data);
    
    /**
     * Удалить сущность
     */
    public static function delete($id);
}
