<?php
/**
 * Validation (Doğrulama) Yardımcı Fonksiyonları
 * Vildan Portal - Okul Yönetim Sistemi
 */

// (Bu dosya yeniden kaydedildi — baştaki BOM/istek dışı çıktı temizlendi.)

/**
 * Genel validasyon fonksiyonu
 *
 * @param array $data Doğrulanacak veriler
 * @param array $rules Validasyon kuralları
 * @param array $messages Özel hata mesajları
 * @return array|true Hata varsa array, yoksa true
 */
function validate($data, $rules, $messages = []) {
    $errors = [];

    foreach ($rules as $field => $fieldRules) {
        $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);
        $value = $data[$field] ?? null;

        foreach ($ruleList as $rule) {
            $rule = trim($rule);
            $params = [];

            // Parametre içeren kuralları parse et (örn: min:5)
            if (strpos($rule, ':') !== false) {
                list($rule, $paramString) = explode(':', $rule, 2);
                $params = explode(',', $paramString);
            }

            // Validasyon kuralını çalıştır
            $method = 'validate' . ucfirst($rule);

            if (function_exists($method)) {
                $result = call_user_func_array($method, array_merge([$value], $params));

                if ($result !== true) {
                    $messageKey = "{$field}.{$rule}";
                    $errors[$field][] = $messages[$messageKey] ?? $result;
                }
            }
        }
    }

    return empty($errors) ? true : $errors;
}

/**
 * Required - Zorunlu alan
 */
function validateRequired($value) {
    if (is_null($value) || (is_string($value) && trim($value) === '')) {
        return 'Bu alan zorunludur.';
    }
    return true;
}

/**
 * Email validasyonu
 */
function validateEmail($value) {
    if (empty($value)) return true;

    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return 'Geçerli bir e-posta adresi giriniz.';
    }
    return true;
}

/**
 * Min - Minimum uzunluk
 */
function validateMin($value, $min) {
    if (empty($value)) return true;

    if (is_numeric($value)) {
        if ($value < $min) {
            return "Bu alan en az {$min} olmalıdır.";
        }
    } else {
        if (mb_strlen($value) < $min) {
            return "Bu alan en az {$min} karakter olmalıdır.";
        }
    }
    return true;
}

/**
 * Max - Maksimum uzunluk
 */
function validateMax($value, $max) {
    if (empty($value)) return true;

    if (is_numeric($value)) {
        if ($value > $max) {
            return "Bu alan en fazla {$max} olmalıdır.";
        }
    } else {
        if (mb_strlen($value) > $max) {
            return "Bu alan en fazla {$max} karakter olmalıdır.";
        }
    }
    return true;
}

/**
 * Between - Aralık kontrolü
 */
function validateBetween($value, $min, $max) {
    if (empty($value)) return true;

    $minResult = validateMin($value, $min);
    if ($minResult !== true) return $minResult;

    $maxResult = validateMax($value, $max);
    if ($maxResult !== true) return $maxResult;

    return true;
}

/**
 * Numeric - Sayısal değer
 */
function validateNumeric($value) {
    if (empty($value)) return true;

    if (!is_numeric($value)) {
        return 'Bu alan sayısal bir değer olmalıdır.';
    }
    return true;
}

/**
 * Integer - Tam sayı
 */
function validateInteger($value) {
    if (empty($value)) return true;

    if (!filter_var($value, FILTER_VALIDATE_INT)) {
        return 'Bu alan tam sayı olmalıdır.';
    }
    return true;
}

/**
 * Alpha - Sadece harf
 */
function validateAlpha($value) {
    if (empty($value)) return true;

    if (!preg_match('/^[\p{L}\s]+$/u', $value)) {
        return 'Bu alan sadece harf içerebilir.';
    }
    return true;
}

/**
 * Alphanumeric - Harf ve rakam
 */
function validateAlphanumeric($value) {
    if (empty($value)) return true;

    if (!preg_match('/^[\p{L}\p{N}\s]+$/u', $value)) {
        return 'Bu alan sadece harf ve rakam içerebilir.';
    }
    return true;
}

/**
 * URL validasyonu
 */
function validateUrl($value) {
    if (empty($value)) return true;

    if (!filter_var($value, FILTER_VALIDATE_URL)) {
        return 'Geçerli bir URL giriniz.';
    }
    return true;
}

/**
 * IP validasyonu
 */
function validateIp($value) {
    if (empty($value)) return true;

    if (!filter_var($value, FILTER_VALIDATE_IP)) {
        return 'Geçerli bir IP adresi giriniz.';
    }
    return true;
}

/**
 * Date - Tarih formatı
 */
function validateDate($value, $format = 'Y-m-d') {
    if (empty($value)) return true;

    $d = DateTime::createFromFormat($format, $value);
    if (!$d || $d->format($format) !== $value) {
        return "Geçerli bir tarih giriniz. Beklenen format: {$format}";
    }
    return true;
}

/**
 * Same - İki alan eşit mi?
 */
function validateSame($value, $otherField) {
    $data = $_POST;
    $otherValue = $data[$otherField] ?? null;

    if ($value !== $otherValue) {
        return "Bu alan {$otherField} ile eşleşmelidir.";
    }
    return true;
}

/**
 * Different - İki alan farklı mı?
 */
function validateDifferent($value, $otherField) {
    $data = $_POST;
    $otherValue = $data[$otherField] ?? null;

    if ($value === $otherValue) {
        return "Bu alan {$otherField} ile farklı olmalıdır.";
    }
    return true;
}

/**
 * In - Belirli değerler arasında mı?
 */
