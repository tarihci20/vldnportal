<?php
/**
 * Response Helper Functions (DÜZELTİLMİŞ)
 * JSON, HTML ve HTTP response yönetimi için yardımcı fonksiyonlar
 * * @package VildanPortal
 * @subpackage Helpers
 */

/**
 * JSON response döndürür
 * * @param mixed $data Response verisi
 * @param int $statusCode HTTP status code
 * @param array $headers Ekstra HTTP headers
 * @return void
 */
if (!function_exists('jsonResponse')) {
    function jsonResponse($data, $statusCode = 200, $headers = []) {
        // HTTP status code ayarla
        http_response_code($statusCode);
        
        // Content-Type header
        header('Content-Type: application/json; charset=utf-8');
        
        // Ekstra headerlar
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
        
        // JSON encode ve çıktı
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        exit;
    }
}

/**
 * Başarılı response döndürür (functions.php'deki jsonSuccess'ın karşılığı)
 * * @param mixed $data Response verisi
 * @param string $message Başarı mesajı
 * @param int $statusCode HTTP status code (varsayılan: 200)
 * @return void
 */
if (!function_exists('successResponse')) {
    function successResponse($data = null, $message = 'İşlem başarılı', $statusCode = 200) {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => time()
        ];
        
        jsonResponse($response, $statusCode);
    }
}
// functions.php'den gelen eski isimle uyumluluk için (tercihen sadece successResponse kullanın)
if (!function_exists('jsonSuccess')) {
    function jsonSuccess($data = null, $message = 'İşlem başarılı', $statusCode = 200) {
        successResponse($data, $message, $statusCode);
    }
}


/**
 * Hata response döndürür (functions.php'deki jsonError'ın karşılığı)
 * * @param string $message Hata mesajı
 * @param int $statusCode HTTP status code (varsayılan: 400)
 * @param array $errors Detaylı hata listesi (opsiyonel)
 * @return void
 */
if (!function_exists('errorResponse')) {
    function errorResponse($message = 'İşlem başarısız', $statusCode = 400, $errors = []) {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => time()
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        jsonResponse($response, $statusCode);
    }
}
// functions.php'den gelen eski isimle uyumluluk için (tercihen sadece errorResponse kullanın)
if (!function_exists('jsonError')) {
    function jsonError($message = 'Bir hata oluştu', $code = 400, $errors = null) {
        errorResponse($message, $code, $errors);
    }
}

/**
 * Validasyon hatası response döndürür
 * * @param array $errors Validasyon hata listesi
 * @param string $message Genel mesaj
 * @return void
 */
if (!function_exists('validationErrorResponse')) {
    function validationErrorResponse($errors, $message = 'Girilen bilgilerde hatalar var') {
        errorResponse($message, 422, $errors);
    }
}

/**
 * Yetkilendirme hatası response döndürür
 * * @param string $message Hata mesajı
 * @return void
 */
if (!function_exists('unauthorizedResponse')) {
    function unauthorizedResponse($message = 'Bu işlem için yetkiniz yok') {
        errorResponse($message, 403);
    }
}

/**
 * Kimlik doğrulama hatası response döndürür
 * * @param string $message Hata mesajı
 * @return void
 */
if (!function_exists('unauthenticatedResponse')) {
    function unauthenticatedResponse($message = 'Lütfen giriş yapın') {
        errorResponse($message, 401);
    }
}

/**
 * Bulunamadı hatası response döndürür
 * * @param string $message Hata mesajı
 * @return void
 */
if (!function_exists('notFoundResponse')) {
    function notFoundResponse($message = 'İstenen kayıt bulunamadı') {
        errorResponse($message, 404);
    }
}

/**
 * Sunucu hatası response döndürür
 * * @param string $message Hata mesajı
 * @return void
 */
if (!function_exists('serverErrorResponse')) {
    function serverErrorResponse($message = 'Sunucu hatası oluştu') {
        errorResponse($message, 500);
    }
}

