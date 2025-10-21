<?php
/**
 * Test Student Creation
 * Direct test of the store endpoint
 */

// Get CSRF token from form page first
$formResponse = file_get_contents('https://vldn.in/portalv2/simple-students/create');
preg_match('/csrf_token" value="([^"]+)"/', $formResponse, $matches);
$csrfToken = $matches[1] ?? '';

echo "CSRF Token: " . substr($csrfToken, 0, 20) . "...\n\n";

// Prepare form data
$postData = [
    'csrf_token' => $csrfToken,
    'tc_no' => '12345678901',
    'first_name' => 'Test İsim',
    'last_name' => 'Test Soyadı',
    'birth_date' => '2010-01-15',
    'class' => '9-A',
    'father_name' => 'Test Baba',
    'father_phone' => '05551234567',
    'mother_name' => 'Test Anne',
    'mother_phone' => '05559876543',
    'teacher_name' => 'Test Öğretmen',
    'teacher_phone' => '05554443322',
    'notes' => 'Test notları'
];

echo "POST Data:\n";
print_r($postData);

// Make request
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://vldn.in/portalv2/simple-students',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($postData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_COOKIE => 'PHPSESSID=' . session_id(),
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerList = curl_getinfo($ch, CURLINFO_HEADER_OUT);

echo "\n\nHTTP Code: $httpCode\n";
echo "Response (first 500 chars):\n";
echo substr($response, 0, 500) . "\n";

curl_close($ch);

// Check for redirect or error
if (strpos($response, 'başarı') !== false) {
    echo "\n✅ Başarı mesajı bulundu!\n";
} elseif (strpos($response, 'hatası') !== false || strpos($response, 'geçersiz') !== false) {
    echo "\n❌ Hata mesajı bulundu!\n";
}
?>
