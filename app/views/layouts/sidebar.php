<?php
/**
 * Sidebar Layout - Sol Menü
 * Vildan Portal
 */

$user = currentUser();
$role = $user['role_name'] ?? 'user'; // Changed from role_slug to role_name
$currentUrl = $_SERVER['REQUEST_URI'] ?? '/';

// Menü aktif kontrolü
function isActive($path, $currentUrl) {
    return strpos($currentUrl, $path) !== false ? 'bg-primary-100 dark:bg-gray-700' : '';
}

function isActiveText($path, $currentUrl) {
    return strpos($currentUrl, $path) !== false ? 'text-primary-700 dark:text-white' : 'text-gray-700 dark:text-gray-200';
}
?>

<!-- Sidebar -->
<aside 
    x-show="sidebarOpen" 
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700"
>
    <div class="h-full px-3 pb-4 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            
            <!-- Dashboard -->
            <?php if ($role !== 'teacher'): ?>
            <li>
                <a href="<?= url('/dashboard') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/dashboard', $currentUrl) ?>">
                    <i class="fas fa-home w-5 h-5 transition duration-75 <?= isActiveText('/dashboard', $currentUrl) ?>"></i>
                    <span class="ml-3 <?= isActiveText('/dashboard', $currentUrl) ?>">Ana Sayfa</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Öğrenci Arama -->
            <?php if ($role === 'teacher' || hasPermission('student-search', 'can_view')): ?>
            <li>
                <a href="<?= url('/student-search') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/student-search', $currentUrl) ?>">
                    <i class="fas fa-search w-5 h-5 transition duration-75 <?= isActiveText('/student-search', $currentUrl) ?>"></i>
                    <span class="ml-3 <?= isActiveText('/student-search', $currentUrl) ?>">Öğrenci Ara</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Öğrenci Yönetimi -->
            <?php if (hasPermission('students', 'can_view')): ?>
            <li x-data="{ open: <?= strpos($currentUrl, '/students') !== false ? 'true' : 'false' ?> }">
                <button @click="open = !open" type="button" class="flex items-center w-full p-2 text-base transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-user-graduate w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap text-gray-700 dark:text-gray-200">Öğrenci Bilgileri</span>
                    <i class="fas fa-chevron-down w-3 h-3 transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-transition class="py-2 space-y-2 pl-4">
                    <li>
                        <a href="<?= url('/students') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/students', $currentUrl) ?>">
                            <i class="fas fa-list w-4 h-4 transition duration-75 <?= isActiveText('/students', $currentUrl) ?>"></i>
                            <span class="ml-3 <?= isActiveText('/students', $currentUrl) ?>">Öğrenci Listesi</span>
                        </a>
                    </li>
                    <?php if (hasPermission('students', 'can_create')): ?>
                    <li>
                        <a href="<?= url('/students/create') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-plus w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            <span class="ml-3 text-gray-700 dark:text-gray-200">Yeni Öğrenci</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('/students/import') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-file-excel w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            <span class="ml-3 text-gray-700 dark:text-gray-200">Excel'den Yükle</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            
            <!-- Etkinlik Yönetimi -->
            <?php if (hasPermission('activities', 'can_view')): ?>
            <li x-data="{ open: <?= strpos($currentUrl, '/activities') !== false ? 'true' : 'false' ?> }">
                <button @click="open = !open" type="button" class="flex items-center w-full p-2 text-base transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-calendar-alt w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap text-gray-700 dark:text-gray-200">Etkinlikler</span>
                    <i class="fas fa-chevron-down w-3 h-3 transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-transition class="py-2 space-y-2 pl-4">
                    <li>
                        <a href="<?= url('/activities') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/activities', $currentUrl) ?>">
                            <i class="fas fa-list w-4 h-4 transition duration-75 <?= isActiveText('/activities', $currentUrl) ?>"></i>
                            <span class="ml-3 <?= isActiveText('/activities', $currentUrl) ?>">Etkinlik Listesi</span>
                        </a>
                    </li>
                    <?php if (hasPermission('activities', 'can_create')): ?>
                    <li>
                        <a href="<?= url('/activities/create') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-plus w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            <span class="ml-3 text-gray-700 dark:text-gray-200">Yeni Etkinlik</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('activity-areas', 'can_view')): ?>
                    <li>
                        <a href="<?= url('/activity-areas') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-map-marker-alt w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            <span class="ml-3 text-gray-700 dark:text-gray-200">Etkinlik Alanları</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            
            <!-- Etüt Yönetimi -->
            <?php if (hasPermission('etut-ortaokul', 'can_view') || hasPermission('etut-lise', 'can_view')): ?>
            <li x-data="{ open: <?= strpos($currentUrl, '/etut') !== false ? 'true' : 'false' ?> }">
                <button @click="open = !open" type="button" class="flex items-center w-full p-2 text-base transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-book-reader w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap text-gray-700 dark:text-gray-200">Etüt</span>
                    <i class="fas fa-chevron-down w-3 h-3 transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-transition class="py-2 space-y-2 pl-4">
                    <!-- Ortaokul -->
                    <?php if (hasPermission('etut-ortaokul', 'can_view')): ?>
                    <li>
                        <a href="<?= url('/etut/ortaokul') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/etut/ortaokul', $currentUrl) ?>">
                            <i class="fas fa-school w-4 h-4 transition duration-75 <?= isActiveText('/etut/ortaokul', $currentUrl) ?>"></i>
                            <span class="ml-3 <?= isActiveText('/etut/ortaokul', $currentUrl) ?>">Ortaokul Başvuruları</span>
                        </a>
                    </li>
                    <?php if (hasPermission('etut-ortaokul', 'can_create')): ?>
                    <li>
                        <a href="<?= url('/etut/ortaokul/create') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-plus w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            <span class="ml-3 text-gray-700 dark:text-gray-200">Yeni Ortaokul Başvurusu</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Lise -->
                    <?php if (hasPermission('etut-lise', 'can_view')): ?>
                    <li class="pt-2 mt-2 border-t border-gray-300 dark:border-gray-600">
                        <a href="<?= url('/etut/lise') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/etut/lise', $currentUrl) ?>">
                            <i class="fas fa-graduation-cap w-4 h-4 transition duration-75 <?= isActiveText('/etut/lise', $currentUrl) ?>"></i>
                            <span class="ml-3 <?= isActiveText('/etut/lise', $currentUrl) ?>">Lise Başvuruları</span>
                        </a>
                    </li>
                    <?php if (hasPermission('etut-lise', 'can_create')): ?>
                    <li>
                        <a href="<?= url('/etut/lise/create') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-plus w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            <span class="ml-3 text-gray-700 dark:text-gray-200">Yeni Lise Başvurusu</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            
            <!-- Raporlar -->
            <?php if (hasPermission('reports', 'can_view')): ?>
            <li>
                <a href="<?= url('/reports') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fas fa-chart-bar w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                    <span class="ml-3 text-gray-700 dark:text-gray-200">Raporlar</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Admin Panel -->
            <?php if (hasPermission('users', 'can_view') || hasPermission('roles', 'can_view')): ?>
            <li class="pt-4 mt-4 space-y-2 border-t border-gray-200 dark:border-gray-700">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Yönetim</p>
            </li>
            
            <!-- Kullanıcılar -->
            <?php if (hasPermission('users', 'can_view')): ?>
            <li>
                <a href="<?= url('/admin/users') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/admin/users', $currentUrl) ?>">
                    <i class="fas fa-users w-5 h-5 transition duration-75 <?= isActiveText('/admin/users', $currentUrl) ?>"></i>
                    <span class="ml-3 <?= isActiveText('/admin/users', $currentUrl) ?>">Kullanıcılar</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Rol İzinleri -->
            <?php if (hasPermission('roles', 'can_view')): ?>
            <li>
                <a href="<?= url('/admin/roles') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/admin/roles', $currentUrl) ?>">
                    <i class="fas fa-shield-alt w-5 h-5 transition duration-75 <?= isActiveText('/admin/roles', $currentUrl) ?>"></i>
                    <span class="ml-3 <?= isActiveText('/admin/roles', $currentUrl) ?>">Rol İzinleri</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Sistem Ayarları -->
            <?php if (hasPermission('settings', 'can_view')): ?>
            <li>
                <a href="<?= url('/admin/settings') ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group <?= isActive('/admin/settings', $currentUrl) ?>">
                    <i class="fas fa-cog w-5 h-5 transition duration-75 <?= isActiveText('/admin/settings', $currentUrl) ?>"></i>
                    <span class="ml-3 <?= isActiveText('/admin/settings', $currentUrl) ?>">Sistem Ayarları</span>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>
            
        </ul>
        
        <!-- Footer Info -->
        <div class="absolute bottom-0 left-0 w-full p-4 bg-gray-50 dark:bg-gray-700">
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                <i class="fas fa-info-circle mr-2"></i>
                <span>v1.0.0 - © 2025 Vildan Portal</span>
            </div>
        </div>
    </div>
</aside>