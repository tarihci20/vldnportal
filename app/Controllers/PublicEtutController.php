<?php
/**
 * Public Etüt Form Controller
 * Vildan Portal - Giriş yapmadan etüt başvurusu
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Etut;
use App\Models\EtutFormSettings;
use App\Models\Student;

class PublicEtutController extends Controller
{
    private $etutModel;
    private $settingsModel;
    private $studentModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->etutModel = new Etut();
        $this->settingsModel = new EtutFormSettings();
        $this->studentModel = new Student();
    }
    
    /**
     * Ortaokul etüt formu
     */
    public function ortaokul()
    {
        $this->showForm('ortaokul');
    }
    
    /**
     * Lise etüt formu
     */
    public function lise()
    {
        $this->showForm('lise');
    }
    
    /**
     * Form göster
     */
    private function showForm($formType)
    {
        // Form ayarlarını al
        $settings = $this->settingsModel->getByFormType($formType);
        
        if (!$settings) {
            $this->view('public/etut-form-error', [
                'title' => 'Hata',
                'message' => 'Form ayarları bulunamadı.'
            ], 'public');
            return;
        }
        
        // Form kapalıysa
        if ($settings['is_active'] != 1) {
            $this->view('public/etut-form-closed', [
                'title' => $settings['title'] ?? 'Etüt Başvuru Formu',
                'message' => $settings['closed_message'] ?? 'Form şu anda kapalıdır.',
                'formType' => $formType
            ], 'public');
            return;
        }
        
        // Form açıksa göster
        $title = $settings['title'] ?? 'Etüt Başvuru Formu';
        if ($formType === 'lise') {
            $title = 'Vildan Lisesi Etüt Başvuru Formu';
        } elseif ($formType === 'ortaokul') {
            $title = 'Vildan Ortaokul Etüt Başvuru Formu';
        }
        $this->view('public/etut-form', [
            'title' => $title,
            'description' => $settings['description'] ?? '',
            'formType' => $formType,
            'settings' => $settings
        ], 'public');
    }
    
    /**
     * Form submit
     */
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek']);
            return;
        }
        
        $formType = $_POST['form_type'] ?? null;
        
        if (!in_array($formType, ['ortaokul', 'lise'])) {
            $this->json(['success' => false, 'message' => 'Geçersiz form tipi']);
            return;
        }
        
        // Form açık mı kontrol et
        if (!$this->settingsModel->isFormActive($formType)) {
            $this->json(['success' => false, 'message' => 'Form şu anda kapalıdır']);
            return;
        }
        
        // Validasyon
        $tcNo = trim($_POST['tc_no'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $grade = trim($_POST['grade'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $subject = trim($_POST['subject'] ?? ''); // Seçilen ders (tek seçim)
        
        if (empty($tcNo) || empty($fullName) || empty($grade) || empty($subject) || empty($notes)) {
            $this->json(['success' => false, 'message' => 'Lütfen tüm zorunlu alanları doldurun']);
            return;
        }
        
        // Öğrenci No için sadece rakam kontrolü
        if (!ctype_digit($tcNo)) {
            $this->json(['success' => false, 'message' => 'Öğrenci No sadece rakamlardan oluşmalıdır.']);
            return;
        }
        
        // Öğrenci başvuru yapabilir mi kontrol et
        if (!$this->settingsModel->canStudentApply($tcNo, $formType)) {
            $this->json(['success' => false, 'message' => 'Bu form için başvuru limitiniz dolmuştur']);
            return;
        }
        
        // Başvuruyu kaydet
            // Öğrenci ID'sini bulmaya çalış
            $student = $this->studentModel->findWhere(['tc_no' => $tcNo]);
        // Numara kontrolü kaldırıldı, public başvurularda student_id = 0
    $studentId = 1; // id=1 olan öğrenciye bağla (dummy)

            $data = [
                'student_id' => $studentId, // Bulunan öğrenci ID'si veya NULL
            'tc_no' => $tcNo,
            'full_name' => $fullName,
            'grade' => $grade,
            'parent_phone' => '', // Artık telefon alanı yok
            'student_phone' => '', // Artık telefon alanı yok
            'address' => $address,
            'subject' => $subject, // Seçilen ders (tek seçim)
            'notes' => $notes,
            'form_type' => $formType,
            'status' => 'pending',
            'application_date' => date('Y-m-d'),
            'start_time' => '00:00:00', // Varsayılan
            'end_time' => '00:00:00', // Varsayılan
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->etutModel->create($data);
        
        if ($result) {
            $this->json([
                'success' => true,
                'message' => 'Başvurunuz başarıyla alındı. En kısa sürede size dönüş yapılacaktır.',
                'application_id' => $result
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Başvurunuz kaydedilirken bir hata oluştu. Lütfen tekrar deneyin.']);
        }
    }
}
