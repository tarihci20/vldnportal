<?php
/**
 * Etut Controller
 * Etüt başvuruları yönetimi
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Etut;

class EtutController extends Controller
{
    private $etutModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->etutModel = new Etut();
        
        // Giriş kontrolü
        if (!isLoggedIn()) {
            redirect('/login');
        }
    }
    
    /**
     * Etüt Alanları Ana Sayfası (Ortaokul/Lise seçimi)
     */
    public function index()
    {
        // Permission kontrolü - etüt sayfası yetkisi gerekli
        \App\Middleware\PermissionMiddleware::canView('etut');
        
        $this->view('etut/areas', [
            'title' => 'Etüt Alanları'
        ]);
    }
    
    /**
     * Ortaokul Etüt Başvuruları Listesi
     */
    public function ortaokul()
    {
        $formType = 'ortaokul';
        $applications = $this->etutModel->getByFormType($formType);
        // Debug: write a compact representation of the first application to logs to verify keys
        if (!empty($applications) && is_array($applications)) {
            $first = $applications[0];
            $logPath = BASE_PATH . '/storage/logs/etut_list_debug.log';
            @file_put_contents($logPath, date('Y-m-d H:i:s') . "\n" . json_encode(array_keys($first), JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
        }

        $settingsModel = new \App\Models\EtutFormSettings();
        $formActive = $settingsModel->isFormActive($formType);

        $this->view('etut/list', [
            'title' => 'Ortaokul Etüt Başvuruları',
            'applications' => $applications,
            'formType' => $formType,
            'formActive' => $formActive
        ]);
    }
    
    /**
     * Lise Etüt Başvuruları Listesi
     */
    public function lise()
    {
        $formType = 'lise';
        $applications = $this->etutModel->getByFormType($formType);
        // Debug: write a compact representation of the first application to logs to verify keys
        if (!empty($applications) && is_array($applications)) {
            $first = $applications[0];
            $logPath = BASE_PATH . '/storage/logs/etut_list_debug.log';
            @file_put_contents($logPath, date('Y-m-d H:i:s') . "\n" . json_encode(array_keys($first), JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
        }

        $settingsModel = new \App\Models\EtutFormSettings();
        $formActive = $settingsModel->isFormActive($formType);

        $this->view('etut/list', [
            'title' => 'Lise Etüt Başvuruları',
            'applications' => $applications,
            'formType' => $formType,
            'formActive' => $formActive
        ]);
    }
    
    /**
     * Etüt başvuru formu
     */
    public function form()
    {
        $this->view('etut/form', [
            'title' => 'Etüt Başvuru Formu'
        ]);
    }
    
    /**
     * Yeni başvuru oluşturma
     */
    public function create()
    {
        $this->view('etut/create', [
            'title' => 'Yeni Etüt Başvurusu'
        ]);
    }
    
    /**
     * Başvuru kaydetme
     */
    public function store()
    {
        // TODO: Implement
        redirect('/etut');
    }
    
    /**
     * Başvuru detayı
     */
    public function detail()
    {
        $this->view('etut/detail', [
            'title' => 'Etüt Başvuru Detayı'
        ]);
    }
    
    /**
     * Başvuru düzenleme
     */
    public function edit()
    {
        $this->view('etut/edit', [
            'title' => 'Etüt Başvurusu Düzenle'
        ]);
    }
    
    /**
     * Başvuru güncelleme
     */
    public function update()
    {
        // TODO: Implement
        redirect('/etut');
    }
    
    /**
     * Başvuru silme
     */
    public function delete()
    {
        // TODO: Implement
        redirect('/etut');
    }
    
    /**
     * Başvuru onaylama
     */
    public function approve()
    {
        // TODO: Implement
        redirect('/etut');
    }
    
    /**
     * Başvuru reddetme
     */
    public function reject()
    {
        // TODO: Implement
        redirect('/etut');
    }
    
    /**
     * API: Başvuru detayı getir
     */
    public function apiGet($id)
    {
        $application = $this->etutModel->find($id);
        
        if (!$application) {
            $this->json(['success' => false, 'message' => 'Başvuru bulunamadı']);
            return;
        }
        
        $this->json(['success' => true, 'data' => $application]);
    }
    
    /**
     * API: Başvuruyu onayla
     */
    public function apiApprove($id)
    {
        $application = $this->etutModel->find($id);
        
        if (!$application) {
            $this->json(['success' => false, 'message' => 'Başvuru bulunamadı']);
            return;
        }
        
        $result = $this->etutModel->update($id, [
            'status' => 'approved',
            'approved_by' => getCurrentUserId(),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Başvuru onaylandı']);
        } else {
            $this->json(['success' => false, 'message' => 'Başvuru onaylanamadı']);
        }
    }
    
    /**
     * API: Başvuruyu reddet
     */
    public function apiReject($id)
    {
        $application = $this->etutModel->find($id);
        
        if (!$application) {
            $this->json(['success' => false, 'message' => 'Başvuru bulunamadı']);
            return;
        }
        
        $result = $this->etutModel->update($id, [
            'status' => 'rejected',
            'approved_by' => getCurrentUserId(),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Başvuru reddedildi']);
        } else {
            $this->json(['success' => false, 'message' => 'Başvuru reddedilemedi']);
        }
    }

    /**
     * Export selected etut applications as XLSX
     * POST /admin/etut/export
     */
    public function exportSelected()
    {
        // Simple admin check
        if (!isAdmin()) {
            http_response_code(403);
            echo 'Yetkisiz';
            exit;
        }

        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids) || empty($ids)) {
            // nothing selected
            
            setFlashMessage('Önce kayıt seçin', 'error');
            redirect('/etut');
            return;
        }

        // sanitize ids
        $ids = array_map('intval', $ids);

    $rows = $this->etutModel->getByIds($ids);

        // Map rows to match admin table columns
        $exportRows = [];
        foreach ($rows as $r) {
            $displayDate = isset($r['created_at']) ? date('d.m.Y H:i:s', strtotime($r['created_at'])) : '';
            $displayName = $r['full_name'] ?? trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')) ?: '';
            $displayTc = $r['tc_no'] ?? $r['student_no'] ?? '';
            $displayGrade = $r['grade'] ?? ($r['class'] ?? '');
            $displaySubject = $r['subject'] ?? '';
            $displayNotes = $r['notes'] ?? $r['topic'] ?? '';
            $displayAddress = $r['address'] ?? $r['student_message'] ?? '';
            $status = $r['status'] ?? 'pending';
            $statusLabel = ($status === 'completed' || $status === 'approved') ? 'Verildi' : 'Verilmedi';

            $exportRows[] = [
                $displayDate,
                $displayName,
                $displayTc,
                $displayGrade,
                $displaySubject,
                $displayNotes,
                $displayAddress,
                $statusLabel
            ];
        }

        // Use helper to build spreadsheet directly (we'll create simple spreadsheet here)
        try {
            // Ensure no buffered output (HTML/errors) will corrupt the XLSX stream
            while (ob_get_level()) {
                ob_end_clean();
            }
            // Temporarily suppress display of errors so notices/warnings don't leak into output
            $prevDisplayErrors = ini_get('display_errors');
            $prevErrorReporting = error_reporting();
            ini_set('display_errors', '0');
            error_reporting(0);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['TARİH','AD SOYAD','ÖĞRENCİ NO','SINIFI','SEÇTİĞİ DERS','KONU/KAZANIM','ÖĞRENCİ MESAJI','ETÜT DURUMU'];
            $sheet->fromArray($headers, null, 'A1');

            $rowNum = 2;
            foreach ($exportRows as $er) {
                $sheet->fromArray($er, null, 'A' . $rowNum);
                $rowNum++;
            }

            foreach (range('A', $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $fileName = 'etut_basvurulari_' . date('Ymd_His') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');

            // restore error display settings (not reached because of exit but kept for completeness)
            ini_set('display_errors', $prevDisplayErrors);
            error_reporting($prevErrorReporting);

            exit;
        } catch (\Exception $e) {
            // Restore error display settings before redirecting
            ini_set('display_errors', $prevDisplayErrors ?? '1');
            error_reporting($prevErrorReporting ?? E_ALL);

            // Log detailed error
            error_log('[Etut exportSelected] Exception: ' . $e->getMessage());
            setFlashMessage('Excel oluşturulurken hata: ' . $e->getMessage(), 'error');
            redirect('/etut');
        }
    }

    /**
     * Export etut applications by date (POST date=YYYY-MM-DD)
     */
    public function exportByDate()
    {
        if (!isAdmin()) {
            http_response_code(403);
            echo 'Yetkisiz';
            exit;
        }

        $date = $_POST['date'] ?? $_GET['date'] ?? null;
        if (!$date) {
            setFlashMessage('Tarih seçin', 'error');
            redirect('/etut');
            return;
        }

    $rows = $this->etutModel->getByDate($date);

        // Build spreadsheet similar to exportSelected
        try {
            // Ensure no buffered output will corrupt the XLSX
            while (ob_get_level()) {
                ob_end_clean();
            }
            $prevDisplayErrors = ini_get('display_errors');
            $prevErrorReporting = error_reporting();
            ini_set('display_errors', '0');
            error_reporting(0);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['TARİH','AD SOYAD','ÖĞRENCİ NO','SINIFI','SEÇTİĞİ DERS','KONU/KAZANIM','ÖĞRENCİ MESAJI','ETÜT DURUMU'];
            $sheet->fromArray($headers, null, 'A1');

            $rowNum = 2;
            foreach ($rows as $r) {
                $displayDate = isset($r['created_at']) ? date('d.m.Y H:i:s', strtotime($r['created_at'])) : '';
                $displayName = $r['full_name'] ?? trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')) ?: '';
                $displayTc = $r['tc_no'] ?? $r['student_no'] ?? '';
                $displayGrade = $r['grade'] ?? ($r['class'] ?? '');
                $displaySubject = $r['subject'] ?? '';
                $displayNotes = $r['notes'] ?? $r['topic'] ?? '';
                $displayAddress = $r['address'] ?? $r['student_message'] ?? '';
                $status = $r['status'] ?? 'pending';
                $statusLabel = ($status === 'completed' || $status === 'approved') ? 'Verildi' : 'Verilmedi';

                $sheet->fromArray([
                    $displayDate,
                    $displayName,
                    $displayTc,
                    $displayGrade,
                    $displaySubject,
                    $displayNotes,
                    $displayAddress,
                    $statusLabel
                ], null, 'A' . $rowNum);
                $rowNum++;
            }

            foreach (range('A', $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $fileName = 'etut_basvurulari_' . $date . '_' . date('Ymd_His') . '.xlsx';
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
            error_log('[Etut exportByDate] Exception: ' . $e->getMessage());
            setFlashMessage('Excel oluşturulurken hata: ' . $e->getMessage(), 'error');
            redirect('/etut');
        }
    }
}
