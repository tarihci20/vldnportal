<!-- Flash Messages -->
<?php $flash = getFlashMessage(); if ($flash): ?>
    <div class="mb-6 p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : ($flash['type'] === 'error' ? 'bg-red-50 text-red-800 border border-red-200' : 'bg-yellow-50 text-yellow-800 border border-yellow-200') ?>">
        <strong><?= $flash['message'] ?></strong>
    </div>
<?php endif; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Yeni Öğrenci Ekle</h1>
        
        <form method="POST" action="<?= (defined('BASE_PATH') ? BASE_PATH : '') ?>/simple-students" class="bg-white shadow rounded-lg p-6">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <!-- Row 1: TC ve İsim -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">TC Kimlik No *</label>
                    <input type="text" name="tc_no" maxlength="11" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="11 haneli TC no" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">İsim *</label>
                    <input type="text" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>
            </div>
            
            <!-- Row 2: Soyisim ve Doğum Tarihi -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Soyisim *</label>
                    <input type="text" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Doğum Tarihi *</label>
                    <input type="date" name="birth_date" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>
            </div>
            
            <!-- Row 3: Sınıf -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sınıf</label>
                <input type="text" name="class" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Örn: 9-A, 10-B, vb (boş bırakılabilir)">
            </div>
            
            <hr class="my-6">
            
            <!-- Baba Bilgileri -->
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Baba Bilgileri</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Baba Adı</label>
                    <input type="text" name="father_name" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Baba Telefonu</label>
                    <input type="tel" name="father_phone" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
            </div>
            
            <hr class="my-6">
            
            <!-- Anne Bilgileri -->
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Anne Bilgileri</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anne Adı</label>
                    <input type="text" name="mother_name" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anne Telefonu</label>
                    <input type="tel" name="mother_phone" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
            </div>
            
            <hr class="my-6">
            
            <!-- Öğretmen Bilgileri -->
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Öğretmen Bilgileri</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Öğretmen Adı</label>
                    <input type="text" name="teacher_name" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Öğretmen Telefonu</label>
                    <input type="tel" name="teacher_phone" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
            </div>
            
            <hr class="my-6">
            
            <!-- Notlar -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notlar</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Kaydet
                </button>
                <a href="<?= (defined('BASE_PATH') ? BASE_PATH : '') ?>/students" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                    İptal
                </a>
            </div>
        </form>
    </div>
</div>
