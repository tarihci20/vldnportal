# \# 🎓 Vildan Portal - Okul Yönetim Sistemi

# 

# Modern, güvenli ve kullanıcı dostu okul yönetim sistemi.

# 

# \## 🚀 Hızlı Başlangıç

# 

# \### 1️⃣ Dosyaları İndirin

# ```bash

# \# GitHub'dan clone edin veya ZIP indirebilirsiniz

# git clone https://github.com/your-repo/vildan-portal.git

# cd vildan-portal

# ```

# 

# \### 2️⃣ Composer Bağımlılıklarını Yükleyin

# ```bash

# composer install

# ```

# 

# \### 3️⃣ Veritabanını Kurun

# 1\. phpMyAdmin'de yeni veritabanı oluşturun: `vildan\_portal`

# 2\. `database/schema.sql` dosyasını import edin

# 

# \### 4️⃣ Yapılandırma

# ```bash

# \# Config dosyasını oluşturun

# cp config/config.example.php config/config.php

# 

# \# config.php'yi düzenleyin (veritabanı bilgileri)

# nano config/config.php

# ```

# 

# \### 5️⃣ Test Edin

# Tarayıcınızda açın: `http://localhost/vildan-portal/install.php`

# 

# \### 6️⃣ Giriş Yapın

# \- \*\*Admin:\*\* admin / Admin123!

# \- \*\*Öğretmen:\*\* ogretmen / Ogretmen123!

# 

# ---

# 

# \## ✨ Özellikler

# 

# \### 👥 Kullanıcı Yönetimi

# \- Rol tabanlı yetkilendirme (Admin, Öğretmen, Sekreter, Müdür, Müdür Yardımcısı)

# \- Google OAuth entegrasyonu

# \- Şifre sıfırlama sistemi

# \- Beni hatırla özelliği (30 gün)

# \- Eşzamanlı oturum desteği (öğretmenler için özel)

# 

# \### 👨‍🎓 Öğrenci Yönetimi

# \- Gelişmiş arama (debounce ile anlık filtreleme)

# \- Excel import/export

# \- Detaylı öğrenci profilleri

# \- Hızlı iletişim (Anne/Baba/Öğretmen ara)

# \- Toplu işlemler

# 

# \### 📅 Etkinlik Yönetimi

# \- Etkinlik alanı rezervasyonu

# \- Çakışma kontrolü

# \- Tekrar kuralları (günlük, haftalık, aylık)

# \- Takvim görünümü (sürükle-bırak)

# \- Renk kodlaması

# 

# \### 📚 Etüt Yönetimi

# \- Etüt başvuruları

# \- Onay sistemi

# \- Öğrenci bazlı takip

# 

# \### 📊 Dashboard ve Raporlama

# \- Günlük özet

# \- Haftalık doluluk grafikleri

# \- Öğrenci istatistikleri

# \- Hızlı erişim kısayolları

# 

# \### 🎨 Kullanıcı Deneyimi

# \- Responsive tasarım (mobil, tablet, desktop)

# \- Açık/Koyu tema desteği

# \- PWA (Progressive Web App) - Offline çalışma

# \- Türkçe arayüz

# \- Hızlı ve akıcı kullanım

# 

# \## 📋 Sistem Gereksinimleri

# 

# \### Sunucu Gereksinimleri

# \- PHP 7.4 veya üzeri (PHP 8.x önerilir)

# \- MySQL 5.7+ veya MariaDB 10.3+

# \- Apache 2.4+ (mod\_rewrite aktif)

# \- SSL sertifikası (HTTPS önerilir)

# 

# \### PHP Eklentileri

# \- PDO

# \- MySQLi

# \- mbstring

# \- JSON

# \- GD veya Imagick (resim işleme için)

# \- Zip (Excel için)

# \- OpenSSL

# 

# \### cPanel Hosting Gereksinimleri

# \- PHP 7.4+

