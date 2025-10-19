<?php
/**
 * PWA Icon Generator
 * 
 * Tek bir yüksek çözünürlüklü logo'dan tüm PWA icon'larını oluşturur
 * 
 * Kullanım:
 * php generate-pwa-icons.php /path/to/source-logo.png
 * 
 * Gereksinimler:
 * - GD veya Imagick extension
 * - Kaynak dosya: En az 512x512px PNG
 */

// Hata raporlamayı aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Komut satırı kontrolü
if (php_sapi_name() !== 'cli') {
    die("Bu script sadece komut satırından çalıştırılabilir.\n");
}

// Extension kontrolü
$hasGD = extension_loaded('gd');
$hasImagick = extension_loaded('imagick');

if (!$hasGD && !$hasImagick) {
    die("Hata: GD veya Imagick extension'ı yüklü değil.\n");
}

echo "===========================================\n";
echo "PWA Icon Generator - Vildan Portal\n";
echo "===========================================\n\n";

// Kaynak dosya kontrolü
$sourceFile = $argv[1] ?? null;

if (!$sourceFile) {
    echo "Kullanım: php generate-pwa-icons.php /path/to/source-logo.png\n\n";
    echo "Kaynak dosya gereksinimleri:\n";
    echo "- Format: PNG (transparan arkaplan önerilir)\n";
    echo "- Minimum boyut: 512x512px\n";
    echo "- Önerilen boyut: 1024x1024px veya daha büyük\n";
    echo "- Kare format olmalı (1:1 aspect ratio)\n";
    exit(1);
}

if (!file_exists($sourceFile)) {
    die("Hata: Kaynak dosya bulunamadı: $sourceFile\n");
}

// Icon boyutları
$iconSizes = [
    16, 32, 72, 96, 128, 144, 152, 192, 384, 512
];

// Splash screen boyutları (Apple)
$splashSizes = [
    ['width' => 640, 'height' => 1136, 'name' => 'iphone5_splash.png'],
    ['width' => 750, 'height' => 1334, 'name' => 'iphone6_splash.png'],
    ['width' => 1242, 'height' => 2208, 'name' => 'iphoneplus_splash.png'],
    ['width' => 1125, 'height' => 2436, 'name' => 'iphonex_splash.png'],
    ['width' => 828, 'height' => 1792, 'name' => 'iphonexr_splash.png'],
    ['width' => 1242, 'height' => 2688, 'name' => 'iphonexsmax_splash.png'],
    ['width' => 1536, 'height' => 2048, 'name' => 'ipad_splash.png'],
    ['width' => 1668, 'height' => 2224, 'name' => 'ipadpro1_splash.png'],
    ['width' => 2048, 'height' => 2732, 'name' => 'ipadpro2_splash.png'],
];

// Çıktı dizinleri
$outputDir = __DIR__ . '/../public/manifest';
$iconsDir = $outputDir . '/icons';
$splashDir = $outputDir . '/splash';

// Dizinleri oluştur
createDirectory($iconsDir);
createDirectory($splashDir);

// Kaynak resmi yükle
echo "Kaynak dosya yükleniyor: $sourceFile\n";
$sourceImage = loadImage($sourceFile);

if (!$sourceImage) {
    die("Hata: Kaynak resim yüklenemedi.\n");
}

// Boyut kontrolü
$sourceWidth = imagesx($sourceImage);
$sourceHeight = imagesy($sourceImage);

echo "Kaynak boyut: {$sourceWidth}x{$sourceHeight}px\n";

if ($sourceWidth < 512 || $sourceHeight < 512) {
    die("Hata: Kaynak resim en az 512x512px olmalıdır.\n");
}

if ($sourceWidth !== $sourceHeight) {
    echo "Uyarı: Kaynak resim kare değil. Kırpılacak.\n";
}

echo "\n";

// İkon'ları oluştur
echo "Icon'lar oluşturuluyor...\n";
echo "-------------------------------------------\n";

