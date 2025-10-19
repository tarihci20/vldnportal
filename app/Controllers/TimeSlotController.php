<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\TimeSlot;
use App\Models\ActivityArea;
use App\Models\ActivityAreaTimeSlot;
use App\Middleware\AuthMiddleware;
use App\Middleware\PermissionMiddleware;

class TimeSlotController extends Controller
{
    private $timeSlotModel;
    private $activityAreaModel;
    private $areaTimeSlotModel;

    public function __construct()
    {
        parent::__construct(); // Bu satırı ekledik!
        
        // Admin kontrolü
        AuthMiddleware::handle();
        if (!isAdmin()) {
            setFlashMessage('Bu sayfaya erişim yetkiniz yok.', 'error');
            $this->redirect(url('/dashboard'));
            return;
        }

        $this->timeSlotModel = new TimeSlot();
        $this->activityAreaModel = new ActivityArea();
        // Alan bazlı saat dilimleri şimdilik devre dışı
        // $this->areaTimeSlotModel = new ActivityAreaTimeSlot();
    }

    /**
     * Saat dilimi yönetim sayfası
     */
    public function index()
    {
        $data = [
            'pageTitle' => 'Saat Dilimi Ayarları',
            'timeSlots' => $this->timeSlotModel->getActive(),
            'activityAreas' => $this->activityAreaModel->getForSelect(),
            'areaTimeSlots' => [], // Şimdilik boş
            'durationOptions' => [
                20 => '20 Dakika',
                30 => '30 Dakika',
                45 => '45 Dakika',
                60 => '60 Dakika'
            ]
        ];

        $this->view('admin/time-slots', $data, 'main');
    }

    /**
     * Yeni saat dilimi ekle
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        $timeStart = $_POST['time_start'] ?? '';
        $timeEnd = $_POST['time_end'] ?? '';
        $duration = (int) ($_POST['duration_minutes'] ?? 30);

        // Validasyon
        if (!$timeStart || !$timeEnd) {
            setFlashMessage('Başlangıç ve bitiş saati zorunludur.', 'error');
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        // Saat formatını kontrol et
        if (!preg_match('/^\d{2}:\d{2}$/', $timeStart) || !preg_match('/^\d{2}:\d{2}$/', $timeEnd)) {
            setFlashMessage('Geçersiz saat formatı. HH:MM formatında giriniz.', 'error');
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        // Başlangıç < Bitiş kontrolü
        if (strtotime($timeStart) >= strtotime($timeEnd)) {
            setFlashMessage('Başlangıç saati bitiş saatinden küçük olmalıdır.', 'error');
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        // Çakışma kontrolü
        if ($this->timeSlotModel->checkConflict($timeStart . ':00', $timeEnd . ':00')) {
            setFlashMessage('Bu saat dilimi mevcut dilimlerle çakışıyor.', 'error');
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        // Ekle
        $result = $this->timeSlotModel->addTimeSlot($timeStart . ':00', $timeEnd . ':00', $duration);

        if ($result) {
            setFlashMessage('Saat dilimi başarıyla eklendi.', 'success');
        } else {
            setFlashMessage('Saat dilimi eklenirken hata oluştu.', 'error');
        }

        $this->redirect(url('/admin/time-slots'));
    }

    /**
     * Saat dilimini güncelle
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $timeStart = $_POST['time_start'] ?? '';
        $timeEnd = $_POST['time_end'] ?? '';
        $duration = (int) ($_POST['duration_minutes'] ?? 30);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        // Mevcut dilimi kontrol et
        $existing = $this->timeSlotModel->find($id);
        if (!$existing) {
            setFlashMessage('Saat dilimi bulunamadı.', 'error');
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        // Çakışma kontrolü (kendi ID'sini hariç tut)
        if ($this->timeSlotModel->checkConflict($timeStart . ':00', $timeEnd . ':00', $id)) {
            setFlashMessage('Bu saat dilimi mevcut dilimlerle çakışıyor.', 'error');
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        // Güncelle
        $result = $this->timeSlotModel->updateTimeSlot($id, [
            'start_time' => $timeStart . ':00',
            'end_time' => $timeEnd . ':00',
            'duration' => $duration,
            'is_active' => $isActive
        ]);

        if ($result) {
            setFlashMessage('Saat dilimi başarıyla güncellendi.', 'success');
        } else {
            setFlashMessage('Saat dilimi güncellenirken hata oluştu.', 'error');
        }

        $this->redirect(url('/admin/time-slots'));
    }

    /**
     * Saat dilimini sil
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);

        $result = $this->timeSlotModel->deleteTimeSlot($id);

        if ($result) {
            setFlashMessage('Saat dilimi başarıyla silindi.', 'success');
        } else {
            setFlashMessage('Saat dilimi silinirken hata oluştu.', 'error');
        }

        $this->redirect(url('/admin/time-slots'));
    }

    /**
     * Alan bazlı saat dilimi ayarla (şimdilik devre dışı)
     */
    public function setAreaTimeSlot()
    {
        setFlashMessage('Alan bazlı ayarlar şu anda aktif değil.', 'info');
        $this->redirect(url('/admin/time-slots'));
    }

    /**
     * Otomatik saat dilimleri oluştur
     */
    public function generateSlots()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url('/admin/time-slots'));
            return;
        }

        $startTime = $_POST['start_time'] ?? '08:00';
        $endTime = $_POST['end_time'] ?? '22:00';
        $interval = (int) ($_POST['interval_minutes'] ?? 30);

        // Mevcut saat dilimlerini temizle
        $this->timeSlotModel->query("UPDATE time_slots SET is_active = 0");

        // Yeni saat dilimlerini oluştur
        $slots = $this->timeSlotModel->generateTimeSlots($startTime, $endTime, $interval);
        
        $addedCount = 0;
        foreach ($slots as $slot) {
            if ($this->timeSlotModel->addTimeSlot($slot['start_time'], $slot['end_time'], $slot['duration'])) {
                $addedCount++;
            }
        }

        if ($addedCount > 0) {
            setFlashMessage("{$addedCount} saat dilimi başarıyla oluşturuldu.", 'success');
        } else {
            setFlashMessage('Saat dilimleri oluşturulurken hata oluştu.', 'error');
        }

        $this->redirect(url('/admin/time-slots'));
    }
}