# \- MySQL veritabanı

# \- 500 MB disk alanı (minimum)

# \- Composer desteği (veya manuel yükleme)

# 

# \## 🚀 Kurulum

# 

# \### 1. Dosyaları Yükleyin

# 

# \*\*cPanel File Manager ile:\*\*

# 1\. cPanel'e giriş yapın

# 2\. File Manager'ı açın

# 3\. `public\_html` klasörüne gidin

# 4\. Tüm dosyaları yükleyin (ZIP olarak yükleyip açabilirsiniz)

# 

# \*\*FTP ile:\*\*

# ```bash

# \# FileZilla veya benzeri FTP programı ile

# \# Tüm dosyaları public\_html klasörüne yükleyin

# ```

# 

# \### 2. Veritabanı Oluşturun

# 

# 1\. cPanel > MySQL Databases

# 2\. Yeni veritabanı oluşturun: `vildanportal\_db`

# 3\. Yeni kullanıcı oluşturun: `vildanportal\_user`

# 4\. Kullanıcıya tüm yetkileri verin

# 5\. phpMyAdmin'i açın

# 6\. Veritabanınızı seçin

# 7\. `database/schema.sql` dosyasını import edin

# 

# \### 3. Yapılandırma

# 

# ```bash

# \# config.example.php dosyasını kopyalayın

# cp config/config.example.php config/config.php

# 

# \# config.php dosyasını düzenleyin

# nano config/config.php

# ```

# 

# \*\*Minimum yapılandırma:\*\*

# ```php

# define('BASE\_URL', 'https://siteniz.com');

# define('DB\_HOST', 'localhost');

# define('DB\_NAME', 'vildanportal\_db');

# define('DB\_USER', 'vildanportal\_user');

# define('DB\_PASS', 'guvenli-sifreniz');

# ```

# 

# \### 4. Composer Bağımlılıklarını Yükleyin

# 

# \*\*SSH erişimi varsa:\*\*

# ```bash

# cd /home/kullanici/public\_html

# composer install --no-dev --optimize-autoloader

# ```

# 

# \*\*SSH erişimi yoksa:\*\*

# 1\. Yerel bilgisayarınızda `composer install` çalıştırın

# 2\. `vendor` klasörünü sunucuya yükleyin

# 

# \### 5. Klasör İzinlerini Ayarlayın

# 

# ```bash

# chmod 755 public/assets/uploads

# chmod 755 storage/logs

# chmod 755 storage/cache

# chmod 755 storage/backups

# ```

# 

# \### 6. .htaccess Kontrolü

# 

# Ana `.htaccess` dosyası:

# ```apache

# RewriteEngine On

# RewriteCond %{REQUEST\_FILENAME} !-f

# RewriteCond %{REQUEST\_FILENAME} !-d

# RewriteRule ^(.\*)$ public/index.php?url=$1 \[QSA,L]

# 

# \# Güvenlik başlıkları

# Header set X-Frame-Options "SAMEORIGIN"

# Header set X-Content-Type-Options "nosniff"

# Header set X-XSS-Protection "1; mode=block"

# ```

# 

# \### 7. İlk Giriş

# 

# \*\*Varsayılan Admin Hesabı:\*\*

# \- Kullanıcı adı: `admin`

# \- E-posta: `admin@vildanportal.com`

# \- Şifre: `Admin123!`

# 

# \*\*⚠️ ÖNEMLİ:\*\* İlk girişten sonra mutlaka şifrenizi değiştirin!

# 

# \*\*Öğretmen Hesabı:\*\*

# \- Kullanıcı adı: `ogretmen`

# \- Şifre: `Ogretmen123!`

# 

# \## 🔧 Yapılandırma Önerileri

# 

# \### Google OAuth Kurulumu

# 

# 1\. \[Google Cloud Console](https://console.cloud.google.com)

# 2\. Yeni proje oluşturun

