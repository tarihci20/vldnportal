<?php
/**
 * Etkinlik Alanları Yönetimi
 * Spor salonu, konferans salonu gibi etkinlik alanlarının yönetimi
 */

require_once VIEW_PATH . '/layouts/header.php';

// Alanlar controller'dan gelmeli
$areas = $areas ?? [];
?>

<div class="container mx-auto px-4 py-6">
    <!-- Başlık ve Yeni Alan Butonu -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Etkinlik Alanları</h1>
            <p class="text-gray-600 mt-1">Rezervasyon yapılabilecek alanları yönetin</p>
        </div>
        
        <button 
            onclick="openModal('createAreaModal')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Alan Ekle
        </button>
    </div>

    <!-- Alert Component -->
    <?php include VIEW_PATH . '/components/alert.php'; ?>

    <!-- Alanlar Grid -->
    <?php if (!empty($areas)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($areas as $area): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Alan Görseli/İkonu -->
                    <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <?php if (!empty($area['image_url'])): ?>
                            <img src="<?= upload($area['image_url']) ?>" alt="<?= e($area['name']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <svg class="w-24 h-24 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        <?php endif; ?>
                    </div>

                    <!-- Alan Bilgileri -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <?= e($area['name']) ?>
                                </h3>
                                <?php if (!empty($area['description'])): ?>
                                    <p class="text-sm text-gray-600">
                                        <?= e(truncate($area['description'], 100)) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Durum Badge -->
                            <?php if ($area['is_active']): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                    Pasif
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Alan Detayları -->
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                            <!-- Kapasite -->
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                <span><?= formatNumber($area['capacity']) ?> kişi</span>
                            </div>

                            <?php if (!empty($area['color'])): ?>
                                <!-- Renk -->
                                <div class="flex items-center gap-1">
                                    <div class="w-4 h-4 rounded-full border border-gray-300" style="background-color: <?= e($area['color']) ?>"></div>
                                    <span class="text-xs"><?= e($area['color']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Aksiyon Butonları -->
                        <div class="flex items-center gap-2">
                            <button 
                                onclick="editArea(<?= $area['id'] ?>)"
                                class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium"
                            >
                                Düzenle
                            </button>
                            <button 
                                onclick="toggleArea(<?= $area['id'] ?>, <?= $area['is_active'] ? 'false' : 'true' ?>)"
                                class="flex-1 px-4 py-2 <?= $area['is_active'] ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?> rounded-lg transition-colors text-sm font-medium"
                            >
                                <?= $area['is_active'] ? 'Pasifleştir' : 'Aktifleştir' ?>
                            </button>
                            <button 
                                onclick="deleteArea(<?= $area['id'] ?>)"
                                class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                                title="Sil"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Boş Durum -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Henüz Etkinlik Alanı Yok</h3>
            <p class="text-gray-600 mb-4">İlk etkinlik alanınızı oluşturmak için yukarıdaki butona tıklayın.</p>
            <button 
                onclick="openModal('createAreaModal')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Yeni Alan Ekle
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Yeni Alan Modal -->
<?php 
$modalId = 'createAreaModal';
$modalTitle = 'Yeni Etkinlik Alanı';
$modalSize = 'lg';
include VIEW_PATH . '/components/modal.php';
?>

<div id="createAreaModal-content">
    <form id="createAreaForm" onsubmit="return handleCreateArea(event)">
        <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
        
        <div class="space-y-4">
            <!-- Alan Adı -->
            <div>
                <label for="area_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Alan Adı <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="area_name" 
                    name="name" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="Örn: Spor Salonu, Konferans Salonu"
                >
            </div>

            <!-- Açıklama -->
            <div>
                <label for="area_description" class="block text-sm font-medium text-gray-700 mb-1">
                    Açıklama
                </label>
                <textarea 
                    id="area_description" 
                    name="description" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="Alan hakkında kısa açıklama"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kapasite -->
                <div>
                    <label for="area_capacity" class="block text-sm font-medium text-gray-700 mb-1">
                        Kapasite <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="area_capacity" 
                        name="capacity" 
                        required
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Kişi sayısı"
                    >
                </div>

                <!-- Renk -->
                <div>
                    <label for="area_color" class="block text-sm font-medium text-gray-700 mb-1">
                        Renk (Takvim için)
                    </label>
                    <input 
                        type="color" 
                        id="area_color" 
                        name="color" 
                        value="#3B82F6"
                        class="w-full h-10 px-2 py-1 border border-gray-300 rounded-lg cursor-pointer"
                    >
                </div>
            </div>

            <!-- Aktif/Pasif -->
            <div class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    id="area_is_active" 
                    name="is_active" 
                    value="1"
                    checked
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                >
                <label for="area_is_active" class="text-sm font-medium text-gray-700">
                    Aktif (Rezervasyon yapılabilir)
                </label>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
            <button 
                type="button"
                onclick="closeModal('createAreaModal')"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
                İptal
            </button>
            <button 
                type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
            >
                Kaydet
            </button>
        </div>
    </form>
</div>

<!-- Düzenleme Modal -->
<?php 
$modalId = 'editAreaModal';
$modalTitle = 'Etkinlik Alanını Düzenle';
$modalSize = 'lg';
include VIEW_PATH . '/components/modal.php';
?>

<div id="editAreaModal-content">
    <!-- Form içeriği AJAX ile yüklenecek -->
</div>

<script>
// Alan oluştur
function handleCreateArea(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    showLoading('Alan oluşturuluyor...');
    
    fetch('<?= url('api/activity-areas/create') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            closeModal('createAreaModal');
            window.location.reload();
        } else {
            alert(data.message || 'Bir hata oluştu');
        }
    })
    .catch(error => {
        hideLoading();
        alert('Bir hata oluştu');
        console.error(error);
    });
    
    return false;
}

