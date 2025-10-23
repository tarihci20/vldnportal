<?php
/**
 * API Routes
 * RESTful API endpoints
 */

use App\Controllers\Api\StudentApiController;
use App\Controllers\Api\ActivityApiController;
use App\Controllers\Api\EtutApiController;

// ============================================
// STUDENT API ROUTES
// ============================================

// Search students (debounce)
$router->get('/api/students/search', [StudentApiController::class, 'search']);

// Student CRUD
$router->get('/api/students/{id}', [StudentApiController::class, 'show']);
$router->post('/api/students/create', [StudentApiController::class, 'create']);
$router->put('/api/students/{id}', [StudentApiController::class, 'update']);
$router->post('/api/students/{id}/update', [StudentApiController::class, 'update']); // Alternative
$router->delete('/api/students/{id}', [StudentApiController::class, 'delete']);
$router->post('/api/students/{id}/delete', [StudentApiController::class, 'delete']); // Alternative

// Excel import/export
$router->post('/api/students/import', [StudentApiController::class, 'import']);
$router->get('/api/students/export', [StudentApiController::class, 'export']);
$router->get('/api/students/download', [StudentApiController::class, 'download']);

// Statistics
$router->get('/api/students/stats', [StudentApiController::class, 'stats']);

// ============================================
// ACTIVITY API ROUTES - YENİ DİLİM SİSTEMİ
// ============================================

// Saat dilimleri ve çakışma kontrolü
$router->get('/api/activities/available-slots', 'ActivityController@getAvailableTimeSlots');
$router->post('/api/activities/check-slots-conflict', 'ActivityController@checkTimeSlotsConflict');

// Check time conflict (eski sistem uyumluluğu için)
$router->post('/api/activities/check-conflict', [ActivityApiController::class, 'checkConflict']);

// Calendar
$router->get('/api/activities/calendar', [ActivityApiController::class, 'calendar']);

// Activity CRUD
$router->post('/api/activities/create', [ActivityApiController::class, 'create']);
$router->put('/api/activities/{id}', [ActivityApiController::class, 'update']);
$router->post('/api/activities/{id}/update', [ActivityApiController::class, 'update']); // Alternative
$router->delete('/api/activities/{id}', [ActivityApiController::class, 'delete']);
$router->post('/api/activities/{id}/delete', [ActivityApiController::class, 'delete']); // Alternative

// Update date/time (drag & drop)
$router->post('/api/activities/{id}/update-datetime', [ActivityApiController::class, 'updateDateTime']);

// Activity areas
$router->get('/api/activity-areas', [ActivityApiController::class, 'getAreas']);

// Statistics
$router->get('/api/activities/stats', [ActivityApiController::class, 'stats']);

// ============================================
// ETUT API ROUTES
// ============================================

// Etut list
$router->get('/api/etut', [EtutApiController::class, 'index']);

// Etut CRUD
$router->get('/api/etut/{id}', [EtutApiController::class, 'show']);
$router->post('/api/etut/create', [EtutApiController::class, 'create']);
$router->put('/api/etut/{id}', [EtutApiController::class, 'update']);
$router->post('/api/etut/{id}/update', [EtutApiController::class, 'update']); // Alternative
$router->delete('/api/etut/{id}', [EtutApiController::class, 'delete']);
$router->post('/api/etut/{id}/delete', [EtutApiController::class, 'delete']); // Alternative

// Update status
$router->post('/api/etut/{id}/status', [EtutApiController::class, 'updateStatus']);

// Batch operations
$router->post('/api/etut/batch-approve', [EtutApiController::class, 'batchApprove']);
// Batch delete
$router->post('/api/etut/batch-delete', [EtutApiController::class, 'batchDelete']);

// Calendar
$router->get('/api/etut/calendar', [EtutApiController::class, 'calendar']);

// Statistics
$router->get('/api/etut/stats', [EtutApiController::class, 'stats']);

// ============================================
// USER API ROUTES (for admin panel)
// ============================================

// Users delete
$router->post('/api/users/{id}/delete', 'AdminController@deleteUser');

// Users CRUD (if needed in future)
// $router->get('/api/users', [UserApiController::class, 'index']);
// $router->get('/api/users/{id}', [UserApiController::class, 'show']);
// $router->post('/api/users/create', [UserApiController::class, 'create']);
// $router->put('/api/users/{id}', [UserApiController::class, 'update']);
// $router->post('/api/users/{id}/status', [UserApiController::class, 'updateStatus']);
// $router->delete('/api/users/{id}', [UserApiController::class, 'delete']);
?>