# 3\. OAuth consent screen yapılandırın

# 4\. Credentials > OAuth 2.0 Client ID

# 5\. Authorized redirect URIs: `https://siteniz.com/auth/google/callback`

# 6\. Client ID ve Secret'i `config.php`'ye ekleyin

# 

# ```php

# define('GOOGLE\_CLIENT\_ID', 'your-client-id');

# define('GOOGLE\_CLIENT\_SECRET', 'your-client-secret');

# define('GOOGLE\_OAUTH\_ENABLED', true);

# ```

# 

# \### E-posta Ayarları (Şifre Sıfırlama)

# 

# Gmail kullanıyorsanız:

# 1\. Google hesabınızda "2-Step Verification" aktif olmalı

# 2\. \[App Password](https://myaccount.google.com/apppasswords) oluşturun

# 3\. Oluşturduğunuz şifreyi kullanın

# 

# ```php

# define('MAIL\_HOST', 'smtp.gmail.com');

# define('MAIL\_PORT', 587);

# define('MAIL\_USERNAME', 'your-email@gmail.com');

# define('MAIL\_PASSWORD', 'your-16-digit-app-password');

# define('MAIL\_ENCRYPTION', 'tls');

# ```

# 

# \### SSL/HTTPS Kurulumu

# 

# cPanel'de Let's Encrypt ile ücretsiz SSL:

# 1\. cPanel > SSL/TLS Status

# 2\. Alan adınızı seçin

# 3\. "Run AutoSSL" tıklayın

# 4\. config.php'de HTTPS'i aktif edin:

# ```php

# define('BASE\_URL', 'https://siteniz.com');

# define('SESSION\_COOKIE\_SECURE', true);

# ```

# 

# \### PWA Yapılandırması

# 

# `public/manifest/manifest.json`:

# ```json

# {

# &nbsp; "name": "Vildan Portal",

# &nbsp; "short\_name": "Vildan",

# &nbsp; "start\_url": "/",

# &nbsp; "display": "standalone",

# &nbsp; "theme\_color": "#3B82F6",

# &nbsp; "background\_color": "#FFFFFF"

# }

# ```

# 

# \## 📱 Mobil Cihazlara Kurulum

# 

# \### iOS (Safari)

# 1\. Safari'de siteyi açın

# 2\. Paylaş butonuna basın

# 3\. "Ana Ekrana Ekle"

# 

# \### Android (Chrome)

# 1\. Chrome'da siteyi açın

# 2\. Menü > "Ana ekrana ekle"

# 

# \## 🔐 Güvenlik Kontrol Listesi

# 

# \- \[ ] Varsayılan admin şifresini değiştirin

# \- \[ ] Database şifresini güçlü yapın

# \- \[ ] `config.php` dosyasının web'den erişilemediğinden emin olun

# \- \[ ] HTTPS kullanın

# \- \[ ] PHP hata gösterimini production'da kapatın

# \- \[ ] Düzenli veritabanı yedeği alın

# \- \[ ] Dosya yükleme klasörlerini güvenli hale getirin

# \- \[ ] Google OAuth izin listesini güncelleyin

# 

# \## 🗂️ Yedekleme

# 

# \### Manuel Yedekleme

# ```bash

# \# Veritabanı yedeği

# mysqldump -u kullanici -p vildanportal\_db > backup.sql

# 

# \# Dosya yedeği

# tar -czf vildan-backup.tar.gz public\_html/

# ```

# 

# \### Otomatik Yedekleme (cPanel)

# 1\. cPanel > Backup Wizard

# 2\. "Backup" seçin

# 3\. "Full Backup" veya "Partial Backup"

# 4\. Düzenli zamanlama ayarlayın

# 

# \## 🐛 Sorun Giderme

# 

# \### Beyaz ekran görünüyorsa

# ```php

# // config.php'de hata raporlamayı açın

