<?php
/**
 * User Model
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $timestamps = true;
    
    /**
     * ID'ye göre kullanıcı getir
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Kullanıcıyı role ile birlikte getir
     */
    public function findWithRole($id) {
        $sql = "SELECT u.*, r.role_name, r.display_name as role_display_name
                FROM {$this->table} u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.id = :id
                LIMIT 1";
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    /**
     * Tüm kullanıcıları getir (basit)
     */
    public function getAll() {
        $sql = "SELECT u.*, r.role_name, r.display_name as role_display_name
                FROM {$this->table} u
                LEFT JOIN roles r ON u.role_id = r.id
                ORDER BY u.created_at DESC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    /**
     * Tüm rolleri getir
     */
    public function getAllRoles() {
        $sql = "SELECT * FROM roles ORDER BY sort_order, id";
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    /**
     * Rol bazlı sayfa izinlerini getir
     */
    public function getRolePermissions($roleId) {
        $sql = "SELECT p.*, rpp.can_view, rpp.can_create, rpp.can_edit, rpp.can_delete
                FROM pages p
                LEFT JOIN role_page_permissions rpp ON p.id = rpp.page_id AND rpp.role_id = :role_id
                WHERE p.is_active = 1
                ORDER BY p.sort_order, p.id";
        
        $this->db->query($sql);
        $this->db->bind(':role_id', $roleId);
        return $this->db->resultSet();
    }
    
    /**
     * Kullanıcı izinlerini güncelle
     */
    public function updateRolePermissions($roleId, $permissions) {
        try {
            // Önce mevcut izinleri sil
            $deleteSql = "DELETE FROM role_page_permissions WHERE role_id = :role_id";
            $this->db->query($deleteSql);
            $this->db->bind(':role_id', $roleId);
            $this->db->execute();
            
            // Yeni izinleri ekle
            $insertSql = "INSERT INTO role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete) 
                         VALUES (:role_id, :page_id, :can_view, :can_create, :can_edit, :can_delete)";
            
            foreach ($permissions as $permission) {
                $this->db->query($insertSql);
                $this->db->bind(':role_id', $roleId);
                $this->db->bind(':page_id', $permission['page_id']);
                $this->db->bind(':can_view', $permission['can_view'] ?? 0);
                $this->db->bind(':can_create', $permission['can_create'] ?? 0);
                $this->db->bind(':can_edit', $permission['can_edit'] ?? 0);
                $this->db->bind(':can_delete', $permission['can_delete'] ?? 0);
                $this->db->execute();
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Tüm sayfaları getir
     */
    public function getAllPages() {
        $sql = "SELECT * FROM pages WHERE is_active = 1 ORDER BY sort_order, id";
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    
    /**
     * Tüm kullanıcıları role ile getir
     */
    public function getAllWithRoles($page = 1, $perPage = 50) {
        $sql = "SELECT u.*, r.role_name, r.display_name as role_display_name
                FROM {$this->table} u
                LEFT JOIN roles r ON u.role_id = r.id
                ORDER BY u.created_at DESC";
        
        // Toplam sayı
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $this->db->query($countSql);
        $totalResult = $this->db->single();
        $total = $totalResult['total'] ?? 0;
        
        // Sayfalama
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $this->db->query($sql);
        $data = $this->db->resultSet();
        
        return [
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }
    
    /**
     * Username var mı?
     */
    public function usernameExists($username, $exceptId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        
        if ($exceptId) {
            $sql .= " AND id != :except_id";
        }
        
        $this->db->query($sql);
        $this->db->bind(':username', $username);
        
        if ($exceptId) {
            $this->db->bind(':except_id', $exceptId);
        }
        
        $result = $this->db->single();
        return $result['count'] > 0;
    }
    
    /**
     * Email var mı?
     */
    public function emailExists($email, $exceptId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        
        if ($exceptId) {
            $sql .= " AND id != :except_id";
        }
        
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        
        if ($exceptId) {
            $this->db->bind(':except_id', $exceptId);
        }
        
        $result = $this->db->single();
        return $result['count'] > 0;
    }
    
    /**
     * Şifreyi güncelle
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        return $this->update($userId, [
            'password_hash' => $hashedPassword
        ]);
    }
    
    /**
     * Kullanıcı oluştur
     */
    public function create($data) {
        // Şifreyi hash'le
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        
        return parent::create($data);
    }
}