<?php
/**
 * Footer Layout - PWA Destekli
 * Alt bilgi ve PWA bileşenleri
 */
?>

        </main>
        <!-- Main Content Sonu -->
    </div>
    <!-- Main Layout Sonu -->

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600">
                    &copy; <?= date('Y') ?> Vildan Portal. Tüm hakları saklıdır.
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <a href="<?= url('help') ?>" class="hover:text-indigo-600">Yardım</a>
                    <a href="<?= url('privacy') ?>" class="hover:text-indigo-600">Gizlilik</a>
                    <a href="<?= url('terms') ?>" class="hover:text-indigo-600">Kullanım Şartları</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ============================================ -->
    <!-- PWA COMPONENTS -->
    <!-- ============================================ -->
    
    <!-- PWA Install Banner -->
    <div id="pwa-install-banner">
        <div style="flex: 1;">
            <strong>📱 Uygulamayı Yükle</strong>
            <p style="margin: 4px 0 0 0; font-size: 14px; opacity: 0.9;">
                Ana ekrana ekleyerek hızlı erişim sağlayın
            </p>
        </div>
        <button onclick="window.PWA && window.PWA.promptInstall()">Yükle</button>
        <button class="close-btn" onclick="document.getElementById('pwa-install-banner').classList.remove('show')">✕</button>
    </div>

    <!-- ============================================ -->
    <!-- JAVASCRIPT -->
    <!-- ============================================ -->
    
    <!-- Mevcut JS dosyalarınız -->
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/ajax-handler.js') ?>"></script>
    
    <!-- Sidebar Toggle (Mobile) -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
        
        function openSearch() {
            window.location.href = '<?= url('student-search') ?>';
        }
    </script>
    
    <!-- TEMA YÖNETİCİSİ -->
    <script>
        // Global Tema Yöneticisi
        window.ThemeManager = {
            storageKey: 'vildan-portal-theme',
            defaultTheme: 'light',
            
            getCurrentTheme: function() {
                return localStorage.getItem(this.storageKey) || this.defaultTheme;
            },
            
            setTheme: function(theme) {
                console.log('Tema ayarlanıyor:', theme);
                localStorage.setItem(this.storageKey, theme);
                document.documentElement.setAttribute('data-theme', theme);
                
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                
                this.updateIcon(theme);
            },
            
            toggleTheme: function() {
                const current = this.getCurrentTheme();
                const newTheme = current === 'light' ? 'dark' : 'light';
                console.log('Tema değiştiriliyor:', current, '->', newTheme);
                this.setTheme(newTheme);
                return newTheme;
            },
            
            updateIcon: function(theme) {
                const iconElement = document.getElementById('theme-icon');
                if (!iconElement) {
                    console.warn('theme-icon elementi bulunamadı');
                    return;
                }
                
                if (theme === 'dark') {
                    // Koyu tema aktif - Güneş ikonu göster
                    iconElement.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>';
                } else {
                    // Açık tema aktif - Ay ikonu göster
                    iconElement.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>';
                }
            }
        };
        
        // Global toggle fonksiyonu
        window.toggleTheme = function() {
            console.log('toggleTheme çağrıldı');
            if (window.ThemeManager) {
                window.ThemeManager.toggleTheme();
            } else {
                console.error('ThemeManager yüklenmedi!');
            }
            return false;
        };
        
        // DOM yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Tema sistemi yükleniyor...');
            
            // İkonu güncelle
            const currentTheme = window.ThemeManager.getCurrentTheme();
            window.ThemeManager.updateIcon(currentTheme);
            
            // Tema butonuna event listener ekle
            const themeBtn = document.getElementById('theme-toggle-btn');
            if (themeBtn) {
                themeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Tema butonu tıklandı (event listener)');
                    window.toggleTheme();
                });
                console.log('Tema butonu event listener eklendi');
            } else {
                console.warn('Tema butonu bulunamadı');
            }
            
            // Klavye kısayolu
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    console.log('Klavye kısayolu kullanıldı');
                    window.toggleTheme();
                }
            });
            
            console.log('Tema sistemi hazır!');
        });
        
        // Multi-tab senkronizasyonu
        window.addEventListener('storage', function(e) {
            if (e.key === 'vildan-portal-theme') {
                const newTheme = e.newValue || 'light';
                document.documentElement.setAttribute('data-theme', newTheme);
                if (newTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                window.ThemeManager.updateIcon(newTheme);
            }
        });
    </script>
    
    <!-- PWA Handler'ı başlat -->
    <script type="module">
        import PWA from '/assets/js/pwa-handler.js';
        
        // PWA install banner'ı göster (30 saniye sonra)
        setTimeout(() => {
            if (window.PWA && window.PWA.deferredPrompt) {
                const banner = document.getElementById('pwa-install-banner');
                if (banner && !localStorage.getItem('pwa-install-dismissed')) {
                    banner.classList.add('show');
                    
                    // 10 saniye sonra otomatik kapat
                    setTimeout(() => {
                        banner.classList.remove('show');
                        localStorage.setItem('pwa-install-dismissed', 'true');
                    }, 10000);
                }
            }
        }, 30000); // 30 saniye bekle
        
        // Push notification izni iste (kullanıcı giriş yaptıktan 5 dakika sonra)
        setTimeout(() => {
            if (window.PWA && Notification.permission === 'default') {
                // SweetAlert2 varsa onu kullan
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Bildirimlere İzin Ver',
                        text: 'Etkinlik hatırlatmaları ve önemli güncellemeler için bildirim almak ister misiniz?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'İzin Ver',
                        cancelButtonText: 'Şimdi Değil',
                        confirmButtonColor: '#3B82F6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.PWA.requestNotificationPermission()
                                .then(granted => {
                                    if (granted) {
                                        Swal.fire({
                                            title: 'Harika!',
                                            text: 'Artık bildirim alabilirsiniz.',
                                            icon: 'success',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                    }
                                });
                        }
                    });
                } else {
                    // SweetAlert2 yoksa basit confirm
                    if (confirm('Etkinlik hatırlatmaları için bildirim almak ister misiniz?')) {
                        window.PWA.requestNotificationPermission();
                    }
                }
            }
        }, 5 * 60 * 1000); // 5 dakika bekle
        
        // Service Worker mesajlarını dinle
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('message', (event) => {
                const { type, data } = event.data;
                
                if (type === 'sync-success') {
                    // Sync başarılı olduğunda bildirim göster
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Senkronize Edildi!',
                            text: 'Offline yapılan işlemler başarıyla gönderildi.',
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                }
            });
        }
    </script>
    
    <!-- Sayfa özel script'leri (isteğe bağlı) -->
    <?php if (isset($pageScript)): ?>
        <?= $pageScript ?>
    <?php endif; ?>
    
    <!-- Analytics (Optional) -->
    <?php if (defined('GA_TRACKING_ID') && !empty(GA_TRACKING_ID)): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= GA_TRACKING_ID ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= GA_TRACKING_ID ?>');
    </script>
    <?php endif; ?>

</body>
</html>