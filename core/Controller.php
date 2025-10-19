<?php
/**
 * Base Controller Sınıfı
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace Core;

class Controller
{
    protected $request;
    protected $response;
    protected $session;
    
    public function __construct() {
        $this->request = new Request();
        $this->response = new Response();
        $this->session = Session::getInstance();
    }
    
    /**
     * View render et
     * 
     * @param string $view View dosya yolu (örn: students/index)
     * @param array $data View'e aktarılacak data
     * @param string $layout Layout dosyası (default: main)
     */
    protected function view($view, $data = [], $layout = 'main') {
        // Data'yı değişkenlere çevir
        extract($data);
        
        // View dosyasını buffer'a al
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View bulunamadı: {$view}");
        }
        
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        
        // Layout varsa içine yerleştir
        if ($layout) {
            $layoutPath = APP_PATH . '/views/layouts/' . $layout . '.php';
            
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }
    
    /**
     * JSON response döndür
     * 
     * @param mixed $data
     * @param int $statusCode
     */
    protected function json($data, $statusCode = 200) {
        $this->response->json($data, $statusCode)->send();
        exit;
    }
    
    /**
     * Success JSON response
     * 
     * @param mixed $data
     * @param string $message
     */
    protected function success($data = null, $message = 'İşlem başarılı') {
        $this->response->success($data, $message)->send();
        exit;
    }
    
    /**
     * Error JSON response
     * 
     * @param string $message
     * @param mixed $errors
     * @param int $statusCode
     */
    protected function error($message = 'İşlem başarısız', $errors = null, $statusCode = 400) {
        $this->response->error($message, $errors, $statusCode)->send();
        exit;
    }
    
    /**
     * Redirect
     * 
     * @param string $url
     */
    protected function redirect($url) {
        $this->response->redirect($url);
    }
    
    /**
     * Back - Önceki sayfaya dön
     */
    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    /**
     * CSRF token doğrula - EKLENDİ
     * 
     * @return bool
     */
    protected function validateCSRF() {
        // POST'tan token al
        $token = $_POST['csrf_token'] ?? '';
        
        // Token boş mu?
        if (empty($token)) {
            return false;
        }
        
        // Session'da token var mı?
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Token süresi kontrolü
        if (isset($_SESSION['csrf_token_time'])) {
            $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
            $elapsed = time() - $_SESSION['csrf_token_time'];
            
            // Süre dolmuş mu?
            if ($elapsed > $lifetime) {
                unset($_SESSION['csrf_token']);
                unset($_SESSION['csrf_token_time']);
                return false;
            }
        }
        
        // Token'ları karşılaştır (timing attack'e karşı güvenli)
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Middleware çalıştır
     * 
     * @param string|array $middleware
     */
    protected function middleware($middleware) {
        $middlewares = is_array($middleware) ? $middleware : [$middleware];
        
        foreach ($middlewares as $m) {
            $middlewareClass = "App\\Middleware\\" . $m;
            
            if (class_exists($middlewareClass)) {
                $middlewareInstance = new $middlewareClass();
                $middlewareInstance->handle($this->request, $this->response);
            }
        }
    }
    
    /**
     * Validate request
     * 
     * @param array $rules
     * @param array $messages
     * @return array|bool Hata varsa array, yoksa true
     */
    protected function validate($rules, $messages = []) {
        $errors = [];
        $data = $this->request->all();
        
        foreach ($rules as $field => $rule) {
            $ruleList = is_array($rule) ? $rule : explode('|', $rule);
            
            foreach ($ruleList as $r) {
                $r = trim($r);
                
                // Required kontrolü
                if ($r === 'required') {
                    if (!isset($data[$field]) || trim($data[$field]) === '') {
                        $errors[$field][] = $messages[$field . '.required'] ?? ucfirst($field) . ' alanı zorunludur.';
                    }
                }
                
                // Email kontrolü
                if ($r === 'email' && isset($data[$field])) {
                    if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                        $errors[$field][] = $messages[$field . '.email'] ?? 'Geçerli bir e-posta adresi giriniz.';
                    }
                }
                
                // Min length
                if (strpos($r, 'min:') === 0 && isset($data[$field])) {
                    $min = (int)substr($r, 4);
                    if (strlen($data[$field]) < $min) {
                        $errors[$field][] = $messages[$field . '.min'] ?? ucfirst($field) . " en az {$min} karakter olmalıdır.";
                    }
                }
                
                // Max length
                if (strpos($r, 'max:') === 0 && isset($data[$field])) {
                    $max = (int)substr($r, 4);
                    if (strlen($data[$field]) > $max) {
                        $errors[$field][] = $messages[$field . '.max'] ?? ucfirst($field) . " en fazla {$max} karakter olmalıdır.";
                    }
                }
                
                // Numeric
                if ($r === 'numeric' && isset($data[$field])) {
                    if (!is_numeric($data[$field])) {
                        $errors[$field][] = $messages[$field . '.numeric'] ?? ucfirst($field) . ' sayısal bir değer olmalıdır.';
                    }
                }
            }
        }
        
        return empty($errors) ? true : $errors;
    }
}