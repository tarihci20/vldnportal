<?php
/**
 * Etkinlik Alanları - Yeni Alan Ekle
 */
$pageTitle = 'Yeni Etkinlik Alanı';
?>

<!-- Main Content -->
<div class="max-w-4xl mx-auto">
    
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= url('/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                        <i class="fas fa-home mr-2"></i> Ana Sayfa
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="<?= url('/activity-areas') ?>" class="text-sm font-medium text-gray-700 hover:text-primary-600">Etkinlik Alanları</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Yeni Alan</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Yeni Etkinlik Alanı Ekle</h1>
            <p class="mt-1 text-sm text-gray-500">
                Etkinlik düzenlenebilecek alanları tanımlayın
            </p>
        </div>
        
        <!-- Alert Messages -->
        <div id="alertContainer" class="mb-4"></div>
        
        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <form id="areaForm" action="<?= url('/activity-areas') ?>" method="POST" enctype="multipart/form-data" class="p-6">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Sol Kolon -->
                    <div class="space-y-6">
                        
                        <!-- Alan Adı -->
                        <div>
                            <label for="area_name" class="block mb-2 text-sm font-medium text-gray-700">
                                Alan Adı <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="area_name" 
                                name="area_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Örn: Konferans Salonu"
                                required
                            >
                        </div>
                        
                        <!-- Renk Kodu -->
                        <div>
                            <label for="color_code" class="block mb-2 text-sm font-medium text-gray-700">
                                Renk Kodu
                            </label>
                            <div class="flex items-center space-x-3">
                                <input 
                                    type="color" 
                                    id="color_code" 
                                    name="color_code"
                                    value="#3B82F6"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer"
                                >
                                <span class="text-sm text-gray-500">Takvimde gösterilecek renk</span>
                            </div>
                        </div>
                        
                        <!-- Varsayılan Slot Süresi -->
                        <div>
                            <label for="default_slot_duration" class="block mb-2 text-sm font-medium text-gray-700">
                                Varsayılan Slot Süresi (dakika)
                            </label>
                            <select 
                                id="default_slot_duration" 
                                name="default_slot_duration"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="15">15 dakika</option>
                                <option value="20">20 dakika</option>
                                <option value="30" selected>30 dakika</option>
                                <option value="45">45 dakika</option>
                                <option value="60">60 dakika</option>
                            </select>
                        </div>
                        
                        <!-- Sıralama -->
                        <div>
                            <label for="sort_order" class="block mb-2 text-sm font-medium text-gray-700">
                                Sıralama
                            </label>
                            <input 
                                type="number" 
                                id="sort_order" 
                                name="sort_order"
                                min="0"
                                value="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0"
                            >
                            <p class="text-xs text-gray-500 mt-1">Küçük numara üstte görünür</p>
                        </div>
                        
                    </div>
                    
                    <!-- Sağ Kolon -->
                    <div class="space-y-6">
                        
                        <!-- Alan Fotoğrafı -->
                        <div>
                            <label for="area_image" class="block mb-2 text-sm font-medium text-gray-700">
                                Alan Fotoğrafı <span class="text-blue-500">(Opsiyonel)</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                <div id="imagePreview" class="hidden mb-4">
                                    <img id="previewImg" src="" alt="Önizleme" class="max-w-full h-48 object-cover mx-auto rounded-lg">
                                </div>
                                <div id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-gray-600 mb-2">Fotoğraf yüklemek için tıklayın</p>
                                    <p class="text-xs text-gray-500">JPG, PNG, GIF veya WebP (Maks. 5MB)</p>
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
                                class="mt-2 text-sm text-blue-600 hover:text-blue-800"
                            >
                                <i class="fas fa-upload mr-1"></i> Fotoğraf Seç
                            </button>
                        </div>
                        
                        <!-- Örnek Görseller -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-lightbulb mr-1 text-yellow-500"></i>
                                Önerilen Alan Türleri
                            </h3>
                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                <div>• Konferans Salonu</div>
                                <div>• Fuaye Alanı</div>
                                <div>• Spor Salonu</div>
                                <div>• Kütüphane</div>
                                <div>• Laboratuvar</div>
                                <div>• Açık Hava Alanı</div>
                                <div>• Yemekhane</div>
                                <div>• Müzik Odası</div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
                <!-- Form Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
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
                        <i class="fas fa-save mr-2"></i> Kaydet
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
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Form validation
document.getElementById('areaForm').addEventListener('submit', function(e) {
    const areaName = document.getElementById('area_name').value.trim();
    
    console.log('Form submit triggered');
    console.log('Area name:', areaName);
    console.log('Form action:', this.action);
    console.log('Form method:', this.method);
    
    if (!areaName) {
        e.preventDefault();
        alert('Alan adı boş olamaz!');
        console.log('Form prevented: Empty area name');
        return false;
    }
    
    if (areaName.length < 3) {
        e.preventDefault();
        alert('Alan adı en az 3 karakter olmalıdır!');
        console.log('Form prevented: Area name too short');
        return false;
    }
    
    console.log('Form validation passed, submitting...');
    console.log('Form will submit to:', this.action);
    
    // Form'un submit olmasını izin ver
    return true;
});

// Drag & Drop özelliği
const uploadArea = document.querySelector('[for="area_image"]').parentElement;

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-blue-400', 'bg-blue-50');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('border-blue-400', 'bg-blue-50');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-blue-400', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('area_image').files = files;
        previewImage(document.getElementById('area_image'));
    }
});
</script>
