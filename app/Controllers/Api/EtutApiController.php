<?php
/**
 * Etut API Controller
 * AJAX endpoints for etut (study hall) operations
 */

namespace App\Controllers\Api;

use Core\Controller;
use Core\Response;
use Core\Auth;
use App\Models\Etut;

class EtutApiController extends Controller
{
    private $etutModel;
    
    public function __construct() {
        parent::__construct();
        
        // Check authentication
        if (!Auth::check()) {
            // Log unauthorized API attempt for debugging (CSRF/session problems)
            try {
                $dbgPath = BASE_PATH . '/storage/logs/etut_status_debug.log';
                $info = [
                    'time' => date('Y-m-d H:i:s'),
                    'event' => 'unauthorized_constructor',
                    'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'cli',
                    'headers' => function_exists('getallheaders') ? getallheaders() : null,
                    'cookies' => $_COOKIE ?? null,
                    'method' => $_SERVER['REQUEST_METHOD'] ?? null,
                    'uri' => $_SERVER['REQUEST_URI'] ?? null
                ];
                @file_put_contents($dbgPath, json_encode($info, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
            } catch (\Throwable $t) {}

            $this->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
            exit;
        }
        
        $this->etutModel = new Etut();
    }
    
    /**
     * Get all etut applications
     * GET /api/etut?status=...&date_from=...&date_to=...
     */
    public function index() {
        try {
            $filters = [
                'status' => $_GET['status'] ?? null,
                'date_from' => $_GET['date_from'] ?? null,
                'date_to' => $_GET['date_to'] ?? null,
                'student_id' => $_GET['student_id'] ?? null
            ];
            
            $applications = $this->etutModel->getAll($filters);
            
            $this->json([
                'success' => true,
                'applications' => $applications,
                'count' => count($applications)
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Liste alınırken hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get etut application by ID
     * GET /api/etut/{id}
     */
    public function show($id) {
        try {
            $application = $this->etutModel->find($id);
            
            if (!$application) {
                $this->json([
                    'success' => false,
                    'message' => 'Başvuru bulunamadı'
                ], 404);
                return;
            }
            
            $this->json([
                'success' => true,
                'application' => $application
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create new etut application
     * POST /api/etut/create
     */
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = $this->validateEtut($data);
            if (!empty($errors)) {
                $this->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $errors
                ], 422);
                return;
            }
            
            // Check if student already has pending application
            $existing = $this->etutModel->findPendingByStudent($data['student_id'], $data['date']);
            if ($existing) {
                $this->json([
                    'success' => false,
                    'message' => 'Bu öğrencinin bu tarih için bekleyen bir başvurusu var'
                ], 422);
                return;
            }
            
            // Add created_by
            $data['created_by'] = Auth::id();
            $data['status'] = 'pending';
            
            // Create application
            $applicationId = $this->etutModel->create($data);
            
            $this->json([
                'success' => true,
                'message' => 'Etüt başvurusu oluşturuldu',
                'application_id' => $applicationId
            ], 201);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Oluşturma hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update etut application status
     * POST /api/etut/{id}/status
     */
    public function updateStatus($id) {
        try {
            // Debug: log incoming request for status changes
            try {
                $raw = file_get_contents('php://input');
                $dbgPath = BASE_PATH . '/storage/logs/etut_status_debug.log';
                $dbg = [
                    'time' => date('Y-m-d H:i:s'),
                    'id' => $id,
                    'raw' => $raw,
                    'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'cli',
                    'user_id' => Auth::check() ? Auth::id() : null
                ];
                @file_put_contents($dbgPath, json_encode($dbg, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
            } catch (\Throwable $t) {
                // ignore debug write failures
            }
            $application = $this->etutModel->find($id);
            if (!$application) {
                $this->json([
                    'success' => false,
                    'message' => 'Başvuru bulunamadı'
                ], 404);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            $status = $data['status'] ?? null;
            
            // Validate status - allow 'completed' which is used by the admin toggle
            $validStatuses = ['pending', 'approved', 'rejected', 'completed'];
            if (!in_array($status, $validStatuses)) {
                $this->json([
                    'success' => false,
                    'message' => 'Geçersiz durum'
                ], 400);
                return;
            }
            
            // Check authorization
            $user = Auth::user();
            if (!in_array($user['role_slug'], ['admin', 'mudur', 'mudur_yardimcisi'])) {
                $this->json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz yok'
                ], 403);
                return;
            }
            
            // Update status and manage approval metadata
            $updateData = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];

            if ($status === 'completed' || $status === 'approved') {
                $updateData['approved_by'] = Auth::id();
                $updateData['updated_at'] = date('Y-m-d H:i:s');
            } else {
                // Clear approval field when reverting to pending or other non-approved states
                $updateData['approved_by'] = null;
                $updateData['updated_at'] = date('Y-m-d H:i:s');
            }

            $this->etutModel->update($id, $updateData);

            $statusText = [
                'pending' => 'Bekliyor',
                'approved' => 'Onaylandı',
                'rejected' => 'Reddedildi',
                'completed' => 'Verildi'
            ];

            $this->json([
                'success' => true,
                'message' => "Başvuru durumu '{$statusText[$status]}' olarak güncellendi"
            ]);
            
        } catch (\Exception $e) {
            // Log exception for debugging
            try {
                $dbgPath = BASE_PATH . '/storage/logs/etut_status_debug.log';
                $err = [
                    'time' => date('Y-m-d H:i:s'),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ];
                @file_put_contents($dbgPath, json_encode($err, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
            } catch (\Throwable $t) {}

            $this->json([
                'success' => false,
                'message' => 'Güncelleme hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update etut application
     * PUT /api/etut/{id}
     */
    public function update($id) {
        try {
            $application = $this->etutModel->find($id);
            if (!$application) {
                $this->json([
                    'success' => false,
                    'message' => 'Başvuru bulunamadı'
                ], 404);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = $this->validateEtut($data);
            if (!empty($errors)) {
                $this->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $errors
                ], 422);
                return;
            }
            
            // Update
            $this->etutModel->update($id, $data);
            
            $this->json([
                'success' => true,
                'message' => 'Başvuru güncellendi'
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Güncelleme hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete etut application
     * DELETE /api/etut/{id}
     */
    public function delete($id) {
        try {
            $application = $this->etutModel->find($id);
            if (!$application) {
                $this->json([
                    'success' => false,
                    'message' => 'Başvuru bulunamadı'
                ], 404);
                return;
            }
            
            // Check authorization
            $user = Auth::user();
            if ($application['created_by'] != Auth::id() && !in_array($user['role_slug'], ['admin', 'mudur'])) {
                $this->json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz yok'
                ], 403);
                return;
            }
            
            // Delete
            $this->etutModel->delete($id);
            
            $this->json([
                'success' => true,
                'message' => 'Başvuru silindi'
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Silme hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get etut statistics
     * GET /api/etut/stats
     */
    public function stats() {
        try {
            $stats = [
                'total' => $this->etutModel->count(),
                'pending' => $this->etutModel->countByStatus('pending'),
                'approved' => $this->etutModel->countByStatus('approved'),
                'rejected' => $this->etutModel->countByStatus('rejected'),
                'today' => $this->etutModel->countToday(),
                'this_week' => $this->etutModel->countThisWeek()
            ];
            
            $this->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'İstatistik hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Batch approve applications
     * POST /api/etut/batch-approve
     */
    public function batchApprove() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ids = $data['ids'] ?? [];
            
            if (empty($ids)) {
                $this->json([
                    'success' => false,
                    'message' => 'Başvuru seçilmedi'
                ], 400);
                return;
            }
            
            // Check authorization
            $user = Auth::user();
            if (!in_array($user['role_slug'], ['admin', 'mudur', 'mudur_yardimcisi'])) {
                $this->json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz yok'
                ], 403);
                return;
            }
            
            // Approve all
            $approved = 0;
            foreach ($ids as $id) {
                $application = $this->etutModel->find($id);
                if ($application && $application['status'] === 'pending') {
                    $this->etutModel->update($id, [
                        'status' => 'approved',
                        'approved_by' => Auth::id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $approved++;
                }
            }
            
            $this->json([
                'success' => true,
                'message' => "{$approved} başvuru onaylandı",
                'approved' => $approved
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Toplu onaylama hatası: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch delete applications
     * POST /api/etut/batch-delete
     */
    public function batchDelete()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            $csrf = $input['csrf_token'] ?? '';

            if (!validateCsrfToken($csrf)) {
                echo json_encode(['success' => false, 'message' => 'Geçersiz token']);
                exit;
            }

            if (empty($ids) || !is_array($ids)) {
                echo json_encode(['success' => false, 'message' => 'Silinecek başvuru seçilmedi']);
                exit;
            }

            $deleted = 0;
            foreach ($ids as $id) {
                $app = $this->etutModel->find($id);
                if ($app) {
                    $this->etutModel->delete($id);
                    $deleted++;
                }
            }

            echo json_encode(['success' => true, 'message' => "{$deleted} başvuru silindi", 'deleted' => $deleted]);
            exit;
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Toplu silme hatası: ' . $e->getMessage()]);
            exit;
        }
    }
    
    /**
     * Get etut calendar (available slots)
     * GET /api/etut/calendar?date=...
     */
    public function calendar() {
        try {
            $date = $_GET['date'] ?? date('Y-m-d');
            
            // Get all applications for this date
            $applications = $this->etutModel->getByDate($date);
            
            // Get available time slots
            $allSlots = $this->getTimeSlots();
            $availableSlots = [];
            
            foreach ($allSlots as $slot) {
                $isBooked = false;
                foreach ($applications as $app) {
                    if ($app['start_time'] === $slot['start'] && $app['end_time'] === $slot['end']) {
                        $isBooked = true;
                        break;
                    }
                }
                
                $availableSlots[] = [
                    'start' => $slot['start'],
                    'end' => $slot['end'],
                    'available' => !$isBooked
                ];
            }
            
            $this->json([
                'success' => true,
                'date' => $date,
                'slots' => $availableSlots,
                'applications' => $applications
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Takvim hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Validate etut data
     */
    private function validateEtut($data) {
        $errors = [];
        
        if (empty($data['student_id'])) {
            $errors['student_id'] = 'Öğrenci seçilmeli';
        }
        
        if (empty($data['subject'])) {
            $errors['subject'] = 'Ders seçilmeli';
        }
        
        if (empty($data['date'])) {
            $errors['date'] = 'Tarih seçilmeli';
        }
        
        if (empty($data['start_time'])) {
            $errors['start_time'] = 'Başlangıç saati seçilmeli';
        }
        
        if (empty($data['end_time'])) {
            $errors['end_time'] = 'Bitiş saati seçilmeli';
        }
        
        // Check time order
        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            if ($data['start_time'] >= $data['end_time']) {
                $errors['end_time'] = 'Bitiş saati başlangıç saatinden sonra olmalı';
            }
        }
        
        return $errors;
    }
    
    /**
     * Get available time slots
     */
    private function getTimeSlots() {
        return [
            ['start' => '08:00', 'end' => '09:00'],
            ['start' => '09:00', 'end' => '10:00'],
            ['start' => '10:00', 'end' => '11:00'],
            ['start' => '11:00', 'end' => '12:00'],
            ['start' => '13:00', 'end' => '14:00'],
            ['start' => '14:00', 'end' => '15:00'],
            ['start' => '15:00', 'end' => '16:00'],
            ['start' => '16:00', 'end' => '17:00'],
        ];
    }
}