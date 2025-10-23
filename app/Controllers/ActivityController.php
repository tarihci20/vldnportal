<?php
/**
 * Activity Controller - Saat Dilimi Sistemi
 * Etkinlik yönetimi için controller
 */

namespace App\Controllers;

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

use Core\Controller;
use App\Models\Activity;
use App\Models\ActivityArea;
use App\Models\TimeSlot;
use DateTime;
use DateInterval;

class ActivityController extends Controller
{
    private $activityModel;
    private $activityAreaModel;
    private $timeSlotModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->activityModel = new Activity();
        $this->activityAreaModel = new ActivityArea();
        $this->timeSlotModel = new TimeSlot();
        
        // Giriş kontrolü
        if (!isloggedin()) {
            redirect('/login');
        }
    }
    
    /**
     * Etkinlik listesi sayfası
     */
    public function index()
    {
        try {
            // Permission kontrolü - etkinlik sayfası yetkisi gerekli
            if (!hasPermission('activities', 'can_view')) {
                setFlashMessage('Bu sayfaya erişim yetkiniz bulunmamaktadır.', 'error');
                // Çoğu rol için home'a yönlendir
                $user = currentUser();
                $role = $user['role_slug'] ?? $user['role'] ?? 'user';
                if ($role === 'teacher') {
                    redirect('/students');
                } else {
                    redirect('/');
                }
                exit;
            }
            
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
            $activityAreas = $this->activityAreaModel->getActive();
            
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
            \setFlashMessage('Veriler yüklenirken hata oluştu', 'error');
            $this->view('activities/index', ['activities' => [], 'activityAreas' => []], 'main');
        }
    }
    
    /**
     * Yeni etkinlik oluşturma sayfası
     */
    public function create()
    {
        try {
            // Permission kontrolü - geçici olarak her zaman true
            // TODO: hasPermission() fonksiyonu implement edilecek
            /* if (!hasPermission('activities', 'can_create')) {
                \setFlashMessage('Bu işlem için yetkiniz yok', 'error');
                \redirect('/activities');
                return;
            } */
            
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
            \setFlashMessage('Sayfa yüklenirken hata oluştu', 'error');
            \redirect('/activities');
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
        
        // Permission kontrolü - geçici olarak devre dışı
        /* if (!hasPermission('activities', 'can_create')) {
            error_log('Permission denied for user: ' . getuserid());
            return $this->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz yok'
            ], 403);
        } */

        try {
            // CSRF kontrolü (küçük harfle)
            if (!verifycsrftoken($_POST['csrf_token'] ?? '')) {
                error_log('CSRF token verification failed');
                return $this->json([
                    'success' => false,
                    'message' => 'Geçersiz istek - CSRF token eşleşmiyor'
                ], 403);
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
                return $this->json([
                    'success' => false,
                    'message' => implode(', ', $errors),
                    'errors' => $errors
                ], 400);
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
                'activity_name' => trim($_POST['activity_name']),
                'responsible_person' => trim($_POST['staff'] ?? ''),
                'notes' => trim($_POST['notes'] ?? ''),
                'created_by' => getuserid(),
                'uses_time_slots' => 1
            ];

            error_log('Seçilen saat dilimleri: ' . implode(', ', $timeSlotIds));

            if ($tekrarDurumu === 'hayir') {
                // Tek seferlik etkinlik
                error_log('Tek seferlik etkinlik oluşturuluyor...');
                
                $activityId = $this->activityModel->createWithTimeSlots($activityData, $timeSlotIds);
                
                if ($activityId) {
                    error_log('Etkinlik oluşturuldu, ID: ' . $activityId);
                    return $this->json([
                        'success' => true,
                        'message' => '✅ Etkinlik başarıyla oluşturuldu!',
                        'activity_id' => $activityId
                    ]);
                } else {
                    throw new \Exception('Etkinlik oluşturulamadı - createWithTimeSlots false döndü');
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
                        '✅ Tebrikler! %d etkinlik başarıyla oluşturuldu.',
                        $result['created_count']
                    );
                    error_log('Tekrarlı etkinlik sonucu: ' . print_r($result, true));
                    return $this->json([
                        'success' => true,
                        'message' => $message,
                        'created_count' => $result['created_count']
                    ]);
                } else {
                    throw new \Exception($result['error'] ?? 'Tekrarlı etkinlik oluşturulamadı');
                }
            }

        } catch (\Exception $e) {
            // DETAYLI HATA MESAJI
            error_log('=== ETKİNLİK OLUŞTURMA HATASI ===');
            error_log('Hata mesajı: ' . $e->getMessage());
            error_log('Dosya: ' . $e->getFile());
            error_log('Satır: ' . $e->getLine());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            // Hata mesajını temizle (HTML etiketleri ve yeni satırlar)
            $cleanMessage = strip_tags($e->getMessage());
            $cleanMessage = str_replace(["\n", "\r", "<br>", "<br/>", "<br />"], ' ', $cleanMessage);
            $cleanMessage = preg_replace('/\s+/', ' ', $cleanMessage);
            $cleanMessage = trim($cleanMessage);
            
            // Kullanıcı dostu mesaj
            if (empty($cleanMessage)) {
                $cleanMessage = 'Etkinlik oluşturulamadı. Lütfen tekrar deneyin.';
            }
            
            // JSON olarak temiz hata döndür
            return $this->json([
                'success' => false,
                'message' => $cleanMessage,
                'error' => $cleanMessage
            ], 500);
        }
    }
    
    /**
     * Tarih listesi oluştur (tekrar için)
     */
    private function generateDateList($startDate, $recurrenceType, $postData)
    {
        $dates = [];
        $start = new DateTime($startDate);
        
        // Başlangıç tarihini ekle
        $dates[] = $start->format('Y-m-d');
        
        switch ($recurrenceType) {
            case 'gunluk_2':
                for ($i = 1; $i <= 2; $i++) {
                    $date = clone $start;
                    $date->add(new DateInterval('P' . $i . 'D'));
                    $dates[] = $date->format('Y-m-d');
                }
                break;
                
            case 'gunluk_3':
                for ($i = 1; $i <= 3; $i++) {
                    $date = clone $start;
                    $date->add(new DateInterval('P' . $i . 'D'));
                    $dates[] = $date->format('Y-m-d');
                }
                break;
                
            case 'gunluk_4':
                for ($i = 1; $i <= 4; $i++) {
                    $date = clone $start;
                    $date->add(new DateInterval('P' . $i . 'D'));
                    $dates[] = $date->format('Y-m-d');
                }
                break;
                
            case 'gunluk_5':
                for ($i = 1; $i <= 5; $i++) {
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
                
            case 'tarihe_kadar':
                if (!empty($postData['bitis_tarihi'])) {
                    $endDate = new DateTime($postData['bitis_tarihi']);
                    
                    $current = clone $start;
                    while ($current < $endDate) {
                        $current->add(new DateInterval('P1D'));
                        if ($current <= $endDate) {
                            $dates[] = $current->format('Y-m-d');
                        }
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
     * Export selected activities as XLSX
     */
    public function exportSelected()
    {
        if (!isAdmin()) {
            http_response_code(403); echo 'Yetkisiz'; exit;
        }

        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids) || empty($ids)) {
            setFlashMessage('Önce etkinlik seçin', 'error');
            redirect('/activities');
            return;
        }

        $rows = $this->activityModel->getByIds($ids);

        try {
            while (ob_get_level()) ob_end_clean();
            $prevDisplayErrors = ini_get('display_errors');
            $prevErrorReporting = error_reporting();
            ini_set('display_errors', '0');
            error_reporting(0);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['TARİH','SAAT','ALAN','ETKİNLİK ADI','SORUMLU KİŞİ','Oluşturan','DURUM'];
            $sheet->fromArray($headers, null, 'A1');

            $rowNum = 2;
            foreach ($rows as $r) {
                $date = $r['activity_date'] ?? '';
                $time = trim(($r['start_time'] ?? '') . ' - ' . ($r['end_time'] ?? ''));
                $area = $r['area_name'] ?? '';
                $name = $r['activity_name'] ?? '';
                $responsible = $r['responsible_person'] ?? '';
                $creator = $r['created_by_name'] ?? '';
                $status = $r['status'] ?? 'scheduled';
                $statusText = ($status === 'completed') ? 'Tamamlandı' : (($status === 'cancelled') ? 'İptal Edildi' : 'Planlandı');

                $sheet->fromArray([$date, $time, $area, $name, $responsible, $creator, $statusText], null, 'A' . $rowNum);
                $rowNum++;
            }

            foreach (range('A', $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $fileName = 'etkinlikler_' . date('Ymd_His') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');

            ini_set('display_errors', $prevDisplayErrors);
            error_reporting($prevErrorReporting);

            exit;
        } catch (\Exception $e) {
            ini_set('display_errors', $prevDisplayErrors ?? '1');
            error_reporting($prevErrorReporting ?? E_ALL);
            error_log('[Activity exportSelected] Exception: ' . $e->getMessage());
            setFlashMessage('Excel oluşturulurken hata: ' . $e->getMessage(), 'error');
            redirect('/activities');
        }
    }

    /**
     * Export activities by date
     */
    public function exportByDate()
    {
        if (!isAdmin()) { http_response_code(403); echo 'Yetkisiz'; exit; }

        $date = $_POST['date'] ?? $_GET['date'] ?? null;
        if (!$date) { setFlashMessage('Tarih seçin', 'error'); redirect('/activities'); return; }

        $rows = $this->activityModel->getByDate($date);

        try {
            while (ob_get_level()) ob_end_clean();
            $prevDisplayErrors = ini_get('display_errors');
            $prevErrorReporting = error_reporting();
            ini_set('display_errors', '0');
            error_reporting(0);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['TARİH','SAAT','ALAN','ETKİNLİK ADI','SORUMLU KİŞİ','Oluşturan','DURUM'];
            $sheet->fromArray($headers, null, 'A1');

            $rowNum = 2;
            foreach ($rows as $r) {
                $date = $r['activity_date'] ?? '';
                $time = trim(($r['start_time'] ?? '') . ' - ' . ($r['end_time'] ?? ''));
                $area = $r['area_name'] ?? '';
                $name = $r['activity_name'] ?? '';
                $responsible = $r['responsible_person'] ?? '';
                $creator = $r['created_by_name'] ?? '';
                $status = $r['status'] ?? 'scheduled';
                $statusText = ($status === 'completed') ? 'Tamamlandı' : (($status === 'cancelled') ? 'İptal Edildi' : 'Planlandı');

                $sheet->fromArray([$date, $time, $area, $name, $responsible, $creator, $statusText], null, 'A' . $rowNum);
                $rowNum++;
            }

            foreach (range('A', $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $fileName = 'etkinlikler_' . $date . '_' . date('Ymd_His') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');

            ini_set('display_errors', $prevDisplayErrors);
            error_reporting($prevErrorReporting);

            exit;
        } catch (\Exception $e) {
            ini_set('display_errors', $prevDisplayErrors ?? '1');
            error_reporting($prevErrorReporting ?? E_ALL);
            error_log('[Activity exportByDate] Exception: ' . $e->getMessage());
            setFlashMessage('Excel oluşturulurken hata: ' . $e->getMessage(), 'error');
            redirect('/activities');
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
    
    /**
     * Etkinlik detaylarını getir
     */
    public function show($id)
    {
        try {
            $activity = $this->activityModel->findWithDetails($id);
            
            if (!$activity) {
                return $this->json([
                    'success' => false,
                    'error' => 'Etkinlik bulunamadı'
                ], 404);
            }
            
            return $this->json([
                'success' => true,
                'activity' => $activity
            ]);
            
        } catch (\Exception $e) {
            error_log('Activity show error: ' . $e->getMessage());
            return $this->json([
                'success' => false,
                'error' => 'Etkinlik yüklenemedi'
            ], 500);
        }
    }
    
    /**
     * Etkinliği sil
     */
    public function delete($id)
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // CSRF kontrolü (küçük harfle)
            if (!verifycsrftoken($input['csrf_token'] ?? '')) {
                return $this->json([
                    'success' => false,
                    'error' => 'Geçersiz istek'
                ], 403);
            }
            
            $activity = $this->activityModel->find($id);
            
            if (!$activity) {
                return $this->json([
                    'success' => false,
                    'error' => 'Etkinlik bulunamadı'
                ], 404);
            }
            
            // Silme yetkisi kontrolü
            $userId = getuserid();
            $userRole = getUserSession('role_id');
            
            if ($userRole != 1 && $activity['created_by'] != $userId) {
                return $this->json([
                    'success' => false,
                    'error' => 'Bu etkinliği silme yetkiniz yok'
                ], 403);
            }
            
            $result = $this->activityModel->deleteWithTimeSlots($id);
            
            if ($result) {
                return $this->json([
                    'success' => true,
                    'message' => 'Etkinlik başarıyla silindi'
                ]);
            } else {
                return $this->json([
                    'success' => false,
                    'error' => 'Silme işlemi başarısız'
                ], 500);
            }
            
        } catch (\Exception $e) {
            error_log('Activity delete error: ' . $e->getMessage());
            return $this->json([
                'success' => false,
                'error' => 'Bir hata oluştu'
            ], 500);
        }
    }
    
    /**
     * Toplu etkinlik silme
     */
    public function bulkDelete()
    {
        error_log('=== BULK DELETE BAŞLADI ===');
        
        try {
            if (!isAdmin()) {
                return $this->json([
                    'success' => false,
                    'error' => 'Bu işlem için admin yetkisi gerekli'
                ], 403);
            }
            
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!isset($data['csrf_token'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'CSRF token eksik'
                ], 403);
            }
            
            // CSRF kontrolü (küçük harfle)
            if (!validatecsrftoken($data['csrf_token'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Geçersiz CSRF token'
                ], 403);
            }
            
            $activityIds = $data['activity_ids'] ?? [];
            
            if (empty($activityIds) || !is_array($activityIds)) {
                return $this->json([
                    'success' => false,
                    'error' => 'Etkinlik ID\'leri gerekli'
                ]);
            }
            
            $deletedCount = 0;
            $errors = [];
            
            foreach ($activityIds as $id) {
                try {
                    $result = $this->activityModel->deleteWithTimeSlots($id);
                    if ($result) {
                        $deletedCount++;
                    } else {
                        $errors[] = "ID $id silinemedi";
                    }
                } catch (\Exception $e) {
                    $errors[] = "ID $id: " . $e->getMessage();
                }
            }
            
            if ($deletedCount > 0) {
                return $this->json([
                    'success' => true,
                    'deleted_count' => $deletedCount,
                    'errors' => $errors
                ]);
            } else {
                return $this->json([
                    'success' => false,
                    'error' => 'Hiçbir etkinlik silinemedi',
                    'errors' => $errors
                ], 500);
            }
            
        } catch (\Exception $e) {
            error_log('Bulk delete exception: ' . $e->getMessage());
            return $this->json([
                'success' => false,
                'error' => 'Toplu silme işlemi başarısız: ' . $e->getMessage()
            ], 500);
        }
    }
}