<?php
/**
 * Test Simple Student Form
 */

// Session başlat
session_start();

// Formu gönderip göndermeyi kontrol et
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<h1>Form Gönderildi!</h1>';
    echo '<pre>';
    echo "POST Verisi:\n";
    print_r($_POST);
    echo "\nSession:\n";
    print_r($_SESSION);
    echo '</pre>';
    exit;
}

// CSRF token oluştur
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_time'] = time();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Simple Student Form</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Test: Yeni Öğrenci Formu</h1>

<form method="POST" action="http://localhost/simple-students" style="max-width: 600px;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    
    <h3>Kişisel Bilgiler</h3>
    <input type="text" name="tc_no" placeholder="TC Kimlik No (11 haneli)" value="12345678901" required>
    <input type="text" name="first_name" placeholder="İsim" value="Ahmet" required>
    <input type="text" name="last_name" placeholder="Soyisim" value="Yılmaz" required>
    <input type="date" name="birth_date" value="2010-01-15" required>
    <select name="class" required>
        <option value="">-- Sınıf Seçin --</option>
        <option value="9-A" selected>9-A</option>
        <option value="10-B">10-B</option>
    </select>
    
    <h3>Baba Bilgileri</h3>
    <input type="text" name="father_name" placeholder="Baba Adı" value="Mehmet Yılmaz">
    <input type="tel" name="father_phone" placeholder="Baba Telefonu" value="05551234567">
    
    <h3>Anne Bilgileri</h3>
    <input type="text" name="mother_name" placeholder="Anne Adı" value="Fatma Yılmaz">
    <input type="tel" name="mother_phone" placeholder="Anne Telefonu" value="05559876543">
    
    <h3>Öğretmen Bilgileri</h3>
    <input type="text" name="teacher_name" placeholder="Öğretmen Adı" value="Öğretmen Adı">
    <input type="tel" name="teacher_phone" placeholder="Öğretmen Telefonu" value="05554443322">
    
    <h3>Diğer</h3>
    <textarea name="notes" placeholder="Notlar" rows="3">Test notları</textarea>
    
    <button type="submit">Gönder</button>
</form>

<hr>
<h3>Session Bilgisi:</h3>
<pre><?php print_r($_SESSION); ?></pre>

</body>
</html>