foreach ($iconSizes as $size) {
    $outputFile = $iconsDir . "/icon-{$size}x{$size}.png";
    
    if (resizeAndSave($sourceImage, $outputFile, $size, $size)) {
        echo "✓ {$size}x{$size}px icon oluşturuldu\n";
    } else {
        echo "✗ {$size}x{$size}px icon oluşturulamadı!\n";
    }
}

// Favicon.ico oluştur (32x32)
$faviconFile = __DIR__ . '/../public/favicon.ico';
echo "\nFavicon oluşturuluyor...\n";
echo "-------------------------------------------\n";

if ($hasImagick && file_exists($iconsDir . '/icon-32x32.png')) {
    try {
        $favicon = new Imagick($iconsDir . '/icon-32x32.png');
        $favicon->setImageFormat('ico');
        $favicon->writeImage($faviconFile);
        echo "✓ favicon.ico oluşturuldu\n";
    } catch (Exception $e) {
        echo "✗ favicon.ico oluşturulamadı: " . $e->getMessage() . "\n";
    }
} else {
    echo "ℹ Imagick extension yok, favicon.ico oluşturulamadı\n";
    echo "  32x32 PNG'yi manuel olarak .ico'ya çevirebilirsiniz\n";
}

// Badge icon oluştur (küçük bildirim ikonu)
echo "\nBadge icon oluşturuluyor...\n";
echo "-------------------------------------------\n";

$badgeFile = $iconsDir . '/badge-72x72.png';
if (resizeAndSave($sourceImage, $badgeFile, 72, 72, true)) {
    echo "✓ Badge icon oluşturuldu (72x72px, monochrome)\n";
}

// Splash screen'ler oluştur
echo "\nSplash screen'ler oluşturuluyor...\n";
echo "-------------------------------------------\n";

foreach ($splashSizes as $splash) {
    $outputFile = $splashDir . '/' . $splash['name'];
    
    if (createSplashScreen($sourceImage, $outputFile, $splash['width'], $splash['height'])) {
        echo "✓ {$splash['name']} oluşturuldu ({$splash['width']}x{$splash['height']})\n";
    } else {
        echo "✗ {$splash['name']} oluşturulamadı!\n";
    }
}

// Wide tile (Microsoft)
echo "\nMicrosoft Wide Tile oluşturuluyor...\n";
echo "-------------------------------------------\n";

$wideFile = $iconsDir . '/wide-310x150.png';
if (createWideTile($sourceImage, $wideFile, 310, 150)) {
    echo "✓ Wide tile oluşturuldu (310x150px)\n";
}

// Temizlik
imagedestroy($sourceImage);

echo "\n===========================================\n";
echo "✓ Tüm icon'lar başarıyla oluşturuldu!\n";
echo "===========================================\n";
echo "Çıktı dizini: $outputDir\n\n";

echo "Oluşturulan dosyalar:\n";
echo "- " . count($iconSizes) . " adet PWA icon\n";
echo "- " . count($splashSizes) . " adet splash screen\n";
echo "- 1 adet badge icon\n";
echo "- 1 adet wide tile\n";
if ($hasImagick) {
    echo "- 1 adet favicon.ico\n";
}
echo "\n";

// ================== HELPER FUNCTIONS ==================

function createDirectory($path) {
    if (!file_exists($path)) {
        mkdir($path, 0755, true);
    }
}

function loadImage($file) {
    $imageInfo = getimagesize($file);
    
    if (!$imageInfo) {
        return false;
    }
    
    $mimeType = $imageInfo['mime'];
    
    switch ($mimeType) {
        case 'image/png':
            return imagecreatefrompng($file);
        case 'image/jpeg':
            return imagecreatefromjpeg($file);
        case 'image/gif':
            return imagecreatefromgif($file);
        default:
            return false;
    }
}

