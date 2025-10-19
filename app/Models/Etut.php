<?php
/**
 * Etut Model
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Models;

use Core\Model;

class Etut extends Model
{
    protected $table = 'etut_applications';
    protected $primaryKey = 'id';
    protected $timestamps = true;
    
    /**
     * Tüm başvuruları öğrenci bilgisiyle getir
     */
    public function getAllWithStudent($page = 1, $perPage = 20, $filters = []) {
        $sql = "SELECT e.*, 
                       s.first_name, s.last_name, s.class,
                       u1.full_name as creator_name,
                       u2.full_name as approver_name
                FROM {$this->table} e
                LEFT JOIN students s ON e.student_id = s.id
                LEFT JOIN users u1 ON e.created_by = u1.id
                LEFT JOIN users u2 ON e.approved_by = u2.id
                WHERE 1=1";
        
        $params = [];
        
        // Durum filtresi
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = :status";
            $params['status'] = $filters['status'];
        }
        
        // Tarih filtresi
        if (!empty($filters['date'])) {
            $sql .= " AND e.application_date = :date";
            $params['date'] = $filters['date'];
        }
        
        // Sıralama
        $sql .= " ORDER BY e.application_date DESC, e.start_time DESC";
        
        // Toplam sayı
        $countSql = "SELECT COUNT(*) as total FROM ({$sql}) as t";
        $this->db->query($countSql);
        foreach ($params as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        $totalResult = $this->db->single();
        $total = $totalResult['total'] ?? 0;
        
        // Sayfalama
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        
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
     * Bugünkü etütler
     */
    public function getToday() {
        $today = date('Y-m-d');
        
        $sql = "SELECT e.*, s.first_name, s.last_name, s.class
                FROM {$this->table} e
                LEFT JOIN students s ON e.student_id = s.id
                WHERE e.application_date = :today
                ORDER BY e.start_time ASC";
        
        $this->db->query($sql);
        $this->db->bind(':today', $today);
        
        return $this->db->resultSet();
    }
    
    /**
     * Onay bekleyenler
     */
    public function getPending($page = 1, $perPage = 20) {
        return $this->getAllWithStudent($page, $perPage, ['status' => 'pending']);
    }
    
    /**
     * Öğrenciye göre etütler
     */
    public function getByStudent($studentId) {
        $sql = "SELECT e.*, u.full_name as creator_name
                FROM {$this->table} e
                LEFT JOIN users u ON e.created_by = u.id
                WHERE e.student_id = :student_id
                ORDER BY e.application_date DESC";
        
        $this->db->query($sql);
        $this->db->bind(':student_id', $studentId);
        
        return $this->db->resultSet();
    }
    
    /**
     * İstatistikler
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM {$this->table}";
        
        $this->db->query($sql);
        return $this->db->single();
    }
    
    /**
     * Form tipine göre başvuruları getir
     */
    public function getByFormType($formType, $limit = 100) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE form_type = :form_type 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        $this->db->query($sql);
        $this->db->bind(':form_type', $formType);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    /**
     * Get applications by an array of IDs
     * @param array $ids
     * @return array
     */
    public function getByIds(array $ids)
    {
        if (empty($ids)) return [];
        $ids = array_map('intval', $ids);
        $placeholders = implode(',', $ids);
        $sql = "SELECT * FROM {$this->table} WHERE id IN ({$placeholders}) ORDER BY created_at DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Get applications by date (YYYY-MM-DD)
     * @param string $date
     * @return array
     */
    public function getByDate($date)
    {
        $sql = "SELECT * FROM {$this->table} WHERE DATE(created_at) = :d ORDER BY created_at DESC";
        $this->db->query($sql);
        $this->db->bind(':d', $date);
        return $this->db->resultSet();
    }
}