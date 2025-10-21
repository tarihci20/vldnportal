<?php
/**
 * Etkinlik Listesi Sayfası
 * Vildan Portal
 */

$pageTitle = 'Etkinlikler';
$activities = $data['activities'] ?? [];
$pagination = $data['pagination'] ?? [];
?>

<!-- Main Content -->
<div class="max-w-7xl mx-auto">
    
        <!-- Page Header -->
        <div class="mb-4 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Etkinlikler</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Tüm etkinlikleri görüntüleyin ve yönetin
                </p>
            </div>
            
            <!-- Butonlar Bölümü -->
            <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <!-- Excel Butonları (Sol) -->
                <?php if (isAdmin()): ?>
                <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                    <button onclick="exportSelectedActivities()" class="inline-flex items-center justify-center text-white bg-emerald-600 hover:bg-emerald-700 font-medium rounded-lg text-sm px-4 py-2 whitespace-nowrap">
                        <i class="fas fa-file-excel mr-2"></i> Seçilenleri Excel
                    </button>
                    <form id="exportActivitiesByDate" method="POST" action="<?= url('/admin/activities/export-by-date') ?>" class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                        <input type="date" name="date" id="activitiesExportDate" class="border rounded px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <button type="submit" class="inline-flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-4 py-2 whitespace-nowrap">
                            <i class="fas fa-calendar-day mr-2"></i> Tarihe Göre Excel
                        </button>
                    </form>
                </div>
                <?php endif; ?>
                
                <!-- Yeni Etkinlik Butonu (Sağ) -->
                <?php if (hasPermission('activities', 'can_create')): ?>
                <a href="<?= url('/activities/create') ?>" class="flex items-center justify-center text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Yeni Etkinlik
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filtrele</h3>
                </div>
                <?php 
                $hasActiveFilters = !empty($data['filters']['area_id']) || !empty($data['filters']['date_from']) || !empty($data['filters']['date_to']);
                if ($hasActiveFilters): 
                ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Aktif Filtre
                </span>
                <?php endif; ?>
            </div>
            <form method="GET" action="<?= url('/activities') ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Etkinlik Alanı -->
                <div class="lg:col-span-1">
                    <label for="area_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Etkinlik Alanı
                    </label>
                    <select 
                        id="area_id" 
                        name="area_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Tümü</option>
                        <?php if (!empty($data['activityAreas'])): ?>
                            <?php foreach ($data['activityAreas'] as $area): ?>
                                <option value="<?= $area['id'] ?>" <?= (isset($data['filters']['area_id']) && $data['filters']['area_id'] == $area['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($area['area_name'] ?? $area['name'] ?? 'İsimsiz Alan') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <!-- Tarih Aralığı -->
                <div class="lg:col-span-1">
                    <label for="date_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Başlangıç Tarihi
                    </label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from"
                        value="<?= $data['filters']['date_from'] ?? '' ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>
                
                <div class="lg:col-span-1">
                    <label for="date_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Bitiş Tarihi
                    </label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to"
                        value="<?= $data['filters']['date_to'] ?? '' ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>
                
                <!-- Filter Buttons -->
                <div class="lg:col-span-2 flex flex-col sm:flex-row items-stretch sm:items-end gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors"
                    >
                        <i class="fas fa-filter mr-2"></i> Filtrele
                    </button>
                    <a 
                        href="<?= url('/activities') ?>" 
                        class="flex-1 text-center text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors"
                    >
                        <i class="fas fa-times mr-2"></i> Temizle
                    </a>
                </div>
                
            </form>
        </div>
        
        <!-- Activities Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            
            <!-- Toplu Silme Toolbar (Sadece Admin) -->
            <?php if (isAdmin()): ?>
            <div id="bulkActionsBar" style="display: none;" class="bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800 px-6 py-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800 dark:text-blue-300">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span id="selectedCount">0</span> etkinlik seçildi
                    </span>
                    <button 
                        onclick="bulkDeleteActivities()" 
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
                    >
                        <i class="fas fa-trash mr-2"></i>Seçilenleri Sil
                    </button>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <?php if (isAdmin()): ?>
                            <th scope="col" class="px-6 py-3 w-12">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 rounded" />
                            </th>
                            <?php endif; ?>
                            <th scope="col" class="px-6 py-3">
                                Etkinlik Alanı
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tarih
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
                                Oluşturan
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
                        <?php if (empty($activities)): ?>
                            <tr>
                                <td colspan="<?= isAdmin() ? '9' : '8' ?>" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                    <p>Henüz etkinlik bulunmuyor.</p>
                                    <a href="<?= url('/activities/create') ?>" class="text-blue-600 hover:underline dark:text-blue-400">
                                        İlk etkinliği oluştur
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $today = date('Y-m-d');
                            foreach ($activities as $activity): 
                                $activityDate = $activity['activity_date'];
                                
                                // Tarih bazlı renk belirleme
                                $rowClass = 'bg-white dark:bg-gray-800'; // Geçmiş etkinlikler
                                if ($activityDate == $today) {
                                    $rowClass = 'bg-green-50 dark:bg-green-900/20'; // Bugün - açık yeşil
                                } elseif ($activityDate > $today) {
                                    $rowClass = 'bg-yellow-50 dark:bg-yellow-900/20'; // Gelecek - açık sarı
                                }
                            ?>
                                <tr class="<?= $rowClass ?> border-b dark:border-gray-700 hover:bg-opacity-75 dark:hover:bg-gray-600">
                                    <?php if (isAdmin()): ?>
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="activity-checkbox w-4 h-4 text-blue-600 rounded" value="<?= $activity['id'] ?>" />
                                    </td>
                                    <?php endif; ?>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?= esc($activity['area_color'] ?? '#6366F1') ?>"></div>
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                <?= esc($activity['area_name'] ?? '-') ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= formatDate($activity['activity_date']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= esc($activity['start_time']) ?> - <?= esc($activity['end_time']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 dark:text-white">
                                            <?= esc($activity['responsible_person'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            <?= esc($activity['activity_name'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= esc($activity['created_by_name'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                        $status = $activity['status'] ?? 'scheduled';
                                        $statusClass = '';
                                        $statusText = '';
                                        $statusIcon = '';
                                        
                                        switch($status) {
                                            case 'completed':
                                                $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                                $statusText = 'Tamamlandı';
                                                $statusIcon = 'fas fa-check';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                                $statusText = 'İptal Edildi';
                                                $statusIcon = 'fas fa-times';
                                                break;
                                            default:
                                                $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                                $statusText = 'Planlandı';
                                                $statusIcon = 'fas fa-clock';
                                        }
                                        ?>
                                        <span class="<?= $statusClass ?> text-xs font-medium px-2.5 py-0.5 rounded">
                                            <i class="<?= $statusIcon ?> mr-1"></i> <?= $statusText ?>
                                        </span>
                                        
                                        <?php if ($activity['is_recurring'] ?? false): ?>
                                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300 ml-2">
                                                <i class="fas fa-repeat mr-1"></i> Tekrarlı
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <!-- Görüntüle butonu - herkes görebilir -->
                                            <a href="<?= url('/activities/' . $activity['id']) ?>" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                               title="Detayları Görüntüle">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            
                                            <!-- Düzenle butonu -->
                                            <?php if (hasPermission('activities', 'can_edit')): ?>
                                            <a href="<?= url('/activities/' . $activity['id'] . '/edit') ?>" 
                                               class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                               title="Düzenle">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <!-- Sil butonu -->
                                            <?php if (hasPermission('activities', 'can_delete')): ?>
                                            <button onclick="deleteActivity(<?= $activity['id'] ?>)" 
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    title="Sil">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination)): ?>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400">
                            <span class="font-medium"><?= $pagination['from'] ?? 0 ?></span>
                            -
                            <span class="font-medium"><?= $pagination['to'] ?? 0 ?></span>
                            /
                            <span class="font-medium"><?= $pagination['total'] ?? 0 ?></span>
                            kayıt
                        </div>
                        
                        <div class="flex space-x-2">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    Önceki
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    Sonraki
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
</div>

<!-- Delete Script -->
<script>
function deleteActivity(id) {
    if (confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
        fetch(`<?= url('/activities/') ?>${id}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                csrf_token: document.querySelector('meta[name="csrf-token"]').content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + (data.message || 'Etkinlik başarıyla silindi'));
                location.reload();
            } else {
                alert('❌ Hata: ' + (data.error || 'Bilinmeyen hata'));
            }
        })
        .catch(error => {
            console.error('Silme hatası:', error);
            alert('❌ Silme işlemi başarısız oldu');
        });
    }
}
</script>

<script>
// Tarih Filtreleri için Flatpickr
document.addEventListener('DOMContentLoaded', function() {
    flatpickr('#date_from', {
        locale: 'tr',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: false,
        theme: 'material_blue'
    });
    
    flatpickr('#date_to', {
        locale: 'tr',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: false,
        theme: 'material_blue'
    });
});
</script>
<script>
function exportSelectedActivities() {
    const ids = Array.from(document.querySelectorAll('.activity-checkbox:checked')).map(cb => cb.value);
    if (ids.length === 0) return alert('Önce etkinlik seçin');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= url('/admin/activities/export') ?>';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const csrfInput = document.createElement('input'); csrfInput.type = 'hidden'; csrfInput.name = 'csrf_token'; csrfInput.value = csrf; form.appendChild(csrfInput);

    ids.forEach(id => {
        const i = document.createElement('input'); i.type = 'hidden'; i.name = 'ids[]'; i.value = id; form.appendChild(i);
    });

    document.body.appendChild(form);
    form.submit();
    form.remove();
}
</script>

<?php if (isAdmin()): ?>
<script>
// Toplu Silme Sistemi (Sadece Admin)
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const activityCheckboxes = document.querySelectorAll('.activity-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            activityCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionsBar();
        });
    }

    activityCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });

    function updateBulkActionsBar() {
        const selectedCount = document.querySelectorAll('.activity-checkbox:checked').length;
        selectedCountSpan.textContent = selectedCount;
        
        console.log('Selected count:', selectedCount); // Debug
        console.log('Toolbar element:', bulkActionsBar); // Debug
        
        if (selectedCount > 0) {
            bulkActionsBar.style.display = 'block';
            console.log('Toolbar gösterildi'); // Debug
        } else {
            bulkActionsBar.style.display = 'none';
            console.log('Toolbar gizlendi'); // Debug
        }
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === activityCheckboxes.length && selectedCount > 0;
        }
    }

    function bulkDeleteActivities() {
        const selectedIds = Array.from(document.querySelectorAll('.activity-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        console.log('Seçilen ID\'ler:', selectedIds); // Debug
        
        if (selectedIds.length === 0) {
            alert('Lütfen en az bir etkinlik seçin.');
            return;
        }
        
        if (!confirm(`${selectedIds.length} etkinliği silmek istediğinizden emin misiniz?`)) {
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        console.log('CSRF Token element:', csrfToken); // Debug
        console.log('CSRF Token value:', csrfToken ? csrfToken.getAttribute('content') : 'BULUNAMADI'); // Debug
        
        const requestData = {
            csrf_token: csrfToken ? csrfToken.getAttribute('content') : '',
            activity_ids: selectedIds
        };
        
        const bulkDeleteUrl = '<?= url("/activities/bulk-delete") ?>';
        
        console.log('Gönderilen veri:', requestData); // Debug
        console.log('URL:', bulkDeleteUrl); // Debug
        
        fetch(bulkDeleteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug
            console.log('Response headers:', response.headers); // Debug
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug
            if (data.success) {
                alert(`✅ ${data.deleted_count} etkinlik başarıyla silindi!`);
                location.reload();
            } else {
                alert('❌ Hata: ' + (data.error || 'Toplu silme işlemi başarısız'));
                console.error('Silme hatası:', data); // Debug
            }
        })
        .catch(error => {
            console.error('Toplu silme hatası:', error);
            alert('❌ Toplu silme işlemi başarısız oldu: ' + error.message);
        });
    }
    
    // Global scope'a ekle ki onclick çalışsın
    window.bulkDeleteActivities = bulkDeleteActivities;
});
</script>
<?php endif; ?>