function resizeAndSave($sourceImage, $outputFile, $width, $height, $monochrome = false) {
    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);
    
    // Yeni resim oluştur
    $newImage = imagecreatetruecolor($width, $height);
    
    // Transparan arkaplan için alpha blending
    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);
    $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
    imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
    imagealphablending($newImage, true);
    
    // Resize (merkeze kırp)
    $size = min($sourceWidth, $sourceHeight);
    $x = ($sourceWidth - $size) / 2;
    $y = ($sourceHeight - $size) / 2;
    
    imagecopyresampled(
        $newImage, $sourceImage,
        0, 0, $x, $y,
        $width, $height, $size, $size
    );
    
    // Monochrome (badge için)
    if ($monochrome) {
        imagefilter($newImage, IMG_FILTER_GRAYSCALE);
    }
    
    // Kaydet
    $result = imagepng($newImage, $outputFile, 9);
    
    imagedestroy($newImage);
    
    return $result;
}

function createSplashScreen($sourceImage, $outputFile, $width, $height) {
    // Arkaplan rengi (gradient)
    $splash = imagecreatetruecolor($width, $height);
    
    // Gradient arkaplan (Vildan Portal renkleri)
    $startColor = imagecolorallocate($splash, 59, 130, 246);   // #3B82F6
    $endColor = imagecolorallocate($splash, 118, 75, 162);     // #764ba2
    
    for ($i = 0; $i < $height; $i++) {
        $r = 59 + ($i / $height) * (118 - 59);
        $g = 130 + ($i / $height) * (75 - 130);
        $b = 246 + ($i / $height) * (162 - 246);
        $color = imagecolorallocate($splash, $r, $g, $b);
        imageline($splash, 0, $i, $width, $i, $color);
    }
    
    // Logo'yu ortala
    $logoSize = min($width, $height) * 0.4; // %40 boyut
    $logoX = ($width - $logoSize) / 2;
    $logoY = ($height - $logoSize) / 2;
    
    // Logo resize
    $logo = imagecreatetruecolor($logoSize, $logoSize);
    imagealphablending($logo, false);
    imagesavealpha($logo, true);
    
    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);
    $size = min($sourceWidth, $sourceHeight);
    $x = ($sourceWidth - $size) / 2;
    $y = ($sourceHeight - $size) / 2;
    
    imagecopyresampled(
        $logo, $sourceImage,
        0, 0, $x, $y,
        $logoSize, $logoSize, $size, $size
    );
    
    // Logo'yu splash'e kopyala (alpha blending ile)
    imagealphablending($splash, true);
    imagecopy($splash, $logo, $logoX, $logoY, 0, 0, $logoSize, $logoSize);
    
    // Kaydet
    $result = imagepng($splash, $outputFile, 9);
    
    imagedestroy($logo);
    imagedestroy($splash);
    
    return $result;
}

function createWideTile($sourceImage, $outputFile, $width, $height) {
    // Wide tile oluştur (Microsoft için)
    $tile = imagecreatetruecolor($width, $height);
    
    // Gradient arkaplan
    for ($i = 0; $i < $width; $i++) {
        $r = 59 + ($i / $width) * (118 - 59);
        $g = 130 + ($i / $width) * (75 - 130);
        $b = 246 + ($i / $width) * (162 - 246);
        $color = imagecolorallocate($tile, $r, $g, $b);
        imageline($tile, $i, 0, $i, $height, $color);
    }
    
    // Logo'yu sola hizala
    $logoSize = $height * 0.7; // Yüksekliğin %70'i
    $logoX = $height * 0.15;   // Sol marjin
    $logoY = ($height - $logoSize) / 2;
    
    // Logo resize
    $logo = imagecreatetruecolor($logoSize, $logoSize);
    imagealphablending($logo, false);
    imagesavealpha($logo, true);
    
    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);
    $size = min($sourceWidth, $sourceHeight);
    $x = ($sourceWidth - $size) / 2;
    $y = ($sourceHeight - $size) / 2;
    
    imagecopyresampled(
        $logo, $sourceImage,
        0, 0, $x, $y,
        $logoSize, $logoSize, $size, $size
    );
    
    // Logo'yu tile'a kopyala
    imagealphablending($tile, true);
    imagecopy($tile, $logo, $logoX, $logoY, 0, 0, $logoSize, $logoSize);
    
    // Kaydet
    $result = imagepng($tile, $outputFile, 9);
    
    imagedestroy($logo);
    imagedestroy($tile);
    
    return $result;
}