/**
 * Çakışma hatası response döndürür (etkinlik çakışmaları için)
 * * @param string $message Hata mesajı
 * @param array $conflicts Çakışan kayıtlar
 * @return void
 */
if (!function_exists('conflictResponse')) {
    function conflictResponse($message = 'Çakışma tespit edildi', $conflicts = []) {
        errorResponse($message, 409, ['conflicts' => $conflicts]);
    }
}

/**
 * Sayfalama ile birlikte response döndürür
 * * @param array $items Kayıtlar
 * @param int $total Toplam kayıt sayısı
 * @param int $page Mevcut sayfa
 * @param int $perPage Sayfa başına kayıt
 * @param string $message Mesaj
 * @return void
 */
if (!function_exists('paginatedResponse')) {
    function paginatedResponse($items, $total, $page, $perPage, $message = 'Başarılı') {
        $totalPages = ceil($total / $perPage);
        
        $response = [
            'success' => true,
            'message' => $message,
            'data' => [
                'items' => $items,
                'pagination' => [
                    'total' => (int)$total,
                    'per_page' => (int)$perPage,
                    'current_page' => (int)$page,
                    'total_pages' => (int)$totalPages,
                    'has_more' => $page < $totalPages,
                    'from' => ($page - 1) * $perPage + 1,
                    'to' => min($page * $perPage, $total)
                ]
            ],
            'timestamp' => time()
        ];
        
        jsonResponse($response);
    }
}


// functions.php'den taşınan HTML Redirect fonksiyonları

/**
 * HTML redirect yapar (functions.php'den taşındı)
 * * @param string $url Yönlendirilecek URL
 * @param int $statusCode HTTP status code (301 veya 302)
 * @return void
 */
if (!function_exists('redirect')) {
    function redirect($path, $code = 302) {
        if (!function_exists('url')) {
             // Eğer url() fonksiyonu yüklenmediyse (functions.php yüklenmediyse)
             header("Location: $path", true, $code);
        } else if (filter_var($path, FILTER_VALIDATE_URL)) {
             header("Location: $path", true, $code);
        } else {
             $url = url($path);
             header("Location: $url", true, $code);
        }
        exit;
    }
}

/**
 * Geri dön (önceki sayfaya) (functions.php'den taşındı)
 * * @return void
 */
if (!function_exists('back')) {
    function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        redirect($referer);
    }
}

/**
 * Geri dön (önceki sayfaya)
 * * @return void
 */
if (!function_exists('redirectBack')) {
    function redirectBack() {
        back();
    }
}
// Diğer Response/Header Fonksiyonları

/**
 * Flash mesaj ile birlikte redirect
 * * @param string $url Yönlendirilecek URL
 * @param string $message Flash mesaj
 * @param string $type Mesaj tipi (success, error, warning, info)
 * @return void
 */
if (!function_exists('redirectWithMessage')) {
    function redirectWithMessage($url, $message, $type = 'success') {
        if (function_exists('setFlashMessage')) {
            setFlashMessage($message, $type);
        }
        redirect($url);
    }
}

/**
 * Hata mesajı ile geri dön
 * * @param string $message Hata mesajı
 * @return void
 */
if (!function_exists('redirectBackWithError')) {
    function redirectBackWithError($message) {
        if (function_exists('setFlashMessage')) {
            setFlashMessage($message, 'error');
        }
        back();
    }
}

/**
 * Başarı mesajı ile geri dön
 * * @param string $message Başarı mesajı
 * @return void
 */
if (!function_exists('redirectBackWithSuccess')) {
    function redirectBackWithSuccess($message) {
        if (function_exists('setFlashMessage')) {
            setFlashMessage($message, 'success');
        }
        back();
    }
}

/**
 * HTTP status code ayarlar
 * * @param int $code Status code
 * @return void
 */
