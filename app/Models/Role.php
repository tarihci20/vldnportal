<?php

namespace App\Models;

use Core\Model;

class Role extends Model {
    public function __construct() {
        parent::__construct();
    }

    protected $table = 'vp_roles';
    
    /**
     * Tüm rolleri getir
     */
    public function getAllRoles() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY id ASC";
            $this->getDb()->query($sql);
            return $this->getDb()->resultSet();
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
            $this->getDb()->query($sql);
            $this->getDb()->bind(':id', $id);
            return $this->getDb()->single();
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
            $this->getDb()->query($sql);
            $this->getDb()->bind(':role_name', $roleName);
            return $this->getDb()->single();
        } catch (\Exception $e) {
            error_log("Role getRoleByName error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Yeni rol oluştur
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (role_name, display_name, description, is_active) 
                    VALUES (:role_name, :display_name, :description, :is_active)";
            
            $this->getDb()->query($sql);
            $this->getDb()->bind(':role_name', $data['role_name']);
            $this->getDb()->bind(':display_name', $data['display_name']);
            $this->getDb()->bind(':description', $data['description'] ?? '');
            $this->getDb()->bind(':is_active', $data['is_active'] ?? 1);
            
            $result = $this->getDb()->execute();
            
            if ($result) {
                $lastId = $this->getDb()->lastInsertId();
                error_log("Role created successfully with ID: " . $lastId);
                return $lastId;
            }
            
            error_log("Role create execute failed. Error: " . $this->getDb()->getError() . " | SQL: " . $sql);
            return false;
        } catch (\Exception $e) {
            error_log("Role create exception: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());
            return false;
        }
    }

    /**
     * Rolü güncelle
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET ";
            $sets = [];
            $params = [':id' => $id];
            
            foreach ($data as $key => $value) {
                $sets[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
            
            $sql .= implode(', ', $sets) . " WHERE id = :id";
            
            $this->getDb()->query($sql);
            foreach ($params as $key => $value) {
                $this->getDb()->bind($key, $value);
            }
            
            return $this->getDb()->execute();
        } catch (\Exception $e) {
            error_log("Role update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rolü sil
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $this->getDb()->query($sql);
            $this->getDb()->bind(':id', $id);
            return $this->getDb()->execute();
        } catch (\Exception $e) {
            error_log("Role delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Belirli bir rolün tüm izinlerini getir
     */
    public function getPermissionsByRoleId($roleId) {
        try {
            $sql = "SELECT rpp.*, p.page_name, p.page_key, p.page_url 
                     FROM vp_role_page_permissions rpp
                     LEFT JOIN vp_pages p ON rpp.page_id = p.id
                     WHERE rpp.role_id = :role_id
                     ORDER BY p.page_name ASC";
            $this->getDb()->query($sql);
            $this->getDb()->bind(':role_id', $roleId);
            return $this->getDb()->resultSet();
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
                     LEFT JOIN vp_pages p ON rpp.page_id = p.id
                     WHERE rpp.role_id = :role_id AND p.page_key = :page_key";
            $this->getDb()->query($sql);
            $this->getDb()->bind(':role_id', $roleId);
            $this->getDb()->bind(':page_key', $pageKey);
            $result = $this->getDb()->single();
            
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
            $this->getDb()->query($sql);
            return $this->getDb()->resultSet();
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
            $checkSql = "SELECT id FROM vp_role_page_permissions WHERE role_id = :check_role_id AND page_id = :check_page_id";
            $this->getDb()->query($checkSql);
            $this->getDb()->bind(':check_role_id', $roleId);
            $this->getDb()->bind(':check_page_id', $pageId);
            $exists = $this->getDb()->single();
            
            if ($exists) {
                // Güncelle
                $sql = "UPDATE vp_role_page_permissions 
                         SET can_view = :can_view, 
                             can_create = :can_create, 
                             can_edit = :can_edit, 
                             can_delete = :can_delete
                         WHERE role_id = :role_id AND page_id = :page_id";
            } else {
                // Yeni kayıt oluştur
                $sql = "INSERT INTO vp_role_page_permissions 
                         (role_id, page_id, can_view, can_create, can_edit, can_delete) 
                         VALUES (:role_id, :page_id, :can_view, :can_create, :can_edit, :can_delete)";
            }
            
            $this->getDb()->query($sql);
            $this->getDb()->bind(':role_id', $roleId);
            $this->getDb()->bind(':page_id', $pageId);
            $this->getDb()->bind(':can_view', $permissions['can_view'] ?? 0);
            $this->getDb()->bind(':can_create', $permissions['can_create'] ?? 0);
            $this->getDb()->bind(':can_edit', $permissions['can_edit'] ?? 0);
            $this->getDb()->bind(':can_delete', $permissions['can_delete'] ?? 0);
            
            $result = $this->getDb()->execute();
            
            if (!$result) {
                error_log("Role updatePermission SQL error: " . $this->getDb()->getError() . " | SQL: " . $sql);
            }
            
            return $result;
        } catch (\Exception $e) {
            error_log("Role updatePermission exception: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Bir rolün tüm izinlerini toplu güncelle
     */
    public function updateRolePermissions($roleId, $permissionsArray) {
        try {
            $this->getDb()->beginTransaction();
            
            foreach ($permissionsArray as $pageId => $permissions) {
                if (!$this->updatePermission($roleId, $pageId, $permissions)) {
                    $this->getDb()->rollback();
                    error_log("Role updateRolePermissions: Failed to update permission for page_id: {$pageId}");
                    return false;
                }
            }
            
            $this->getDb()->commit();
            return true;
        } catch (\Exception $e) {
            try {
                $this->getDb()->rollback();
            } catch (\Exception $rollbackError) {
                error_log("Role updateRolePermissions rollback error: " . $rollbackError->getMessage());
            }
            error_log("Role updateRolePermissions error: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());
            return false;
        }
    }
    
    /**
     * Kullanıcının rolünü kontrol et
     */
    public function isAdmin($userId) {
        try {
            $sql = "SELECT role_id FROM vp_users WHERE id = :user_id";
            $this->getDb()->query($sql);
            $this->getDb()->bind(':user_id', $userId);
            $user = $this->getDb()->single();
            
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
                     INNER JOIN vp_users u ON u.role_id = r.id
                     WHERE u.id = :user_id";
            $this->getDb()->query($sql);
            $this->getDb()->bind(':user_id', $userId);
            return $this->getDb()->single();
        } catch (\Exception $e) {
            error_log("Role getUserRole error: " . $e->getMessage());
            return null;
        }
    }
}


