<?php
/**
 * Etkinlik Alanları - Alan Düzenle
 */
$pageTitle = 'Etkinlik Alanı Düzenle';
?>

<!-- Main Content -->
<div class="max-w-4xl mx-auto">
    
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= url('/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600">
                        <i class="fas fa-home mr-2"></i> Ana Sayfa
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="<?= url('/activity-areas') ?>" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600">Etkinlik Alanları</a>
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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Etkinlik Alanı Düzenle</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <?= htmlspecialchars($area['area_name']) ?> alanını düzenleyin
            </p>
        </div>
        
        <!-- Alert Messages -->
        <?php include VIEW_PATH . '/components/alert.php'; ?>
        
        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <form id="areaForm" action="<?= url('/activity-areas/' . $area['id']) ?>" method="POST" enctype="multipart/form-data" class="p-6">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="_method" value="PUT">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Sol Kolon -->
                    <div class="space-y-6">
                        
                        <!-- Alan Adı -->
                        <div>
                            <label for="area_name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Alan Adı <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="area_name" 
                                name="area_name"
                                value="<?= htmlspecialchars($area['area_name']) ?>"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Örn: Konferans Salonu"
                                required
                            >
                        </div>
                        
                        <!-- Renk Kodu -->
                        <div>
                            <label for="color_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Renk Kodu
                            </label>
                            <div class="flex items-center space-x-3">
                                <input 
                                    type="color" 
                                    id="color_code" 
                                    name="color_code"
                                    value="<?= htmlspecialchars($area['color_code'] ?? '#3B82F6') ?>"
                                    class="h-10 w-16 border border-gray-300 dark:border-gray-600 rounded cursor-pointer"
                                >
                                <span class="text-sm text-gray-500 dark:text-gray-400">Takvimde gösterilecek renk</span>
                            </div>
                        </div>
                        
                        <!-- Varsayılan Slot Süresi -->
                        <div>
                            <label for="default_slot_duration" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Varsayılan Slot Süresi (dakika)
                            </label>
                            <select 
                                id="default_slot_duration" 
                                name="default_slot_duration"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="15" <?= ($area['default_slot_duration'] ?? 30) == 15 ? 'selected' : '' ?>>15 dakika</option>
                                <option value="20" <?= ($area['default_slot_duration'] ?? 30) == 20 ? 'selected' : '' ?>>20 dakika</option>
                                <option value="30" <?= ($area['default_slot_duration'] ?? 30) == 30 ? 'selected' : '' ?>>30 dakika</option>
                                <option value="45" <?= ($area['default_slot_duration'] ?? 30) == 45 ? 'selected' : '' ?>>45 dakika</option>
                                <option value="60" <?= ($area['default_slot_duration'] ?? 30) == 60 ? 'selected' : '' ?>>60 dakika</option>
                            </select>
                        </div>
                        
                        <!-- Sıralama -->
                        <div>
                            <label for="sort_order" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Sıralama
                            </label>
                            <input 
                                type="number" 
                                id="sort_order" 
                                name="sort_order"
                                min="0"
                                value="<?= htmlspecialchars($area['sort_order'] ?? 0) ?>"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Küçük numara üstte görünür</p>
                        </div>
                        
                        <!-- Aktiflik Durumu -->
                        <div>
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="is_active" 
                                    name="is_active"
                                    value="1"
                                    <?= ($area['is_active'] ?? 1) ? 'checked' : '' ?>
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                >
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pasif alanlar etkinlik oluşturmada görünmez</p>
                        </div>
                        
                    </div>
                    
                    <!-- Sağ Kolon -->
                    <div class="space-y-6">
                        
                        <!-- Alan Fotoğrafı -->
                        <div>
                            <label for="area_image" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Alan Fotoğrafı <span class="text-blue-500">(Opsiyonel)</span>
                            </label>
                            
                            <!-- Mevcut Fotoğraf -->
                            <?php if (!empty($area['area_image'])): ?>
                            <div id="currentImage" class="mb-4">
                                <img src="<?= url('assets/uploads/activity-areas/' . $area['area_image']) ?>" 
                                     alt="Mevcut fotoğraf" 
                                     class="max-w-full h-48 object-cover mx-auto rounded-lg border border-gray-300 dark:border-gray-600">
                                <button 
                                    type="button" 
                                    onclick="removeCurrentImage()"
                                    class="mt-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400"
                                >
                                    <i class="fas fa-trash mr-1"></i> Mevcut Fotoğrafı Kaldır
                                </button>
                            </div>
                            <?php endif; ?>
                            
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                <div id="imagePreview" class="hidden mb-4">
                                    <img id="previewImg" src="" alt="Önizleme" class="max-w-full h-48 object-cover mx-auto rounded-lg">
                                </div>
                                <div id="uploadArea" <?= !empty($area['area_image']) ? '' : '' ?>>
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">Yeni fotoğraf yüklemek için tıklayın</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, GIF veya WebP (Maks. 5MB)</p>
                                </div>
                                <input 
                                    type="file" 
                                    id="area_image" 
                                    name="area_image"
                                    accept="image/*"
                                    class="hidden"
                                    onchange="previewImage(this)"
                                >
                            </div>
                            <button 
                                type="button" 
                                onclick="document.getElementById('area_image').click()"
                                class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400"
                            >
                                <i class="fas fa-upload mr-1"></i> Yeni Fotoğraf Seç
                            </button>
                            
                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                        </div>
                        
                    </div>
                    
                </div>
                
                <!-- Form Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a 
                        href="<?= url('/activity-areas') ?>" 
                        class="px-6 py-2 bg-gray-500 text-white font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        <i class="fas fa-times mr-2"></i> İptal
                    </a>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="px-6 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <i class="fas fa-save mr-2"></i> Güncelle
                    </button>
                </div>
                
            </form>
        </div>
        
    </div>
</div>

<!-- JavaScript -->
<script>
// Fotoğraf önizleme
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
            const currentImage = document.getElementById('currentImage');
            if (currentImage) {
                currentImage.classList.add('hidden');
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Mevcut fotoğrafı kaldır
function removeCurrentImage() {
    if (confirm('Mevcut fotoğrafı kaldırmak istediğinizden emin misiniz?')) {
        document.getElementById('currentImage').classList.add('hidden');
        document.getElementById('remove_image').value = '1';
    }
}

// Form validation
document.getElementById('areaForm').addEventListener('submit', function(e) {
    const areaName = document.getElementById('area_name').value.trim();
    
    if (!areaName) {
        e.preventDefault();
        alert('Alan adı boş olamaz!');
        return false;
    }
    
    if (areaName.length < 3) {
        e.preventDefault();
        alert('Alan adı en az 3 karakter olmalıdır!');
        return false;
    }
    
    return true;
});

// Drag & Drop özelliği
const uploadArea = document.querySelector('[for="area_image"]').parentElement;

if (uploadArea) {
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('area_image').files = files;
            previewImage(document.getElementById('area_image'));
        }
    });
}
</script>
