<?php
/**
 * Test Student Creation with Session/Cookies
 * Follow the redirect and check for success message
 */

// Step 1: Get CSRF token from form page
echo "Step 1: Fetching form page...\n";
$formResponse = file_get_contents('https://vldn.in/portalv2/simple-students/create');
preg_match('/csrf_token" value="([^"]+)"/', $formResponse, $matches);
$csrfToken = $matches[1] ?? '';
echo "✓ CSRF Token: " . substr($csrfToken, 0, 20) . "...\n\n";

// Step 2: Prepare form data
echo "Step 2: Preparing form data...\n";
$postData = [
    'csrf_token' => $csrfToken,
    'tc_no' => '98765432100',
    'first_name' => 'Test2 İsim',
    'last_name' => 'Test2 Soyadı',
    'birth_date' => '2010-01-15',
    'class' => '9-B',
    'father_name' => 'Test2 Baba',
    'father_phone' => '05551234567',
    'mother_name' => 'Test2 Anne',
    'mother_phone' => '05559876543',
    'teacher_name' => 'Test2 Öğretmen',
    'teacher_phone' => '05554443322',
    'notes' => 'Test2 notları'
];
echo "✓ Data ready\n\n";

// Step 3: Submit form (with cookie jar)
echo "Step 3: Submitting form...\n";

$cookieFile = tempnam(sys_get_temp_dir(), 'cookies_');

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://vldn.in/portalv2/simple-students',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($postData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectHeader = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

echo "HTTP Code: $httpCode\n";
echo "Redirect URL: " . ($redirectHeader ? $redirectHeader : "N/A") . "\n";

if ($httpCode == 302) {
    // Extract location header from response headers
    preg_match('/Location:\s*([^\r\n]+)/i', $response, $locMatches);
    $location = $locMatches[1] ?? '';
    echo "Location header: " . $location . "\n\n";
}

// Step 4: Follow redirect to /students
echo "Step 4: Following redirect to /students...\n";

$ch2 = curl_init();
curl_setopt_array($ch2, [
    CURLOPT_URL => 'https://vldn.in/portalv2/students',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_HEADER => true,
]);

$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode2\n";

// Extract HTML content (skip headers)
$headerEnd = strpos($response2, "\r\n\r\n");
if ($headerEnd !== false) {
    $htmlContent = substr($response2, $headerEnd + 4);
} else {
    $htmlContent = $response2;
}

// Search for success message
if (stripos($htmlContent, 'başarıyla') !== false) {
    echo "✅ SUCCESS MESSAGE FOUND!\n\n";
} elseif (stripos($htmlContent, 'hatası') !== false) {
    echo "❌ ERROR MESSAGE FOUND!\n\n";
} else {
    echo "⚠️  No success or error message found\n\n";
}

// Show flash message area
preg_match('/<div class="mb-6 p-4 rounded[^>]*>.*?<\/div>/is', $htmlContent, $flashMatches);
if ($flashMatches) {
    echo "Flash message HTML:\n";
    echo substr($flashMatches[0], 0, 200) . "...\n\n";
}

// Cleanup
curl_close($ch);
curl_close($ch2);
unlink($cookieFile);
?>
