<div class="p-6">
    <!-- Başlık -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📚 Etüt Alanları</h1>
        <p class="text-gray-600 mt-2">Ortaokul ve Lise etüt başvurularını görüntüleyin ve yönetin</p>
    </div>
    
    <!-- Alanlar Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Ortaokul Etütleri -->
        <a href="<?= url('/etut/ortaokul') ?>" class="block group">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-8 text-white transform transition-all duration-200 hover:scale-105 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-school text-3xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">
                            <?php
                            // Ortaokul başvuru sayısı
                            $db = \Core\Database::getInstance();
                            $db->query("SELECT COUNT(*) as count FROM etut_applications WHERE form_type = 'ortaokul'");
                            $result = $db->single();
                            echo $result['count'] ?? 0;
                            ?>
                        </div>
                        <div class="text-sm opacity-90">Başvuru</div>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">Ortaokul Etütleri</h3>
                <p class="text-blue-100 text-sm mb-4">5-8. Sınıf öğrencileri için etüt başvuruları</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm opacity-90">Başvuruları Görüntüle</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </div>
            </div>
        </a>
        
        <!-- Lise Etütleri -->
        <a href="<?= url('/etut/lise') ?>" class="block group">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-8 text-white transform transition-all duration-200 hover:scale-105 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-graduation-cap text-3xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">
                            <?php
                            // Lise başvuru sayısı
                            $db->query("SELECT COUNT(*) as count FROM etut_applications WHERE form_type = 'lise'");
                            $result = $db->single();
                            echo $result['count'] ?? 0;
                            ?>
                        </div>
                        <div class="text-sm opacity-90">Başvuru</div>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">Lise Etütleri</h3>
                <p class="text-purple-100 text-sm mb-4">9-12. Sınıf öğrencileri için etüt başvuruları</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm opacity-90">Başvuruları Görüntüle</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>
    
    <!-- İstatistikler -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <?php
        // Genel istatistikler
        $db->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM etut_applications");
        $stats = $db->single();
        ?>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
            <div class="text-2xl font-bold text-gray-700"><?= $stats['total'] ?? 0 ?></div>
            <div class="text-sm text-gray-600">Toplam Başvuru</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="text-2xl font-bold text-yellow-600"><?= $stats['pending'] ?? 0 ?></div>
            <div class="text-sm text-gray-600">Bekleyen</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="text-2xl font-bold text-green-600"><?= $stats['approved'] ?? 0 ?></div>
            <div class="text-sm text-gray-600">Onaylanan</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="text-2xl font-bold text-red-600"><?= $stats['rejected'] ?? 0 ?></div>
            <div class="text-sm text-gray-600">Reddedilen</div>
        </div>
    </div>
    
    <!-- Bilgilendirme -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Bilgi:</strong> Öğrenciler giriş yapmadan başvuru formu doldurabilirler. 
                    Public formlar: 
                    <a href="<?= url('/etut/ortaokul-basvuru') ?>" target="_blank" class="underline font-semibold">Ortaokul Formu</a> | 
                    <a href="<?= url('/etut/lise-basvuru') ?>" target="_blank" class="underline font-semibold">Lise Formu</a>
                </p>
            </div>
        </div>
    </div>
</div>
