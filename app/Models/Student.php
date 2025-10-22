<?php
/**
 * Student Model
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Models;

use Core\Model;

class Student extends Model
{
    protected $table = 'students';  // DB_PREFIX otomatik eklenecek
    protected $primaryKey = 'id';
    protected $timestamps = true;
    
    /**
     * Tüm öğrencileri sayfalama ile getir
     * 
     * @param int $page Sayfa numarası
     * @param int $perPage Sayfa başına kayıt
     * @param array $filters Filtreler (search, class)
     * @return array
     */
    public function getAll($page = 1, $perPage = 50, $filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1";
        $params = [];
        
        // Arama filtresi
        if (!empty($filters['search'])) {
            $searchValue = '%' . $filters['search'] . '%';
            $sql .= " AND (
                first_name LIKE :search1 
                OR last_name LIKE :search2 
                OR CONCAT(first_name, ' ', last_name) LIKE :search3
                OR tc_no LIKE :search4
                OR father_name LIKE :search5
                OR mother_name LIKE :search6
                OR teacher_name LIKE :search7
            )";
            $params['search1'] = $searchValue;
            $params['search2'] = $searchValue;
            $params['search3'] = $searchValue;
            $params['search4'] = $searchValue;
            $params['search5'] = $searchValue;
            $params['search6'] = $searchValue;
            $params['search7'] = $searchValue;
        }
        
        // Sınıf filtresi
        if (!empty($filters['class'])) {
            $sql .= " AND class = :class";
            $params['class'] = $filters['class'];
        }
        
        // Sıralama
        $sql .= " ORDER BY first_name ASC, last_name ASC";
        
        // Toplam kayıt sayısı
        $countSql = "SELECT COUNT(*) as total FROM ({$sql}) as t";
        $this->getDb()->query($countSql);
        foreach ($params as $key => $value) {
            $this->getDb()->bind(":{$key}", $value);
        }
        $totalResult = $this->getDb()->single();
        $total = $totalResult['total'] ?? 0;
        
        // Sayfalama
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $this->getDb()->query($sql);
        foreach ($params as $key => $value) {
            $this->getDb()->bind(":{$key}", $value);
        }
        
        $data = $this->getDb()->resultSet();
        
        return [
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ]
        ];
    }
    
    /**
     * Öğrenci ara (AJAX için - debounce)
     * 
     * @param string $query Arama sorgusu
     * @param int $page Sayfa numarası
     * @param int $perPage Sayfa başına kayıt
     * @return array
     */
    public function search($query, $page = 1, $perPage = 20) {
        $searchTerm = '%' . $query . '%';
        
        $sql = "SELECT id, tc_no, first_name, last_name, class, teacher_name, teacher_phone, father_phone, mother_phone
                FROM {$this->table} 
                WHERE is_active = 1 
                AND (
                    first_name LIKE :query1
                    OR last_name LIKE :query2
                    OR CONCAT(first_name, ' ', last_name) LIKE :query3
                    OR tc_no LIKE :query4
                    OR class LIKE :query5
                )";
        
        // Toplam sayı
        $countSql = "SELECT COUNT(*) as total FROM ({$sql}) as t";
        $this->getDb()->query($countSql);
        $this->getDb()->bind(':query1', $searchTerm);
        $this->getDb()->bind(':query2', $searchTerm);
        $this->getDb()->bind(':query3', $searchTerm);
        $this->getDb()->bind(':query4', $searchTerm);
        $this->getDb()->bind(':query5', $searchTerm);
        $totalResult = $this->getDb()->single();
        $total = $totalResult['total'] ?? 0;
        
        // Sayfalama
        $offset = ($page - 1) * $perPage;
        $sql .= " ORDER BY first_name ASC, last_name ASC LIMIT {$perPage} OFFSET {$offset}";
        
        $this->getDb()->query($sql);
        $this->getDb()->bind(':query1', $searchTerm);
        $this->getDb()->bind(':query2', $searchTerm);
        $this->getDb()->bind(':query3', $searchTerm);
        $this->getDb()->bind(':query4', $searchTerm);
        $this->getDb()->bind(':query5', $searchTerm);
        
        $data = $this->getDb()->resultSet();
        
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
     * ID'ye göre öğrenci bul
     * 
     * @param int $id
     * @return array|null
     */
    public function findById($id) {
        return $this->find($id);
    }
    
    /**
     * TC kimlik no ile öğrenci bul
     * 
     * @param string $tcNo
     * @return array|null
     */
    public function findByTc($tcNo) {
        return $this->findWhere(['tc_no' => $tcNo]);
    }
    
    /**
     * TC numarası daha önce kullanılmış mı?
     * 
     * @param string $tcNo
     * @param int|null $exceptId Bu ID hariç
     * @return bool
     */
    public function isTcExists($tcNo, $exceptId = null) {
        if (empty($tcNo)) {
            return false;
        }
        
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE tc_no = :tc_no";
        
        if ($exceptId) {
            $sql .= " AND id != :except_id";
        }
        
        $this->getDb()->query($sql);
        $this->getDb()->bind(':tc_no', $tcNo);
        
        if ($exceptId) {
            $this->getDb()->bind(':except_id', $exceptId);
        }
        
        $result = $this->getDb()->single();
        
        return $result['count'] > 0;
    }
    
    /**
     * Benzersiz sınıf listesini getir
     * 
     * @return array
     */
    public function getUniqueClasses() {
        $sql = "SELECT DISTINCT class FROM {$this->table} 
                WHERE class IS NOT NULL AND class != '' AND is_active = 1 
                ORDER BY class ASC";
        
        $this->getDb()->query($sql);
        $results = $this->getDb()->resultSet();
        
        return array_column($results, 'class');
    }
    
    /**
     * Sınıfa göre öğrenci sayısı
     * 
     * @return array
     */
    public function getCountByClass() {
        $sql = "SELECT class, COUNT(*) as count 
                FROM {$this->table} 
                WHERE is_active = 1 AND class IS NOT NULL 
                GROUP BY class 
                ORDER BY class ASC";
        
        $this->getDb()->query($sql);
        return $this->getDb()->resultSet();
    }
    
    /**
     * Toplam öğrenci sayısı
     * 
     * @return int
     */
    public function countAll() {
        return $this->count(['is_active' => 1]);
    }
    
    /**
     * Excel export için tüm öğrencileri getir
     * 
     * @return array
     */
    public function getAllForExport() {
        $sql = "SELECT tc_no, first_name, last_name, class, birth_date, 
                       father_name, father_phone, mother_name, mother_phone, 
                       address, teacher_name, teacher_phone
                FROM {$this->table} 
                WHERE is_active = 1 
                ORDER BY class ASC, first_name ASC, last_name ASC";
        
        $this->getDb()->query($sql);
        return $this->getDb()->resultSet();
    }
    
    /**
     * Öğrenci oluştur
     * 
     * @param array $data
     * @return int|bool ID veya false
     */
    public function create($data) {
        // TC varsa ve boşsa null yap
        if (isset($data['tc_no']) && empty($data['tc_no'])) {
            $data['tc_no'] = null;
        }
        $result = parent::create($data);
        if ($result === false && isset($this->db) && method_exists($this->db, 'getError')) {
            return $this->getDb()->getError();
        }
        return $result;
    }
    
    /**
     * Öğrenci güncelle
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        // TC varsa ve boşsa null yap
        if (isset($data['tc_no']) && empty($data['tc_no'])) {
            $data['tc_no'] = null;
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Öğrenci sil (soft delete)
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        return $this->update($id, ['is_active' => 0]);
    }
    
    /**
     * Öğrenciyi kalıcı olarak sil (hard delete)
     * 
     * @param int $id
     * @return bool
     */
    public function hardDelete($id) {
        return parent::delete($id);
    }
    
    /**
     * Tüm öğrencileri sil
     * 
     * @return bool
     */
    public function deleteAll() {
        $sql = "DELETE FROM {$this->table}";
        $this->getDb()->query($sql);
        return $this->getDb()->execute();
    }
    
    /**
     * En çok etüt alan öğrenciler
     * 
     * @param int $limit
     * @return array
     */
    public function getMostActiveStudents($limit = 10) {
        $sql = "SELECT s.*, COUNT(e.id) as etut_count
                FROM {$this->table} s
                LEFT JOIN etut_applications e ON s.id = e.student_id
                WHERE s.is_active = 1
                GROUP BY s.id
                ORDER BY etut_count DESC
                LIMIT {$limit}";
        
        $this->getDb()->query($sql);
        return $this->getDb()->resultSet();
    }
    
    /**
     * Öğretmene göre öğrencileri getir
     * 
     * @param string $teacherName
     * @return array
     */
    public function getByTeacher($teacherName) {
        return $this->where([
            'teacher_name' => $teacherName,
            'is_active' => 1
        ], ['orderBy' => 'first_name ASC, last_name ASC']);
    }
    
    /**
     * Sınıfa göre öğrencileri getir
     * 
     * @param string $class
     * @return array
     */
    public function getByClass($class) {
        return $this->where([
            'class' => $class,
            'is_active' => 1
        ], ['orderBy' => 'first_name ASC, last_name ASC']);
    }
}
