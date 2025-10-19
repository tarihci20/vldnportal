<?php
/** @var array $timeSlots */
/** @var array $activityAreas */
/** @var array $areaTimeSlots */
/** @var array $durationOptions */
?>

<!-- Başlık -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Saat Dilimi Ayarları</h1>
    <a href="<?= url('/admin/settings') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Geri Dön
    </a>
</div>

<!-- Otomatik Saat Dilimi Oluşturucu -->
<div class="bg-blue-50 p-6 rounded-lg shadow mb-6 border border-blue-200">
    <h2 class="text-xl font-semibold mb-4 text-blue-900">Otomatik Saat Dilimi Oluştur</h2>
    <form method="POST" action="<?= url('/admin/time-slots/generate') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <?= csrfField() ?>
        <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Saati</label>
                    <input type="time" name="start_time" value="08:00" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş Saati</label>
                    <input type="time" name="end_time" value="22:00" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aralık (Dakika)</label>
                    <select name="interval_minutes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="30" selected>30 Dakika</option>
                        <option value="20">20 Dakika</option>
                        <option value="45">45 Dakika</option>
                        <option value="60">60 Dakika</option>
                    </select>
                </div>
                <button type="submit" onclick="return confirm('Mevcut saat dilimleri silinip yenileri oluşturulacak. Devam edilsin mi?')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Oluştur
                </button>
            </form>
            <p class="text-sm text-blue-700 mt-2">
                <strong>Uyarı:</strong> Bu işlem mevcut tüm saat dilimlerini siler ve yenilerini oluşturur.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sol Kolon: Saat Dilimleri -->
            <div class="space-y-6">
                <!-- Yeni Saat Dilimi Ekle -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Yeni Saat Dilimi Ekle</h2>
                    <form method="POST" action="<?= url('/admin/time-slots') ?>" class="space-y-4">
                        <?= csrfField() ?>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç</label>
                                <input type="time" name="time_start" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş</label>
                                <input type="time" name="time_end" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Süre (Dakika)</label>
                            <select name="duration_minutes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="20">20 Dakika</option>
                                <option value="30" selected>30 Dakika</option>
                                <option value="45">45 Dakika</option>
                                <option value="60">60 Dakika</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md">
                            Saat Dilimi Ekle
                        </button>
                    </form>
                </div>

                <!-- Mevcut Saat Dilimleri -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Mevcut Saat Dilimleri (<?= count($timeSlots) ?>)</h2>
                    <div class="max-h-96 overflow-y-auto space-y-2">
                        <?php if (empty($timeSlots)): ?>
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>Henüz saat dilimi eklenmemiş</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($timeSlots as $slot): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <div>
                                            <span class="font-mono text-sm font-medium">
                                                <?= date('H:i', strtotime($slot['start_time'])) ?> - <?= date('H:i', strtotime($slot['end_time'])) ?>
                                            </span>
                                            <span class="text-xs text-gray-600 ml-2">(<?= $slot['duration'] ?? 30 ?> dk)</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button onclick="editTimeSlot(<?= htmlspecialchars(json_encode($slot)) ?>)" class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form method="POST" action="<?= url('/admin/time-slots/delete') ?>" class="inline" onsubmit="return confirm('Bu saat dilimini silmek istediğinizden emin misiniz?')">
                                            <?= csrfField() ?>
                                            <input type="hidden" name="id" value="<?= $slot['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon: Alan Bazlı Ayarlar -->
            <div class="space-y-6">
                <!-- Alan Bazlı Saat Dilimi Ayarları -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Alan Bazlı Saat Dilimi Ayarları</h2>
                    <form method="POST" action="<?= url('/admin/time-slots/area') ?>" class="space-y-4">
                        <?= csrfField() ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Etkinlik Alanı</label>
                            <select name="area_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                <option value="">Alan Seçin</option>
                                <?php foreach ($activityAreas as $area): ?>
                                    <option value="<?= $area['id'] ?>">
                                        <?= e($area['area_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slot Süresi</label>
                            <select name="duration_minutes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                <?php foreach ($durationOptions as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $value == 30 ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-md">
                            Alan Ayarını Kaydet
                        </button>
                    </form>
                </div>

                <!-- Mevcut Alan Ayarları -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Mevcut Alan Ayarları</h2>
                    <div class="space-y-3">
                        <?php if (empty($areaTimeSlots)): ?>
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p>Henüz alan ayarı yapılmamış</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($areaTimeSlots as $areaSetting): ?>
                                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded" style="background-color: <?= e($areaSetting['color_code']) ?>"></div>
                                        <div>
                                            <h3 class="font-medium text-gray-900"><?= e($areaSetting['area_name']) ?></h3>
                                            <p class="text-sm text-gray-600">
                                                Slot Süresi: <span class="font-medium"><?= $areaSetting['duration_minutes'] ?> dakika</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?= date('d.m.Y H:i', strtotime($areaSetting['updated_at'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Düzenleme Modal'ı -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Saat Dilimini Düzenle</h3>
            <form id="editForm" method="POST" action="<?= url('/admin/time-slots/update') ?>" class="space-y-4">
                <?= csrfField() ?>
                <input type="hidden" id="edit_id" name="id">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç</label>
                        <input type="time" id="edit_time_start" name="time_start" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş</label>
                        <input type="time" id="edit_time_end" name="time_end" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Süre (Dakika)</label>
                    <select id="edit_duration" name="duration_minutes" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="20">20 Dakika</option>
                        <option value="30">30 Dakika</option>
                        <option value="45">45 Dakika</option>
                        <option value="60">60 Dakika</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="edit_is_active" name="is_active" class="mr-2">
                    <label for="edit_is_active" class="text-sm text-gray-700">Aktif</label>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md">
                        Güncelle
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-md">
                        İptal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editTimeSlot(slot) {
    document.getElementById('edit_id').value = slot.id;
    document.getElementById('edit_time_start').value = slot.start_time.substring(0, 5);
    document.getElementById('edit_time_end').value = slot.end_time.substring(0, 5);
    document.getElementById('edit_duration').value = slot.duration || 30;
    document.getElementById('edit_is_active').checked = slot.is_active == 1;
    
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Modal dışına tıklandığında kapat
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>