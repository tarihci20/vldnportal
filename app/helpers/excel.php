<?php
/**
 * Excel Import/Export Helper Fonksiyonları
 * Vildan Portal - Okul Yönetim Sistemi
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Öğrenci Excel dosyasını import et
 * 
 * @param string $filePath Excel dosya yolu
 * @return array ['success' => bool, 'message' => string, 'data' => array]
 */
function importStudentsFromExcel($filePath) {
    try {
        // Execution time'ı artır (1500+ öğrenci için)
        set_time_limit(defined('EXCEL_TIMEOUT') ? EXCEL_TIMEOUT : 300);
        ini_set('memory_limit', '512M');
        
        // Excel dosyasını yükle
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Başlık satırını kontrol et
        $headers = array_shift($rows);
        
        // Maksimum satır kontrolü
        $maxRows = defined('EXCEL_MAX_ROWS') ? EXCEL_MAX_ROWS : 2000;
        if (count($rows) > $maxRows) {
            return [
                'success' => false,
                'message' => "Excel dosyası çok büyük. Maksimum {$maxRows} öğrenci yüklenebilir. Dosyanızı bölün ve parça parça yükleyin.",
                'data' => []
            ];
        }
        
        // Beklenen sütunlar
        $expectedHeaders = [
            'TC', 'İsim', 'Soyisim', 'Sınıfı', 'Doğum Tarihi',
            'Baba Adı', 'Baba Telefon', 'Anne Adı', 'Anne Telefon',
            'Adres', 'Öğretmen', 'Öğretmen Telefon'
        ];
        
        // Başlık kontrolü (esnek)
        $headerMapping = mapExcelHeaders($headers, $expectedHeaders);
        
        if (empty($headerMapping)) {
            return [
                'success' => false,
                'message' => 'Excel dosyası geçersiz format. Beklenen sütunlar: ' . implode(', ', $expectedHeaders),
                'data' => []
            ];
        }
        
        // Verileri işle
        $students = [];
        $errors = [];
        $lineNumber = 2; // Başlık satırından sonra
        
        foreach ($rows as $row) {
            // Boş satırları atla
            if (isEmptyRow($row)) {
                $lineNumber++;
                continue;
            }
            
            try {
                $student = [
                    'tc_no' => cleanTcNo($row[$headerMapping['TC']] ?? null),
                    'first_name' => cleanName($row[$headerMapping['İsim']] ?? ''),
                    'last_name' => cleanName($row[$headerMapping['Soyisim']] ?? ''),
                    'class' => cleanText($row[$headerMapping['Sınıfı']] ?? ''),
                    'birth_date' => cleanDate($row[$headerMapping['Doğum Tarihi']] ?? ''),
                    'father_name' => cleanName($row[$headerMapping['Baba Adı']] ?? ''),
                    'father_phone' => cleanPhone($row[$headerMapping['Baba Telefon']] ?? ''),
                    'mother_name' => cleanName($row[$headerMapping['Anne Adı']] ?? ''),
                    'mother_phone' => cleanPhone($row[$headerMapping['Anne Telefon']] ?? ''),
                    'address' => cleanText($row[$headerMapping['Adres']] ?? ''),
                    'teacher_name' => cleanName($row[$headerMapping['Öğretmen']] ?? ''),
                    'teacher_phone' => cleanPhone($row[$headerMapping['Öğretmen Telefon']] ?? ''),
                ];
                
                // Zorunlu alanları kontrol et
                if (empty($student['first_name']) || empty($student['last_name'])) {
                    $errors[] = "Satır {$lineNumber}: İsim ve soyisim zorunludur.";
                    $lineNumber++;
                    continue;
                }
                
                // TC kontrolü (varsa 11 haneli olmalı)
                if (!empty($student['tc_no']) && strlen($student['tc_no']) != 11) {
                    $errors[] = "Satır {$lineNumber}: TC kimlik no 11 haneli olmalıdır. (" . $student['first_name'] . " " . $student['last_name'] . ")";
                }
                
                $students[] = $student;
                
            } catch (Exception $e) {
                $errors[] = "Satır {$lineNumber}: " . $e->getMessage();
            }
            
            $lineNumber++;
        }
        
        return [
            'success' => true,
            'message' => count($students) . ' öğrenci başarıyla okundu.' . (count($errors) > 0 ? ' ' . count($errors) . ' hata ile.' : ''),
            'data' => $students,
            'errors' => $errors,
            'total' => count($students),
            'error_count' => count($errors)
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Excel dosyası okunamadı: ' . $e->getMessage(),
            'data' => []
        ];
    }
}

/**
 * Öğrenci verilerini Excel'e export et
 * 
 * @param array $students Öğrenci dizisi
 * @param string $filename Dosya adı (opsiyonel)
 * @return bool|string Başarılıysa dosya yolu, değilse false
 */
