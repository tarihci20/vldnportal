<?php
/**
 * Etkinlik Alanları - Liste
 */
?>

<div class="max-w-7xl mx-auto px-2 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Etkinlik Alanları</h1>
        <a href="<?= url('/activity-areas/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Yeni Alan Ekle
        </a>
    </div>

    <?php if (empty($areas)): ?>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Henüz etkinlik alanı bulunmamaktadır.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($areas as $area): ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <?php if (!empty($area['area_image'])): ?>
                        <img src="<?= url('assets/uploads/activity-areas/' . $area['area_image']) ?>" 
                             alt="<?= htmlspecialchars($area['area_name']) ?>" 
                             class="w-full h-48 object-cover"
                             onerror="this.onerror=null; this.src='<?= url('assets/img/placeholder.jpg') ?>'; this.parentElement.querySelector('.fallback-text')?.classList.remove('hidden');">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">Fotoğraf yok</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                            <?= htmlspecialchars($area['area_name']) ?>
                        </h3>
                        
                        <?php if (!empty($area['description'])): ?>
                            <p class="text-gray-600 dark:text-gray-300 mb-3">
                                <?= htmlspecialchars($area['description']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-2" 
                                     style="background-color: <?= htmlspecialchars($area['color_code'] ?? '#3b82f6') ?>"></div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Renk Kodu</span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="<?= url('/activity-areas/' . $area['id'] . '/edit') ?>" 
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">Düzenle</a>
                                <a href="<?= url('/activity-areas/' . $area['id'] . '/delete') ?>" 
                                   class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm"
                                   onclick="return confirm('Bu alanı silmek istediğinizden emin misiniz?')">Sil</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
