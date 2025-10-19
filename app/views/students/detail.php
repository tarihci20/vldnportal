<?php
/**
 * Öğrenci Detay Kartı
 * Vildan Portal
 */

$pageTitle = 'Öğrenci Detay';
$student = $data['student'] ?? null;

if (!$student) {
    redirect('/students');
    exit;
}
?>

<!-- Main Content -->

        
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
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Action Buttons -->
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Öğrenci Detay Kartı</h1>
            <div class="flex space-x-2">
                <?php if (hasPermission('students', 'can_edit')): ?>
                <a href="<?= url('/students/' . $student['id'] . '/edit') ?>" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700">
                    <i class="fas fa-edit mr-2"></i> Düzenle
                </a>
                <?php endif; ?>
                <a href="<?= url('/students') ?>" class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Geri Dön
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column - Student Info -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Kişisel Bilgiler -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-user mr-2 text-primary-600"></i>
                        Kişisel Bilgiler
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">TC Kimlik No</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                <?= esc($student['tc_no'] ?: '-') ?>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ad Soyad</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Doğum Tarihi</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                <?= formatDate($student['birth_date']) ?>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sınıf</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                <?= esc($student['class']) ?>
                            </p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Adres</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                <?= esc($student['address'] ?: '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Veli Bilgileri -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-users mr-2 text-blue-600"></i>
                        Veli Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Baba Bilgileri -->
                        <div class="space-y-3">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-male text-blue-600 mr-2"></i>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Baba</h3>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ad Soyad</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    <?= esc($student['father_name'] ?: '-') ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Telefon</p>
                                <?php if ($student['father_phone']): ?>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-base font-medium text-gray-900 dark:text-white">
                                            <?= formatPhone($student['father_phone']) ?>
                                        </p>
                                        <a href="tel:<?= esc($student['father_phone']) ?>" class="text-primary-600 hover:text-primary-700">
                                            <i class="fas fa-phone-alt"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-base font-medium text-gray-900 dark:text-white">-</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Anne Bilgileri -->
                        <div class="space-y-3">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-female text-pink-600 mr-2"></i>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Anne</h3>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ad Soyad</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    <?= esc($student['mother_name'] ?: '-') ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Telefon</p>
                                <?php if ($student['mother_phone']): ?>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-base font-medium text-gray-900 dark:text-white">
                                            <?= formatPhone($student['mother_phone']) ?>
                                        </p>
                                        <a href="tel:<?= esc($student['mother_phone']) ?>" class="text-primary-600 hover:text-primary-700">
                                            <i class="fas fa-phone-alt"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-base font-medium text-gray-900 dark:text-white">-</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Öğretmen Bilgileri -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-chalkboard-teacher mr-2 text-green-600"></i>
                        Öğretmen Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Öğretmen Adı</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                <?= esc($student['teacher_name'] ?: '-') ?>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Telefon</p>
                            <?php if ($student['teacher_phone']): ?>
                                <div class="flex items-center space-x-2">
                                    <p class="text-base font-medium text-gray-900 dark:text-white">
                                        <?= formatPhone($student['teacher_phone']) ?>
                                    </p>
                                    <a href="tel:<?= esc($student['teacher_phone']) ?>" class="text-primary-600 hover:text-primary-700">
                                        <i class="fas fa-phone-alt"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <p class="text-base font-medium text-gray-900 dark:text-white">-</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Right Column - Quick Actions & Stats -->
            <div class="space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                        Hızlı İşlemler
                    </h2>
                    
                    <div class="space-y-2">
                        <?php if ($student['father_phone']): ?>
                        <a href="tel:<?= esc($student['father_phone']) ?>" class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition">
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-400">
                                <i class="fas fa-phone mr-2"></i> Babayı Ara
                            </span>
                            <i class="fas fa-chevron-right text-blue-500"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($student['mother_phone']): ?>
                        <a href="tel:<?= esc($student['mother_phone']) ?>" class="flex items-center justify-between p-3 bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/30 rounded-lg transition">
                            <span class="text-sm font-medium text-pink-700 dark:text-pink-400">
                                <i class="fas fa-phone mr-2"></i> Anneyi Ara
                            </span>
                            <i class="fas fa-chevron-right text-pink-500"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($student['teacher_phone']): ?>
                        <a href="tel:<?= esc($student['teacher_phone']) ?>" class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition">
                            <span class="text-sm font-medium text-green-700 dark:text-green-400">
                                <i class="fas fa-phone mr-2"></i> Öğretmeni Ara
                            </span>
                            <i class="fas fa-chevron-right text-green-500"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                        İstatistikler
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Toplam Etkinlik</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">0</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Toplam Etüt</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">0</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Kayıt Tarihi</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                <?= formatDate($student['created_at']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Action -->
                <?php if (hasPermission('students', 'can_delete')): ?>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Tehlikeli Bölge
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Bu öğrenciyi silmek tüm ilişkili kayıtları da siler.
                    </p>
                    <button 
                        onclick="confirmDelete(<?= $student['id'] ?>)" 
                        class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2"
                    >
                        <i class="fas fa-trash mr-2"></i> Öğrenciyi Sil
                    </button>
                </div>
                <?php endif; ?>
                
            </div>
            
        </div>
        
    </div>
</div>

<!-- Delete Confirmation Script -->
<script>
function confirmDelete(studentId) {
    if (confirm('Bu öğrenciyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
        // AJAX ile silme işlemi
        fetch(`<?= url('/students/') ?>${studentId}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                csrf_token: document.querySelector('meta[name="csrf-token"]').content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Öğrenci başarıyla silindi!');
                window.location.href = '<?= url('/students') ?>';
            } else {
                alert('Hata: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu!');
        });
    }
}
</script>