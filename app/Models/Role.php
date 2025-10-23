<?php

namespace App\Models;

use Core\Model;

class Role extends Model {
    protected $table = 'roles';
    
    /**
     * Tüm rolleri getir
     */
    public function getAllRoles() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY id ASC";
            $this->db->query($sql);
            return $this->db->resultSet();
        } catch (\Exception $e) {
            error_log("Role getAllRoles error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * ID'ye göre rol getir
     */
    public function getRoleById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (\Exception $e) {
            error_log("Role getRoleById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Rol adına göre rol getir
     */
    public function getRoleByName($roleName) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE role_name = :role_name";
            $this->db->query($sql);
            $this->db->bind(':role_name', $roleName);
            return $this->db->single();
        } catch (\Exception $e) {
            error_log("Role getRoleByName error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Belirli bir rolün tüm izinlerini getir
     */
    public function getPermissionsByRoleId($roleId) {
        try {
            $sql = "SELECT rpp.*, p.page_name, p.page_key, p.page_url 
                     FROM vp_role_page_permissions rpp
                     LEFT JOIN pages p ON rpp.page_id = p.id
                     WHERE rpp.role_id = :role_id
                     ORDER BY p.page_name ASC";
            $this->db->query($sql);
            $this->db->bind(':role_id', $roleId);
            return $this->db->resultSet();
        } catch (\Exception $e) {
            error_log("Role getPermissionsByRoleId error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Belirli bir rol için belirli bir sayfanın iznini kontrol et
     */
    public function checkPermission($roleId, $pageKey, $permissionType = 'can_view') {
        try {
            $sql = "SELECT rpp.{$permissionType}
                     FROM vp_role_page_permissions rpp
                     LEFT JOIN pages p ON rpp.page_id = p.id
                     WHERE rpp.role_id = :role_id AND p.page_key = :page_key";
            $this->db->query($sql);
            $this->db->bind(':role_id', $roleId);
            $this->db->bind(':page_key', $pageKey);
            $result = $this->db->single();
            
            return $result && isset($result[$permissionType]) && $result[$permissionType] == 1;
        } catch (\Exception $e) {
            error_log("Role checkPermission error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tüm sayfaları getir
     */
    public function getAllPages() {
        try {
            $sql = "SELECT * FROM vp_pages ORDER BY page_name ASC";
            $this->db->query($sql);
            return $this->db->resultSet();
        } catch (\Exception $e) {
            error_log("Role getAllPages error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Rol izinlerini güncelle veya oluştur
     */
    public function updatePermission($roleId, $pageId, $permissions) {
        try {
            // Önce izin var mı kontrol et
            $checkSql = "SELECT id FROM vp_role_page_permissions WHERE role_id = :role_id AND page_id = :page_id";
            $this->db->query($checkSql);
            $this->db->bind(':role_id', $roleId);
            $this->db->bind(':page_id', $pageId);
            $exists = $this->db->single();
            
            if ($exists) {
                // Güncelle
                $sql = "UPDATE role_page_permissions 
                         SET can_view = :can_view, 
                             can_create = :can_create, 
                             can_edit = :can_edit, 
                             can_delete = :can_delete
                         WHERE role_id = :role_id AND page_id = :page_id";
            } else {
                // Yeni kayıt oluştur
                $sql = "INSERT INTO role_page_permissions 
                         (role_id, page_id, can_view, can_create, can_edit, can_delete) 
                         VALUES (:role_id, :page_id, :can_view, :can_create, :can_edit, :can_delete)";
            }
            
            $this->db->query($sql);
            $this->db->bind(':role_id', $roleId);
            $this->db->bind(':page_id', $pageId);
            $this->db->bind(':can_view', $permissions['can_view']);
            $this->db->bind(':can_create', $permissions['can_create']);
            $this->db->bind(':can_edit', $permissions['can_edit']);
            $this->db->bind(':can_delete', $permissions['can_delete']);
            
            return $this->db->execute();
        } catch (\Exception $e) {
            error_log("Role updatePermission error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Bir rolün tüm izinlerini toplu güncelle
     */
    public function updateRolePermissions($roleId, $permissionsArray) {
        try {
            $this->db->beginTransaction();
            
            foreach ($permissionsArray as $pageId => $permissions) {
                if (!$this->updatePermission($roleId, $pageId, $permissions)) {
                    $this->db->rollback();
                    return false;
                }
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Role updateRolePermissions error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kullanıcının rolünü kontrol et
     */
    public function isAdmin($userId) {
        try {
            $sql = "SELECT role_id FROM vp_users WHERE id = :user_id";
            $this->db->query($sql);
            $this->db->bind(':user_id', $userId);
            $user = $this->db->single();
            
            return $user && $user['role_id'] == 1; // 1 = admin
        } catch (\Exception $e) {
            error_log("Role isAdmin error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kullanıcının rolünü al
     */
    public function getUserRole($userId) {
        try {
            $sql = "SELECT r.* FROM vp_roles r
                     INNER JOIN users u ON u.role_id = r.id
                     WHERE u.id = :user_id";
            $this->db->query($sql);
            $this->db->bind(':user_id', $userId);
            return $this->db->single();
        } catch (\Exception $e) {
            error_log("Role getUserRole error: " . $e->getMessage());
            return null;
        }
    }
}

