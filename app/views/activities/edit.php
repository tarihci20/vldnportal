<?php
/**
 * Etkinlik Düzenleme Sayfası
 * Vildan Portal
 */

$pageTitle = 'Etkinlik Düzenle';
$activity = $data['activity'] ?? null;
$activityAreas = $data['activityAreas'] ?? [];
$timeSlots = $data['timeSlots'] ?? [];

if (!$activity) {
    redirect('/activities');
    exit;
}
?>

<!-- Main Content -->
<div class="p-2 sm:ml-48">
    <div class="p-4 mt-14">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= url('/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                        <i class="fas fa-home mr-2"></i> Ana Sayfa
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="<?= url('/activities') ?>" class="text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">Etkinlikler</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Düzenle</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Etkinlik Düzenle</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <?= esc($activity['student_name']) ?> - <?= esc($activity['area_name']) ?>
            </p>
        </div>
        
        <!-- Alert Messages -->
        <div id="alertContainer" class="mb-4"></div>
        
        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <form id="activityForm" method="POST" action="<?= url('/activities/' . $activity['id'] . '/update') ?>" class="p-6">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="_method" value="PUT">
                
                <!-- Öğrenci Bilgileri (Read-only) -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-user-graduate mr-2 text-primary-600"></i>
                        Öğrenci Bilgileri
                    </h2>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?= esc($activity['student_name']) ?>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Sınıf: <?= esc($activity['student_class']) ?>
                                </p>
                            </div>
                            <a href="<?= url('/students/' . $activity['student_id']) ?>" class="text-primary-600 hover:text-primary-700">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    
                    <input type="hidden" name="student_id" value="<?= $activity['student_id'] ?>">
                </div>
                
                <!-- Etkinlik Detayları -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                        Etkinlik Detayları
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Etkinlik Alanı -->
                        <div>
                            <label for="activity_area_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Etkinlik Alanı <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="activity_area_id" 
                                name="activity_area_id"
                                onchange="checkAvailability()"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                                <?php foreach ($activityAreas as $area): ?>
                                    <option 
                                        value="<?= $area['id'] ?>" 
                                        <?= $area['id'] == $activity['activity_area_id'] ? 'selected' : '' ?>
                                        data-color="<?= esc($area['color']) ?>"
                                    >
                                        <?= esc($area['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Tarih -->
                        <div>
                            <label for="activity_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Tarih <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="activity_date" 
                                name="activity_date"
                                value="<?= esc($activity['activity_date']) ?>"
                                onchange="checkAvailability()"
                                min="<?= date('Y-m-d') ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                        </div>
                        
                        <!-- Başlangıç Saati -->
                        <div>
                            <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Başlangıç Saati <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="start_time" 
                                name="start_time"
                                onchange="checkAvailability()"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                                <?php foreach ($timeSlots as $slot): ?>
                                    <option 
                                        value="<?= esc($slot['start_time']) ?>"
                                        <?= $slot['start_time'] == $activity['start_time'] ? 'selected' : '' ?>
                                    >
                                        <?= esc($slot['start_time']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Bitiş Saati -->
                        <div>
                            <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Bitiş Saati <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="end_time" 
                                name="end_time"
                                onchange="checkAvailability()"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                                <?php foreach ($timeSlots as $slot): ?>
                                    <option 
                                        value="<?= esc($slot['end_time']) ?>"
                                        <?= $slot['end_time'] == $activity['end_time'] ? 'selected' : '' ?>
                                    >
                                        <?= esc($slot['end_time']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Çakışma Kontrolü Sonucu -->
                        <div class="md:col-span-2">
                            <div id="availabilityStatus" class="hidden"></div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Notlar -->
                <div class="mb-8">
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Notlar (Opsiyonel)
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    ><?= esc($activity['notes'] ?? '') ?></textarea>
                </div>
                
                <!-- Form Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a 
                        href="<?= url('/activities') ?>" 
                        class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700"
                    >
                        <i class="fas fa-times mr-2"></i> İptal
                    </a>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    >
                        <i class="fas fa-save mr-2"></i> Güncelle
                    </button>
                </div>
                
            </form>
        </div>
        
    </div>
</div>

<!-- Scripts -->
<script>
// Çakışma Kontrolü
function checkAvailability() {
    const areaId = document.getElementById('activity_area_id').value;
    const date = document.getElementById('activity_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const activityId = <?= $activity['id'] ?>;
    
    if (!areaId || !date || !startTime || !endTime) {
        return;
    }
    
    fetch(`<?= url('/api/activities/check-conflict') ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            activity_area_id: areaId,
            activity_date: date,
            start_time: startTime,
            end_time: endTime,
            exclude_id: activityId // Bu etkinliği kontrolden çıkar
        })
    })
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById('availabilityStatus');
        statusDiv.classList.remove('hidden');
        
        if (data.available) {
            statusDiv.innerHTML = `
                <div class="bg-green-100 border border-green-300 text-green-800 rounded-lg p-3 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">
                    <i class="fas fa-check-circle mr-2"></i>
                    Bu saat aralığı müsait!
                </div>
            `;
            document.getElementById('submitBtn').disabled = false;
        } else {
            statusDiv.innerHTML = `
                <div class="bg-red-100 border border-red-300 text-red-800 rounded-lg p-3 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Bu saat aralığında çakışma var! Lütfen farklı bir saat seçin.
                </div>
            `;
            document.getElementById('submitBtn').disabled = true;
        }
    });
}

// Form submit
document.getElementById('activityForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Güncelleniyor...';
});
</script>