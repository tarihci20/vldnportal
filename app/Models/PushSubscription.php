<?php
/**
 * Push Subscription Model
 * 
 * Web Push subscription verilerini yönetir
 */

namespace App\Models;

use App\Core\Model;

class PushSubscription extends Model
{
    public function __construct() {
        parent::__construct();
    }

    protected $table = 'vp_push_subscriptions';
    
    protected $fillable = [
        'user_id',
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
        'user_agent',
        'ip_address'
    ];
    
    /**
     * Yeni subscription oluştur
     */
    public function create($data)
    {
        try {
            // Aynı endpoint zaten varsa güncelle
            $existing = $this->findByEndpoint($data['endpoint'], $data['user_id']);
            
            if ($existing) {
                return $this->update($existing['id'], $data);
            }
            
            $sql = "INSERT INTO {$this->table} (
                user_id,
                endpoint,
                public_key,
                auth_token,
                content_encoding,
                user_agent,
                ip_address,
                created_at
            ) VALUES (
                :user_id,
                :endpoint,
                :public_key,
                :auth_token,
                :content_encoding,
                :user_agent,
                :ip_address,
                NOW()
            )";
            
            $stmt = $this->getDb()->prepare($sql);
            
            return $stmt->execute([
                'user_id' => $data['user_id'],
                'endpoint' => $data['endpoint'],
                'public_key' => $data['public_key'],
                'auth_token' => $data['auth_token'],
                'content_encoding' => $data['content_encoding'] ?? 'aes128gcm',
                'user_agent' => $data['user_agent'] ?? '',
                'ip_address' => $data['ip_address'] ?? ''
            ]);
            
        } catch (\PDOException $e) {
            $this->logError('Create subscription failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Subscription güncelle
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET
                public_key = :public_key,
                auth_token = :auth_token,
                content_encoding = :content_encoding,
                user_agent = :user_agent,
                ip_address = :ip_address,
                last_used_at = NOW(),
                updated_at = NOW()
            WHERE id = :id";
            
            $stmt = $this->getDb()->prepare($sql);
            
            return $stmt->execute([
                'id' => $id,
                'public_key' => $data['public_key'],
                'auth_token' => $data['auth_token'],
                'content_encoding' => $data['content_encoding'] ?? 'aes128gcm',
                'user_agent' => $data['user_agent'] ?? '',
                'ip_address' => $data['ip_address'] ?? ''
            ]);
            
        } catch (\PDOException $e) {
            $this->logError('Update subscription failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kullanıcının tüm subscription'larını getir
     */
    public function getByUserId($userId)
    {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE user_id = :user_id 
                    AND is_active = 1
                    ORDER BY created_at DESC";
            
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            $this->logError('Get subscriptions failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Endpoint'e göre subscription bul
     */
    public function findByEndpoint($endpoint, $userId = null)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE endpoint = :endpoint";
            $params = ['endpoint' => $endpoint];
            
            if ($userId) {
                $sql .= " AND user_id = :user_id";
                $params['user_id'] = $userId;
            }
            
            $sql .= " LIMIT 1";
            
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            $this->logError('Find subscription failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Tüm aktif subscription'ları getir (broadcast için)
     */
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE is_active = 1 
                    ORDER BY created_at DESC";
            
            $stmt = $this->getDb()->query($sql);
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            $this->logError('Get all subscriptions failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Subscription sil (endpoint'e göre)
     */
    public function deleteByEndpoint($endpoint, $userId = null)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE endpoint = :endpoint";
            $params = ['endpoint' => $endpoint];
            
            if ($userId) {
                $sql .= " AND user_id = :user_id";
                $params['user_id'] = $userId;
            }
            
            $stmt = $this->getDb()->prepare($sql);
            
            return $stmt->execute($params);
            
        } catch (\PDOException $e) {
            $this->logError('Delete subscription failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Subscription sil (ID'ye göre)
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            
            $stmt = $this->getDb()->prepare($sql);
            
            return $stmt->execute(['id' => $id]);
            
        } catch (\PDOException $e) {
            $this->logError('Delete subscription failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kullanıcının tüm subscription'larını sil
     */
    public function deleteByUserId($userId)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id";
            
            $stmt = $this->getDb()->prepare($sql);
            
            return $stmt->execute(['user_id' => $userId]);
            
        } catch (\PDOException $e) {
            $this->logError('Delete user subscriptions failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Pasif subscription'ları devre dışı bırak
     */
    public function deactivate($id)
    {
        try {
            $sql = "UPDATE {$this->table} 
                    SET is_active = 0, 
                        updated_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->getDb()->prepare($sql);
            
            return $stmt->execute(['id' => $id]);
            
        } catch (\PDOException $e) {
            $this->logError('Deactivate subscription failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Son kullanma tarihini güncelle
     */
    public function updateLastUsed($id)
    {
        try {
            $sql = "UPDATE {$this->table} 
                    SET last_used_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->getDb()->prepare($sql);
            
            return $stmt->execute(['id' => $id]);
            
        } catch (\PDOException $e) {
            $this->logError('Update last used failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eski ve kullanılmayan subscription'ları temizle
     * (30 günden uzun süredir kullanılmamış)
     */
    public function cleanupOldSubscriptions()
    {
        try {
            $sql = "DELETE FROM {$this->table} 
                    WHERE last_used_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
                    OR (last_used_at IS NULL AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY))";
            
            $stmt = $this->getDb()->query($sql);
            
            $deletedCount = $stmt->rowCount();
            
            $this->logInfo("Cleaned up {$deletedCount} old subscriptions");
            
            return $deletedCount;
            
        } catch (\PDOException $e) {
            $this->logError('Cleanup failed: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Kullanıcı istatistikleri
     */
    public function getUserStats($userId)
    {
        try {
            $sql = "SELECT 
                    COUNT(*) as total_subscriptions,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_subscriptions,
                    MAX(created_at) as last_subscription_date,
                    MAX(last_used_at) as last_notification_date
                FROM {$this->table}
                WHERE user_id = :user_id";
            
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            $this->logError('Get user stats failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Genel istatistikler
     */
    public function getStats()
    {
        try {
            $sql = "SELECT 
                    COUNT(*) as total_subscriptions,
                    COUNT(DISTINCT user_id) as total_users,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_subscriptions,
                    SUM(CASE WHEN created_at > DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as new_this_week,
                    SUM(CASE WHEN last_used_at > DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as used_this_week
                FROM {$this->table}";
            
            $stmt = $this->getDb()->query($sql);
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            $this->logError('Get stats failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Helper: Log error
     */
    private function logError($message)
    {
        error_log('[PushSubscription] ' . $message);
    }
    
    /**
     * Helper: Log info
     */
    private function logInfo($message)
    {
        error_log('[PushSubscription] ' . $message);
    }
}

