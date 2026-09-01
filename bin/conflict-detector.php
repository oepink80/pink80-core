<?php

/**
 * Детектор конфликтов между project.core и pink80.core
 * 
 * Использование: php bin/conflict-detector.php
 */

$documentRoot = dirname(dirname(dirname(dirname(__DIR__))));
$projectCorePath = $documentRoot . '/local/modules/project.core';
$pink80CorePath = $documentRoot . '/local/modules/pink80.core';

if (!is_dir($projectCorePath)) {
    echo "project.core не найден. Конфликты невозможны.\n";
    exit(0);
}

if (!is_dir($pink80CorePath)) {
    echo "pink80.core не найден. Конфликты невозможны.\n";
    exit(0);
}

$conflicts = [];

// Проверка дубликатов классов
$projectClasses = getClassesFromDirectory($projectCorePath);
$pink80Classes = getClassesFromDirectory($pink80CorePath);

foreach ($projectClasses as $class) {
    if (in_array($class, $pink80Classes)) {
        $conflicts[] = [
            'type' => 'duplicate_class',
            'class' => $class,
            'file' => getClassFile($class, $projectCorePath)
        ];
    }
}

if (!empty($conflicts)) {
    echo "⚠️  Обнаружены конфликты между project.core и pink80.core:\n\n";
    foreach ($conflicts as $conflict) {
        echo "- Дубликат класса: {$conflict['class']}\n";
        echo "  Файл: {$conflict['file']}\n";
    }
    echo "\n💡 Рекомендуется удалить дубликаты из project.core или переименовать классы.\n";
    echo "   Используйте другое пространство имен для временных решений.\n";
    exit(1);
}

echo "✅ Конфликты не обнаружены. Обновление прошло успешно.\n";
exit(0);

function getClassesFromDirectory($dir) {
    $classes = [];
    
    if (!is_dir($dir . '/lib')) {
        return $classes;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir . '/lib')
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            
            // Проверяем на namespace и class
            if (preg_match('/namespace\s+([\w\\\\]+);/', $content, $namespaceMatch) &&
                preg_match('/class\s+(\w+)/', $content, $classMatch)) {
                $classes[] = $namespaceMatch[1] . '\\' . $classMatch[1];
            }
        }
    }
    
    return $classes;
}

function getClassFile($class, $dir) {
    $classPath = str_replace(['Project\\Core\\', 'Pink80\\Core\\'], '', $class);
    $classPath = str_replace('\\', '/', $classPath) . '.php';
    return $dir . '/lib/' . $classPath;
}
