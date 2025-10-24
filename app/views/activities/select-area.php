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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200 hover:border-teal-300">
                        
                        <!-- Card Container -->
                        <div class="h-48 flex flex-col items-center justify-center relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                            
                            <!-- Color Accent -->
                            <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity"
                                 style="background-color: <?= $area['color_code'] ?? '#3b82f6' ?>"></div>
                            
                            <!-- Color Circle Background -->
                            <div class="w-20 h-20 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform"
                                 style="background-color: <?= $area['color_code'] ?? '#3b82f6' ?>; opacity: 0.15;">
                                <svg class="w-10 h-10" fill="currentColor" 
                                     style="color: <?= $area['color_code'] ?? '#3b82f6' ?>" 
                                     viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2m0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8m3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5m-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11m3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-4 text-center">
                            <h3 class="font-bold text-gray-900 text-lg line-clamp-2 group-hover:text-teal-700 transition-colors">
                                <?= esc($area['area_name']) ?>
                            </h3>
                            
                            <!-- Click to Select -->
                            <div class="mt-3 inline-block">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800 group-hover:bg-teal-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Seç
                                </span>
                            </div>
                        </div>
                        
                        <!-- Hover Effect Overlay -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute inset-0 rounded-lg border-2 border-transparent group-hover:border-teal-400 transition-colors duration-300"></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Geri Dön Butonu -->
    <div class="mt-12 text-center">
        <a href="<?= url('/activities') ?>" 
           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Etkinliklere Geri Dön
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
