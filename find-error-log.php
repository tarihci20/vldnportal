<?php
/**
 * Production Error Log Finder
 * Bu script error log dosyalarını arıyor ve en son error'ları gösteriyor
 */

// Olası log dosyası yolları
$possibleLogPaths = [
    // Proje log'ları
    '/home/vildacgg/vldn.in/portalv2/storage/logs/error.log',
    '/home/vildacgg/public_html/portalv2/storage/logs/error.log',
    '/home/vildacgg/vldn.in/portalv2/storage/logs/php_error.log',
    '/home/vildacgg/public_html/portalv2/storage/logs/php_error.log',
    
    // cPanel/Server log'ları
    '/home/vildacgg/error_log',
    '/home/vildacgg/public_html/error_log',
    '/home/vildacgg/.log',
    
    // Sistem log'ları
    '/var/log/php_error.log',
    '/var/log/php-errors.log',
    '/tmp/php_error.log',
    
    // Diğer olasılıklar
    ini_get('error_log'),
];

echo "=== ERROR LOG DOSYALARI ARANIYOR ===\n\n";

$found = false;
foreach ($possibleLogPaths as $path) {
    // Boş path'i skip et
    if (empty($path) || $path === 'ini_get(\'error_log\')') continue;
    
    if (file_exists($path)) {
        $found = true;
        echo "✓ BULUNDU: $path\n";
        echo "  Dosya boyutu: " . filesize($path) . " bytes\n";
        echo "  Son düzenlenme: " . date('Y-m-d H:i:s', filemtime($path)) . "\n";
        
        // Son 10 satırı göster
        echo "  Son 10 satır:\n";
        $lines = array_slice(file($path), -10);
        foreach ($lines as $line) {
            echo "    " . trim($line) . "\n";
        }
        echo "\n";
    }
}

if (!$found) {
    echo "✗ Hiç error log dosyası bulunamadı!\n";
    echo "\nini_get('error_log') value: " . (ini_get('error_log') ?: 'SET EDILMEMIŞ') . "\n";
    echo "ini_get('log_errors'): " . (ini_get('log_errors') ?: '0') . "\n";
}

// Directory'leri kontrol et
echo "\n=== STORAGE/LOGS KLASÖRÜ KONTROL ===\n";
$storageDir = '/home/vildacgg/vldn.in/portalv2/storage/logs';
if (is_dir($storageDir)) {
    echo "✓ Klasör bulundu: $storageDir\n";
    echo "  İçeriği:\n";
    $files = scandir($storageDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $storageDir . '/' . $file;
            if (is_file($filePath)) {
                echo "    - $file (" . filesize($filePath) . " bytes)\n";
            } else if (is_dir($filePath)) {
                echo "    - [DIR] $file\n";
            }
        }
    }
} else {
    echo "✗ Klasör bulunamadı: $storageDir\n";
}
?>
