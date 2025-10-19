<?php
/**
 * Activity Controller - Saat Dilimi Sistemi
 * Etkinlik yönetimi için controller
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Activity;
use App\Models\ActivityArea;
use DateTime;
use DateInterval;

class ActivityController extends Controller
{
    private $activityModel;
    private $activityAreaModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->activityModel = new Activity();
        $this->activityAreaModel = new ActivityArea();
        
        // Giriş kontrolü
        if (!isLoggedIn()) {
            redirect('/login');
        }
    }
    
    /**
     * Etkinlik listesi sayfası
     */
    public function index()
    {
        try {
            // Sayfalama parametreleri
            $page = $_GET['page'] ?? 1;
            $perPage = 20;
            $offset = ($page - 1) * $perPage;
            
            // Filtreler
            $filters = [
                'area_id' => $_GET['area_id'] ?? null,
                'date_from' => $_GET['date_from'] ?? null,
                'date_to' => $_GET['date_to'] ?? null
            ];
            
            // Etkinlikleri getir (saat dilimleri ile birlikte)
            $activities = $this->activityModel->getActivitiesWithTimeSlots($filters, $perPage, $offset);
            
            // Activity Areas (filtre için)
            $activityAreas = $this->activityAreaModel->getAll();
            
            // Total count (sayfalama için)
            $totalCount = $this->activityModel->getTotalCount($filters);
            $totalPages = ceil($totalCount / $perPage);
            
            $data = [
                'activities' => $activities,
                'activityAreas' => $activityAreas,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalCount' => $totalCount,
                'filters' => $filters
            ];
            
            $this->view('activities/index', $data, 'main');
            
        } catch (\Exception $e) {
            error_log('Activity index error: ' . $e->getMessage());
            setFlashMessage('Veriler yüklenirken hata oluştu', 'error');
            $this->view('activities/index', ['activities' => [], 'activityAreas' => []], 'main');
        }
    }
    
    /**
     * Yeni etkinlik oluşturma sayfası
     */
    public function create()
    {
        try {
            // Activity Areas getir
            $activityAreas = $this->activityAreaModel->where(['is_active' => 1]);
            
            // Tüm saat dilimlerini getir
            $timeSlots = $this->activityModel->getAllTimeSlots();
            
            $data = [
                'activityAreas' => $activityAreas,
                'timeSlots' => $timeSlots
            ];
            
            $this->view('activities/create', $data, 'main');
            
        } catch (\Exception $e) {
            error_log('Activity create page error: ' . $e->getMessage());
            setFlashMessage('Sayfa yüklenirken hata oluştu', 'error');
            redirect('/activities');
        }
    }
    
    /**
     * Yeni rezervasyon kaydet - Saat Dilimi Sistemi
     * POST /activities
     */
    public function store()
    {
        error_log('=== SAAT DİLİMİ SİSTEMİ REZERVASYON ===');
        error_log('POST Data: ' . print_r($_POST, true));
        
        // Permission kontrolü
        if (!hasPermission('activities', 'can_create')) {
            error_log('Permission denied for user: ' . getCurrentUserId());
            setFlashMessage('Bu işlem için yetkiniz yok', 'error');
            redirect('/activities');
            return;
        }

        try {
            // CSRF kontrolü
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                error_log('CSRF token verification failed');
                setFlashMessage('Geçersiz istek', 'error');
                redirect('/activities/create');
                return;
            }

            // Validasyon
            $errors = [];
            
            if (empty($_POST['activity_area_id'])) {
                $errors[] = 'Etkinlik alanı gerekli';
            }
            
            if (empty($_POST['activity_date'])) {
                $errors[] = 'Etkinlik tarihi gerekli';
            }
            
            if (empty($_POST['activity_name'])) {
                $errors[] = 'Etkinlik adı gerekli';
            }
            
            // Saat dilimleri kontrolü
            if (empty($_POST['time_slots']) || !is_array($_POST['time_slots'])) {
                $errors[] = 'En az bir saat dilimi seçmelisiniz';
            }
            
            if (!empty($errors)) {
                setFlashMessage(implode(', ', $errors), 'error');
                redirect('/activities/create');
                return;
            }

            // Form verilerini al
            $areaId = (int)$_POST['activity_area_id'];
            $activityDate = $_POST['activity_date'];
            $timeSlotIds = array_map('intval', $_POST['time_slots']);
            $tekrarDurumu = $_POST['tekrar_durumu'] ?? 'hayir';

            // Etkinlik verilerini hazırla
            $activityData = [
                'area_id' => $areaId,
                'activity_date' => $activityDate,
                'activity_name' => sanitizeInput($_POST['activity_name']),
                'staff' => sanitizeInput($_POST['staff'] ?? ''),
                'notes' => sanitizeInput($_POST['notes'] ?? ''),
                'created_by' => getCurrentUserId(),
                'uses_time_slots' => 1
            ];

            error_log('Seçilen saat dilimleri: ' . implode(', ', $timeSlotIds));

            if ($tekrarDurumu === 'hayir') {
                // Tek seferlik etkinlik
                error_log('Tek seferlik etkinlik oluşturuluyor...');
                
                $activityId = $this->activityModel->createWithTimeSlots($activityData, $timeSlotIds);
                
                if ($activityId) {
                    setFlashMessage('Etkinlik başarıyla oluşturuldu!', 'success');
                    error_log('Etkinlik oluşturuldu, ID: ' . $activityId);
                } else {
                    throw new \Exception('Etkinlik oluşturulamadı');
                }
                
            } else {
                // Tekrarlı etkinlik
                error_log('Tekrarlı etkinlik oluşturuluyor...');
                
                $tekrarTuru = $_POST['tekrar_turu'] ?? '';
                
                // Tarih listesi oluştur
                $dateList = $this->generateDateList($activityDate, $tekrarTuru, $_POST);
                
                if (empty($dateList)) {
                    throw new \Exception('Tekrar tarihleri oluşturulamadı');
                }
                
                error_log('Oluşturulan tarih sayısı: ' . count($dateList));
                
                // Tekrarlı etkinlik oluştur
                $result = $this->activityModel->createRecurringWithTimeSlots($activityData, $timeSlotIds, $dateList);
                
                if ($result['success']) {
                    $message = sprintf(
                        '%d etkinlik oluşturuldu. %d tarih çakışma nedeniyle atlandı.',
                        $result['created_count'],
                        $result['skipped_count']
                    );
                    setFlashMessage($message, 'success');
                    error_log('Tekrarlı etkinlik sonucu: ' . print_r($result, true));
                } else {
                    throw new \Exception($result['error'] ?? 'Tekrarlı etkinlik oluşturulamadı');
                }
            }

            redirect('/activities');

        } catch (\Exception $e) {
            error_log('Etkinlik oluşturma hatası: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            setFlashMessage('Hata: ' . $e->getMessage(), 'error');
            redirect('/activities/create');
        }
    }
    
    /**
     * Tarih listesi oluştur (tekrar için)
     */
    private function generateDateList($startDate, $recurrenceType, $postData)
    {
        $dates = [];
        $start = new DateTime($startDate);
        
        switch ($recurrenceType) {
            case 'gunluk_3':
                for ($i = 1; $i <= 3; $i++) {
                    $date = clone $start;
                    $date->add(new DateInterval('P' . $i . 'D'));
                    $dates[] = $date->format('Y-m-d');
                }
                break;
                
            case 'gunluk_7':
                for ($i = 1; $i <= 7; $i++) {
                    $date = clone $start;
                    $date->add(new DateInterval('P' . $i . 'D'));
                    $dates[] = $date->format('Y-m-d');
                }
                break;
                
            case 'haftalik_3':
                for ($i = 1; $i <= 3; $i++) {
                    $date = clone $start;
                    $date->add(new DateInterval('P' . $i . 'W'));
                    $dates[] = $date->format('Y-m-d');
                }
                break;
                
            case 'belirli_gunler':
                if (!empty($postData['secilen_gunler']) && !empty($postData['hafta_sayisi'])) {
                    $selectedDays = array_map('intval', $postData['secilen_gunler']);
                    $weekCount = (int)$postData['hafta_sayisi'];
                    
                    for ($week = 0; $week < $weekCount; $week++) {
                        foreach ($selectedDays as $dayOfWeek) {
                            $date = clone $start;
                            $date->add(new DateInterval('P' . $week . 'W'));
                            
                            // Haftanın günü ayarla (1=Pazartesi, 7=Pazar)
                            $currentDayOfWeek = $date->format('N');
                            $diff = $dayOfWeek - $currentDayOfWeek;
                            if ($diff != 0) {
                                $date->add(new DateInterval('P' . $diff . 'D'));
                            }
                            
                            // Başlangıç tarihinden sonra olmalı
                            if ($date > $start) {
                                $dates[] = $date->format('Y-m-d');
                            }
                        }
                    }
                }
                break;
                
            case 'ay_sonu':
                $endOfMonth = clone $start;
                $endOfMonth->modify('last day of this month');
                
                $current = clone $start;
                while ($current < $endOfMonth) {
                    $current->add(new DateInterval('P1D'));
                    $dates[] = $current->format('Y-m-d');
                }
                break;
                
            case 'tarih_araligi':
                if (!empty($postData['bitis_tarihi'])) {
                    $endDate = new DateTime($postData['bitis_tarihi']);
                    
                    $current = clone $start;
                    while ($current < $endDate) {
                        $current->add(new DateInterval('P1D'));
                        $dates[] = $current->format('Y-m-d');
                    }
                }
                break;
        }
        
        return array_unique($dates);
    }
    
    // =====================
    // API ENDPOINTS
    // =====================
    
    /**
     * Belirli tarih ve alan için boş saat dilimlerini getir
     */
    public function getAvailableTimeSlots()
    {
        try {
            $areaId = $_GET['area_id'] ?? null;
            $date = $_GET['date'] ?? null;
            
            if (!$areaId || !$date) {
                return $this->json([
                    'success' => false,
                    'error' => 'Alan ID ve tarih gerekli'
                ]);
            }
            
            $availableSlots = $this->activityModel->getAvailableTimeSlots($areaId, $date);
            $freeSlots = array_filter($availableSlots, function($slot) {
                return $slot['is_reserved'] == 0;
            });
            
            return $this->json([
                'success' => true,
                'all_slots' => $availableSlots,
                'free_slots' => array_values($freeSlots),
                'reserved_count' => count($availableSlots) - count($freeSlots),
                'free_count' => count($freeSlots)
            ]);
            
        } catch (\Exception $e) {
            error_log('API getAvailableTimeSlots error: ' . $e->getMessage());
            return $this->json([
                'success' => false,
                'error' => 'Saat dilimleri alınamadı'
            ]);
        }
    }
    
    /**
     * Saat dilimi çakışma kontrolü
     */
    public function checkTimeSlotsConflict()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $areaId = $input['area_id'] ?? $_POST['area_id'] ?? null;
            $date = $input['date'] ?? $_POST['date'] ?? null;
            $timeSlotIds = $input['time_slot_ids'] ?? $_POST['time_slot_ids'] ?? [];
            
            if (!$areaId || !$date || empty($timeSlotIds)) {
                return $this->json([
                    'success' => false,
                    'error' => 'Eksik parametreler'
                ]);
            }
            
            if (is_string($timeSlotIds)) {
                $timeSlotIds = json_decode($timeSlotIds, true);
            }
            
            $conflictResult = $this->activityModel->checkTimeSlotsConflict($areaId, $date, $timeSlotIds);
            
            return $this->json([
                'success' => true,
                'has_conflict' => $conflictResult['has_conflict'],
                'conflicts' => $conflictResult['conflicts'],
                'conflict_count' => $conflictResult['conflict_count']
            ]);
            
        } catch (\Exception $e) {
            error_log('API checkTimeSlotsConflict error: ' . $e->getMessage());
            return $this->json([
                'success' => false,
                'error' => 'Çakışma kontrolü yapılamadı'
            ]);
        }
    }
}
?>