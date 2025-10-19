<?php
/**
 * PWA Cron Jobs
 * 
 * Zamanlanmış bildirimler ve otomatik temizlik işlemleri
 * 
 * Crontab ayarı:
 * */5 * * * * php /path/to/pwa-cron-jobs.php >> /var/log/pwa-cron.log 2>&1
 * 
 * Her 5 dakikada bir çalışır
 */

// CLI kontrolü
if (php_sapi_name() !== 'cli') {
    die("Bu script sadece komut satırından çalıştırılabilir.\n");
}

// Bootstrap
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

use App\Controllers\PushNotificationController;
use App\Models\PushSubscription;

// Log başlat
$logFile = __DIR__ . '/../storage/logs/pwa-cron.log';
$startTime = microtime(true);
logMessage("========== PWA Cron Job Started ==========");

try {
    // 1. Zamanlanmış bildirimleri gönder
    logMessage("Checking scheduled notifications...");
    processScheduledNotifications();
    
    // 2. Etkinlik hatırlatmalarını kontrol et
    logMessage("Checking activity reminders...");
    processActivityReminders();
    
    // 3. Eski subscription'ları temizle (günde 1 kez - 03:00)
    $currentHour = (int)date('H');
    if ($currentHour === 3) {
        logMessage("Running daily cleanup...");
        cleanupOldSubscriptions();
    }
    
    // 4. İstatistikleri güncelle
    updateStats();
    
    $endTime = microtime(true);
    $executionTime = round($endTime - $startTime, 2);
    
    logMessage("========== PWA Cron Job Completed (Execution time: {$executionTime}s) ==========\n");
    
} catch (Exception $e) {
    logMessage("ERROR: " . $e->getMessage());
    logMessage("Stack trace: " . $e->getTraceAsString());
    exit(1);
}

// ================== FUNCTIONS ==================

/**
 * Zamanlanmış bildirimleri işle
 */
