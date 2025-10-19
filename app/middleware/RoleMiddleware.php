<?php
/**
 * Role Middleware
 * Rol bazlı yetkilendirme kontrolü
 */

namespace App\Middleware;

use Core\Auth;
use Core\Response;

class RoleMiddleware
{
    /**
     * Kullanıcının belirli bir role sahip olup olmadığını kontrol et
     * 
     * @param string|array $roles İzin verilen roller
     * @return bool
     */
    public static function handle($roles)
    {
        // Önce giriş kontrolü
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        $user = Auth::user();
        $userRole = $user['role_slug'] ?? null;
        
        // Roller array olarak gelebilir
        if (is_string($roles)) {
            $roles = [$roles];
        }
        
        // Admin her zaman geçer
        if ($userRole === 'admin') {
            return true;
        }
        
        // Kullanıcının rolü izin verilen roller arasında mı?
        if (!in_array($userRole, $roles)) {
            // Yetkisiz erişim
            http_response_code(403);
            
            if (self::isAjaxRequest()) {
                Response::json([
                    'error' => 'Bu işlem için yetkiniz yok.',
                    'required_roles' => $roles
                ], 403);
                exit;
            }
            
            // HTML response
            include __DIR__ . '/../views/errors/403.php';
            exit;
        }
        
        return true;
    }
    
    /**
     * Admin kontrolü
     */
    public static function admin()
    {
        return self::handle(['admin']);
    }
    
    /**
     * Müdür veya Müdür Yardımcısı kontrolü
     */
    public static function management()
    {
        return self::handle(['admin', 'mudur', 'mudur_yardimcisi']);
    }
    
    /**
     * Öğretmen dahil yetkililer
     */
    public static function staff()
    {
        return self::handle(['admin', 'mudur', 'mudur_yardimcisi', 'ogretmen', 'sekreter']);
    }
    
    /**
     * Kullanıcının belirli izinlere sahip olup olmadığını kontrol et
     * 
     * @param string|array $permissions İzin adları
     * @return bool
     */
    public static function can($permissions)
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Admin her şeyi yapabilir
        if ($user['role_slug'] === 'admin') {
            return true;
        }
        
        // İzinleri kontrol et (permissions tablosundan)
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        
        // TODO: İzin kontrolü database'den yapılabilir
        // Şimdilik rol bazlı kontrol yapıyoruz
        
        return false;
    }
    
    /**
     * AJAX request kontrolü
     */
    private static function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}