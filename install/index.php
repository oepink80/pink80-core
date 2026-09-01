<?php

global $MESS;
IncludeModuleLangFile(str_replace("\\", "/", __FILE__));

if (class_exists('pink80_core'))
    return;

class pink80_core extends CModule
{
    var $MODULE_ID = 'pink80.core';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = 'Y';
    var $PARTNER_NAME;
    var $PARTNER_URI;
    
    function __construct()
    {
        $arModuleVersion = array();
        
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/install/index.php'));
        include($path.'/install/version.php');
        
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        
        $this->MODULE_NAME = GetMessage('PINK80_CORE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('PINK80_CORE_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = GetMessage('PINK80_CORE_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('PINK80_CORE_PARTNER_URI');
    }
    
    function DoInstall()
    {
        global $APPLICATION;
        
        // Проверка прав
        if (!$APPLICATION->GetGroupRight($this->MODULE_ID) == 'W') {
            $APPLICATION->ThrowException(GetMessage('PINK80_CORE_INSTALL_RIGHTS_ERROR'));
            return false;
        }
        
        // Выполнение установки
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        
        // Регистрация модуля
        RegisterModule($this->MODULE_ID);
        
        return true;
    }
    
    function DoUninstall()
    {
        global $APPLICATION;
        
        // Проверка прав
        if (!$APPLICATION->GetGroupRight($this->MODULE_ID) == 'W') {
            $APPLICATION->ThrowException(GetMessage('PINK80_CORE_UNINSTALL_RIGHTS_ERROR'));
            return false;
        }
        
        // Выполнение деинсталляции
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        
        // Удаление регистрации модуля
        UnRegisterModule($this->MODULE_ID);
        
        return true;
    }
    
    function InstallDB()
    {
        // Модуль не требует создания таблиц в БД
        return true;
    }
    
    function UnInstallDB()
    {
        // Модуль не требует удаления таблиц из БД
        return true;
    }
    
    function InstallEvents()
    {
        // Регистрация обработчиков событий
        // Обработчики уже регистрируются автоматически через init()
        return true;
    }
    
    function UnInstallEvents()
    {
        // Удаление регистрации обработчиков событий
        // Битрикс автоматически удаляет обработчики при удалении модуля
        return true;
    }
    
    function InstallFiles()
    {
        // Копирование файлов если необходимо
        // Модуль уже находится в local/modules/
        return true;
    }
    
    function UnInstallFiles()
    {
        // Удаление файлов если необходимо
        // Папка local/modules/pink80.core/ остается для сохранения настроек
        return true;
    }
}
