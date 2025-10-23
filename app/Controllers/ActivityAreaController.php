<?php
/**
 * ActivityArea Controller
 * Etkinlik alanları yönetimi
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\ActivityArea;

class ActivityAreaController extends Controller
{
    private $areaModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->areaModel = new ActivityArea();
        
        // Giriş kontrolü
        if (!isLoggedIn()) {
            redirect('/login');
        }
    }
    
    /**
     * Etkinlik alanları listesi
     */
    public function index()
    {
        // Permission kontrolü - etkinlik alanları yetkisi gerekli
        \App\Middleware\PermissionMiddleware::canView('activity_areas');
        
        $areas = $this->areaModel->getActive();
        
        $this->view('activity-areas/index', [
            'title' => 'Etkinlik Alanları',
            'areas' => $areas
        ]);
    }
    
    /**
     * Yeni alan oluşturma sayfası
     */
    public function create()
    {
        $this->view('activity-areas/create', [
            'title' => 'Yeni Etkinlik Alanı'
        ]);
    }
    
    /**
     * Alan kaydetme
     */
    public function store()
    {
        // Debug: POST verilerini log'la
        error_log("ActivityAreaController store called");
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));
        
        try {
            // Fonksiyonların varlığını kontrol et
            if (!function_exists('setFlashMessage')) {
                error_log("ERROR: setFlashMessage function not found!");
                echo json_encode(['success' => false, 'message' => 'setFlashMessage function not found!']);
                return;
            }
            
            if (!function_exists('url')) {
                error_log("ERROR: url function not found!");
                echo json_encode(['success' => false, 'message' => 'url function not found!']);
                return;
            }
            
            error_log("Functions exist, continuing...");
            
            // CSRF kontrolü - geçici olarak devre dışı
            /*
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlashMessage('Güvenlik hatası!', 'error');
                $this->redirect(url('/activity-areas/create'));
                return;
            }
            */
            
            // Form validation
            $areaName = trim($_POST['area_name'] ?? '');
            $colorCode = trim($_POST['color_code'] ?? '#3B82F6');
            $defaultDuration = (int)($_POST['default_slot_duration'] ?? 30);
            $sortOrder = (int)($_POST['sort_order'] ?? 0);
            
            error_log("Parsed data - Name: $areaName, Color: $colorCode, Duration: $defaultDuration");
            
            if (empty($areaName)) {
                error_log("Validation failed: Empty area name");
                setFlashMessage('Alan adı boş olamaz!', 'error');
                $this->redirect(url('/activity-areas/create'));
                return;
            }
            
            // Fotoğraf upload kontrolü
            $areaImage = null;
            if (isset($_FILES['area_image']) && $_FILES['area_image']['error'] === UPLOAD_ERR_OK) {
                error_log("File upload detected");
                $uploadResult = $this->uploadAreaImage($_FILES['area_image']);
                if ($uploadResult['success']) {
                    $areaImage = $uploadResult['filename'];
                    error_log("File uploaded successfully: $areaImage");
                } else {
                    error_log("File upload failed: " . $uploadResult['message']);
                    setFlashMessage($uploadResult['message'], 'error');
                    $this->redirect(url('/activity-areas/create'));
                    return;
                }
            }
            
            // Veritabanına kaydet
            $data = [
                'area_name' => $areaName,
                'area_image' => $areaImage,
                'color_code' => $colorCode,
                'default_slot_duration' => $defaultDuration,
                'sort_order' => $sortOrder,
                'is_active' => 1
            ];
            
            error_log("Data to save: " . print_r($data, true));
            
            try {
                $result = $this->areaModel->create($data);
                error_log("Create result: " . print_r($result, true));
                
                if ($result && $result !== false) {
                    setFlashMessage('Etkinlik alanı başarıyla eklendi!', 'success');
                    error_log("Area created successfully with ID: $result");
                } else {
                    error_log("Create failed - result is false or empty");
                    setFlashMessage('Etkinlik alanı eklenirken hata oluştu! Lütfen forma kontrol edin.', 'error');
                }
            } catch (\Exception $dbException) {
                error_log("Database error: " . $dbException->getMessage());
                setFlashMessage('Veritabanı hatası: ' . $dbException->getMessage(), 'error');
            }
            
        } catch (Exception $e) {
            error_log("EXCEPTION: " . $e->getMessage());
            setFlashMessage('Hata: ' . $e->getMessage(), 'error');
        }
        
        $this->redirect(url('/activity-areas'));
    }
    
    /**
     * Alan düzenleme sayfası
     */
    public function edit($id)
    {
        if (!$id) {
            setFlashMessage('Alan ID belirtilmedi.', 'error');
            $this->redirect(url('/activity-areas'));
            return;
        }
        
        // Alanı getir
        $area = $this->areaModel->find($id);
        
        if (!$area) {
            setFlashMessage('Alan bulunamadı.', 'error');
            $this->redirect(url('/activity-areas'));
            return;
        }
        
        $this->view('activity-areas/edit', [
            'title' => 'Etkinlik Alanı Düzenle',
            'area' => $area
        ]);
    }
    
    /**
     * Alan güncelleme
     */
    public function update($id)
    {
        if (!$id) {
            setFlashMessage('Alan ID belirtilmedi.', 'error');
            $this->redirect(url('/activity-areas'));
            return;
        }
        
        try {
            // Mevcut alanı getir
            $area = $this->areaModel->find($id);
            
            if (!$area) {
                setFlashMessage('Alan bulunamadı.', 'error');
                $this->redirect(url('/activity-areas'));
                return;
            }
            
            // Form verilerini al
            $areaData = [
                'area_name' => trim($_POST['area_name']),
                'color_code' => $_POST['color_code'] ?? '#3B82F6',
                'default_slot_duration' => (int)($_POST['default_slot_duration'] ?? 30),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
            ];
            
            // Resim güncelleme kontrolü
            if (isset($_FILES['area_image']) && $_FILES['area_image']['error'] === UPLOAD_ERR_OK) {
                // Yeni resim yüklendi
                $uploadResult = $this->uploadAreaImage($_FILES['area_image']);
                
                if ($uploadResult['success']) {
                    // Eski resmi sil
                    if (!empty($area['area_image'])) {
                        $oldImagePath = ACTIVITY_AREA_UPLOAD_PATH . '/' . $area['area_image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $areaData['area_image'] = $uploadResult['filename'];
                } else {
                    setFlashMessage('Resim yüklenemedi: ' . $uploadResult['error'], 'error');
                    $this->redirect(url('/activity-areas/' . $id . '/edit'));
                    return;
                }
            } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
                // Mevcut resmi kaldır
                if (!empty($area['area_image'])) {
                    $oldImagePath = ACTIVITY_AREA_UPLOAD_PATH . '/' . $area['area_image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $areaData['area_image'] = null;
            }
            
            // Güncelle
            $result = $this->areaModel->update($id, $areaData);
            
            if ($result) {
                setFlashMessage('✅ Etkinlik alanı başarıyla güncellendi!', 'success');
            } else {
                setFlashMessage('Alan güncellenirken bir hata oluştu.', 'error');
            }
            
        } catch (\Exception $e) {
            error_log("Alan güncelleme hatası: " . $e->getMessage());
            setFlashMessage('Hata: ' . $e->getMessage(), 'error');
        }
        
        $this->redirect(url('/activity-areas'));
    }
    
    /**
     * Alan silme
     */
    public function delete($id)
    {
        // Geçici olarak authentication bypass
        // TODO: Remove this bypass in production
        
        if (!$id) {
            setFlashMessage('Alan ID belirtilmedi.', 'error');
            $this->redirect(url('/activity-areas'));
            return;
        }
        
        try {
            // Alanı bul
            $area = $this->areaModel->find($id);
            
            if (!$area) {
                setFlashMessage('Alan bulunamadı.', 'error');
                $this->redirect(url('/activity-areas'));
                return;
            }
            
            // Silme işlemi (soft delete - is_active = 0)
            $result = $this->areaModel->update($id, [
                'is_active' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($result) {
                setFlashMessage('Etkinlik alanı başarıyla silindi.', 'success');
            } else {
                setFlashMessage('Alan silinirken bir hata oluştu.', 'error');
            }
            
        } catch (Exception $e) {
            setFlashMessage('Sistem hatası: ' . $e->getMessage(), 'error');
        }
        
        $this->redirect(url('/activity-areas'));
    }
    
    /**
     * API: Alan listesi
     */
    public function apiList()
    {
        $areas = $this->areaModel->getActive();
        $this->json($areas);
    }
    
    /**
     * Alan fotoğrafı upload
     */
    private function uploadAreaImage($file)
    {
        // Upload klasörünü kontrol et
        $uploadDir = ACTIVITY_AREA_UPLOAD_PATH;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Dosya kontrolü
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return [
                'success' => false,
                'message' => 'Geçersiz dosya türü! Sadece JPG, PNG, GIF ve WebP dosyaları kabul edilir.'
            ];
        }
        
        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'message' => 'Dosya boyutu çok büyük! Maksimum 5MB olmalıdır.'
            ];
        }
        
        // Dosya adı oluştur
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'area_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        // Dosyayı taşı
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return [
                'success' => true,
                'filename' => $filename
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Dosya yüklenirken hata oluştu!'
            ];
        }
    }
}
