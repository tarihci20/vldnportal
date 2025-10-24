<?php
require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\Models\Role;

header('Content-Type: application/json');

try {
    error_log("=== Test Role Create Started ===");
    
    $roleModel = new Role();
    
    $testData = [
        'role_name' => 'test_role_' . time(),
        'display_name' => 'Test Rol',
        'description' => 'Bu bir test rolüdür',
        'is_active' => 1
    ];
    
    error_log("Test data: " . json_encode($testData));
    
    $result = $roleModel->create($testData);
    
    error_log("Create method returned: " . var_export($result, true));
    
    echo json_encode([
        'success' => true,
        'message' => 'Test başarılı',
        'role_id' => $result,
        'test_data' => $testData
    ], JSON_UNESCAPED_UNICODE);
    
} catch (\Exception $e) {
    error_log("Test exception: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ], JSON_UNESCAPED_UNICODE);
}
?>
