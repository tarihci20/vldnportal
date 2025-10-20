<?php
/**
 * Test Student Form Submission
 */

// Load constants
require_once dirname(__DIR__) . '/config/constants.php';

// Load helpers
require_once ROOT_PATH . '/app/helpers/session.php';

// Start session
startSession();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_time'] = time();
}

echo "<h1>Test Student Form Submission</h1>";
echo "<p>Session started. CSRF Token: " . htmlspecialchars($_SESSION['csrf_token']) . "</p>";

?>

<form method="POST" action="<?php echo BASE_URL; ?>/students" style="border:1px solid #ccc; padding:20px; max-width:500px;">
    <h2>Test Student Form</h2>
    
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    
    <div style="margin-bottom:10px;">
        <label>TC Kimlik (11 haneli):</label><br>
        <input type="text" name="tc_no" value="12345678901" required>
    </div>
    
    <div style="margin-bottom:10px;">
        <label>Ad:</label><br>
        <input type="text" name="first_name" value="Test" required>
    </div>
    
    <div style="margin-bottom:10px;">
        <label>Soyadı:</label><br>
        <input type="text" name="last_name" value="Öğrenci" required>
    </div>
    
    <div style="margin-bottom:10px;">
        <label>Doğum Tarihi:</label><br>
        <input type="date" name="birth_date" value="2010-01-01" required>
    </div>
    
    <div style="margin-bottom:10px;">
        <label>Sınıf:</label><br>
        <input type="text" name="class" value="9-A" required>
    </div>
    
    <button type="submit">Gönder</button>
</form>

<hr>

<h2>Log Control</h2>
<p><a href="check-db.php">Database Check</a></p>
<p><a href="view-error-log.php">View Error Log</a></p>
