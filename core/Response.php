<?php
/**
 * Response SÄ±nÄ±fÄ± - HTTP Response YÃ¶netimi
 * Vildan Portal - Okul YÃ¶netim Sistemi
 */

namespace Core;

class Response
{
    private $statusCode = 200;
    private $headers = [];
    private $content = '';
    
    /**
     * Status code ayarla
     * 
     * @param int $code
     * @return $this
     */
    public function setStatusCode($code) {
        $this->statusCode = $code;
        return $this;
    }
    
    /**
     * Header ekle
     * 
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setHeader($key, $value) {
        $this->headers[$key] = $value;
        return $this;
    }
    
    /**
     * Content ayarla
     * 
     * @param mixed $content
     * @return $this
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    /**
     * JSON response
     * 
     * @param mixed $data
     * @param int $statusCode
     * @return $this
     */
    public function json($data, $statusCode = 200) {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->setContent(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $this;
    }
    
    /**
     * HTML response
     * 
     * @param string $html
     * @param int $statusCode
     * @return $this
     */
    public function html($html, $statusCode = 200) {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');
        $this->setContent($html);
        return $this;
    }
    
    /**
     * Redirect
     * 
     * @param string $url
     * @param int $statusCode
     */
    public function redirect($url, $statusCode = 302) {
        // Eğer URL tam path değilse (http ile başlamıyorsa), BASE_PATH ekle
        if (!preg_match('/^https?:\/\//', $url)) {
            // BASE_PATH'i kontrol et ve ekle
            if (defined('BASE_PATH') && BASE_PATH !== '') {
                $url = rtrim(BASE_PATH, '/') . '/' . ltrim($url, '/');
            }
        }
        
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        $this->send();
        exit;
    }
    
    /**
     * Download file
     * 
     * @param string $filePath
     * @param string|null $filename
     */
    public function download($filePath, $filename = null) {
        if (!file_exists($filePath)) {
            $this->setStatusCode(404);
            $this->setContent('File not found');
            $this->send();
            exit;
        }
        
        $filename = $filename ?: basename($filePath);
        
        $this->setHeader('Content-Type', 'application/octet-stream');
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->setHeader('Content-Length', filesize($filePath));
        $this->setHeader('Cache-Control', 'must-revalidate');
        $this->setHeader('Pragma', 'public');
        
        $this->send();
        
        readfile($filePath);
        exit;
    }
    
    /**
     * Response'u gÃ¶nder
     */
    public function send() {
        // Status code
        http_response_code($this->statusCode);
        
        // Headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        // Content
        echo $this->content;
    }
    
    /**
     * Success JSON response
     * 
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return $this
     */
    public function success($data = null, $message = 'Ä°ÅŸlem baÅŸarÄ±lÄ±', $statusCode = 200) {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    
    /**
     * Error JSON response
     * 
     * @param string $message
     * @param mixed $errors
     * @param int $statusCode
     * @return $this
     */
    public function error($message = 'Ä°ÅŸlem baÅŸarÄ±sÄ±z', $errors = null, $statusCode = 400) {
        return $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}