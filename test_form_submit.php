<?php
/**
 * Test form submission with cookie persistence
 */
$baseUrl = 'https://vldn.in/portalv2';
$cookieFile = '/tmp/test_cookies.txt';

// Step 1: Get CSRF token
echo "Step 1: Fetching form...\n";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$baseUrl/simple-students/create",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
]);

$formHtml = curl_exec($ch);
curl_close($ch);

if (!preg_match('/csrf_token" value="([^"]+)"/', $formHtml, $matches)) {
    die("❌ CSRF token not found!\n");
}

$csrfToken = $matches[1];
echo "✓ CSRF Token: " . substr($csrfToken, 0, 30) . "...\n";
echo "✓ Cookies saved to: $cookieFile\n\n";

// Step 2: Prepare form data
echo "Step 2: Preparing form data...\n";
$formData = [
    'csrf_token' => $csrfToken,
    'tc_no' => '55555555555',
    'first_name' => 'Başarılı',
    'last_name' => 'Test',
    'birth_date' => '2009-10-10',
    'class' => '11-B',
    'father_name' => 'Baba',
    'father_phone' => '05551111111',
    'mother_name' => 'Anne',
    'mother_phone' => '05552222222',
    'teacher_name' => 'Öğretmen',
    'teacher_phone' => '05553333333',
    'notes' => 'Test'
];

echo "Submitting with TC: " . $formData['tc_no'] . "\n\n";

// Step 3: Submit form (WITH SAME SESSION)
echo "Step 3: Submitting form...\n";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$baseUrl/simple-students",
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($formData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HEADER => true,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Parse response headers and body
list($headers, $body) = explode("\r\n\r\n", $response, 2);

echo "✓ HTTP Status: $httpCode\n";

// Extract Location header (case-insensitive)
if (preg_match('/^location:\s*(.+)$/im', $headers, $locationMatches)) {
    $location = trim($locationMatches[1]);
    echo "✓ Redirect to: $location\n\n";
    
    // Step 4: Follow redirect (WITH SAME COOKIES)
    echo "Step 4: Following redirect...\n";
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $location,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIEFILE => $cookieFile,
    ]);
    
    $finalResponse = curl_exec($ch);
    curl_close($ch);
    
    echo "✓ Final page loaded\n\n";
    
    // Check for success message
    if (stripos($finalResponse, 'başarıyla') !== false) {
        echo "✅ ✅ ✅ SUCCESS MESSAGE FOUND! ✅ ✅ ✅\n";
    } elseif (stripos($finalResponse, 'hatası') !== false) {
        echo "❌ ERROR MESSAGE FOUND\n";
        // Extract error message
        if (preg_match('/<strong>([^<]+)<\/strong>/', $finalResponse, $errorMatch)) {
            echo "Error: " . $errorMatch[1] . "\n";
        }
    } else {
        echo "⚠️  No flash message found\n";
        echo "\nResponse (first 1000 chars):\n";
        echo substr($finalResponse, 0, 1000) . "\n";
    }
} else {
    echo "❌ NO REDIRECT HEADER\n";
    echo "Response headers:\n";
    echo $headers . "\n";
    echo "\nResponse body (first 500 chars):\n";
    echo substr($body, 0, 500) . "\n";
}

// Cleanup
@unlink($cookieFile);
echo "\n✓ Test complete!\n";
?>