// Alan düzenle
function editArea(areaId) {
    showLoading('Alan yükleniyor...');
    
    fetch(`<?= url('api/activity-areas/') ?>${areaId}`)
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Form HTML'i oluştur
            const area = data.data;
            document.getElementById('editAreaModal-content').innerHTML = `
                <form id="editAreaForm" onsubmit="return handleEditArea(event, ${areaId})">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alan Adı</label>
                            <input type="text" name="name" value="${area.name}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">${area.description || ''}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kapasite</label>
                                <input type="number" name="capacity" value="${area.capacity}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Renk</label>
                                <input type="color" name="color" value="${area.color}" class="w-full h-10 px-2 py-1 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" ${area.is_active ? 'checked' : ''} class="w-4 h-4">
                            <label class="text-sm font-medium text-gray-700">Aktif</label>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                        <button type="button" onclick="closeModal('editAreaModal')" class="px-4 py-2 border border-gray-300 rounded-lg">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Güncelle</button>
                    </div>
                </form>
            `;
            openModal('editAreaModal');
        } else {
            alert(data.message || 'Alan yüklenemedi');
        }
    });
}

// Alan güncelle
function handleEditArea(event, areaId) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    showLoading('Alan güncelleniyor...');
    
    fetch(`<?= url('api/activity-areas/') ?>${areaId}/update`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            closeModal('editAreaModal');
            window.location.reload();
        } else {
            alert(data.message || 'Güncelleme başarısız');
        }
    });
    
    return false;
}

// Alan aktif/pasif
function toggleArea(areaId, isActive) {
    const action = isActive ? 'aktif' : 'pasif';
    if (!confirm(`Bu alanı ${action}leştirmek istediğinizden emin misiniz?`)) {
        return;
    }
    
    showLoading(`Alan ${action}leştiriliyor...`);
    
    fetch(`<?= url('api/activity-areas/') ?>${areaId}/toggle`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            is_active: isActive,
            csrf_token: '<?= getCsrfToken() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'İşlem başarısız');
        }
    });
}

// Alan sil
function deleteArea(areaId) {
    if (!confirm('Bu alanı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
        return;
    }
    
    showLoading('Alan siliniyor...');
    
    fetch(`<?= url('api/activity-areas/') ?>${areaId}`, {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            csrf_token: '<?= getCsrfToken() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Silme başarısız');
        }
    });
}
</script>

<?php require_once VIEW_PATH . '/layouts/footer.php'; ?>