<?php
/**
 * Activity API Controller
 * AJAX endpoints for activity operations
 */

namespace App\Controllers\Api;

use Core\Controller;
use Core\Response;
use Core\Auth;
use App\Models\Activity;
use App\Models\ActivityArea;

class ActivityApiController extends Controller
{
    private $activityModel;
    private $activityAreaModel;
    
    public function __construct() {
        parent::__construct();
        
        // Check authentication
        if (!Auth::check()) {
            Response::json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
            exit;
        }
        
        $this->activityModel = new Activity();
        $this->activityAreaModel = new ActivityArea();
    }
    
    /**
     * Check time conflict (çakışma kontrolü)
     * POST /api/activities/check-conflict
     */
    public function checkConflict() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Required fields
            if (empty($data['activity_area_id']) || empty($data['activity_date']) || 
                empty($data['start_time']) || empty($data['end_time'])) {
                Response::json([
                    'success' => false,
                    'message' => 'Eksik bilgi'
                ], 400);
                return;
            }
            
            // Check for conflicts
            $hasConflict = $this->activityModel->hasTimeConflict(
                $data['activity_area_id'],
                $data['activity_date'],
                $data['start_time'],
                $data['end_time'],
                $data['exclude_id'] ?? null
            );
            
            if ($hasConflict) {
                $conflicts = $this->activityModel->getConflicts(
                    $data['activity_area_id'],
                    $data['activity_date'],
                    $data['start_time'],
                    $data['end_time']
                );
                
                Response::json([
                    'success' => false,
                    'available' => false,
                    'message' => 'Bu saat aralığında çakışma var',
                    'conflicts' => $conflicts
                ]);
            } else {
                Response::json([
                    'success' => true,
                    'available' => true,
                    'message' => 'Bu saat aralığı müsait'
                ]);
            }
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Kontrol sırasında hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get calendar events
     * GET /api/activities/calendar?start=...&end=...
     */
    public function calendar() {
        try {
            $start = $_GET['start'] ?? date('Y-m-d', strtotime('-1 month'));
            $end = $_GET['end'] ?? date('Y-m-d', strtotime('+2 months'));
            
            // Get activities in date range
            $activities = $this->activityModel->getByDateRange($start, $end);
            
            // Get activity areas for legend
            $areas = $this->activityAreaModel->getAll();
            
            Response::json([
                'success' => true,
                'activities' => $activities,
                'areas' => $areas
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Takvim yüklenirken hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update activity date/time (drag & drop)
     * POST /api/activities/{id}/update-datetime
     */
    public function updateDateTime($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Check if activity exists
            $activity = $this->activityModel->find($id);
            if (!$activity) {
                Response::json([
                    'success' => false,
                    'message' => 'Etkinlik bulunamadı'
                ], 404);
                return;
            }
            
            // Check for conflicts
            $hasConflict = $this->activityModel->hasTimeConflict(
                $activity['activity_area_id'],
                $data['activity_date'],
                $data['start_time'],
                $data['end_time'],
                $id
            );
            
            if ($hasConflict) {
                Response::json([
                    'success' => false,
                    'message' => 'Yeni tarih/saatte çakışma var'
                ], 422);
                return;
            }
            
            // Update
            $this->activityModel->update($id, [
                'activity_date' => $data['activity_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time']
            ]);
            
            Response::json([
                'success' => true,
                'message' => 'Etkinlik güncellendi'
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Güncelleme hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create activity with recurrence
     * POST /api/activities/create
     */
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate
            $errors = $this->validateActivity($data);
            if (!empty($errors)) {
                Response::json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $errors
                ], 422);
                return;
            }
            
            // Check conflict
            $hasConflict = $this->activityModel->hasTimeConflict(
                $data['activity_area_id'],
                $data['activity_date'],
                $data['start_time'],
                $data['end_time']
            );
            
            if ($hasConflict) {
                Response::json([
                    'success' => false,
                    'message' => 'Çakışma var, farklı saat seçin'
                ], 422);
                return;
            }
            
            // Add created_by
            $data['created_by'] = Auth::id();
            
            // Create activity
            if (!empty($data['is_recurring'])) {
                // Create recurring activities
                $result = $this->createRecurringActivities($data);
                
                Response::json([
                    'success' => true,
                    'message' => 'Tekrarlı etkinlikler oluşturuldu',
                    'created' => $result['created'],
                    'skipped' => $result['skipped']
                ], 201);
            } else {
                // Create single activity
                $activityId = $this->activityModel->create($data);
                
                Response::json([
                    'success' => true,
                    'message' => 'Etkinlik oluşturuldu',
                    'activity_id' => $activityId
                ], 201);
            }
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Oluşturma hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update activity
     * PUT /api/activities/{id}
     */
    public function update($id) {
        try {
            $activity = $this->activityModel->find($id);
            if (!$activity) {
                Response::json([
                    'success' => false,
                    'message' => 'Etkinlik bulunamadı'
                ], 404);
                return;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate
            $errors = $this->validateActivity($data);
            if (!empty($errors)) {
                Response::json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $errors
                ], 422);
                return;
            }
            
            // Check conflict
            $hasConflict = $this->activityModel->hasTimeConflict(
                $data['activity_area_id'],
                $data['activity_date'],
                $data['start_time'],
                $data['end_time'],
                $id
            );
            
            if ($hasConflict) {
                Response::json([
                    'success' => false,
                    'message' => 'Çakışma var'
                ], 422);
                return;
            }
            
            // Update
            $this->activityModel->update($id, $data);
            
            Response::json([
                'success' => true,
                'message' => 'Etkinlik güncellendi'
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Güncelleme hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete activity
     * DELETE /api/activities/{id}
     */
    public function delete($id) {
        try {
            $activity = $this->activityModel->find($id);
            if (!$activity) {
                Response::json([
                    'success' => false,
                    'message' => 'Etkinlik bulunamadı'
                ], 404);
                return;
            }
            
            // Check authorization
            $user = Auth::user();
            if ($activity['created_by'] != Auth::id() && !in_array($user['role_slug'], ['admin', 'mudur'])) {
                Response::json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz yok'
                ], 403);
                return;
            }
            
            // Delete
            $this->activityModel->delete($id);
            
            Response::json([
                'success' => true,
                'message' => 'Etkinlik silindi'
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Silme hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get activity areas
     * GET /api/activity-areas
     */
    public function getAreas() {
        try {
            $areas = $this->activityAreaModel->getAll();
            
            Response::json([
                'success' => true,
                'areas' => $areas
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get activity statistics
     * GET /api/activities/stats
     */
    public function stats() {
        try {
            $stats = [
                'total' => $this->activityModel->count(),
                'today' => $this->activityModel->countToday(),
                'this_week' => $this->activityModel->countThisWeek(),
                'by_area' => $this->activityModel->countByArea()
            ];
            
            Response::json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'İstatistik hatası: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create recurring activities
     */
    private function createRecurringActivities($data) {
        $created = 0;
        $skipped = 0;
        
        $recurrenceType = $data['recurrence_type'] ?? 'weekly';
        $recurrenceEndDate = $data['recurrence_end_date'] ?? null;
        $recurrenceCount = $data['recurrence_count'] ?? 10;
        
        $currentDate = new \DateTime($data['activity_date']);
        $endDate = $recurrenceEndDate ? new \DateTime($recurrenceEndDate) : null;
        
        $count = 0;
        while ($count < $recurrenceCount) {
            // Check if we've reached end date
            if ($endDate && $currentDate > $endDate) {
                break;
            }
            
            // Check for conflict
            $hasConflict = $this->activityModel->hasTimeConflict(
                $data['activity_area_id'],
                $currentDate->format('Y-m-d'),
                $data['start_time'],
                $data['end_time']
            );
            
            if (!$hasConflict) {
                // Create activity
                $activityData = $data;
                $activityData['activity_date'] = $currentDate->format('Y-m-d');
                $activityData['is_recurring'] = 1;
                unset($activityData['recurrence_type']);
                unset($activityData['recurrence_end_date']);
                unset($activityData['recurrence_count']);
                
                $this->activityModel->create($activityData);
                $created++;
            } else {
                $skipped++;
            }
            
            // Increment date based on recurrence type
            switch ($recurrenceType) {
                case 'daily':
                    $currentDate->modify('+1 day');
                    break;
                case 'weekly':
                    $currentDate->modify('+1 week');
                    break;
                case 'monthly':
                    $currentDate->modify('+1 month');
                    break;
            }
            
            $count++;
        }
        
        return [
            'created' => $created,
            'skipped' => $skipped
        ];
    }
    
    /**
     * Validate activity data
     */
    private function validateActivity($data) {
        $errors = [];
        
        if (empty($data['student_id'])) {
            $errors['student_id'] = 'Öğrenci seçilmeli';
        }
        
        if (empty($data['activity_area_id'])) {
            $errors['activity_area_id'] = 'Etkinlik alanı seçilmeli';
        }
        
        if (empty($data['activity_date'])) {
            $errors['activity_date'] = 'Tarih seçilmeli';
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
}