function processScheduledNotifications() {
    global $db;
    
    try {
        // Bekleyen bildirimleri getir
        $stmt = $db->query("CALL sp_get_pending_notifications()");
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($notifications)) {
            logMessage("No scheduled notifications to send");
            return;
        }
        
        logMessage("Found " . count($notifications) . " scheduled notification(s)");
        
        $pushController = new PushNotificationController();
        $sentCount = 0;
        $failedCount = 0;
        
        foreach ($notifications as $notification) {
            try {
                // Kullanıcının bildirim tercihlerini kontrol et
                $shouldSend = checkUserPreferences(
                    $notification['user_id'],
                    $notification['notification_type'],
                    $notification['scheduled_for']
                );
                
                if (!$shouldSend) {
                    logMessage("⊘ Notification #{$notification['id']} skipped (user preferences)");
                    markNotificationCancelled($notification['id']);
                    continue;
                }
                
                // Bildirim verisini hazırla
                $notificationData = [
                    'title' => $notification['title'],
                    'body' => $notification['body'],
                    'icon' => '/manifest/icons/icon-192x192.png',
                    'badge' => '/manifest/icons/badge-72x72.png',
                    'tag' => $notification['notification_type'] . '-' . $notification['id'],
                    'data' => json_decode($notification['notification_data'], true) ?? []
                ];
                
                // Gönder
                if ($notification['user_id']) {
                    // Belirli kullanıcıya
                    $result = $pushController->sendToUser(
                        $notification['user_id'],
                        $notificationData
                    );
                } else {
                    // Broadcast (tüm kullanıcılar)
                    $result = $pushController->broadcast($notificationData);
                }
                
                if ($result['success']) {
                    $db->query("CALL sp_mark_notification_sent({$notification['id']})");
                    $sentCount++;
                    logMessage("✓ Sent notification #{$notification['id']}");
                } else {
                    $errorMsg = $db->quote($result['message'] ?? 'Unknown error');
                    $db->query("CALL sp_mark_notification_failed({$notification['id']}, {$errorMsg})");
                    $failedCount++;
                    logMessage("✗ Failed notification #{$notification['id']}: " . ($result['message'] ?? 'Unknown error'));
                }
                
            } catch (Exception $e) {
                $errorMsg = $db->quote($e->getMessage());
                $db->query("CALL sp_mark_notification_failed({$notification['id']}, {$errorMsg})");
                $failedCount++;
                logMessage("✗ Exception for notification #{$notification['id']}: " . $e->getMessage());
            }
        }
        
        logMessage("Notification processing complete: {$sentCount} sent, {$failedCount} failed");
        
    } catch (Exception $e) {
        logMessage("ERROR in processScheduledNotifications: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Etkinlik hatırlatmalarını kontrol et ve gönder
 */
function processActivityReminders() {
    global $db;
    
    try {
        // Önümüzdeki 1 saat içinde başlayacak etkinlikleri bul
        $stmt = $db->query("
            SELECT a.*, aa.name as area_name
            FROM activities a
            LEFT JOIN activity_areas aa ON a.area_id = aa.id
            WHERE a.start_datetime > NOW()
            AND a.start_datetime <= DATE_ADD(NOW(), INTERVAL 60 MINUTE)
            AND a.is_active = 1
            AND a.status = 'approved'
            ORDER BY a.start_datetime ASC
        ");
        
        $upcomingActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($upcomingActivities)) {
            logMessage("No upcoming activities for reminders");
            return;
        }
        
        logMessage("Found " . count($upcomingActivities) . " upcoming activity/activities");
        
        $pushController = new PushNotificationController();
        $sentCount = 0;
        
        foreach ($upcomingActivities as $activity) {
            try {
                // Bu etkinlik için daha önce hatırlatma gönderilmiş mi kontrol et
                $checkStmt = $db->prepare("
                    SELECT COUNT(*) FROM scheduled_notifications
                    WHERE reference_id = :activity_id
                    AND notification_type = 'activity_reminder'
                    AND status = 'sent'
                    AND sent_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ");
                $checkStmt->execute(['activity_id' => $activity['id']]);
                
                if ($checkStmt->fetchColumn() > 0) {
                    continue; // Zaten gönderilmiş
                }
                
                // Etkinliğe kayıtlı kullanıcıları al
                $participantsStmt = $db->prepare("
                    SELECT ap.*, u.id as user_id, u.email, u.full_name
                    FROM activity_participants ap
                    JOIN users u ON ap.user_id = u.id
                    WHERE ap.activity_id = :activity_id
                    AND ap.status = 'confirmed'
                ");
                $participantsStmt->execute(['activity_id' => $activity['id']]);
                $participants = $participantsStmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($participants)) {
                    continue;
                }
                
                // Kaç dakika sonra başlayacak
                $startTime = strtotime($activity['start_datetime']);
                $now = time();
                $minutesUntilStart = round(($startTime - $now) / 60);
                
                foreach ($participants as $participant) {
                    // Kullanıcının bildirim tercihlerini kontrol et
                    $prefsStmt = $db->prepare("
                        SELECT activity_reminders, reminder_time_before
                        FROM user_notification_preferences
                        WHERE user_id = :user_id
                    ");
                    $prefsStmt->execute(['user_id' => $participant['user_id']]);
                    $prefs = $prefsStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$prefs || !$prefs['activity_reminders']) {
                        continue; // Bu kullanıcı etkinlik hatırlatmalarını kapatmış
                    }
                    
                    // Hatırlatma zamanı kontrolü
                    $reminderTime = $prefs['reminder_time_before'] ?? 60;
                    if ($minutesUntilStart > $reminderTime) {
                        continue; // Henüz hatırlatma zamanı gelmedi
                    }
                    
                    // Hatırlatma bildirimi gönder
                    $result = $pushController->sendActivityReminder(
                        $participant['user_id'],
                        [
                            'id' => $activity['id'],
                            'title' => $activity['title'],
                            'time' => $minutesUntilStart
                        ]
                    );
                    
                    if ($result['success']) {
                        $sentCount++;
                        
                        // Zamanlanmış bildirim kaydı oluştur (tekrar gönderilmemesi için)
                        $insertStmt = $db->prepare("
                            INSERT INTO scheduled_notifications (
                                user_id, notification_type, reference_id,
                                title, body, scheduled_for, sent_at, status
                            ) VALUES (
                                :user_id, 'activity_reminder', :activity_id,
                                :title, :body, NOW(), NOW(), 'sent'
                            )
                        ");
                        $insertStmt->execute([
                            'user_id' => $participant['user_id'],
                            'activity_id' => $activity['id'],
                            'title' => 'Etkinlik Hatırlatması',
                            'body' => "{$activity['title']} etkinliği {$minutesUntilStart} dakika sonra başlayacak"
                        ]);
                    }
                }
                
                if ($sentCount > 0) {
                    logMessage("✓ Sent {$sentCount} reminder(s) for activity: {$activity['title']}");
                }
                
            } catch (Exception $e) {
                logMessage("✗ Failed to process activity #{$activity['id']}: " . $e->getMessage());
            }
        }
        
        logMessage("Activity reminders complete: {$sentCount} reminder(s) sent");
        
    } catch (Exception $e) {
        logMessage("ERROR in processActivityReminders: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Eski subscription'ları temizle
 */
function cleanupOldSubscriptions() {
    global $db;
    
    try {
        $stmt = $db->query("CALL sp_cleanup_old_subscriptions()");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        logMessage("Cleanup complete: " . $result['result']);
        
    } catch (Exception $e) {
        logMessage("ERROR in cleanupOldSubscriptions: " . $e->getMessage());
        throw $e;
    }
}

/**
 * İstatistikleri güncelle
 */
function updateStats() {
    try {
        $subscriptionModel = new PushSubscription();
        $stats = $subscriptionModel->getStats();
        
        logMessage("Current stats: " . json_encode($stats));
        
    } catch (Exception $e) {
        logMessage("ERROR in updateStats: " . $e->getMessage());
    }
}

/**
 * Kullanıcının bildirim tercihlerini kontrol et
 */
function checkUserPreferences($userId, $notificationType, $scheduledTime) {
    global $db;
    
    try {
        $stmt = $db->prepare("
            SELECT fn_should_send_notification(?, ?, ?) as should_send
        ");
        $stmt->execute([$userId, $notificationType, $scheduledTime]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (bool)$result['should_send'];
        
    } catch (Exception $e) {
        logMessage("ERROR checking user preferences: " . $e->getMessage());
        return true; // Hata durumunda gönder
    }
}

/**
 * Bildirimi iptal edildi olarak işaretle
 */
function markNotificationCancelled($notificationId) {
    global $db;
    
    try {
        $stmt = $db->prepare("
            UPDATE scheduled_notifications
            SET status = 'cancelled'
            WHERE id = :id
        ");
        $stmt->execute(['id' => $notificationId]);
        
    } catch (Exception $e) {
        logMessage("ERROR marking notification as cancelled: " . $e->getMessage());
    }
}

/**
 * Log mesajı yaz
 */
function logMessage($message) {
    global $logFile;
    
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$message}\n";
    
    // Console'a yaz
    echo $logEntry;
    
    // Dosyaya yaz
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}