function exportStudentsToExcel($students, $filename = null) {
    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Başlık satırı
        $headers = [
            'TC', 'İsim', 'Soyisim', 'Sınıfı', 'Doğum Tarihi',
            'Baba Adı', 'Baba Telefon', 'Anne Adı', 'Anne Telefon',
            'Adres', 'Öğretmen', 'Öğretmen Telefon'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        
        // Başlık stilini ayarla
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
        
        // Sütun genişliklerini ayarla
        $sheet->getColumnDimension('A')->setWidth(15); // TC
        $sheet->getColumnDimension('B')->setWidth(20); // İsim
        $sheet->getColumnDimension('C')->setWidth(20); // Soyisim
        $sheet->getColumnDimension('D')->setWidth(12); // Sınıfı
        $sheet->getColumnDimension('E')->setWidth(15); // Doğum Tarihi
        $sheet->getColumnDimension('F')->setWidth(25); // Baba Adı
        $sheet->getColumnDimension('G')->setWidth(15); // Baba Telefon
        $sheet->getColumnDimension('H')->setWidth(25); // Anne Adı
        $sheet->getColumnDimension('I')->setWidth(15); // Anne Telefon
        $sheet->getColumnDimension('J')->setWidth(40); // Adres
        $sheet->getColumnDimension('K')->setWidth(25); // Öğretmen
        $sheet->getColumnDimension('L')->setWidth(15); // Öğretmen Telefon
        
        // Veri satırları
        $row = 2;
        foreach ($students as $student) {
            $sheet->setCellValue('A' . $row, $student['tc_no'] ?? '');
            $sheet->setCellValue('B' . $row, $student['first_name'] ?? '');
            $sheet->setCellValue('C' . $row, $student['last_name'] ?? '');
            $sheet->setCellValue('D' . $row, $student['class'] ?? '');
            $sheet->setCellValue('E' . $row, $student['birth_date'] ?? '');
            $sheet->setCellValue('F' . $row, $student['father_name'] ?? '');
            $sheet->setCellValue('G' . $row, $student['father_phone'] ?? '');
            $sheet->setCellValue('H' . $row, $student['mother_name'] ?? '');
            $sheet->setCellValue('I' . $row, $student['mother_phone'] ?? '');
            $sheet->setCellValue('J' . $row, $student['address'] ?? '');
            $sheet->setCellValue('K' . $row, $student['teacher_name'] ?? '');
            $sheet->setCellValue('L' . $row, $student['teacher_phone'] ?? '');
            
            $row++;
        }
        
        // Tüm hücrelere border ekle
        $sheet->getStyle('A1:L' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        // Dosya adı oluştur
        if (!$filename) {
            $filename = 'ogrenci_bilgileri_' . date('Y-m-d_His') . '.xlsx';
        }
        
        $filePath = UPLOAD_PATH . '/excel/' . $filename;
        
        // Excel klasörünü oluştur
        $excelDir = UPLOAD_PATH . '/excel';
        if (!is_dir($excelDir)) {
            mkdir($excelDir, 0755, true);
        }
        
        // Dosyayı kaydet
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        
        return $filePath;
        
    } catch (Exception $e) {
        error_log("Excel export hatası: " . $e->getMessage());
        return false;
    }
}

/**
 * Boş Excel şablonu oluştur
 * 
 * @return string|bool Dosya yolu veya false
 */
function createStudentExcelTemplate() {
    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Başlık satırı
        $headers = [
            'TC', 'İsim', 'Soyisim', 'Sınıfı', 'Doğum Tarihi',
            'Baba Adı', 'Baba Telefon', 'Anne Adı', 'Anne Telefon',
            'Adres', 'Öğretmen', 'Öğretmen Telefon'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        
        // Başlık stili
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
        
        // Sütun genişlikleri
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(40);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setWidth(15);
        
        // Örnek satır ekle
        $exampleRow = [
            '12345678901',
            'AHMET',
            'YILMAZ',
            '9-A',
            '01.01.2008',
            'MEHMET YILMAZ',
            '05301234567',
            'AYŞE YILMAZ',
            '05301234568',
            'ÖRNEK MAHALLE ÖRNEK SOKAK NO:1',
            'FATİH ÖZTÜRK',
            '05301234569'
        ];
        
        $sheet->fromArray($exampleRow, null, 'A2');
        
        // Örnek satırı gri yap
        $sheet->getStyle('A2:L2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3F4F6']
            ]
        ]);
        
        // Açıklama notu ekle
        $sheet->setCellValue('A4', 'NOT: Öğrenci bilgilerini 2. satırdan itibaren giriniz.');
        $sheet->setCellValue('A5', 'TC kimlik numarası boş bırakılabilir.');
        $sheet->setCellValue('A6', 'Doğum tarihi formatı: GG.AA.YYYY (örn: 01.01.2008)');
        $sheet->setCellValue('A7', 'Sınıf formatı: 9-A, 10-B, 8.SINIF gibi yazılabilir.');
        
        $sheet->getStyle('A4:A7')->getFont()->setItalic(true)->setSize(10);
        
        $filename = 'ogrenci_sablonu.xlsx';
        $filePath = UPLOAD_PATH . '/excel/' . $filename;
        
        // Excel klasörünü oluştur
        $excelDir = UPLOAD_PATH . '/excel';
        if (!is_dir($excelDir)) {
            mkdir($excelDir, 0755, true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        
        return $filePath;
        
    } catch (Exception $e) {
        error_log("Excel şablon oluşturma hatası: " . $e->getMessage());
        return false;
    }
}

