<?php
/**
 * Authentication Sınıfı
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace Core;

use Core\Database;
use Core\Session;

class Auth
{
    private static ?Auth $instance = null;
    private Database $db;
    private Session $session;
    private bool $sessionLoaded = false;

    private function __construct()
    {
        $this->db = Database::getInstance();
        $this->session = Session::getInstance();
    }

    /**
     * Singleton instance al
     * @return Auth
     */
    public static function getInstance(): Auth
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Kullanıcı girişi
     * 
     * @param string $username Kullanıcı adı veya email
     * @param string $password Şifre
     * @param bool $remember Beni hatırla
     * @return array ['success' => bool, 'message' => string]
     */
    public function login(string $username, string $password, bool $remember = false): array
    {
        // Kullanıcıyı veritabanından bul (rollerle birlikte)
        $sql = "SELECT u.*, r.role_name as role, r.role_name as role_slug 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.username = :username OR u.email = :email";
        
        $user = $this->db->query($sql)->bind(':username', $username)->bind(':email', $username)->single();

        // Kullanıcı bulundu ve şifre doğru mu?
        if ($user && password_verify($password, $user['password_hash'])) {
            // Başarılı giriş
            
            // Diğer oturumları kontrol et ve gerekirse sonlandır
            $this->handleConcurrentSessions($user);

            // Yeni oturum oluştur
            $this->createUserSession($user, $remember);

            // Son giriş zamanını güncelle
            $this->updateLastLogin($user['id']);

            return ['success' => true];
        }

        // Başarısız giriş
        return ['success' => false, 'message' => 'Geçersiz kullanıcı adı veya şifre.'];
    }

    /**
     * Eşzamanlı oturumları yönet
     * Öğretmen rolü dışındaki kullanıcılar için eski oturumları siler
     * 
     * @param array $user Kullanıcı bilgileri
     * @return void
     */
    private function handleConcurrentSessions(array $user): void
    {
        // Öğretmen rolüne sahipse, birden fazla cihazdan giriş yapabilir
        $role = $user['role'] ?? $user['role_slug'] ?? null;
        
        if ($role === 'teacher') {
            // Öğretmenler için eşzamanlı oturum sınırlaması yok
            return;
        }

        // Diğer roller için mevcut tüm oturumları sil
        $this->db->query("DELETE FROM user_sessions WHERE user_id = :user_id")
                 ->bind(':user_id', $user['id'])
                 ->execute();
    }

    /**
     * Google OAuth ile giriş
     * 
     * @param string $googleId Google ID
     * @param string $email Email
     * @param string $name İsim
     * @return array
     */
    public function loginWithGoogle($googleId, $email, $name)
    {
        // İzin listesinde var mı kontrol et
        $sql = "SELECT * FROM allowed_google_emails WHERE email = :email AND is_active = 1";
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        $allowedEmail = $this->db->single();

        if (!$allowedEmail) {
            return [
                'success' => false,
                'message' => 'Bu Google hesabıyla giriş yapma yetkiniz yok.'
            ];
        }

        // Kullanıcı zaten var mı?
        $sql = "SELECT u.*, r.role_name, r.display_name as role_display_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.google_id = :google_id AND u.is_active = 1 
                LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':google_id', $googleId);
        $user = $this->db->single();

        // Kullanıcı yoksa oluştur
        if (!$user) {
            $userId = $this->createGoogleUser($googleId, $email, $name, $allowedEmail['role_id']);

            if (!$userId) {
                return [
                    'success' => false,
                    'message' => 'Kullanıcı oluşturulamadı.'
                ];
            }

            // Yeni kullanıcıyı yükle
            $sql = "SELECT u.*, r.role_name, r.display_name as role_display_name 
                    FROM users u 
                    LEFT JOIN roles r ON u.role_id = r.id 
                    WHERE u.id = :id";
            $this->db->query($sql);
            $this->db->bind(':id', $userId);
            $user = $this->db->single();
        }

        // Google ile girişte her zaman tek oturum olsun
        $this->db->delete('user_sessions', ['user_id' => $user['id']]);

        // Session oluştur
        $this->createUserSession($user, true); // Google login'de "remember me" varsayılan olarak aktif

        // Son giriş güncelle
        $this->updateLastLogin($user['id']);

        return [
            'success' => true,
            'message' => 'Google ile giriş başarılı.',
            'user' => $user
        ];
    }

    /**
     * Kullanıcı çıkışı
     */
    public function logout(): void
    {
        // "Beni Hatırla" token'ını veritabanından sil
        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $this->db->query("DELETE FROM user_sessions WHERE token = :token")->bind(':token', hash('sha256', $token))->execute();
            
            // Cookie'yi silirken BASE_PATH kullan (çünkü set ederken de BASE_PATH kullanıldı)
            $basePath = defined('BASE_PATH') ? BASE_PATH : '/';
            setcookie('remember_me', '', time() - 3600, $basePath, '', false, true);
        }

        $this->session->destroy();
    }

    /**
     * Giriş yapılmış mı?
     * 
     * @return bool
     */
    public static function check(): bool
    {
        return Session::getInstance()->get('user') !== null;
    }

    /**
     * Return current user array from session. Ensures session/remember-me is loaded.
     * @return array|null
     */
    public static function user(): ?array
    {
        // Ensure the Auth instance attempts to load user from remember-me token if needed
        $instance = self::getInstance();
        try {
            $instance->loadUserFromSession();
        } catch (\Throwable $t) {
            // ignore
        }

        return Session::getInstance()->get('user');
    }

    /**
     * Return current user id or null
     * @return int|null
     */
    public static function id(): ?int
    {
        $u = self::user();
        return isset($u['id']) ? (int)$u['id'] : null;
    }

    /**
     * Return current user role slug
     * @return string|null
     */
    public static function role(): ?string
    {
        $u = self::user();
        if (!$u) return null;
        return $u['role_slug'] ?? $u['role'] ?? null;
    }

    /**
     * Kullanıcının belirli bir role sahip olup olmadığını kontrol eder.
     * 
     * @param string|array $role
     * @return bool
     */
    public static function hasRole(string $role): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        // role_slug varsa onu, yoksa role'ü kullan
        $userRole = $user['role_slug'] ?? $user['role'] ?? null;
        return $userRole === $role;
    }

    /**
     * Kullanıcı belirli bir sayfaya erişebilir mi?
     * 
     * @param string $pageKey
     * @param string $permission (view, create, edit, delete)
     * @return bool
     */
    public function canAccess($pageKey, $permission = 'view')
    {
        if (!$this->check()) {
            return false;
        }

        // Admin her şeye erişebilir
        if ($this->role() === 'admin') {
            return true;
        }

        $sql = "SELECT rpp.can_{$permission} 
                FROM role_page_permissions rpp 
                INNER JOIN pages p ON rpp.page_id = p.id 
                WHERE rpp.role_id = :role_id AND p.page_key = :page_key";

        $this->db->query($sql);
        $this->db->bind(':role_id', $this->user()['role_id']);
        $this->db->bind(':page_key', $pageKey);

        $result = $this->db->single();

        return $result && $result["can_{$permission}"] == 1;
    }

    /**
     * Kullanıcı belirli bir izne sahip mi?
     * 
     * @param string $permission
     * @return bool
     */
    public static function hasPermission(string $permission): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        // Basit rol tabanlı yetkilendirme
        // Daha karmaşık bir sistem için (örneğin veritabanı tabanlı roller/izinler),
        // burada veritabanı sorgusu yapılabilir.
        $role = $user['role_slug'] ?? $user['role'];

        $permissions = [
            'admin' => ['manage_users', 'manage_settings', 'view_reports', 'delete_students'],
            'teacher' => ['view_students', 'manage_activities'],
            'mudur' => ['manage_users', 'manage_settings', 'view_reports', 'delete_students', 'manage_activities'],
            // Diğer roller...
        ];

        return in_array($permission, $permissions[$role] ?? []);
    }


    /**
     * Kullanıcı session'ı oluştur
     */
    private function createUserSession(array $user, bool $remember = false): void
    {
        // Oturum verisini ayarla - TÜM kullanıcı bilgilerini session'a kaydet
        $this->session->set('user', [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'] ?? null,
            'full_name' => $user['full_name'] ?? null,
            'role_id' => $user['role_id'],
            'role' => $user['role'] ?? $user['role_name'],
            'role_slug' => $user['role_slug'] ?? $user['role'] ?? $user['role_name'],
            'can_change_password' => $user['can_change_password'] ?? 1,
            'can_change_username' => $user['can_change_username'] ?? 1
        ]);
        
        // user_id'yi de ayrıca kaydet (geriye dönük uyumluluk için)
        $this->session->set('user_id', $user['id']);

        // "Beni Hatırla" özelliği
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 gün

            $this->db->query(
                "INSERT INTO user_sessions (user_id, token, ip_address, user_agent, expires_at) VALUES (:user_id, :token, :ip, :ua, :expires)",
                [
                    'user_id' => $user['id'],
                    'token' => hash('sha256', $token),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
                    'ua' => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN',
                    'expires' => $expiresAt
                ]
            );

            // Cookie'yi BASE_PATH altında set et
            $basePath = defined('BASE_PATH') ? BASE_PATH : '/';
            setcookie('remember_me', $token, time() + (86400 * 30), $basePath, '', false, true);
        }
    }

    /**
     * Oturumdan veya "Beni Hatırla" çerezinden kullanıcıyı yükler.
     * @return bool
     */
    public function loadUserFromSession(): bool
    {
        // Zaten yüklendiyse tekrar kontrol etme
        if ($this->sessionLoaded) {
            return $this->session->get('user') !== null;
        }
        
        $this->sessionLoaded = true;
        
        if ($this->session->get('user')) {
            return true;
        }

        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $hashedToken = hash('sha256', $token);

            $sql = "SELECT s.*, u.sessions_valid_from, u.id as user_id, u.username, u.email, u.full_name, 
                           u.role_id, u.can_change_password, u.can_change_username,
                           r.role_name as role, r.role_name as role_slug
                    FROM user_sessions s
                    JOIN users u ON s.user_id = u.id
                    LEFT JOIN roles r ON u.role_id = r.id
                    WHERE s.token = :token AND s.expires_at > NOW()";
            
            $sessionData = $this->db->query($sql)->bind(':token', $hashedToken)->single();

            if ($sessionData) {
                // Oturumun geçerliliğini kontrol et (örn: şifre değiştirildi mi?)
                if (isset($sessionData['sessions_valid_from']) && 
                    isset($sessionData['created_at']) &&
                    strtotime($sessionData['sessions_valid_from']) > strtotime($sessionData['created_at'])) {
                    // Bu token artık geçerli değil. Çerezi temizle.
                    $basePath = defined('BASE_PATH') ? BASE_PATH : '/';
                    setcookie('remember_me', '', time() - 3600, $basePath);
                    return false;
                }

                // Oturumu yeniden oluştur - TÜM bilgilerle
                $this->session->set('user', [
                    'id' => $sessionData['user_id'],
                    'username' => $sessionData['username'],
                    'email' => $sessionData['email'],
                    'full_name' => $sessionData['full_name'],
                    'role_id' => $sessionData['role_id'],
                    'role' => $sessionData['role'],
                    'role_slug' => $sessionData['role_slug'],
                    'can_change_password' => $sessionData['can_change_password'],
                    'can_change_username' => $sessionData['can_change_username']
                ]);
                
                $this->session->set('user_id', $sessionData['user_id']);
                
                return true;
            } else {
                // Veritabanında geçerli bir token bulunamadı, çerezi temizle
                $basePath = defined('BASE_PATH') ? BASE_PATH : '/';
                setcookie('remember_me', '', time() - 3600, $basePath);
            }
        }

        return false;
    }


    /**
     * Bir kullanıcı için tüm oturumları geçersiz kıl
     * (Örn: şifre değiştirildiğinde)
     */
    public function invalidateAllUserSessions(int $userId): void
    {
        // 1. `user_sessions` tablosundaki tüm tokenları sil
        $this->db->query("DELETE FROM user_sessions WHERE user_id = :user_id")->bind(':user_id', $userId)->execute();

        // 2. `users` tablosundaki `sessions_valid_from` zaman damgasını güncelle
        // Bu, hala aktif olabilecek "Beni Hatırla" çerezlerini geçersiz kılacaktır.
        $this->db->query("UPDATE users SET sessions_valid_from = NOW() WHERE id = :id")->bind(':id', $userId)->execute();
    }

    /**
     * Google kullanıcısı oluştur
     */
    private function createGoogleUser($googleId, $email, $name, $roleId)
    {
        // Username oluştur (email'den)
        $username = explode('@', $email)[0];

        // Kullanıcı adı benzersiz mi kontrol et
        $counter = 1;
        $originalUsername = $username;
        while ($this->usernameExists($username)) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        // Random şifre oluştur (Google login kullanıldığı için önemli değil)
        $password = bin2hex(random_bytes(16));

        $this->db->insert('users', [
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'full_name' => $name,
            'role_id' => $roleId,
            'google_id' => $googleId,
            'is_active' => 1,
            'can_change_password' => 0
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Username var mı kontrol et
     */
    private function usernameExists($username)
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $this->db->query($sql);
        $this->db->bind(':username', $username);
        $result = $this->db->single();
        return $result['count'] > 0;
    }

    /**
     * Son giriş bilgilerini güncelle
     */
    private function updateLastLogin(int $userId): void
    {
        $this->db->update('users', [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'
        ], ['id' => $userId]);
    }
}
