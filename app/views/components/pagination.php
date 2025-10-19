<?php
/**
 * Pagination Component
 * Sayfalama bileşeni
 * 
 * Kullanım:
 * <?php
 * $totalItems = 150;
 * $currentPage = 1;
 * $itemsPerPage = 20;
 * $baseUrl = '/students';
 * include VIEW_PATH . '/components/pagination.php';
 * ?>
 */

// Varsayılan değerler
$totalItems = $totalItems ?? 0;
$currentPage = $currentPage ?? 1;
$itemsPerPage = $itemsPerPage ?? 20;
$baseUrl = $baseUrl ?? '';

// Hesaplamalar
$totalPages = ceil($totalItems / $itemsPerPage);
$showingFrom = ($currentPage - 1) * $itemsPerPage + 1;
$showingTo = min($currentPage * $itemsPerPage, $totalItems);

// Sayfa yoksa gösterme
if ($totalPages <= 1) {
    return;
}

// URL builder
function buildPageUrl($baseUrl, $page, $additionalParams = []) {
    $params = array_merge(['page' => $page], $additionalParams);
    $queryString = http_build_query($params);
    return $baseUrl . '?' . $queryString;
}

// Gösterilecek sayfa numaraları
$pageRange = 2; // Her iki yönde kaç sayfa gösterilecek
$pages = [];

// İlk sayfa
if ($currentPage > $pageRange + 1) {
    $pages[] = 1;
    if ($currentPage > $pageRange + 2) {
        $pages[] = '...';
    }
}

// Orta sayfalar
for ($i = max(1, $currentPage - $pageRange); $i <= min($totalPages, $currentPage + $pageRange); $i++) {
    $pages[] = $i;
}

// Son sayfa
if ($currentPage < $totalPages - $pageRange) {
    if ($currentPage < $totalPages - $pageRange - 1) {
        $pages[] = '...';
    }
    $pages[] = $totalPages;
}
?>

<div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">
    <!-- Bilgi -->
    <div class="text-sm text-gray-600">
        <span class="font-medium"><?= formatNumber($showingFrom) ?></span>
        -
        <span class="font-medium"><?= formatNumber($showingTo) ?></span>
        arası gösteriliyor
        (Toplam: <span class="font-medium"><?= formatNumber($totalItems) ?></span>)
    </div>

    <!-- Pagination -->
    <nav class="flex items-center gap-1" aria-label="Sayfalama">
        <!-- Önceki -->
        <?php if ($currentPage > 1): ?>
            <a 
                href="<?= buildPageUrl($baseUrl, $currentPage - 1, $_GET) ?>"
                class="inline-flex items-center justify-center w-10 h-10 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                aria-label="Önceki sayfa"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        <?php else: ?>
            <span class="inline-flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        <?php endif; ?>

        <!-- Sayfa numaraları -->
        <?php foreach ($pages as $page): ?>
            <?php if ($page === '...'): ?>
                <span class="inline-flex items-center justify-center w-10 h-10 text-gray-500">
                    ...
                </span>
            <?php elseif ($page == $currentPage): ?>
                <span 
                    class="inline-flex items-center justify-center w-10 h-10 text-white bg-indigo-600 border border-indigo-600 rounded-lg font-medium"
                    aria-current="page"
                >
                    <?= $page ?>
                </span>
            <?php else: ?>
                <a 
                    href="<?= buildPageUrl($baseUrl, $page, $_GET) ?>"
                    class="inline-flex items-center justify-center w-10 h-10 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    <?= $page ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Sonraki -->
        <?php if ($currentPage < $totalPages): ?>
            <a 
                href="<?= buildPageUrl($baseUrl, $currentPage + 1, $_GET) ?>"
                class="inline-flex items-center justify-center w-10 h-10 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                aria-label="Sonraki sayfa"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        <?php else: ?>
            <span class="inline-flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        <?php endif; ?>
    </nav>

    <!-- Sayfa başına kayıt seçimi (opsiyonel) -->
    <?php if (isset($showPerPageSelector) && $showPerPageSelector): ?>
        <div class="flex items-center gap-2">
            <label for="perPage" class="text-sm text-gray-600">Sayfa başına:</label>
            <select 
                id="perPage" 
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                onchange="window.location.href = '<?= $baseUrl ?>?per_page=' + this.value"
            >
                <option value="20" <?= $itemsPerPage == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $itemsPerPage == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $itemsPerPage == 100 ? 'selected' : '' ?>>100</option>
                <option value="200" <?= $itemsPerPage == 200 ? 'selected' : '' ?>>200</option>
            </select>
        </div>
    <?php endif; ?>
</div>

<!-- Mobil için basit pagination -->
<div class="sm:hidden flex items-center justify-between py-4">
    <?php if ($currentPage > 1): ?>
        <a 
            href="<?= buildPageUrl($baseUrl, $currentPage - 1, $_GET) ?>"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Önceki
        </a>
    <?php else: ?>
        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Önceki
        </span>
    <?php endif; ?>

    <span class="text-sm text-gray-600">
        Sayfa <?= $currentPage ?> / <?= $totalPages ?>
    </span>

    <?php if ($currentPage < $totalPages): ?>
        <a 
            href="<?= buildPageUrl($baseUrl, $currentPage + 1, $_GET) ?>"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
        >
            Sonraki
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    <?php else: ?>
        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
            Sonraki
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </span>
    <?php endif; ?>
</div>