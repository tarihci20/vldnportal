<?php
/**
 * Base Model Sınıfı
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace Core;

use Core\Database;

class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $timestamps = true;
    
    public function __construct() {
        // Tablo adına otomatik prefix ekle
        // NOT: Child class'daki $table property'si zaten tanımlandı
        // Burada onu prefix'li versiyonla değiştiriyoruz
        if (!empty($this->table)) {
            $this->table = $this->getTableName();
        }
        // Database instance'ı lazy-load et (ilk kullanımda)
        // $this->db = Database::getInstance();
    }
    
    /**
     * Database instance'ı getir (Lazy loading)
     */
    protected function getDb() {
        if ($this->db === null) {
            $this->db = Database::getInstance();
        }
        return $this->db;
    }
    
    /**
     * Tablo adı prefix ile birlikte getir
     * Child class'da tanımlanan table property'sine prefix ekle
     * 
     * @return string
     */
    protected function getTableName() {
        $prefix = \DB_PREFIX ?? 'vp_';
        $table = $this->table; // Child class'daki değer
        
        // Eğer tablo adı zaten prefix ile başlıyorsa, iki kez ekleme
        if (!empty($table) && strpos($table, $prefix) === 0) {
            return $table;
        }
        
        // Prefix ekle
        return !empty($table) ? $prefix . $table : null;
    }
    
    /**
     * Tüm kayıtları getir
     * 
     * @param array $columns
     * @return array
     */
    public function all($columns = ['*']) {
        return $this->getDb()->select($this->table, $columns);
    }
    
    /**
     * ID'ye göre kayıt bul
     * 
     * @param int $id
     * @return array|null
     */
    public function find($id) {
        $results = $this->getDb()->select($this->table, ['*'], [$this->primaryKey => $id]);
        return $results[0] ?? null;
    }
    
    /**
     * Where koşulu ile kayıt bul (ilk sonucu döndür)
     * 
     * @param array $where
     * @return array|null
     */
    public function findWhere($where) {
        $results = $this->getDb()->select($this->table, ['*'], $where, ['limit' => 1]);
        return $results[0] ?? null;
    }
    
    /**
     * Where koşulu ile tüm kayıtları getir
     * 
     * @param array $where
     * @param array $options
     * @return array
     */
    public function where($where, $options = []) {
        return $this->getDb()->select($this->table, ['*'], $where, $options);
    }
    
    /**
     * Yeni kayıt oluştur
     * 
     * @param array $data
     * @return int|bool Son eklenen ID veya false
     */
    public function create($data) {
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->getDb()->insert($this->table, $data);
    }
    
    /**
     * Kayıt güncelle
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        if ($this->timestamps && !isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->getDb()->update($this->table, $data, [$this->primaryKey => $id]);
    }
    
    /**
     * Where koşulu ile güncelle
     * 
     * @param array $where
     * @param array $data
     * @return bool
     */
    public function updateWhere($where, $data) {
        if ($this->timestamps && !isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->getDb()->update($this->table, $data, $where);
    }
    
    /**
     * Kayıt sil
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        return $this->getDb()->delete($this->table, [$this->primaryKey => $id]);
    }
    
    /**
     * Where koşulu ile sil
     * 
     * @param array $where
     * @return bool
     */
    public function deleteWhere($where) {
        return $this->getDb()->delete($this->table, $where);
    }
    
    /**
     * Kayıt sayısını döndür
     * 
     * @param array $where
     * @return int
     */
    public function count($where = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} = :{$key}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $this->getDb()->query($sql);
        
        foreach ($where as $key => $value) {
            $this->getDb()->bind(":{$key}", $value);
        }
        
        $result = $this->getDb()->single();
        return (int)($result['total'] ?? 0);
    }
    
    /**
     * Kayıt var mı kontrol et
     * 
     * @param array $where
     * @return bool
     */
    public function exists($where) {
        return $this->count($where) > 0;
    }
    
    /**
     * Paginate - Sayfalama
     * 
     * @param int $page Sayfa numarası
     * @param int $perPage Sayfa başına kayıt
     * @param array $where Where koşulları
     * @param array $options Ek seçenekler
     * @return array
     */
    public function paginate($page = 1, $perPage = 20, $where = [], $options = []) {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        
        // Toplam kayıt sayısı
        $total = $this->count($where);
        
        // Kayıtları getir
        $options['limit'] = $perPage;
        $options['offset'] = $offset;
        
        $data = $this->where($where, $options);
        
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
     * Raw SQL query çalıştır
     * 
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function query($sql, $params = []) {
        $this->getDb()->query($sql);
        
        foreach ($params as $key => $value) {
            $param = is_int($key) ? $key + 1 : ":{$key}";
            $this->getDb()->bind($param, $value);
        }
        
        return $this->getDb()->resultSet();
    }
    
    /**
     * Transaction başlat
     * 
     * @return bool
     */
    public function beginTransaction() {
        return $this->getDb()->beginTransaction();
    }
    
    /**
     * Transaction commit
     * 
     * @return bool
     */
    public function commit() {
        return $this->getDb()->commit();
    }
    
    /**
     * Transaction rollback
     * 
     * @return bool
     */
    public function rollback() {
        return $this->getDb()->rollback();
    }
    
    /**
     * Son eklenen ID
     * 
     * @return int
     */
    public function lastInsertId() {
        return $this->getDb()->lastInsertId();
    }
    
    /**
     * Magic getter - Lazy load database
     */
    public function __get($name) {
        if ($name === 'db' && $this->db === null) {
            $this->db = Database::getInstance();
        }
        return $this->$name ?? null;
    }
}