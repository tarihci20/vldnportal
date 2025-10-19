<?php
/**
 * Push Notification Controller
 * 
 * Web Push bildirimleri için VAPID key yönetimi ve bildirim gönderimi
 * 
 * Gerekli: composer require minishlink/web-push
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationController extends Controller
{
    private $vapidPublicKey;
    private $vapidPrivateKey;
    private $webPush;
    
    public function __construct()
    {
        parent::__construct();
        
        // VAPID keys'leri config'den al veya oluştur
        $this->vapidPublicKey = getenv('VAPID_PUBLIC_KEY') ?: $this->generateVapidKeys()['publicKey'];
        $this->vapidPrivateKey = getenv('VAPID_PRIVATE_KEY') ?: $this->generateVapidKeys()['privateKey'];
        
        // WebPush instance'ı oluştur
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => getenv('APP_URL') ?: 'mailto:info@vildanportal.com',
                'publicKey' => $this->vapidPublicKey,
                'privateKey' => $this->vapidPrivateKey,
            ]
        ]);
    }
    
    /**
     * VAPID Public Key'i döndür (istemci tarafı için)
     */
    public function getVapidPublicKey()
    {
        $this->jsonResponse([
            'success' => true,
            'publicKey' => $this->vapidPublicKey
        ]);
    }
    
    /**
     * Push subscription kaydet
     */
    public function subscribe()
    {
        try {
            // Kullanıcı giriş kontrolü
            if (!$this->isAuthenticated()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }
            
            // POST verisini al
            $json = file_get_contents('php://input');
            $subscriptionData = json_decode($json, true);
            
            if (!$subscriptionData) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Invalid subscription data'
                ], 400);
                return;
            }
            
            // Subscription'ı veritabanına kaydet
            $subscription = new PushSubscription();
            $result = $subscription->create([
                'user_id' => $_SESSION['user_id'],
                'endpoint' => $subscriptionData['endpoint'],
                'public_key' => $subscriptionData['keys']['p256dh'],
                'auth_token' => $subscriptionData['keys']['auth'],
                'content_encoding' => $subscriptionData['contentEncoding'] ?? 'aes128gcm',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
            ]);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Subscription saved successfully'
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Failed to save subscription'
                ], 500);
            }
            
        } catch (\Exception $e) {
            $this->logError('Push subscription failed: ' . $e->getMessage());
            $this->jsonResponse([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
    
    /**
     * Subscription'ı sil
     */
    public function unsubscribe()
    {
        try {
            if (!$this->isAuthenticated()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }
            
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $endpoint = $data['endpoint'] ?? null;
            
            if (!$endpoint) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Endpoint required'
                ], 400);
                return;
            }
            
            $subscription = new PushSubscription();
            $result = $subscription->deleteByEndpoint($endpoint, $_SESSION['user_id']);
            
            $this->jsonResponse([
                'success' => $result,
                'message' => $result ? 'Subscription removed' : 'Subscription not found'
            ]);
            
        } catch (\Exception $e) {
            $this->logError('Push unsubscribe failed: ' . $e->getMessage());
            $this->jsonResponse([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
    
    /**
     * Tek bir kullanıcıya bildirim gönder
     */
    public function sendToUser($userId, $notification)
    {
        try {
            $subscription = new PushSubscription();
            $subscriptions = $subscription->getByUserId($userId);
            
            if (empty($subscriptions)) {
                return [
                    'success' => false,
                    'message' => 'No subscriptions found for user'
                ];
            }
            
            $successCount = 0;
            $failureCount = 0;
            
            foreach ($subscriptions as $sub) {
                $pushSubscription = Subscription::create([
                    'endpoint' => $sub['endpoint'],
                    'publicKey' => $sub['public_key'],
                    'authToken' => $sub['auth_token'],
                    'contentEncoding' => $sub['content_encoding']
                ]);
                
                $payload = json_encode($notification);
                
                $result = $this->webPush->sendOneNotification(
                    $pushSubscription,
                    $payload
                );
                
                if ($result->isSuccess()) {
                    $successCount++;
                } else {
                    $failureCount++;
                    
                    // Expired subscription'ları temizle
                    if ($result->isSubscriptionExpired()) {
                        $subscription->delete($sub['id']);
                    }
                }
            }
            
            return [
                'success' => $successCount > 0,
                'sent' => $successCount,
                'failed' => $failureCount
            ];
            
        } catch (\Exception $e) {
            $this->logError('Send notification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Birden fazla kullanıcıya bildirim gönder
     */
    public function sendToMultipleUsers($userIds, $notification)
    {
        $results = [
            'total' => count($userIds),
            'success' => 0,
            'failed' => 0
        ];
        
        foreach ($userIds as $userId) {
            $result = $this->sendToUser($userId, $notification);
            
            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }
        
        return $results;
    }
    
    /**
     * Tüm kullanıcılara broadcast bildirim gönder
     */
    public function broadcast($notification)
    {
        try {
            $subscription = new PushSubscription();
            $allSubscriptions = $subscription->getAll();
            
            if (empty($allSubscriptions)) {
                return [
                    'success' => false,
                    'message' => 'No subscriptions found'
                ];
            }
            
            $notifications = [];
            
            foreach ($allSubscriptions as $sub) {
                $pushSubscription = Subscription::create([
                    'endpoint' => $sub['endpoint'],
                    'publicKey' => $sub['public_key'],
                    'authToken' => $sub['auth_token'],
                    'contentEncoding' => $sub['content_encoding']
                ]);
                
                $notifications[] = [
                    'subscription' => $pushSubscription,
                    'payload' => json_encode($notification)
                ];
            }
            
            // Toplu gönderim
            $results = $this->webPush->sendNotifications($notifications);
            
            $successCount = 0;
            $failureCount = 0;
            
            foreach ($results as $result) {
                if ($result->isSuccess()) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            }
            
            return [
                'success' => true,
                'sent' => $successCount,
                'failed' => $failureCount,
                'total' => count($notifications)
            ];
            
        } catch (\Exception $e) {
            $this->logError('Broadcast failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Özel bildirim şablonları
     */
    
    /**
     * Etkinlik hatırlatma bildirimi
     */
    public function sendActivityReminder($userId, $activity)
    {
        $notification = [
            'title' => 'Etkinlik Hatırlatması',
            'body' => "{$activity['title']} etkinliği {$activity['time']} dakika sonra başlayacak",
            'icon' => '/manifest/icons/icon-192x192.png',
            'badge' => '/manifest/icons/badge-72x72.png',
            'tag' => 'activity-reminder-' . $activity['id'],
            'requireInteraction' => true,
            'data' => [
                'type' => 'activity_reminder',
                'activity_id' => $activity['id'],
                'url' => '/activities/calendar'
            ],
            'actions' => [
                [
                    'action' => 'view',
                    'title' => 'Görüntüle'
                ],
                [
                    'action' => 'dismiss',
                    'title' => 'Kapat'
                ]
            ]
        ];
        
        return $this->sendToUser($userId, $notification);
    }
    
    /**
     * Etüt başvurusu onay bildirimi
     */
    public function sendEtutApproval($userId, $etut)
    {
        $notification = [
            'title' => 'Etüt Başvurunuz Onaylandı',
            'body' => "Öğrenci: {$etut['student_name']} - {$etut['date']} tarihli etüt başvurunuz onaylandı",
            'icon' => '/manifest/icons/icon-192x192.png',
            'badge' => '/manifest/icons/badge-72x72.png',
            'tag' => 'etut-approval-' . $etut['id'],
            'data' => [
                'type' => 'etut_approval',
                'etut_id' => $etut['id'],
                'url' => '/etut'
            ],
            'actions' => [
                [
                    'action' => 'view',
                    'title' => 'Detayları Gör'
                ]
            ]
        ];
        
        return $this->sendToUser($userId, $notification);
    }
    
    /**
     * Sistem bildirimi (tüm kullanıcılar)
     */
    public function sendSystemNotification($message, $priority = 'normal')
    {
        $notification = [
            'title' => 'Sistem Bildirimi',
            'body' => $message,
            'icon' => '/manifest/icons/icon-192x192.png',
            'badge' => '/manifest/icons/badge-72x72.png',
            'tag' => 'system-notification-' . time(),
            'requireInteraction' => $priority === 'high',
            'data' => [
                'type' => 'system_notification',
                'priority' => $priority,
                'url' => '/'
            ]
        ];
        
        return $this->broadcast($notification);
    }
    
    /**
     * VAPID key'leri oluştur (ilk kurulum için)
     */
    private function generateVapidKeys()
    {
        // Web-push library kullanarak key oluştur
        if (class_exists('Minishlink\WebPush\VAPID')) {
            $keys = \Minishlink\WebPush\VAPID::createVapidKeys();
            
            // .env dosyasına ekle
            $this->saveToEnv('VAPID_PUBLIC_KEY', $keys['publicKey']);
            $this->saveToEnv('VAPID_PRIVATE_KEY', $keys['privateKey']);
            
            return $keys;
        }
        
        throw new \Exception('VAPID keys could not be generated');
    }
    
    /**
     * .env dosyasına kaydet
     */
    private function saveToEnv($key, $value)
    {
        $envFile = dirname(__DIR__, 2) . '/.env';
        
        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            
            if (strpos($envContent, $key) === false) {
                file_put_contents($envFile, PHP_EOL . "$key=$value", FILE_APPEND);
            }
        }
    }
    
    /**
     * Test bildirimi gönder (development için)
     */
    public function sendTestNotification()
    {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
            return;
        }
        
        $notification = [
            'title' => 'Test Bildirimi',
            'body' => 'Bu bir test bildirimidir. PWA bildirimleriniz çalışıyor! 🎉',
            'icon' => '/manifest/icons/icon-192x192.png',
            'badge' => '/manifest/icons/badge-72x72.png',
            'tag' => 'test-notification',
            'requireInteraction' => false,
            'data' => [
                'type' => 'test',
                'url' => '/'
            ],
            'actions' => [
                [
                    'action' => 'open',
                    'title' => 'Aç'
                ],
                [
                    'action' => 'close',
                    'title' => 'Kapat'
                ]
            ]
        ];
        
        $result = $this->sendToUser($_SESSION['user_id'], $notification);
        
        $this->jsonResponse($result);
    }
    
    /**
     * Helper: Authentication kontrolü
     */
    private function isAuthenticated()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Helper: JSON response
     */
    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Helper: Error logging
     */
    private function logError($message)
    {
        error_log('[PushNotification] ' . $message);
    }
}