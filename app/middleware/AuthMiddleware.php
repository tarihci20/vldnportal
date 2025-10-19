<?php
/**
 * Authentication Middleware
 * Kullanıcı giriş kontrolü
 */

namespace App\Middleware;

use Core\Session;
use Core\Auth;

class AuthMiddleware
{
    /**
     * Kullanıcı giriş yapmış mı kontrol et
     */
    public static function handle()
    {
        // Oturumu yükle
        $auth = Auth::getInstance();
        $auth->loadUserFromSession();
        
        if (!Auth::check()) {
            // If request looks like an API/XHR/json request, return JSON 401 instead of HTML redirect
            $isApiRequest = false;
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
            $xRequested = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

            if (strpos($uri, '/api/') === 0 || stripos($accept, 'application/json') !== false || strtolower($xRequested) === 'xmlhttprequest') {
                http_response_code(401);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Session'a dönüş URL'i kaydet
            $session = Session::getInstance();
            $session->set('redirect_after_login', $_SERVER['REQUEST_URI']);

            // Login sayfasına yönlendir
            header('Location: ' . url('/login'));
            exit;
        }
        
        return true;
    }
    
    /**
     * Misafir kullanıcı kontrolü (giriş yapmamış)
     */
    public static function guest()
    {
        // Oturumu yükle
        $auth = Auth::getInstance();
        $auth->loadUserFromSession();
        
        if (Auth::check()) {
            // If API/XHR request, return JSON 403
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
            $xRequested = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

            if (strpos($uri, '/api/') === 0 || stripos($accept, 'application/json') !== false || strtolower($xRequested) === 'xmlhttprequest') {
                http_response_code(403);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Forbidden'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Zaten giriş yapmış, dashboard'a yönlendir
            header('Location: ' . url('/dashboard'));
            exit;
        }
        
        return true;
    }
    
    /**
     * Email doğrulama kontrolü
     */
    public static function verified()
    {
        if (!Auth::check()) {
            return self::handle();
        }
        
        $user = Auth::user();
        
        if (!$user['email_verified']) {
            header('Location: /email/verify');
            exit;
        }
        
        return true;
    }
}