<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= getCsrfToken() ?>">
    <title><?= $title ?? 'Vildan Portal' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Flatpickr CSS (Modern Date Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
    
    <!-- Tema CSS -->
    <link rel="stylesheet" href="<?= url('assets/css/themes.css') ?>">
    
    <!-- CSRF Token -->
    <?= csrfMeta() ?>
    
    <!-- TEMA SİSTEMİ - SAYFA YÜKLENMEDEN ÖNCE -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('vildan-portal-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php 
    $currentUser = currentUser();
    $userRole = $currentUser['role'] ?? 'user';
    $userFullName = $currentUser['full_name'] ?? 'Kullanıcı';
    ?>
    
    <!-- Top Navigation -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Mobile menu button -->
                    <button 
                        @click="sidebarOpen = !sidebarOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 lg:hidden"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center px-4">
                        <h1 class="text-xl font-bold text-indigo-600">Vildan Portal</h1>
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <button 
                        id="theme-toggle-btn"
                        class="p-2 text-gray-400 hover:text-gray-500 rounded-lg hover:bg-gray-100"
                        title="Tema Değiştir"
                    >
                        <span id="theme-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </span>
                    </button>

                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button 
                            @click="open = !open"
                            class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100"
                        >
                            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                <?= strtoupper(substr($userFullName, 0, 1)) ?>
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden sm:block">
                                <?= e($userFullName) ?>
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div 
                            x-show="open"
                            @click.away="open = false"
                            x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200"
                        >
                            <?php if ($userRole === 'admin'): ?>
                                <a href="<?= url('/admin/settings') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Admin Ayarları
                                </a>
                            <?php endif; ?>
                            <a href="<?= url('/profile') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Profilim
                            </a>
                            <a href="<?= url('/profile/change-password') ?>#change-password" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Şifre Değiştir
                            </a>
                            <hr class="my-1">
                            <a href="<?= url('/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Çıkış Yap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside 
        class="fixed inset-y-0 left-0 z-20 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 ease-in-out lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        x-cloak
    >
        <div class="h-full pt-20 pb-4 overflow-y-auto">
            <nav class="px-3 space-y-1">
                
                <!-- Dashboard -->
                <?php if (hasPermission('dashboard', 'can_view')): ?>
                <a href="<?= url('/dashboard') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Ana Sayfa</span>
                </a>
                <?php endif; ?>

                <!-- Öğrenci Ara -->
                <?php if (hasPermission('student_search', 'can_view')): ?>
                <a href="<?= url('/student-search') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="font-medium">Öğrenci Ara</span>
                </a>
                <?php endif; ?>

                <!-- Öğrenci Bilgileri -->
                <?php if (hasPermission('students', 'can_view')): ?>
                <a href="<?= url('/students') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium">Öğrenci Bilgileri</span>
                </a>
                <?php endif; ?>

                <!-- Etkinlikler -->
                <?php if (hasPermission('activities', 'can_view')): ?>
                <a href="<?= url('/activities') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Etkinlikler</span>
                </a>
                <?php endif; ?>

                <!-- Etkinlik Alanları -->
                <?php if (hasPermission('activity_areas', 'can_view')): ?>
                <a href="<?= url('/activity-areas') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="font-medium">Etkinlik Alanları</span>
                </a>
                <?php endif; ?>

                <!-- Etüt Alanları -->
                <?php if (hasPermission('etut', 'can_view')): ?>
                <a href="<?= url('/etut') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="font-medium">Etüt Alanları</span>
                </a>
                <?php endif; ?>

                <?php if (isAdmin()): ?>
                    <hr class="my-2">
                    
                    <!-- Admin Paneli -->
                    <div class="pt-2">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Yönetim
                        </p>
                        
                        <!-- Kullanıcı Yönetimi -->
                        <a href="<?= url('/admin/users') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="font-medium">Kullanıcılar</span>
                        </a>
                        
                        <!-- Rol İzinleri -->
                        <a href="<?= url('/admin/roles') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="font-medium">Rol İzinleri</span>
                        </a>
                        
                        <!-- Etüt Form Ayarları -->
                        <a href="<?= url('/admin/etut-settings') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span class="font-medium">Etüt Form Ayarları</span>
                        </a>
                        
                        <!-- Bildirimler -->
                        <a href="<?= url('/admin/push-notifications') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="font-medium">Bildirim Gönder</span>
                        </a>
                        
                        <!-- Sistem Ayarları -->
                        <a href="<?= url('/admin/settings') ?>" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">Sistem Ayarları</span>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:pl-64 pt-16">
        <div class="p-2 sm:p-4 lg:p-6">
            
            <!-- Flash Messages -->
            <?php $flash = getFlashMessage(); if ($flash): ?>
                <div class="mb-4 p-3 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <?php if ($flash['type'] === 'success'): ?>
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            <?php else: ?>
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium"><?= e($flash['message']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $content ?>
        </div>
    </main>

    <!-- Mobile sidebar overlay -->
    <div 
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-10 bg-gray-900 bg-opacity-50 lg:hidden"
        x-cloak
    ></div>

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
                this.setTheme(newTheme);
                return newTheme;
            },
            
            updateIcon: function(theme) {
                const iconElement = document.getElementById('theme-icon');
                if (!iconElement) return;
                
                if (theme === 'dark') {
                    // Koyu tema aktif - Güneş ikonu göster
                    iconElement.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>';
                } else {
                    // Açık tema aktif - Ay ikonu göster
                    iconElement.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>';
                }
            }
        };
        
        // Global toggle fonksiyonu
        window.toggleTheme = function() {
            if (window.ThemeManager) {
                window.ThemeManager.toggleTheme();
            }
            return false;
        };
        
        // DOM yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            // İkonu güncelle
            const currentTheme = window.ThemeManager.getCurrentTheme();
            window.ThemeManager.updateIcon(currentTheme);
            
            // Tema butonuna event listener ekle
            const themeBtn = document.getElementById('theme-toggle-btn');
            if (themeBtn) {
                themeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.toggleTheme();
                });
            }
            
            // Klavye kısayolu (Ctrl+Shift+D)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    window.toggleTheme();
                }
            });
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

</body>
</html>