/**
 * Excel başlıklarını eşleştir (esnek arama)
 */
function mapExcelHeaders($actualHeaders, $expectedHeaders) {
    $mapping = [];
    
    foreach ($expectedHeaders as $expected) {
        $found = false;
        
        foreach ($actualHeaders as $index => $actual) {
            // Başlıkları temizle ve karşılaştır
            $cleanExpected = mb_strtolower(trim((string)$expected));
            $cleanActual = mb_strtolower(trim((string)$actual));
            
            if ($cleanExpected === $cleanActual) {
                $mapping[$expected] = $index;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            // Bazı sütunlar eksik olabilir, sadece uyarı ver
            error_log("Excel sütunu bulunamadı: " . $expected);
        }
    }
    
    return $mapping;
}

/**
 * Satırın boş olup olmadığını kontrol et
 */
function isEmptyRow($row) {
    foreach ($row as $cell) {
        if (!empty(trim((string)$cell))) {
            return false;
        }
    }
    return true;
}

/**
 * TC kimlik numarasını temizle
 */
function cleanTcNo($value) {
    if (empty($value)) {
        return null;
    }
    
    // Sadece rakamları al
    $tc = preg_replace('/[^0-9]/', '', $value);
    
    // 11 haneli değilse null dön
    if (strlen($tc) != 11) {
        return null;
    }
    
    return $tc;
}

/**
 * İsim/soyisim temizle (Türkçe karakterleri koru)
 */
function cleanName($value) {
    if (empty($value)) {
        return '';
    }
    
    // Başındaki ve sonundaki boşlukları temizle
    $value = trim((string)$value);
    
    // Büyük harfe çevir (Türkçe karakterleri destekle)
    $value = mb_strtoupper($value, 'UTF-8');
    
    // Çoklu boşlukları tek boşluğa indir
    $value = preg_replace('/\s+/', ' ', $value);
    
    return $value;
}

/**
 * Genel metin temizleme
 */
function cleanText($value) {
    if (empty($value)) {
        return '';
    }
    
    return trim((string)$value);
}

/**
 * Telefon numarası temizle
 */
function cleanPhone($value) {
    if (empty($value)) {
        return '';
    }
    
    // Sadece rakamları al
    $phone = preg_replace('/[^0-9]/', '', $value);
    
    // Başında 0 yoksa ekle (Türkiye için)
    if (!empty($phone) && substr($phone, 0, 1) != '0') {
        $phone = '0' . $phone;
    }
    
    return $phone;
}

/**
 * Tarih değerini temizle ve MySQL formatına çevir
 */
function cleanDate($value) {
    if (empty($value)) {
        return null;
    }
    
    // Excel serial date ise
    if (is_numeric($value)) {
        try {
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            return $date->format('Y-m-d'); // MySQL formatı
        } catch (Exception $e) {
            return null;
        }
    }
    
    // String ise parse et
    $dateString = trim((string)$value);
    
    // Farklı tarih formatlarını dene
    $formats = ['Y-m-d', 'd.m.Y', 'd/m/Y', 'd-m-Y', 'Y/m/d'];
    
    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $dateString);
        if ($date !== false) {
            return $date->format('Y-m-d'); // MySQL formatına çevir
        }
    }
    
    // Hiçbir format uymadıysa null dön
    return null;
}

/**
 * Excel dosya boyutunu ve türünü kontrol et
 */
function validateExcelFile($file) {
    $errors = [];
    
    // Dosya var mı?
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $errors[] = 'Dosya yüklenemedi.';
        return $errors;
    }
    
    // Dosya boyutu kontrolü (5MB)
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = 'Dosya boyutu çok büyük. Maksimum ' . (MAX_FILE_SIZE / 1024 / 1024) . ' MB olmalıdır.';
    }
    
    // Dosya uzantısı kontrolü
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['xls', 'xlsx'];
    
    if (!in_array($extension, $allowedExtensions)) {
        $errors[] = 'Geçersiz dosya uzantısı. İzin verilen uzantılar: ' . implode(', ', $allowedExtensions);
    }
    
    // MIME type kontrolü (opsiyonel, bazı sistemlerde farklı olabilir)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    // Excel MIME type'ları geniş bir yelpazede olabilir, bu yüzden sadece uyarı ver
    if (!in_array($mimeType, ALLOWED_EXCEL_TYPES) && !in_array($extension, $allowedExtensions)) {
        $errors[] = 'Dosya formatı tanınamadı. Lütfen geçerli bir Excel dosyası yükleyin.';
    }
    
    return $errors;
}