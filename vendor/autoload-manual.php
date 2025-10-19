<?php
/**
 * Manuel Autoload - ACİL DURUM
 * vendor/autoload.php yerine geçici kullanılacak
 */

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    // Namespace prefix
    $prefixes = [
        'App\\' => __DIR__ . '/../app/',
        'Core\\' => __DIR__ . '/../core/',
    ];
    
    foreach ($prefixes as $prefix => $baseDir) {
        // Class bu prefix ile başlıyor mu?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        
        // Relative class name
        $relativeClass = substr($class, $len);
        
        // Namespace'i dosya yoluna çevir
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        
        // Dosya varsa require et
        if (file_exists($file)) {
            require $file;
            return true;
        }
    }
    
    return false;
});

// Composer packages için minimal autoload
$composerAutoload = __DIR__ . '/autoload.php';
if (file_exists($composerAutoload)) {
    require $composerAutoload;
}