function validateIn($value, ...$options) {
    if (empty($value)) return true;

    if (!in_array($value, $options)) {
        return 'Geçersiz değer seçildi.';
    }
    return true;
}

/**
 * Not In - Belirli değerler dışında mı?
 */
function validateNotIn($value, ...$options) {
    if (empty($value)) return true;

    if (in_array($value, $options)) {
        return 'Bu değer kabul edilmiyor.';
    }
    return true;
}

/**
 * Regex - Regex pattern kontrolü
 */
function validateRegex($value, $pattern) {
    if (empty($value)) return true;

    if (!preg_match($pattern, $value)) {
        return 'Bu alan geçerli formatta değil.';
    }
    return true;
}

/**
 * TC Kimlik No validasyonu
 */
function validateTc($value) {
    if (empty($value)) return true;

    if (!isValidTcNo($value)) {
        return 'Geçerli bir TC kimlik numarası giriniz.';
    }
    return true;
}

/**
 * Telefon numarası validasyonu (Türkiye)
 */
function validatePhone($value) {
    if (empty($value)) return true;

    if (!isValidPhone($value)) {
        return 'Geçerli bir telefon numarası giriniz. (05XXXXXXXXX)';
    }
    return true;
}

/**
 * File - Dosya yükleme kontrolü
 */
function validateFile($file, $maxSize = null, $allowedTypes = null) {
    if (empty($file) || !isset($file['tmp_name'])) {
        return true;
    }

    // Dosya yükleme hatası kontrolü
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return 'Dosya yüklenirken bir hata oluştu.';
    }

    // Boyut kontrolü
    if ($maxSize && $file['size'] > $maxSize) {
        $maxMB = round($maxSize / 1024 / 1024, 2);
        return "Dosya boyutu {$maxMB} MB'dan küçük olmalıdır.";
    }

    // Tip kontrolü
    if ($allowedTypes) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $types = is_array($allowedTypes) ? $allowedTypes : explode(',', $allowedTypes);

        if (!in_array($extension, $types)) {
            return 'Geçersiz dosya türü. İzin verilen türler: ' . implode(', ', $types);
        }
    }

    return true;
}

/**
 * Image - Resim dosyası kontrolü
 */
function validateImage($file, $maxSize = null) {
    return validateFile($file, $maxSize, ALLOWED_IMAGE_TYPES);
}

/**
 * Password - şifre güvenlik kontrolü
 */
function validatePassword($value) {
    if (empty($value)) return true;

    $errors = [];

    if (strlen($value) < PASSWORD_MIN_LENGTH) {
        $errors[] = "En az " . PASSWORD_MIN_LENGTH . " karakter";
    }

    if (PASSWORD_REQUIRE_UPPERCASE && !preg_match('/[A-Z]/', $value)) {
        $errors[] = "En az bir büyük harf";
    }

    if (PASSWORD_REQUIRE_LOWERCASE && !preg_match('/[a-z]/', $value)) {
        $errors[] = "En az bir küçük harf";
    }

    if (PASSWORD_REQUIRE_NUMBER && !preg_match('/[0-9]/', $value)) {
        $errors[] = "En az bir rakam";
    }

    if (PASSWORD_REQUIRE_SPECIAL && !preg_match('/[^a-zA-Z0-9]/', $value)) {
        $errors[] = "En az bir özel karakter";
    }

    if (!empty($errors)) {
        return 'şifre şu gereksinimleri karşılamalıdır: ' . implode(', ', $errors);
    }

    return true;
}

/**
 * Unique - Veritabanında benzersiz mi?
 */
function validateUnique($value, $table, $column, $exceptId = null) {
    if (empty($value)) return true;

    $db = \Core\Database::getInstance();

    $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";

    if ($exceptId) {
        $sql .= " AND id != :except_id";
    }

    $db->query($sql);
    $db->bind(':value', $value);

    if ($exceptId) {
        $db->bind(':except_id', $exceptId);
    }

    $result = $db->single();

    if ($result['count'] > 0) {
        return 'Bu değer zaten kullanılıyor.';
    }

    return true;
}

/**
 * Exists - Veritabanında var mı?
 */
function validateExists($value, $table, $column) {
    if (empty($value)) return true;

    $db = \Core\Database::getInstance();

    $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";

    $db->query($sql);
    $db->bind(':value', $value);

    $result = $db->single();

    if ($result['count'] == 0) {
        return 'Seçilen değer geçersiz.';
    }

    return true;
}

/**
 * Confirmed - Onay alanı eşleşiyor mu?
 */
function validateConfirmed($value, $field) {
    $data = $_POST;
    $confirmField = $field . '_confirmation';
    $confirmValue = $data[$confirmField] ?? null;

    if ($value !== $confirmValue) {
        return 'Onay alanı eşleşmiyor.';
    }

    return true;
}

/**
 * Accepted - Kabul edildi mi? (checkbox için)
 */
function validateAccepted($value) {
    $accepted = ['yes', 'on', '1', 1, true, 'true'];

    if (!in_array($value, $accepted, true)) {
        return 'Bu alan kabul edilmelidir.';
    }

    return true;
}

/**
 * Boolean - Boolean değer mi?
 */
function validateBoolean($value) {
    $valid = [true, false, 0, 1, '0', '1'];

    if (!in_array($value, $valid, true)) {
        return 'Bu alan true veya false olmalıdır.';
    }

    return true;
}

/**
 * JSON - Geçerli JSON mı?
 */
function validateJson($value) {
    if (empty($value)) return true;

    json_decode($value);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return 'Geçerli bir JSON formatı giriniz.';
    }

    return true;
}