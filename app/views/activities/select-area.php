<?php
/**
 * Etkinlik Alanı Seçim Sayfası
 * Kare kartlar şeklinde etkinlik alanlarını gösterir
 */

$activityAreas = $data['activityAreas'] ?? [];
$pageTitle = $data['title'] ?? 'Etkinlik Alanı Seçin';
?>

<!-- Main Content -->
<div class="max-w-7xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-teal-50 to-blue-50 border border-teal-200 rounded-lg p-6">
            <h1 class="text-2xl font-bold text-teal-800">📍 <?= $pageTitle ?></h1>
            <p class="text-teal-700 mt-2">Etkinlik oluşturmak istediğiniz alanı seçin</p>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <div id="alertContainer" class="mb-6"></div>
    
    <!-- Activity Areas Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php if (empty($activityAreas)): ?>
            <div class="col-span-full">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <svg class="w-12 h-12 mx-auto text-yellow-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7 9c0-1.657.895-3 2-3h6c1.105 0 2 1.343 2 3"></path>
                    </svg>
                    <p class="text-yellow-800">Henüz etkinlik alanı eklenmiş değil</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($activityAreas as $area): ?>
                <a href="<?= url('/activities/create?area_id=' . $area['id']) ?>" 
                   class="group block">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 hover:border-teal-400 h-full flex flex-col">
                        
                        <!-- Image or Color Background Section -->
                        <div class="h-32 flex items-center justify-center relative overflow-hidden transition-all bg-cover bg-center"
                             <?php if (!empty($area['area_image'])): ?>
                                 style="background-image: url('<?= url('/assets/uploads/activity-areas/' . esc($area['area_image'])) ?>'); background-size: cover; background-position: center;"
                             <?php else: ?>
                                 style="background: linear-gradient(135deg, <?= $area['color_code'] ?? '#3b82f6' ?> 0%, <?= $area['color_code'] ?? '#3b82f6' ?>dd 100%); opacity: 0.9;"
                             <?php endif; ?>>
                            
                            <!-- Overlay for better text readability if image exists -->
                            <?php if (!empty($area['area_image'])): ?>
                                <div class="absolute inset-0 bg-black opacity-20 group-hover:opacity-10 transition-opacity"></div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="flex-1 p-3 text-center flex flex-col items-center justify-center">
                            <h3 class="font-bold text-gray-900 text-sm line-clamp-3 group-hover:text-teal-700 transition-colors leading-tight">
                                <?= esc($area['area_name']) ?>
                            </h3>
                            
                            <!-- Click to Select Button -->
                            <div class="mt-2 w-full">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800 group-hover:bg-teal-200 transition-colors">
                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Seç
                                </span>
                            </div>
                        </div>
                        
                        <!-- Hover Effect Border -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute inset-0 rounded-lg border-2 border-transparent group-hover:border-teal-400 transition-colors duration-300"></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Geri Dön Butonu -->
    <div class="mt-12 flex justify-center">
        <a href="<?= url('/activities') ?>" 
           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Etkinliklere Geri Dön</span>
        </a>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
