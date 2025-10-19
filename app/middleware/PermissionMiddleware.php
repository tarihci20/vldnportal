<?php

namespace App\Middleware;

use App\Models\Role;

class PermissionMiddleware
{
    private $roleModel;
    
    public function __construct() {
        $this->roleModel = new Role();
    }
    
    /**
     * Middleware handler
     * 
     * @param string $pageSlug Sayfa slug'ı
     * @param string $permissionType İzin tipi: can_view, can_create, can_edit, can_delete
     * @return bool
     */
    public function handle($pageSlug, $permissionType = 'can_view') {
        // Kullanıcı giriş yapmış mı?
        if (!isLoggedIn()) {
            redirect('/login');
            exit;
        }
        
        $userId = getCurrentUserId();
        $userRole = $this->roleModel->getUserRole($userId);
        
        // Rol bulunamadı
        if (!$userRole) {
            setFlashMessage('Kullanıcı rolü bulunamadı.', 'error');
            redirect('/dashboard');
            exit;
        }
        
        // Admin her zaman erişebilir
        if ($userRole['role_name'] === 'admin') {
            return true;
        }
        
        // İzin kontrolü
        $hasPermission = $this->roleModel->checkPermission($userRole['id'], $pageSlug, $permissionType);
        
        if (!$hasPermission) {
            setFlashMessage('Bu sayfaya erişim yetkiniz bulunmamaktadır.', 'error');
            redirect('/dashboard');
            exit;
        }
        
        return true;
    }
    
    /**
     * Görüntüleme izni kontrolü
     */
    public static function canView($pageSlug) {
        $middleware = new self();
        return $middleware->handle($pageSlug, 'can_view');
    }
    
    /**
     * Oluşturma izni kontrolü
     */
    public static function canCreate($pageSlug) {
        $middleware = new self();
        return $middleware->handle($pageSlug, 'can_create');
    }
    
    /**
     * Düzenleme izni kontrolü
     */
    public static function canEdit($pageSlug) {
        $middleware = new self();
        return $middleware->handle($pageSlug, 'can_edit');
    }
    
    /**
     * Silme izni kontrolü
     */
    public static function canDelete($pageSlug) {
        $middleware = new self();
        return $middleware->handle($pageSlug, 'can_delete');
    }
    
    /**
     * Kullanıcının belirli bir izne sahip olup olmadığını kontrol et (view için kullan)
     */
    public static function check($pageSlug, $permissionType = 'can_view') {
        if (!isLoggedIn()) {
            error_log("PermissionMiddleware: User not logged in");
            return false;
        }
        
        $roleModel = new Role();
        $userId = getCurrentUserId();
        $userRole = $roleModel->getUserRole($userId);
        
        error_log("PermissionMiddleware: Checking $pageSlug for user $userId");
        error_log("PermissionMiddleware: User role: " . json_encode($userRole));
        
        if (!$userRole) {
            error_log("PermissionMiddleware: No user role found");
            return false;
        }
        
        // Admin her zaman izinli
        if ($userRole['role_name'] === 'admin') {
            error_log("PermissionMiddleware: User is admin - access granted");
            return true;
        }
        
        $hasPermission = $roleModel->checkPermission($userRole['id'], $pageSlug, $permissionType);
        error_log("PermissionMiddleware: Permission check result: " . ($hasPermission ? 'true' : 'false'));
        
        return $hasPermission;
    }
}
