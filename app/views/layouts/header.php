<?php
/**
 * Header Layout - PWA Destekli
 * Üst menü ve navigasyon
 */

// Session kontrolü
if (!isLoggedIn()) {
    redirect('/login');
    exit;
}

$user = getUserSession();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vildan Portal - Öğrenci bilgileri, etkinlikler ve etüt yönetimi">
    <title><?= $pageTitle ?? 'Vildan Portal' ?></title>
    
    <!-- ============================================ -->
    <!-- PWA META TAGS -->
    <!-- ============================================ -->
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest/manifest.json">
    
    <!-- Theme Colors -->
    <meta name="theme-color" content="#3B82F6" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e40af" media="(prefers-color-scheme: dark)">
    <meta name="msapplication-TileColor" content="#3B82F6">
    
    <!-- Apple Web App -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Vildan Portal">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="/manifest/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/manifest/icons/icon-192x192.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/manifest/icons/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/manifest/icons/icon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">
    
    <!-- Microsoft Tiles -->
    <meta name="msapplication-config" content="/browserconfig.xml">
    
    <!-- SEO -->
    <meta name="keywords" content="öğrenci yönetimi, etkinlik takibi, etüt sistemi, okul portalı">
    <meta property="og:title" content="Vildan Portal - Öğrenci Yönetim Sistemi">
    <meta property="og:description" content="Öğrenci bilgileri, etkinlikler ve etüt yönetimi için kapsamlı portal">
    <meta property="og:image" content="/manifest/icons/icon-512x512.png">
    
    <!-- ============================================ -->
    <!-- STYLES -->
    <!-- ============================================ -->
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/themes.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/responsive.css') ?>">
    
    <!-- PWA Styles -->
    <style>
        /* PWA Install Banner */
        #pwa-install-banner {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            max-width: 90%;
        }
        
        #pwa-install-banner.show {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        #pwa-install-banner button {
            background: white;
            color: #667eea;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        #pwa-install-banner button:hover {
            transform: scale(1.05);
        }
        
        #pwa-install-banner .close-btn {
            background: transparent;
            color: white;
            padding: 4px 8px;
            opacity: 0.8;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translate(-50%, 100px);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }
        
        /* Online Status Indicator */
        .online-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .online-status.online {
            background: #d1fae5;
            color: #065f46;
        }
        
        .online-status.offline {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .online-status::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        .online-status.online::before {
            background: #10b981;
        }
        
        .online-status.offline::before {
            background: #ef4444;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 animate-fade-in">
    
    <!-- TEMA SİSTEMİ - HEMEN ÇALIŞTIR -->
    <script>
        // Sayfa yüklenmeden önce tema ayarla (FOUC önleme)
        (function() {
            const savedTheme = localStorage.getItem('vildan-portal-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <!-- Logo ve Menu Toggle -->
                <div class="flex items-center gap-3">
                    <!-- Sidebar Toggle (Mobile) -->
                    <button 
                        onclick="toggleSidebar()"
                        class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                        aria-label="Toggle sidebar"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Logo -->
                    <a href="<?= url('dashboard') ?>" class="flex items-center gap-3">
                        <img src="<?= asset('img/logo.png') ?>" alt="Vildan Portal Logo" class="h-10 w-10 object-contain">
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Vildan Portal
                        </span>
                    </a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-2">
                    <!-- Search (Desktop) -->
                    <button 
                        onclick="openSearch()"
                        class="hidden md:flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                        title="Ara (Ctrl+K)"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span class="text-sm">Ara</span>
                    </button>

                    <!-- ============================================ -->
                    <!-- PWA ONLINE STATUS - BURAYA EKLENDİ -->
                    <!-- ============================================ -->
                    <span id="online-status" class="online-status online hidden md:inline-flex">
                        Çevrimiçi
                    </span>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                            title="Bildirimler"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <!-- Badge -->
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Notifications Dropdown -->
                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 animate-scale-in"
                        >
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Bildirimler</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <div class="p-4 text-center text-gray-500 text-sm">
                                    Yeni bildirim yok
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <button 
                        id="theme-toggle-btn"
                        class="p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        title="Tema Değiştir (Ctrl+Shift+D)"
                    >
                        <span id="theme-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition-colors"
                        >
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                <?= strtoupper(mb_substr($user['full_name'], 0, 2)) ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium text-gray-900"><?= e($user['full_name']) ?></div>
                                <div class="text-xs text-gray-500"><?= e($user['role_name']) ?></div>
                            </div>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- User Dropdown -->
                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 animate-scale-in"
                        >
                            <div class="p-2">
                                <a href="<?= url('profile') ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profilim
                                </a>
                                <a href="<?= url('profile/change-password') ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    Şifre Değiştir
                                </a>
                                
                                <div class="border-t border-gray-200 my-2"></div>
                                
                                <a href="<?= url('logout') ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Çıkış Yap
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="flex pt-16">
        <!-- Sidebar -->
        <?php require_once VIEW_PATH . '/layouts/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 p-4 min-h-screen">
            <!-- Alert Component -->
            <?php if (hasFlash()): ?>
                <?php include VIEW_PATH . '/components/alert.php'; ?>
            <?php endif; ?>
            
            <!-- Sayfa içeriği buraya gelecek -->