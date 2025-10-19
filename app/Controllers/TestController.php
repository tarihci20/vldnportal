<?php
/**
 * Test Controller - Kurulum test için
 * Path: /home/vildacgg/vldn.in/portalv2/app/controllers/TestController.php
 * 
 * NOT: Kurulum tamamlandıktan sonra bu dosyayı SİLEBİLİRSİNİZ
 */

class TestController {
    
    public function index() {
        echo "<!DOCTYPE html>";
        echo "<html lang='tr'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Test - Vildan Portal</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }";
        echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }";
        echo "h1 { color: #667eea; margin-bottom: 30px; }";
        echo ".success { background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 5px; }";
        echo ".info { background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 5px; }";
        echo "table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
        echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }";
        echo "th { background: #f9fafb; font-weight: 600; }";
        echo ".btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; margin: 5px; }";
        echo ".btn:hover { background: #5568d3; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        echo "<div class='container'>";
        
        echo "<h1>🎉 Vildan Portal Test Sayfası</h1>";
        
        echo "<div class='success'>";
        echo "<strong>✅ Router Çalışıyor!</strong><br>";
        echo "Bu sayfayı görüyorsanız, routing sistemi başarıyla çalışıyor demektir.";
        echo "</div>";
        
        echo "<div class='info'>";
        echo "<strong>ℹ️ Sistem Bilgileri</strong>";
        echo "</div>";
        
        echo "<table>";
        echo "<tr><th>Özellik</th><th>Değer</th></tr>";
        echo "<tr><td>PHP Versiyonu</td><td>" . phpversion() . "</td></tr>";
        echo "<tr><td>ROOT_PATH</td><td>" . ROOT_PATH . "</td></tr>";
        echo "<tr><td>BASE_URL</td><td>" . BASE_URL . "</td></tr>";
        echo "<tr><td>BASE_PATH</td><td>" . BASE_PATH . "</td></tr>";
        echo "<tr><td>APP_DEBUG</td><td>" . (APP_DEBUG ? 'true' : 'false') . "</td></tr>";
        echo "<tr><td>Database Bağlantı</td><td>";
        
        try {
            $db = Database::getInstance();
            $result = $db->query("SELECT 1 as test")->fetch();
            echo "✅ Başarılı";
        } catch (Exception $e) {
            echo "❌ Hata: " . $e->getMessage();
        }
        
        echo "</td></tr>";
        echo "</table>";
        
        echo "<h2>🔗 Test Linkleri</h2>";
        echo "<p>";
        echo "<a href='" . url('test/info') . "' class='btn'>PHP Info</a>";
        echo "<a href='" . url('test/db') . "' class='btn'>Database Test</a>";
        echo "<a href='" . url('test/helpers') . "' class='btn'>Helper Test</a>";
        echo "</p>";
        
        echo "<hr style='margin: 30px 0;'>";
        echo "<p style='color: #6b7280; font-size: 14px;'>";
        echo "Bu test sayfasını gördüğünüze göre sistem temel olarak çalışıyor. ";
        echo "Artık controller ve view dosyalarınızı ekleyebilirsiniz.";
        echo "</p>";
        
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }
    
    public function info() {
        phpinfo();
    }
    
    public function db() {
        echo "<!DOCTYPE html>";
        echo "<html><head><meta charset='UTF-8'><title>Database Test</title></head><body>";
        echo "<h1>Database Test</h1>";
        
        try {
            $db = Database::getInstance();
            
            // Test query
            $result = $db->query("SELECT DATABASE() as db_name, NOW() as current_time")->fetch();
            echo "<h2>✅ Bağlantı Başarılı</h2>";
            echo "<p>Database: " . $result['db_name'] . "</p>";
            echo "<p>Server Time: " . $result['current_time'] . "</p>";
            
            // Tablo listesi
            $tables = $db->query("SHOW TABLES")->fetchAll();
            echo "<h2>Tablolar (" . count($tables) . " adet)</h2>";
            
            if (count($tables) > 0) {
                echo "<ul>";
                foreach ($tables as $table) {
                    echo "<li>" . array_values($table)[0] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color: orange;'>⚠️ Henüz tablo yok. schema.sql dosyasını import edin.</p>";
            }
            
        } catch (Exception $e) {
            echo "<h2 style='color: red;'>❌ Bağlantı Hatası</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
        
        echo "<p><a href='" . url('test') . "'>← Geri Dön</a></p>";
        echo "</body></html>";
    }
    
    public function helpers() {
        echo "<!DOCTYPE html>";
        echo "<html><head><meta charset='UTF-8'><title>Helper Functions Test</title></head><body>";
        echo "<h1>Helper Functions Test</h1>";
        
        echo "<h2>URL Functions</h2>";
        echo "<pre>";
        echo "url('test'): " . url('test') . "\n";
        echo "asset('css/main.css'): " . asset('css/main.css') . "\n";
        echo "upload('profiles/test.jpg'): " . upload('profiles/test.jpg') . "\n";
        echo "</pre>";
        
        echo "<h2>Date Functions</h2>";
        echo "<pre>";
        echo "formatDate('2024-01-15'): " . formatDate('2024-01-15') . "\n";
        echo "formatDateTurkish('2024-01-15'): " . formatDateTurkish('2024-01-15') . "\n";
        echo "</pre>";
        
        echo "<h2>String Functions</h2>";
        echo "<pre>";
        echo "truncate('Bu çok uzun bir metin', 10): " . truncate('Bu çok uzun bir metin', 10) . "\n";
        echo "slugify('Türkçe Başlık Örneği'): " . slugify('Türkçe Başlık Örneği') . "\n";
        echo "</pre>";
        
        echo "<h2>Validation Functions</h2>";
        echo "<pre>";
        echo "isEmail('test@example.com'): " . (isEmail('test@example.com') ? 'true' : 'false') . "\n";
        echo "isEmail('invalid-email'): " . (isEmail('invalid-email') ? 'true' : 'false') . "\n";
        echo "isPhoneNumber('05551234567'): " . (isPhoneNumber('05551234567') ? 'true' : 'false') . "\n";
        echo "</pre>";
        
        echo "<p><a href='" . url('test') . "'>← Geri Dön</a></p>";
        echo "</body></html>";
    }
}