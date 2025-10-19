<?php
/**
 * Sanitize (Temizleme) Yardımcı Fonksiyonları
 * Vildan Portal - Okul Yönetim Sistemi
 */

/**
 * String temizle (genel)
 */
function sanitizeString($value) {
    if (is_null($value)) return null;
    
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    
    return $value;
}

/**
 * Email temizle
 */
function sanitizeEmail($value) {
    if (is_null($value)) return null;
    
    $value = trim($value);
    $value = strtolower($value);
    $value = filter_var($value, FILTER_SANITIZE_EMAIL);
    
    return $value;
}

/**
 * URL temizle
 */
function sanitizeUrl($value) {
    if (is_null($value)) return null;
    
    $value = trim($value);
    $value = filter_var($value, FILTER_SANITIZE_URL);
    
    return $value;
}

/**
 * Integer temizle
 */
function sanitizeInt($value) {
    if (is_null($value) || $value === '') return null;
    
    return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Float temizle
 */
function sanitizeFloat($value) {
    if (is_null($value) || $value === '') return null;
    
    return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Boolean temizle
 */
function sanitizeBool($value) {
    return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
}

/**
 * Array temizle
 */
function sanitizeArray($array, $callback = null) {
    if (!is_array($array)) return [];
    
    if ($callback && is_callable($callback)) {
        return array_map($callback, $array);
    }
    
    return array_map('sanitizeString', $array);
}

/**
 * HTML temizle (güvenli HTML'e izin ver)
 */
function sanitizeHtml($value, $allowedTags = null) {
    if (is_null($value)) return null;
    
    if ($allowedTags === null) {
        $allowedTags = '<p><br><strong><em><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6>';
    }
    
    return strip_tags($value, $allowedTags);
}

/**
 * Telefon numarası temizle
 */
function sanitizePhone($value) {
    if (is_null($value) || $value === '') return null;
    
    // Sadece rakamları al
    $phone = preg_replace('/[^0-9]/', '', $value);
    
    // Başında 0 yoksa ekle
    if (!empty($phone) && substr($phone, 0, 1) !== '0') {
        $phone = '0' . $phone;
    }
    
    return $phone;
}

/**
 * TC Kimlik No temizle
 */
function sanitizeTc($value) {
    if (is_null($value) || $value === '') return null;
    
    // Sadece rakamları al
    $tc = preg_replace('/[^0-9]/', '', $value);
    
    // 11 haneli değilse null dön
    if (strlen($tc) !== 11) {
        return null;
    }
    
    return $tc;
}

/**
 * İsim/Soyisim temizle (Türkçe karakterleri koru)
 */
function sanitizeName($value) {
    if (is_null($value) || $value === '') return null;
    
    // Başındaki ve sonundaki boşlukları temizle
    $value = trim($value);
    
    // Sadece harf, boşluk ve Türkçe karakterlere izin ver
    $value = preg_replace('/[^a-zA-ZğüşöçİĞÜŞÖÇıI\s]/u', '', $value);
    
    // Çoklu boşlukları tek boşluğa indir
    $value = preg_replace('/\s+/', ' ', $value);
    
    // Büyük harfe çevir
    $value = mb_strtoupper($value, 'UTF-8');
    
    return $value;
}

/**
 * Tarih temizle
 */
function sanitizeDate($value, $inputFormat = 'd.m.Y', $outputFormat = 'Y-m-d') {
    if (is_null($value) || $value === '') return null;
    
    try {
        $date = DateTime::createFromFormat($inputFormat, $value);
        
        if ($date === false) {
            return null;
        }
        
        return $date->format($outputFormat);
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Dosya adını temizle
 */
function sanitizeFilename($filename) {
    // Uzantıyı ayır
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    
    // Türkçe karakterleri değiştir
    $search = ['ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ş', 'Ş', 'ö', 'Ö', 'ç', 'Ç'];
    $replace = ['i', 'I', 'g', 'G', 'u', 'U', 's', 'S', 'o', 'O', 'c', 'C'];
    $basename = str_replace($search, $replace, $basename);
    
    // Küçük harfe çevir
    $basename = strtolower($basename);
    
    // Özel karakterleri temizle
    $basename = preg_replace('/[^a-z0-9\-_]/', '-', $basename);
    
    // Çoklu tire/alt çizgileri tek tire yap
    $basename = preg_replace('/[\-_]+/', '-', $basename);
    
    // Başındaki ve sonundaki tireleri temizle
    $basename = trim($basename, '-');
    
    // Boşsa varsayılan isim
    if (empty($basename)) {
        $basename = 'file-' . time();
    }
    
    return $basename . ($extension ? '.' . $extension : '');
}

/**
 * Slug temizle
 */
function sanitizeSlug($value) {
    if (is_null($value) || $value === '') return null;
    
    return slug($value);
}

/**
 * JSON temizle
 */
function sanitizeJson($value) {
    if (is_null($value) || $value === '') return null;
    
    // JSON decode/encode ile temizle
    $decoded = json_decode($value, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        return json_encode($decoded);
    }
    
    return null;
}

/**
 * Sınıf adını temizle
 */
function sanitizeClass($value) {
    if (is_null($value) || $value === '') return null;
    
    // Boşlukları temizle ve trim yap
    $value = trim($value);
    $value = preg_replace('/\s+/', ' ', $value);
    
    return $value;
}

/**
 * Adres temizle
 */
function sanitizeAddress($value) {
    if (is_null($value) || $value === '') return null;
    
    // Strip tags ama satır sonlarını koru
    $value = strip_tags($value);
    
    // Trim
    $value = trim($value);
    
    // Çoklu boşlukları düzenle
    $value = preg_replace('/[ \t]+/', ' ', $value);
    
    return $value;
}

/**
 * Toplu temizlik (array için)
 */
function sanitizeInput($data, $rules = []) {
    $sanitized = [];
    
    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            
            switch ($rule) {
                case 'string':
                    $sanitized[$key] = sanitizeString($value);
                    break;
                case 'email':
                    $sanitized[$key] = sanitizeEmail($value);
                    break;
                case 'url':
                    $sanitized[$key] = sanitizeUrl($value);
                    break;
                case 'int':
                case 'integer':
                    $sanitized[$key] = sanitizeInt($value);
                    break;
                case 'float':
                    $sanitized[$key] = sanitizeFloat($value);
                    break;
                case 'bool':
                case 'boolean':
                    $sanitized[$key] = sanitizeBool($value);
                    break;
                case 'html':
                    $sanitized[$key] = sanitizeHtml($value);
                    break;
                case 'phone':
                    $sanitized[$key] = sanitizePhone($value);
                    break;
                case 'tc':
                    $sanitized[$key] = sanitizeTc($value);
                    break;
                case 'name':
                    $sanitized[$key] = sanitizeName($value);
                    break;
                case 'date':
                    $sanitized[$key] = sanitizeDate($value);
                    break;
                case 'slug':
                    $sanitized[$key] = sanitizeSlug($value);
                    break;
                case 'address':
                    $sanitized[$key] = sanitizeAddress($value);
                    break;
                case 'class':
                    $sanitized[$key] = sanitizeClass($value);
                    break;
                default:
                    $sanitized[$key] = sanitizeString($value);
            }
        } else {
            // Kural yoksa varsayılan string temizliği
            $sanitized[$key] = is_array($value) ? $value : sanitizeString($value);
        }
    }
    
    return $sanitized;
}

/**
 * XSS temizliği (çok katmanlı)
 */
function sanitizeXss($value) {
    if (is_null($value)) return null;
    
    if (is_array($value)) {
        return array_map('sanitizeXss', $value);
    }
    
    // Strip tags
    $value = strip_tags($value);
    
    // HTML entities
    $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Tehlikeli string'leri temizle
    $dangerous = [
        'javascript:',
        'vbscript:',
        'data:text/html',
        'onclick',
        'onerror',
        'onload',
        '<script',
        '</script>',
        '<iframe',
        '</iframe>',
    ];
    
    $value = str_ireplace($dangerous, '', $value);
    
    return $value;
}

/**
 * SQL Injection temizliği (prepared statements tercih edilmeli)
 */
function sanitizeSql($value) {
    if (is_null($value)) return null;
    
    $db = \Core\Database::getInstance();
    $connection = $db->getConnection();
    
    return $connection->quote($value);
}

/**
 * Path traversal temizliği
 */
function sanitizePath($value) {
    if (is_null($value) || $value === '') return null;
    
    // .. ve ./ dizinlerini engelle
    $value = str_replace(['..', './'], '', $value);
    
    // Sadece alfanumerik, tire, alt çizgi ve slash'e izin ver
    $value = preg_replace('/[^a-zA-Z0-9\-_\/]/', '', $value);
    
    return $value;
}

/**
 * Command injection temizliği
 */
function sanitizeCommand($value) {
    if (is_null($value)) return null;
    
    // Tehlikeli karakterleri escape et
    return escapeshellcmd($value);
}

/**
 * CSV injection temizliği
 */
function sanitizeCsv($value) {
    if (is_null($value)) return null;
    
    // = + - @ karakterleri ile başlıyorsa temizle
    $first_char = substr($value, 0, 1);
    
    if (in_array($first_char, ['=', '+', '-', '@'])) {
        $value = "'" . $value;
    }
    
    return $value;
}

/**
 * LDAP injection temizliği
 */
function sanitizeLdap($value) {
    if (is_null($value)) return null;
    
    $sanitized = '';
    $len = strlen($value);
    
    for ($i = 0; $i < $len; $i++) {
        $char = $value[$i];
        
        switch ($char) {
            case '\\':
                $sanitized .= '\\5c';
                break;
            case '*':
                $sanitized .= '\\2a';
                break;
            case '(':
                $sanitized .= '\\28';
                break;
            case ')':
                $sanitized .= '\\29';
                break;
            case "\x00":
                $sanitized .= '\\00';
                break;
            default:
                $sanitized .= $char;
        }
    }
    
    return $sanitized;
}