if (!function_exists('setStatusCode')) {
    function setStatusCode($code) {
        http_response_code($code);
    }
}

/**
 * Custom header ekler
 * * @param string $key Header anahtarı
 * @param string $value Header değeri
 * @return void
 */
if (!function_exists('setHeader')) {
    function setHeader($key, $value) {
        header("$key: $value");
    }
}

/**
 * CORS headerlarını ayarlar
 * * @param string|array $allowedOrigins İzin verilen origin(ler)
 * @param array $allowedMethods İzin verilen HTTP metodları
 * @param array $allowedHeaders İzin verilen headerlar
 * @return void
 */
if (!function_exists('setCorsHeaders')) {
    function setCorsHeaders($allowedOrigins = '*', $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'], $allowedHeaders = ['Content-Type', 'Authorization']) {
        // Origin kontrolü
        if (is_array($allowedOrigins)) {
            $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
            if (in_array($origin, $allowedOrigins)) {
                header("Access-Control-Allow-Origin: $origin");
            }
        } else {
            header("Access-Control-Allow-Origin: $allowedOrigins");
        }
        
        header('Access-Control-Allow-Methods: ' . implode(', ', $allowedMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $allowedHeaders));
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // 24 saat
        
        // Preflight request için
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}

/**
 * Cache headerlarını ayarlar
 * * @param int $seconds Cache süresi (saniye)
 * @return void
 */
if (!function_exists('setCacheHeaders')) {
    function setCacheHeaders($seconds = 3600) {
        $expires = gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT';
        
        header("Cache-Control: public, max-age=$seconds");
        header("Expires: $expires");
        header('Pragma: cache');
    }
}

/**
 * No-cache headerlarını ayarlar
 * * @return void
 */
if (!function_exists('setNoCacheHeaders')) {
    function setNoCacheHeaders() {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}

/**
 * Download header ayarlar (dosya indirme için)
 * * @param string $filename Dosya adı
 * @param string $contentType Content-Type
 * @return void
 */
if (!function_exists('setDownloadHeaders')) {
    function setDownloadHeaders($filename, $contentType = 'application/octet-stream') {
        header("Content-Type: $contentType");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
    }
}

/**
 * Excel download header ayarlar
 * * @param string $filename Dosya adı
 * @return void
 */
if (!function_exists('setExcelDownloadHeaders')) {
    function setExcelDownloadHeaders($filename) {
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        setDownloadHeaders($filename, $contentType);
    }
}

/**
 * PDF download header ayarlar
 * * @param string $filename Dosya adı
 * @return void
 */
if (!function_exists('setPdfDownloadHeaders')) {
    function setPdfDownloadHeaders($filename) {
        setDownloadHeaders($filename, 'application/pdf');
    }
}

/**
 * CSV download header ayarlar
 * * @param string $filename Dosya adı
 * @return void
 */
if (!function_exists('setCsvDownloadHeaders')) {
    function setCsvDownloadHeaders($filename) {
        setDownloadHeaders($filename, 'text/csv; charset=utf-8');
    }
}

/**
 * API rate limit headerlarını ayarlar
 * * @param int $limit Maksimum istek sayısı
 * @param int $remaining Kalan istek sayısı
 * @param int $reset Reset zamanı (Unix timestamp)
 * @return void
 */
if (!function_exists('setRateLimitHeaders')) {
    function setRateLimitHeaders($limit, $remaining, $reset) {
        header("X-RateLimit-Limit: $limit");
        header("X-RateLimit-Remaining: $remaining");
        header("X-RateLimit-Reset: $reset");
    }
}

/**
 * Response'u buffer'a alır ve string olarak döndürür
 * * @param callable $callback Çalıştırılacak fonksiyon
 * @return string Buffer içeriği
 */
if (!function_exists('captureOutput')) {
    function captureOutput($callback) {
        ob_start();
        call_user_func($callback);
        $output = ob_get_clean();
        return $output;
    }
}