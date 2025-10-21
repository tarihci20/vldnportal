<?php
/**
 * Router Sınıfı - URL Yönlendirme
 * Vildan Portal - Okul Yönetim Sistemi
 * DÜZELTME: getUri() metodu, LiteSpeed sunucularında sızabilen index.php segmentini ve kalan /public kalıntısını agresif olarak temizler.
 */

namespace Core;

class Router
{
    private $routes = [];
    private $namedRoutes = [];
    private $currentRoute = null;
    private $basePath = '';
    
    /**
     * Constructor
     * @param string $basePath Base path (örn: /portalv2)
     */
    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }
    
    /**
     * GET route ekle
     * @param string $path URL path
     * @param callable|string $handler Handler
     * @param string|null $name Route ismi
     */
    public function get($path, $handler, $name = null) {
        $this->addRoute('GET', $path, $handler, $name);
    }
    
    /**
     * POST route ekle
     */
    public function post($path, $handler, $name = null) {
        $this->addRoute('POST', $path, $handler, $name);
    }
    
    /**
     * PUT route ekle
     */
    public function put($path, $handler, $name = null) {
        $this->addRoute('PUT', $path, $handler, $name);
    }
    
    /**
     * DELETE route ekle
     */
    public function delete($path, $handler, $name = null) {
        $this->addRoute('DELETE', $path, $handler, $name);
    }
    
    /**
     * Route ekle
     * @param string $method HTTP method
     * @param string $path URL path
     * @param callable|string $handler Handler
     * @param string|null $name Route ismi
     */
    private function addRoute($method, $path, $handler, $name = null) {
        // Rotaya basePath eklenmeyecek. 
        $path = '/' . trim($path, '/');

        $route = [
            'method' => $method,
            // Route() helper'ı için tam yolu tut.
            'fullPath' => $this->basePath . $path, 
            'path' => $path,
            'handler' => $handler,
            'regex' => $this->pathToRegex($path),
            'params' => $this->extractParams($path)
        ];
        
        $this->routes[] = $route;
        
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
    }
    
    /**
     * Path'i regex'e çevir
     * @param string $path
     * @return string
     */
    private function pathToRegex($path) {
        // {id} gibi parametreleri regex'e çevir
        $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        // Sondaki /'yi isteğe bağlı yap (\/?$) ve başta ^ ile bitişi $ ile belirle
        return '#^' . rtrim($regex, '/') . '\/?$#';
    }
    
    /**
     * Path'den parametreleri çıkar
     * @param string $path
     * @return array
     */
    private function extractParams($path) {
        preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $path, $matches);
        return $matches[1];
    }
    
    /**
     * Request'i route'a eşleştir ve çalıştır
     * @return mixed
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri(); // Temizlenmiş URI (örn: /)
        
        // ALWAYS LOG - Dispatch başladı
        error_log("Router::dispatch() - Method: $method, URI: $uri");
        
        // Method override kontrolü (_method POST parametresi)
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
            error_log("Router::dispatch() - Method override: $method");
        }
        
        error_log("Router::dispatch() - Total routes to check: " . count($this->routes));
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            error_log("Router::dispatch() - Checking route: {$route['method']} {$route['path']} (regex: {$route['regex']})");
            
            if (preg_match($route['regex'], $uri, $matches)) { // Eşleşme burada yapılır
                error_log("Router::dispatch() - MATCHED! {$route['method']} {$route['path']} -> {$route['handler']}");
                $this->currentRoute = $route;
                
                // Parametreleri çıkar
                $params = [];
                foreach ($route['params'] as $param) {
                    if (isset($matches[$param])) {
                        $params[] = $matches[$param];
                    }
                }
                
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    error_log("Router: Params extracted - " . print_r($params, true));
                }
                
                // Middleware'i burada çalıştır... (şimdilik atlandı)
                
                try {
                    $result = $this->callHandler($route['handler'], $params);
                    
                    if (defined('APP_DEBUG') && APP_DEBUG) {
                        error_log("Router: Handler returned successfully, type: " . gettype($result));
                    }
                    
                    return $result;
                } catch (\Exception $e) {
                    // Hata durumunda debug bilgisi göster
                    if (defined('APP_DEBUG') && APP_DEBUG) {
                        error_log("Router: Exception in handler - " . $e->getMessage());
                        echo "<h1>Route Handler Hatası</h1>";
                        echo "<p><strong>Route:</strong> {$route['method']} {$route['path']}</p>";
                        echo "<p><strong>Handler:</strong> {$route['handler']}</p>";
                        echo "<p><strong>Params:</strong> " . implode(', ', $params) . "</p>";
                        echo "<p><strong>Hata:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                        exit;
                    }
                    throw $e;
                }
            }
        }
        
        // 404 Not Found
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: No route matched for {$method} {$uri}");
        }
        $this->handleNotFound();
    }
    
    /**
     * Handler'ı çağır - GELİŞTİRİLMİŞ VERSİYON
     * @param callable|string $handler
     * @param array $params
     * @return mixed
     */
    private function callHandler($handler, $params = []) {
        // Debug: Handler çağrısı logla
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router callHandler: Handler=" . print_r($handler, true) . ", Params=" . print_r($params, true));
        }
        
        // String handler (Controller@method formatı)
        if (is_string($handler)) {
            // @ işaretini kontrol et
            if (strpos($handler, '@') === false) {
                throw new \Exception("Geçersiz handler formatı: {$handler}. Controller@method formatında olmalı.");
            }
            
            list($controller, $method) = explode('@', $handler);
            
            // Controller namespace - tam yol oluştur
            $controllerClass = "\\App\\Controllers\\" . $controller;
            
            // Debug: Aranan controller sınıfını logla
            if (defined('APP_DEBUG') && APP_DEBUG) {
                error_log("Router: Aranan controller sınıfı: {$controllerClass}");
            }
            
            // Controller sınıfının var olup olmadığını kontrol et
            if (!class_exists($controllerClass)) {
                // Detaylı hata bilgisi topla
                $debugInfo = [];
                $debugInfo[] = "Controller sınıfı bulunamadı: {$controllerClass}";
                $debugInfo[] = "Aranan controller: {$controller}";
                $debugInfo[] = "Aranan method: {$method}";
                $debugInfo[] = "Handler string: {$handler}";
                
                // Klasör yapısını kontrol et
                $baseDir = dirname(__DIR__);
                $controllerDir = $baseDir . '/app/Controllers';
                
                $debugInfo[] = "Base dizin: {$baseDir}";
                $debugInfo[] = "Controllers dizini: {$controllerDir}";
                
                if (is_dir($controllerDir)) {
                    $debugInfo[] = "✓ Controllers klasörü mevcut";
                    
                    // Klasör içeriğini listele
                    $files = scandir($controllerDir);
                    $debugInfo[] = "Controllers klasörü içeriği:";
                    foreach ($files as $file) {
                        if ($file !== '.' && $file !== '..') {
                            $fullPath = $controllerDir . '/' . $file;
                            $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
                            $debugInfo[] = "  - {$file} (İzinler: {$perms})";
                        }
                    }
                    
                    // Özellikle aranan controller dosyasını kontrol et
                    $controllerFile = $controllerDir . '/' . $controller . '.php';
                    if (file_exists($controllerFile)) {
                        $debugInfo[] = "✓ Controller dosyası mevcut: {$controllerFile}";
                        
                        // Dosya okunabilir mi?
                        if (is_readable($controllerFile)) {
                            $debugInfo[] = "✓ Controller dosyası okunabilir";
                            
                            // Dosya içeriğini kontrol et
                            $content = file_get_contents($controllerFile);
                            
                            // Namespace kontrolü
                            if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
                                $namespace = $matches[1];
                                $debugInfo[] = "Dosyadaki namespace: {$namespace}";
                                
                                if ($namespace !== 'App\\Controllers') {
                                    $debugInfo[] = "⚠ UYARI: Namespace yanlış! 'App\\Controllers' olmalı";
                                }
                            } else {
                                $debugInfo[] = "⚠ UYARI: Dosyada namespace bulunamadı!";
                            }
                            
                            // Class adı kontrolü
                            if (preg_match('/class\s+(\w+)/', $content, $matches)) {
                                $className = $matches[1];
                                $debugInfo[] = "Dosyadaki class adı: {$className}";
                                
                                if ($className !== $controller) {
                                    $debugInfo[] = "⚠ UYARI: Class adı yanlış! '{$controller}' olmalı";
                                }
                            } else {
                                $debugInfo[] = "⚠ UYARI: Dosyada class tanımı bulunamadı!";
                            }
                        } else {
                            $debugInfo[] = "✗ Controller dosyası okunamıyor!";
                        }
                    } else {
                        $debugInfo[] = "✗ Controller dosyası bulunamadı: {$controllerFile}";
                    }
                } else {
                    $debugInfo[] = "✗ Controllers klasörü bulunamadı!";
                }
                
                // Autoload bilgisi
                $autoloadFunctions = spl_autoload_functions();
                $debugInfo[] = "Kayıtlı autoloader sayısı: " . count($autoloadFunctions);
                
                // Yüklenmiş dosyaları kontrol et
                $includedFiles = get_included_files();
                $debugInfo[] = "Yüklenmiş toplam dosya sayısı: " . count($includedFiles);
                
                // Composer autoload var mı?
                $composerAutoload = $baseDir . '/vendor/autoload.php';
                if (file_exists($composerAutoload)) {
                    $debugInfo[] = "✓ Composer autoload mevcut: {$composerAutoload}";
                } else {
                    $debugInfo[] = "✗ Composer autoload bulunamadı: {$composerAutoload}";
                    $debugInfo[] = "⚠ Composer install veya dump-autoload çalıştırılmalı!";
                }
                
                // Yüklenmiş App namespace sınıfları
                $loadedClasses = get_declared_classes();
                $appClasses = array_filter($loadedClasses, function($class) {
                    return strpos($class, 'App\\') === 0;
                });
                
                if (!empty($appClasses)) {
                    $debugInfo[] = "Yüklenmiş App\\ sınıfları:";
                    foreach ($appClasses as $class) {
                        $debugInfo[] = "  - {$class}";
                    }
                } else {
                    $debugInfo[] = "⚠ App\\ namespace'inden hiçbir sınıf yüklenmemiş!";
                }
                
                // Hata mesajını oluştur ve fırlat
                $errorMessage = implode("\n", $debugInfo);
                throw new \Exception($errorMessage);
            }
            
            // Controller örneğini oluştur
            try {
                $controllerInstance = new $controllerClass();
                
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    error_log("Router: Controller örneği oluşturuldu: " . get_class($controllerInstance));
                }
            } catch (\Exception $e) {
                throw new \Exception("Controller oluşturulamadı: {$controllerClass}\nHata: " . $e->getMessage());
            }
            
            // Method'un varlığını kontrol et
            if (!method_exists($controllerInstance, $method)) {
                // Mevcut methodları listele
                $availableMethods = get_class_methods($controllerInstance);
                $methodsList = implode(', ', $availableMethods);
                
                throw new \Exception(
                    "Method bulunamadı: {$controllerClass}@{$method}\n" .
                    "Mevcut methodlar: {$methodsList}"
                );
            }
            
            // Method'u çağır
            if (defined('APP_DEBUG') && APP_DEBUG) {
                error_log("Router: Method çağrılıyor: {$method}");
            }
            
            $result = call_user_func_array([$controllerInstance, $method], $params);
            
            // Debug: Return değerini logla
            if (defined('APP_DEBUG') && APP_DEBUG) {
                error_log("Router: callHandler başarılı, return type: " . gettype($result));
            }
            
            return $result;
        }
        
        // Callable handler
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
        
        throw new \Exception("Geçersiz handler tipi: " . gettype($handler));
    }
    
    /**
     * Mevcut URI'ı al
     * @return string
     */
    private function getUri() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // 1. Query string'i temizle
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // 2. Base path'i çıkar (örn: /portalv2)
        if ($this->basePath && strpos($uri, $this->basePath) === 0) {
            $uri = (string)substr($uri, strlen($this->basePath));
        }

        // 3. URI içinde /index.php segmentini kaldır (Sunucu yapılandırması sızıntısı)
        $uri = str_replace('/index.php', '', $uri);
        $uri = str_replace('index.php', '', $uri);

        // 4. Baştaki ve sondaki eğik çizgileri temizle
        $uri = trim($uri, '/');
        
        // 4.5 DÜZELTME: Eğer temizleme sonrası sadece "public" kaldıysa, bunu kök yol olarak kabul et.
        if (strtolower($uri) === 'public') {
            $uri = '';
        }

        // 5. Sonucu her zaman başında eğik çizgi olacak şekilde döndür (kök yol için "/")
        return '/' . $uri;
    }
    
    /**
     * 404 Not Found handler
     */
    private function handleNotFound() {
        http_response_code(404);
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<h1>404 - Sayfa Bulunamadı</h1>";
            // Hata ayıklama için Base Path'i göster.
            echo "<p>Base Path: " . htmlspecialchars($this->basePath) . "</p>";
            // getUri()'ı tekrar çağırmak yerine, sunucudan gelen orijinal REQUEST_URI'ı gösterelim
            $originalUri = $_SERVER['REQUEST_URI'] ?? 'Bilinmiyor';
            // Query string olmadan göster.
            $originalUriClean = strstr($originalUri, '?', true) ?: $originalUri;
            
            echo "<p>URI: " . htmlspecialchars($this->getUri()) . " (Temizlenmiş)</p>";
            echo "<p>Orijinal Request URI: " . htmlspecialchars($originalUriClean) . "</p>";
            echo "<p>Method: " . $_SERVER['REQUEST_METHOD'] . "</p>";
            
            // Tanımlı route'ları göster
            echo "<h3>Tanımlı Routes (ilk 20):</h3>";
            echo "<ul style='font-family: monospace; font-size: 12px;'>";
            $count = 0;
            foreach ($this->routes as $route) {
                if ($count++ >= 20) break;
                $match = preg_match($route['regex'], $this->getUri()) ? '✅ Eşleşti' : '❌ Eşleşmedi';
                $methodMatch = $route['method'] === $_SERVER['REQUEST_METHOD'] ? '✅' : '❌';
                echo "<li><strong>{$methodMatch} {$route['method']}</strong> {$route['path']} → {$route['handler']} | Regex: {$route['regex']} | {$match}</li>";
            }
            echo "</ul>";
        } else {
            // Production'da güzel 404 sayfası göster
            if (defined('APP_PATH') && file_exists(APP_PATH . '/views/errors/404.php')) {
                require APP_PATH . '/views/errors/404.php';
            } else {
                echo "<h1>404 - Sayfa Bulunamadı</h1>";
            }
        }
        exit;
    }
    
    /**
     * Named route için URL oluştur
     * @param string $name Route ismi
     * @param array $params Parametreler
     * @return string
     */
    public function route($name, $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            throw new \Exception("Route bulunamadı: {$name}");
        }
        
        $route = $this->namedRoutes[$name];
        // fullPath'i kullanarak URL oluştur
        $path = $route['fullPath'];
        
        // Parametreleri yerine koy
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }
        
        return $path;
    }
    
    /**
     * Mevcut route bilgisini al
     * @return array|null
     */
    public function getCurrentRoute() {
        return $this->currentRoute;
    }
}