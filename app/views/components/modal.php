<?php
/**
 * Modal Component
 * Yeniden kullanılabilir modal yapısı
 * 
 * Kullanım:
 * <?php
 * $modalId = 'deleteModal';
 * $modalTitle = 'Öğrenciyi Sil';
 * $modalSize = 'md'; // sm, md, lg, xl
 * include VIEW_PATH . '/components/modal.php';
 * ?>
 * 
 * Modal içeriği:
 * <div id="deleteModal-content">
 *     İçerik buraya...
 * </div>
 * 
 * Modal açma:
 * <button onclick="openModal('deleteModal')">Aç</button>
 */

$modalId = $modalId ?? 'modal';
$modalTitle = $modalTitle ?? 'Modal';
$modalSize = $modalSize ?? 'md'; // sm, md, lg, xl

// Boyut sınıfları
$sizeClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    'full' => 'max-w-full'
];

$sizeClass = $sizeClasses[$modalSize] ?? $sizeClasses['md'];
?>

<!-- Modal Backdrop -->
<div 
    id="<?= e($modalId) ?>" 
    class="modal-backdrop fixed inset-0 z-50 hidden overflow-y-auto"
    aria-labelledby="<?= e($modalId) ?>-title" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Overlay -->
    <div 
        class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"
        onclick="closeModal('<?= e($modalId) ?>')"
    ></div>

    <!-- Modal Container -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <!-- Modal Content -->
        <div class="modal-content relative w-full <?= e($sizeClass) ?> transform transition-all">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 id="<?= e($modalId) ?>-title" class="text-lg font-semibold text-gray-900">
                        <?= e($modalTitle) ?>
                    </h3>
                    <button 
                        type="button"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                        onclick="closeModal('<?= e($modalId) ?>')"
                        aria-label="Kapat"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-4">
                    <div id="<?= e($modalId) ?>-content">
                        <!-- Modal content will be inserted here -->
                    </div>
                </div>

                <!-- Footer (Optional) -->
                <div id="<?= e($modalId) ?>-footer" class="hidden px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <!-- Footer content will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Animasyon
            setTimeout(() => {
                modal.querySelector('.modal-content').style.opacity = '1';
                modal.querySelector('.modal-content').style.transform = 'scale(1)';
            }, 10);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const content = modal.querySelector('.modal-content');
            content.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 200);
        }
    }

    // ESC tuşu ile kapat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal-backdrop:not(.hidden)');
            openModals.forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
</script>

<style>
    .modal-content {
        opacity: 0;
        transform: scale(0.95);
        transition: opacity 0.2s ease-out, transform 0.2s ease-out;
    }
</style>