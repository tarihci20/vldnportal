<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Hoş geldiniz, <?= currentUser() ? e(currentUser()['full_name'] ?? currentUser()['username'] ?? 'Kullanıcı') : 'Kullanıcı' ?>!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Toplam Öğrenci -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Toplam Öğrenci</p>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($students['total'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <!-- Bugünkü Etkinlikler -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Bugünkü Etkinlikler</p>
                <p class="text-2xl font-bold text-gray-900"><?= $today['activities'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <!-- Bugünkü Etütler -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Bugünkü Etütler</p>
                <p class="text-2xl font-bold text-gray-900"><?= $today['etut'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <!-- Bekleyen İşlemler -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Bekleyen Etüt</p>
                <p class="text-2xl font-bold text-gray-900"><?= $etut['pending'] ?? 0 ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Bugünkü Etkinlikler Tablosu -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Bugünkü Etkinlikler</h2>
            <a href="<?= url('/activities') ?>" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
                Tümünü Gör →
            </a>
        </div>
    </div>
    
    <?php if (!empty($todayActivities) && count($todayActivities) > 0): ?>
    <!-- Tablo -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Etkinlik Alanı
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Saat
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Sorumlu Kişi
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Etkinlik Adı
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Durum
                    </th>
                    <th scope="col" class="px-6 py-3">
                        İşlemler
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todayActivities as $activity): ?>
                    <tr class="bg-green-50 dark:bg-green-900/20 border-b dark:border-gray-700 hover:bg-green-100 dark:hover:bg-green-900/30">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?= esc($activity['area_color'] ?? '#6366F1') ?>"></div>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    <?= esc($activity['area_name'] ?? '-') ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            <?= substr($activity['start_time'], 0, 5) ?> - <?= substr($activity['end_time'], 0, 5) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900 dark:text-white">
                                <?= esc($activity['responsible_person'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs truncate font-medium text-gray-900 dark:text-white" title="<?= esc($activity['activity_name'] ?? '-') ?>">
                                <?= esc($activity['activity_name'] ?? '-') ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Bugün
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <?php if (hasPermission('activities', 'can_view')): ?>
                                <a href="<?= url('/activities/' . $activity['id']) ?>" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                   title="Detay">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                
                                <?php if (hasPermission('activities', 'can_edit')): ?>
                                <a href="<?= url('/activities/' . $activity['id'] . '/edit') ?>" 
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                   title="Düzenle">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <!-- Boş Durum -->
    <div class="p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Bugün etkinlik alanlarında etkinlik yok</p>
    </div>
    <?php endif; ?>
</div>