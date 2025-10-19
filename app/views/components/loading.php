<?php
/**
 * Loading Component
 * Yüklenme göstergeleri
 * 
 * Kullanım:
 * <?php
 * $loadingType = 'spinner'; // spinner, dots, pulse
 * $loadingText = 'Yükleniyor...';
 * $loadingSize = 'md'; // sm, md, lg
 * include VIEW_PATH . '/components/loading.php';
 * ?>
 */

$loadingType = $loadingType ?? 'spinner';
$loadingText = $loadingText ?? 'Yükleniyor...';
$loadingSize = $loadingSize ?? 'md';

// Boyut sınıfları
$sizes = [
    'sm' => ['w-5 h-5', 'text-sm'],
    'md' => ['w-8 h-8', 'text-base'],
    'lg' => ['w-12 h-12', 'text-lg']
];

$sizeClasses = $sizes[$loadingSize] ?? $sizes['md'];
?>

<?php if ($loadingType === 'spinner'): ?>
    <!-- Spinner Loading -->
    <div class="flex flex-col items-center justify-center gap-3">
        <div class="<?= $sizeClasses[0] ?> animate-spin">
            <svg class="w-full h-full text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <?php if ($loadingText): ?>
            <p class="<?= $sizeClasses[1] ?> text-gray-600 font-medium"><?= e($loadingText) ?></p>
        <?php endif; ?>
    </div>

<?php elseif ($loadingType === 'dots'): ?>
    <!-- Dots Loading -->
    <div class="flex flex-col items-center justify-center gap-3">
        <div class="flex gap-2">
            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
        <?php if ($loadingText): ?>
            <p class="<?= $sizeClasses[1] ?> text-gray-600 font-medium"><?= e($loadingText) ?></p>
        <?php endif; ?>
    </div>

<?php elseif ($loadingType === 'pulse'): ?>
    <!-- Pulse Loading -->
    <div class="flex flex-col items-center justify-center gap-3">
        <div class="<?= $sizeClasses[0] ?> bg-indigo-600 rounded-full animate-pulse"></div>
        <?php if ($loadingText): ?>
            <p class="<?= $sizeClasses[1] ?> text-gray-600 font-medium"><?= e($loadingText) ?></p>
        <?php endif; ?>
    </div>

<?php elseif ($loadingType === 'bars'): ?>
    <!-- Bars Loading -->
    <div class="flex flex-col items-center justify-center gap-3">
        <div class="flex gap-1 h-8">
            <div class="w-1 bg-indigo-600 animate-pulse" style="animation-delay: 0ms"></div>
            <div class="w-1 bg-indigo-600 animate-pulse" style="animation-delay: 150ms"></div>
            <div class="w-1 bg-indigo-600 animate-pulse" style="animation-delay: 300ms"></div>
            <div class="w-1 bg-indigo-600 animate-pulse" style="animation-delay: 450ms"></div>
        </div>
        <?php if ($loadingText): ?>
            <p class="<?= $sizeClasses[1] ?> text-gray-600 font-medium"><?= e($loadingText) ?></p>
        <?php endif; ?>
    </div>

<?php else: ?>
    <!-- Simple text loading -->
    <div class="flex items-center justify-center">
        <p class="<?= $sizeClasses[1] ?> text-gray-600 font-medium"><?= e($loadingText) ?></p>
    </div>
<?php endif; ?>

<!-- Full page loading overlay (kullanımı: id="fullPageLoading" hidden class'ını kaldırarak göster) -->
<div id="fullPageLoading" class="fixed inset-0 z-50 bg-white bg-opacity-90 hidden">
    <div class="flex items-center justify-center h-full">
        <div class="flex flex-col items-center gap-4">
            <div class="w-16 h-16 animate-spin">
                <svg class="w-full h-full text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <p class="text-lg text-gray-600 font-medium">Yükleniyor...</p>
        </div>
    </div>
</div>

<script>
    // Full page loading göster/gizle
    function showFullPageLoading(text = 'Yükleniyor...') {
        const loading = document.getElementById('fullPageLoading');
        if (loading) {
            loading.querySelector('p').textContent = text;
            loading.classList.remove('hidden');
        }
    }

    function hideFullPageLoading() {
        const loading = document.getElementById('fullPageLoading');
        if (loading) {
            loading.classList.add('hidden');
        }
    }

    // AJAX isteklerinde otomatik loading
    window.showLoading = showFullPageLoading;
    window.hideLoading = hideFullPageLoading;
</script>

<style>
    @keyframes bounce {
        0%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
    }
    
    .animate-bounce {
        animation: bounce 1s infinite;
    }
</style>