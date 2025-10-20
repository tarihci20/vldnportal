<?php
/**
 * Database Sınıfı - PDO Wrapper
 * Vildan Portal - Okul Yönetim Sistemi
 * * Güvenli veritabanı işlemleri için PDO wrapper
 */

namespace Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static $instance = null;
    private $connection;
    private $statement;
    private $error;
    private $transactionCount = 0;
    
    /**
     * Constructor - Singleton pattern
     */
    private function __construct() {
        // HATA DÜZELTMESİ: DB sabitleri, global alanda (Core namespace dışında) tanımlandığı için
        // mutlak yol (backslash \) kullanılarak çağrılıyor.
        $dsn = 'mysql:host=' . \DB_HOST . ';port=' . \DB_PORT . ';dbname=' . \DB_NAME . ';charset=' . \DB_CHARSET;
        
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_PERSISTENT => false,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . \DB_CHARSET
        ];
        
        try {
            // HATA DÜZELTMESİ: DB_USER ve DB_PASS için mutlak yol kullanıldı.
            $this->connection = new \PDO($dsn, \DB_USER, \DB_PASS, $options);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            
            // HATA DÜZELTMESİ: APP_DEBUG için mutlak yol kullanıldı.
            if (\APP_DEBUG) {
                die("Database bağlantı hatası: " . $e->getMessage());
            } else {
                die("Veritabanı bağlantısı kurulamadı. Lütfen sistem yöneticisine başvurun.");
            }
        }
    }
    
    /**
     * Singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * PDO connection'ı döndür
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * SQL query hazırla
     * * @param string $sql SQL sorgusu
     * @return $this
     */
    public function query($sql) {
        try {
            $this->statement = $this->connection->prepare($sql);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            $this->logError($sql, $e);
            
            // HATA DÜZELTMESİ: APP_DEBUG için mutlak yol kullanıldı.
            if (\APP_DEBUG) {
                throw $e;
            }
        }
        
        return $this;
    }
    
    /**
     * Parametreleri bind et
     * * @param string|int $param Parametre adı veya pozisyonu
     * @param mixed $value Değer
     * @param int|null $type PDO veri tipi
     * @return $this
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        
        $this->statement->bindValue($param, $value, $type);
        
        return $this;
    }
    
    /**
     * Query'yi çalıştır
     * * @return bool
     */
    public function execute() {
        try {
            return $this->statement->execute();
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            $this->logError($this->statement->queryString, $e);
            
            // HATA DÜZELTMESİ: APP_DEBUG için mutlak yol kullanıldı.
            if (\APP_DEBUG) {
                throw $e;
            }
            
            return false;
        }
    }
    
    /**
     * Tek satır döndür
     * * @return mixed
     */
    public function single() {
        $this->execute();
        return $this->statement->fetch();
    }
    
    /**
     * Tüm satırları döndür
     * * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->statement->fetchAll();
    }
    
    /**
     * Satır sayısını döndür
     * * @return int
     */
    public function rowCount() {
        return $this->statement->rowCount();
    }
    
    /**
     * Son eklenen ID'yi döndür
     * * @return string
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Transaction başlat
     * * @return bool
     */
    public function beginTransaction() {
        if ($this->transactionCount === 0) {
            $result = $this->connection->beginTransaction();
        } else {
            $this->connection->exec('SAVEPOINT trans' . $this->transactionCount);
            $result = true;
        }
        
        $this->transactionCount++;
        
        return $result;
    }
    
    /**
     * Transaction'ı commit et
     * * @return bool
     */
    public function commit() {
        $this->transactionCount--;
        
        if ($this->transactionCount === 0) {
            return $this->connection->commit();
        }
        
        return true;
    }
    
    /**
     * Transaction'ı geri al
     * * @return bool
     */
    public function rollback() {
        if ($this->transactionCount === 0) {
            return false;
        }
        
        $this->transactionCount--;
        
        if ($this->transactionCount === 0) {
            return $this->connection->rollBack();
        }
        
        $this->connection->exec('ROLLBACK TO SAVEPOINT trans' . $this->transactionCount);
        
        return true;
    }
    
    /**
     * Hata mesajını döndür
     * * @return string
     */
    public function getError() {
        if ($this->statement && is_object($this->statement)) {
            $info = $this->statement->errorInfo();
            return isset($info[2]) && !empty($info[2]) ? $info[2] : $this->error;
        }
        return $this->error;
    }
    
    /**
     * SELECT query builder
     * * @param string $table Tablo adı
     * @param array $columns Sütunlar
     * @param array $where Where koşulları
     * @param array $options Ek seçenekler (orderBy, limit, offset)
     * @return array
     */
    public function select($table, $columns = ['*'], $where = [], $options = []) {
        $sql = "SELECT " . implode(', ', $columns) . " FROM {$table}";
        
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} = :{$key}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        if (isset($options['orderBy'])) {
            $sql .= " ORDER BY {$options['orderBy']}";
        }
        
        if (isset($options['limit'])) {
            $sql .= " LIMIT {$options['limit']}";
            
            if (isset($options['offset'])) {
                $sql .= " OFFSET {$options['offset']}";
            }
        }
        
        $this->query($sql);
        
        foreach ($where as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        return $this->resultSet();
    }
    
    /**
     * INSERT query builder
     * * @param string $table Tablo adı
     * @param array $data Veri dizisi
     * @return bool|string Son eklenen ID veya false
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->query($sql);
        
        foreach ($data as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        if ($this->execute()) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * UPDATE query builder
     * * @param string $table Tablo adı
     * @param array $data Güncellenecek veri
     * @param array $where Where koşulları
     * @return bool
     */
    public function update($table, $data, $where) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }
        
        $conditions = [];
        foreach ($where as $key => $value) {
            $conditions[] = "{$key} = :where_{$key}";
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $conditions);
        
        $this->query($sql);
        
        foreach ($data as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        foreach ($where as $key => $value) {
            $this->bind(":where_{$key}", $value);
        }
        
        return $this->execute();
    }
    
    /**
     * DELETE query builder
     * * @param string $table Tablo adı
     * @param array $where Where koşulları
     * @return bool
     */
    public function delete($table, $where) {
        $conditions = [];
        foreach ($where as $key => $value) {
            $conditions[] = "{$key} = :{$key}";
        }
        
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $conditions);
        
        $this->query($sql);
        
        foreach ($where as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        return $this->execute();
    }
    
    /**
     * Hata logla
     * * @param string $sql SQL sorgusu
     * @param PDOException $e Exception
     */
    private function logError($sql, $e) {
        // HATA DÜZELTMESİ: ERROR_LOG için mutlak yol kullanıldı.
        $errorMessage = "[" . date('Y-m-d H:i:s') . "] Database Error: " . $e->getMessage() . "\n";
        $errorMessage .= "SQL: " . $sql . "\n";
        $errorMessage .= "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
        $errorMessage .= "---\n";
        
        $logFile = defined('ERROR_LOG') ? ERROR_LOG : ROOT_PATH . '/storage/logs/error.log';
    }
    
    /**
     * Debug - Son çalıştırılan query'yi göster
     * * @return string
     */
    public function debugDumpParams() {
        if ($this->statement) {
            ob_start();
            $this->statement->debugDumpParams();
            return ob_get_clean();
        }
        return '';
    }
    
    /**
     * Connection'ı kapat
     */
    public function close() {
        $this->connection = null;
        $this->statement = null;
    }
    
    /**
     * Singleton pattern için clone'u engelle
     */
    private function __clone() {}
    
    /**
     * Singleton pattern için unserialize'i engelle
     */
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