# define('APP\_DEBUG', true);

# define('DISPLAY\_ERRORS', true);

# ```

# 

# \### Veritabanı bağlantı hatası

# \- cPanel'de veritabanı kullanıcısının doğru eklendiğini kontrol edin

# \- `config.php`'deki DB bilgilerini kontrol edin

# \- phpMyAdmin'de veritabanına erişebildiğinizi test edin

# 

# \### Session hataları

# ```bash

# \# Session klasörü izinlerini kontrol edin

# chmod 755 storage/

# ```

# 

# \### Google OAuth çalışmıyorsa

# \- Client ID ve Secret doğru mu?

# \- Redirect URI tam olarak eşleşiyor mu?

# \- Domain doğrulanmış mı?

# 

# \## 📚 Kullanım Kılavuzu

# 

# \### Yeni Öğrenci Ekleme

# 1\. Öğrenci Bilgileri > Yeni Ekle

# 2\. Formu doldurun

# 3\. Kaydet

# 

# \### Excel'den Öğrenci Yükleme

# 1\. Öğrenci Bilgileri > Excel ile Yükle

# 2\. Örnek şablonu indirin

# 3\. Doldurup yükleyin

# 

# \### Etkinlik Rezervasyonu

# 1\. Etkinlik Alanı > Yeni

# 2\. Alan, tarih, saat seçin

# 3\. Çakışma kontrolü otomatik

# 4\. Kaydet

# 

# \### Tekrar Kuralı Oluşturma

# 1\. Etkinlik oluştururken "Tekrarla" seçin

# 2\. Kural seçin (günlük, haftalık, vs.)

# 3\. Sistem otomatik oluşturur

# 

# \## 🔄 Güncelleme

# 

# ```bash

# \# Dosyaları yedekleyin

# \# Yeni sürümü indirin

# \# Eski dosyaların üzerine yazın

# \# Veritabanı migration varsa çalıştırın

# ```

# 

# \## 📞 Destek

# 

# Sorun yaşıyorsanız:

# 1\. Dokümantasyonu kontrol edin

# 2\. Log dosyalarını inceleyin (`storage/logs/`)

# 3\. Hata mesajını not edin

# 

# \## ⚡ Performans ve Kapasite

# 

# \### Öğrenci Kapasitesi

# \- \*\*Desteklenen:\*\* 10,000+ öğrenci

# \- \*\*Optimize Edilmiş:\*\* 1,500+ öğrenci için özel ayarlar

# \- \*\*Excel Import:\*\* Tek seferde 2,000 satır (bölünerek yüklenebilir)

# \- \*\*Sayfalama:\*\* Her sayfada 50 öğrenci

# \- \*\*Arama:\*\* Debounce ile hızlı sonuçlar

# 

# \### Sistem Gereksinimleri (1500+ Öğrenci için)

# \- PHP Memory: 512M (config'de ayarlanmış)

# \- Max Execution Time: 600 saniye

# \- Upload Limit: 20MB

# \- MySQL: InnoDB engine (index'li tablolar)

# 

# \### Performans Özellikleri

# \- ✅ Index'lenmiş veritabanı sorguları

# \- ✅ Sayfalama ile yükleme

# \- ✅ Lazy loading

# \- ✅ Debounce arama (500ms)

# \- ✅ Chunk processing (100 kayıt/batch)

# \- ✅ AJAX ile asenkron yükleme

# 

# \## 👥 Geliştirici

# 

# Vildan Portal v1.0.0

# 

# ---

# 

# \*\*⚠️ Önemli Notlar:\*\*

# \- İlk kurulumdan sonra mutlaka güvenlik ayarlarını yapın

# \- Production ortamında `APP\_DEBUG` false yapın

# \- Düzenli yedek alın

# \- Sunucu loglarını takip edin

# 

# \*\*🎉 Kurulum tamamlandı! Başarılar dileriz.\*\*

