<?php
/**
 * Request SÄ±nÄ±fÄ± - HTTP Request YÃ¶netimi
 * Vildan Portal - Okul YÃ¶netim Sistemi
 */

namespace Core;

class Request
{
    private $get;
    private $post;
    private $files;
    private $server;
    private $cookies;
    
    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        
        // JSON request body'yi parse et
        if ($this->isJson()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            if ($data) {
                $this->post = array_merge($this->post, $data);
            }
        }
    }
    
    /**
     * GET parametresi al
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return $this->get[$key] ?? $default;
    }
    
    /**
     * POST parametresi al
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = null) {
        return $this->post[$key] ?? $default;
    }
    
    /**
     * GET veya POST'tan al (Ã¶nce POST kontrol et)
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input($key, $default = null) {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }
    
    /**
     * TÃ¼m input'larÄ± al
     * 
     * @return array
     */
    public function all() {
        return array_merge($this->get, $this->post);
    }
    
    /**
     * Sadece belirtilen anahtarlarÄ± al
     * 
     * @param array $keys
     * @return array
     */
    public function only($keys) {
        $result = [];
        $all = $this->all();
        
        foreach ($keys as $key) {
            if (isset($all[$key])) {
                $result[$key] = $all[$key];
            }
        }
        
        return $result;
    }
    
    /**
     * Belirtilen anahtarlar hariÃ§ tÃ¼mÃ¼nÃ¼ al
     * 
     * @param array $keys
     * @return array
     */
    public function except($keys) {
        $result = $this->all();
        
        foreach ($keys as $key) {
            unset($result[$key]);
        }
        
        return $result;
    }
    
    /**
     * YÃ¼klenen dosyayÄ± al
     * 
     * @param string $key
     * @return array|null
     */
    public function file($key) {
        return $this->files[$key] ?? null;
    }
    
    /**
     * Cookie al
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function cookie($key, $default = null) {
        return $this->cookies[$key] ?? $default;
    }
    
    /**
     * Request method'unu al
     * 
     * @return string
     */
    public function method() {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }
    
    /**
     * Request URI'Ä± al
     * 
     * @return string
     */
    public function uri() {
        return $this->server['REQUEST_URI'] ?? '/';
    }
    
    /**
     * Request path'i al (query string olmadan)
     * 
     * @return string
     */
    public function path() {
        $uri = $this->uri();
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }
    
    /**
     * IP adresini al
     * 
     * @return string
     */
    public function ip() {
        if (!empty($this->server['HTTP_CLIENT_IP'])) {
            return $this->server['HTTP_CLIENT_IP'];
        } elseif (!empty($this->server['HTTP_X_FORWARDED_FOR'])) {
            return $this->server['HTTP_X_FORWARDED_FOR'];
        }
        return $this->server['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * User agent al
     * 
     * @return string
     */
    public function userAgent() {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }
    
    /**
     * AJAX request mi?
     * 
     * @return bool
     */
    public function isAjax() {
        return !empty($this->server['HTTP_X_REQUESTED_WITH']) && 
               strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * JSON request mi?
     * 
     * @return bool
     */
    public function isJson() {
        return isset($this->server['CONTENT_TYPE']) && 
               strpos($this->server['CONTENT_TYPE'], 'application/json') !== false;
    }
    
    /**
     * POST request mi?
     * 
     * @return bool
     */
    public function isPost() {
        return $this->method() === 'POST';
    }
    
    /**
     * GET request mi?
     * 
     * @return bool
     */
    public function isGet() {
        return $this->method() === 'GET';
    }
    
    /**
     * Secure connection mi (HTTPS)?
     * 
     * @return bool
     */
    public function isSecure() {
        return (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ||
               (!empty($this->server['HTTP_X_FORWARDED_PROTO']) && $this->server['HTTP_X_FORWARDED_PROTO'] === 'https') ||
               (!empty($this->server['HTTP_X_FORWARDED_SSL']) && $this->server['HTTP_X_FORWARDED_SSL'] === 'on');
    }
    
    /**
     * Has - Parametre var mÄ±?
     * 
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return isset($this->all()[$key]);
    }
    
    /**
     * Filled - Parametre dolu mu?
     * 
     * @param string $key
     * @return bool
     */
    public function filled($key) {
        $value = $this->input($key);
        return !empty($value);
    }
}