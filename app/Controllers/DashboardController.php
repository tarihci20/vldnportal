<?php
/**
 * Dashboard Controller
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Student;
use App\Models\Activity;
use App\Models\Etut;
use App\Models\ActivityArea;

class DashboardController extends Controller
{
    public function __construct() {
        parent::__construct();
        
        // Giriş kontrolü
        if (!isLoggedIn()) {
            redirect('/login');
        }
    }
    
    /**
     * Dashboard ana sayfa
     */
    public function index() {
        // Permission kontrolü - dashboard yetkisi gerekli
        // hasPermission() helper'ını kullan (zaten test edilmiş ve çalışıyor)
        if (!hasPermission('dashboard', 'can_view')) {
            setFlashMessage('Bu sayfaya erişim yetkiniz bulunmamaktadır.', 'error');
            redirect('/dashboard');
            exit;
        }
        
        $studentModel = new Student();
        $activityModel = new Activity();
        $etutModel = new Etut();
        $areaModel = new ActivityArea();
        
        // Bugünün özeti
        $todayStats = [
            'activities' => count($activityModel->getToday()),
            'etut' => count($etutModel->getToday()),
            'areas' => $areaModel->getActive()
        ];
        
        // Haftalık doluluk grafiği
        $weeklyStats = $activityModel->getWeeklyStats();
        
        // Öğrenci istatistikleri
        $studentStats = [
            'total' => $studentModel->countAll(),
            'by_class' => $studentModel->getCountByClass(),
            'most_active' => $studentModel->getMostActiveStudents(5)
        ];
        
        // Etüt istatistikleri
        $etutStats = $etutModel->getStats();
        
        // Bugünkü etkinlikler (detaylı)
        $todayActivities = $activityModel->getToday();
        
        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'today' => $todayStats,
            'weekly' => $weeklyStats,
            'students' => $studentStats,
            'etut' => $etutStats,
            'todayActivities' => $todayActivities
        ]);
    }
    
    /**
     * İstatistikler API (AJAX)
     */
    public function stats() {
        $studentModel = new Student();
        $activityModel = new Activity();
        $etutModel = new Etut();
        
        $stats = [
            'students' => $studentModel->countAll(),
            'activities_today' => count($activityModel->getToday()),
            'etut_pending' => $etutModel->getStats()['pending'] ?? 0,
            'activities_this_week' => count($activityModel->getWeeklyStats())
        ];
        
        $this->json($stats);
    }
    
    /**
     * Haftalık grafik API (AJAX)
     */
    public function weeklyChart() {
        $activityModel = new Activity();
        $weeklyStats = $activityModel->getWeeklyStats();
        
        // Chart.js formatına çevir
        $labels = [];
        $data = [];
        
        // Türkçe gün isimleri
        $dayNames = [
            'Monday' => 'Pazartesi',
            'Tuesday' => 'Salı',
            'Wednesday' => 'Çarşamba',
            'Thursday' => 'Perşembe',
            'Friday' => 'Cuma',
            'Saturday' => 'Cumartesi',
            'Sunday' => 'Pazar'
        ];
        
        foreach ($weeklyStats as $stat) {
            $labels[] = $dayNames[$stat['day_name']] ?? $stat['day_name'];
            $data[] = (int)$stat['activity_count'];
        }
        
        $this->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}