<?php
/**
 * Simülasyon: Checkbox form submission
 * Unchecked checkbox'lar nasıl POST'a gelmez, bunu gösteriyor
 */

echo "=== HTML Form Submission Simülasyonu ===\n\n";

// Simüle edilen form submission
// Sayfa 12 (Ortaokul Etüt Başvuruları): can_view ve can_create checked
// Sayfa 12: can_edit ve can_delete UNCHECKED
// Sayfa 13 (Lise Etüt Başvuruları): Tüm alanlar UNCHECKED
// Sayfa 14 (Bazı başka sayfa): Tüm alanlar UNCHECKED

echo "Formda şu checkboxlar ticked:\n";
echo "✓ permissions[12][can_view]\n";
echo "✓ permissions[12][can_create]\n";
echo "✗ permissions[12][can_edit] (UNCHECKED - FORM'DA GÖNDERİLMEZ)\n";
echo "✗ permissions[12][can_delete] (UNCHECKED - FORM'DA GÖNDERİLMEZ)\n";
echo "✗ permissions[13][can_view] (UNCHECKED - FORM'DA GÖNDERİLMEZ)\n";
echo "✗ permissions[13][can_create] (UNCHECKED - FORM'DA GÖNDERİLMEZ)\n";
echo "✗ permissions[13][can_edit] (UNCHECKED - FORM'DA GÖNDERİLMEZ)\n";
echo "✗ permissions[13][can_delete] (UNCHECKED - FORM'DA GÖNDERİLMEZ)\n";
echo "✗ permissions[14][can_view] (UNCHECKED - FORM'DA GÖNDERİLMEZ)\n";
echo "✗ ... vb\n\n";

// Gerçek POST array'i
$_POST_permissions = [
    12 => [
        'can_view' => 1,
        'can_create' => 1
        // can_edit ve can_delete EKSIK!
    ]
    // Page 13 ve 14 EKSIK!
];

echo "Sonuç: \$_POST['permissions'] = " . json_encode($_POST_permissions) . "\n\n";

echo "=== ESKİ APPROACH (HATALI) ===\n";
echo "Loopla gelen permission'ları kaydet:\n";
foreach ($_POST_permissions as $pageId => $perms) {
    echo "  Page $pageId: can_view=" . (isset($perms['can_view']) ? 1 : 0) . ", can_create=" . (isset($perms['can_create']) ? 1 : 0) . ", can_edit=" . (isset($perms['can_edit']) ? 1 : 0) . ", can_delete=" . (isset($perms['can_delete']) ? 1 : 0) . "\n";
}
echo "\nSonuç: Page 13 ve 14 HIÇBIR ZAM VERİLMİYOR!\n\n";

echo "=== YENİ APPROACH (DOĞRU) ===\n";
// Tüm sayfaları al
$allPages = [
    12 => ['id' => 12, 'page_name' => 'Ortaokul Etüt Başvuruları', 'is_active' => 1, 'etut_type' => 'ortaokul'],
    13 => ['id' => 13, 'page_name' => 'Lise Etüt Başvuruları', 'is_active' => 1, 'etut_type' => 'lise'],
    14 => ['id' => 14, 'page_name' => 'Başka Sayfa', 'is_active' => 1, 'etut_type' => 'all'],
];

echo "Tüm sayfalar için permission verisi oluştur:\n";
$permissionData = [];
foreach ($allPages as $page) {
    $pageId = $page['id'];
    $perms = $_POST_permissions[$pageId] ?? [];
    
    echo "  Page $pageId: Form'da " . count($perms) . " permission";
    
    $permissionData[] = [
        'page_id' => $pageId,
        'can_view' => isset($perms['can_view']) ? 1 : 0,
        'can_create' => isset($perms['can_create']) ? 1 : 0,
        'can_edit' => isset($perms['can_edit']) ? 1 : 0,
        'can_delete' => isset($perms['can_delete']) ? 1 : 0
    ];
    echo " → kaydet: can_view=" . $permissionData[count($permissionData)-1]['can_view'] . ", can_create=" . $permissionData[count($permissionData)-1]['can_create'] . ", can_edit=" . $permissionData[count($permissionData)-1]['can_edit'] . ", can_delete=" . $permissionData[count($permissionData)-1]['can_delete'] . "\n";
}

echo "\nSonuç: Tüm sayfalar veriye sahip!\n";
echo json_encode($permissionData, JSON_PRETTY_PRINT) . "\n";
?>
