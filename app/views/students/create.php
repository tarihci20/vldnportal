<?php
/**
 * Yeni Öğrenci Oluşturma Sayfası
 * Vildan Portal
 */

$pageTitle = 'Yeni Öğrenci Ekle';
?>

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
                        <a href="<?= url('/students') ?>" class="text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">Öğrenciler</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Yeni Öğrenci</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Öğrenci Ekle</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Aşağıdaki formu doldurarak yeni öğrenci kaydı oluşturabilirsiniz.
            </p>
        </div>
        
        <!-- Alert Messages -->
        <div id="alertContainer" class="mb-4"></div>
        
        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <form id="studentForm" method="POST" action="<?= url('/students/store') ?>" class="p-6">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <!-- Kişisel Bilgiler -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-user mr-2 text-primary-600"></i>
                        Kişisel Bilgiler
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- TC Kimlik No -->
                        <div>
                            <label for="tc_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                TC Kimlik No <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="tc_no" 
                                name="tc_no" 
                                maxlength="11"
                                pattern="[0-9]{11}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="12345678901"
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">11 haneli TC kimlik numarası</p>
                        </div>
                        
                        <!-- İsim -->
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                İsim <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Ahmet"
                                required
                            >
                        </div>
                        
                        <!-- Soyisim -->
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Soyisim <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Yılmaz"
                                required
                            >
                        </div>
                        
                        <!-- Doğum Tarihi -->
                        <div>
                            <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Doğum Tarihi <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="birth_date" 
                                name="birth_date" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                required
                            >
                        </div>
                        
                        <!-- Sınıf -->
                        <div>
                            <label for="class" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Sınıfı <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="class" 
                                name="class" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="9-A"
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Örnek: 9-A, 10-B, 8.SINIF</p>
                        </div>
                        
                        <!-- Adres -->
                        <div class="md:col-span-2">
                            <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Adres
                            </label>
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="3" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Mahalle, Sokak, Bina No, Daire No"
                            ></textarea>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Baba Bilgileri -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-male mr-2 text-blue-600"></i>
                        Baba Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Baba Adı -->
                        <div>
                            <label for="father_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Baba Adı
                            </label>
                            <input 
                                type="text" 
                                id="father_name" 
                                name="father_name" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Mehmet Yılmaz"
                            >
                        </div>
                        
                        <!-- Baba Telefon -->
                        <div>
                            <label for="father_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Baba Telefon
                            </label>
                            <input 
                                type="tel" 
                                id="father_phone" 
                                name="father_phone" 
                                maxlength="11"
                                pattern="0[0-9]{10}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="05XXXXXXXXX"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">11 haneli telefon numarası (0 ile başlayın)</p>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Anne Bilgileri -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-female mr-2 text-pink-600"></i>
                        Anne Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Anne Adı -->
                        <div>
                            <label for="mother_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Anne Adı
                            </label>
                            <input 
                                type="text" 
                                id="mother_name" 
                                name="mother_name" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Ayşe Yılmaz"
                            >
                        </div>
                        
                        <!-- Anne Telefon -->
                        <div>
                            <label for="mother_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Anne Telefon
                            </label>
                            <input 
                                type="tel" 
                                id="mother_phone" 
                                name="mother_phone" 
                                maxlength="11"
                                pattern="0[0-9]{10}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="05XXXXXXXXX"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">11 haneli telefon numarası (0 ile başlayın)</p>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Öğretmen Bilgileri -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-chalkboard-teacher mr-2 text-green-600"></i>
                        Öğretmen Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Öğretmen Adı -->
                        <div>
                            <label for="teacher_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Öğretmen Adı
                            </label>
                            <input 
                                type="text" 
                                id="teacher_name" 
                                name="teacher_name" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Fatma Öztürk"
                            >
                        </div>
                        
                        <!-- Öğretmen Telefon -->
                        <div>
                            <label for="teacher_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Öğretmen Telefon
                            </label>
                            <input 
                                type="tel" 
                                id="teacher_phone" 
                                name="teacher_phone" 
                                maxlength="11"
                                pattern="0[0-9]{10}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="05XXXXXXXXX"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">11 haneli telefon numarası (0 ile başlayın)</p>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Form Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a 
                        href="<?= url('/students') ?>" 
                        class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                    >
                        <i class="fas fa-times mr-2"></i> İptal
                    </a>
                    <button 
                        type="submit" 
                        class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    >
                        <i class="fas fa-save mr-2"></i> Kaydet
                    </button>
                </div>
                
            </form>
        </div>
        
    </div>
</div>

<!-- Form Validation Script -->
<script>
document.getElementById('studentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // TC Kimlik No kontrolü
    const tcNo = document.getElementById('tc_no').value;
    if (tcNo.length !== 11 || !/^\d{11}$/.test(tcNo)) {
        showAlert('error', 'TC Kimlik No 11 haneli olmalıdır!');
        return;
    }
    
    // Form gönder
    this.submit();
});

function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    const alertClass = type === 'success' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300';
    
    alertContainer.innerHTML = `
        <div class="${alertClass} border rounded-lg p-4 mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}
</script>