<?php
// Normalize inputs passed from controller
$selected_form = $selected_form ?? null;
$settings = $settings ?? [];

$orta = $settings['ortaokul'] ?? [
    'is_active' => 0,
    'title' => '',
    'description' => '',
    'closed_message' => '',
    // max_applications_per_student removed
];

$lise = $settings['lise'] ?? [
    'is_active' => 0,
    'title' => '',
    'description' => '',
    'closed_message' => '',
    // max_applications_per_student removed
];
?>

<div class="p-6">
    <!-- Başlık -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📋 Etüt Form Ayarları</h1>
        <p class="text-gray-600 mt-2">Ortaokul ve lise etüt başvuru formlarının açık/kapalı durumunu ve ayarlarını yönetin</p>
    </div>
    
    <!-- Ortaokul Form Ayarları -->
    <?php if ($selected_form === null || $selected_form === 'ortaokul'): ?>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-school text-blue-600"></i> Ortaokul Etüt Formu
            </h2>
            <div class="flex items-center gap-2">
                <a 
                    href="<?= url('/etut/ortaokul-basvuru') ?>" 
                    target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"
                >
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Formu Görüntüle
                </a>
                <button 
                    onclick="toggleForm('ortaokul')" 
                    id="ortaokul-toggle-btn"
                    class="px-4 py-2 rounded-lg font-semibold transition duration-200 <?= (int)$orta['is_active'] === 1 ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-red-600 hover:bg-red-700 text-white' ?>"
                >
                    <i class="fas <?= (int)$orta['is_active'] === 1 ? 'fa-toggle-on' : 'fa-toggle-off' ?> mr-2"></i>
                    <span id="ortaokul-toggle-text"><?= (int)$orta['is_active'] === 1 ? 'Form Açık' : 'Form Kapalı' ?></span>
                </button>
            </div>
        </div>
        
        <form id="ortaokul-settings-form" class="space-y-4">
            <input type="hidden" name="form_type" value="ortaokul">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Form Başlığı</label>
                <input 
                    type="text" 
                    name="title" 
                    value="<?= htmlspecialchars((string)$orta['title']) ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Form Açıklaması</label>
                <textarea 
                    name="description" 
                    rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                ><?= htmlspecialchars((string)$orta['description']) ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Form Kapalıyken Gösterilecek Mesaj</label>
                <textarea 
                    name="closed_message" 
                    rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    required
            ><?= htmlspecialchars((string)$orta['closed_message']) ?></textarea>
            </div>
            
            <!-- max_applications_per_student removed; unlimited applications allowed -->
            
            <button 
                type="submit" 
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold"
            >
                <i class="fas fa-save mr-2"></i>
                Ayarları Kaydet
            </button>
        </form>
        
        
    </div>
    <?php endif; ?>
    
    <!-- Lise Form Ayarları -->
    <?php if ($selected_form === null || $selected_form === 'lise'): ?>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-graduation-cap text-purple-600"></i> Lise Etüt Formu
            </h2>
            <div class="flex items-center gap-2">
                <a 
                    href="<?= url('/etut/lise-basvuru') ?>" 
                    target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"
                >
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Formu Görüntüle
                </a>
                <button 
                    onclick="toggleForm('lise')" 
                    id="lise-toggle-btn"
                    class="px-4 py-2 rounded-lg font-semibold transition duration-200 <?= (int)$lise['is_active'] === 1 ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-red-600 hover:bg-red-700 text-white' ?>"
                >
                    <i class="fas <?= (int)$lise['is_active'] === 1 ? 'fa-toggle-on' : 'fa-toggle-off' ?> mr-2"></i>
                    <span id="lise-toggle-text"><?= (int)$lise['is_active'] === 1 ? 'Form Açık' : 'Form Kapalı' ?></span>
                </button>
            </div>
        </div>
        
        <form id="lise-settings-form" class="space-y-4">
            <input type="hidden" name="form_type" value="lise">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Form Başlığı</label>
                <input 
                    type="text" 
                    name="title" 
                    value="<?= htmlspecialchars((string)$lise['title']) ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500"
                    required
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Form Açıklaması</label>
                <textarea 
                    name="description" 
                    rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500"
                ><?= htmlspecialchars((string)$lise['description']) ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Form Kapalıyken Gösterilecek Mesaj</label>
                <textarea 
                    name="closed_message" 
                    rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500"
                    required
            ><?= htmlspecialchars((string)$lise['closed_message']) ?></textarea>
            </div>
            
            <!-- max_applications_per_student removed; unlimited applications allowed -->
            
            <button 
                type="submit" 
                class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold"
            >
                <i class="fas fa-save mr-2"></i>
                Ayarları Kaydet
            </button>
        </form>
        
        
    </div>
    <?php endif; ?>
</div>

<script>
// Toggle form status
async function toggleForm(formType) {
    const btn = document.getElementById(`${formType}-toggle-btn`);
    const text = document.getElementById(`${formType}-toggle-text`);
    const icon = btn.querySelector('i');
    
    try {
        const formData = new FormData();
        formData.append('form_type', formType);
        // Include CSRF token for admin actions
        formData.append('csrf_token', '<?= csrf_token() ?>');
        
        const response = await fetch('<?= url('/admin/etut/toggle') ?>', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Toggle button state
            const isActive = text.textContent === 'Form Kapalı';
            
            if (isActive) {
                btn.className = 'px-4 py-2 rounded-lg font-semibold transition duration-200 bg-green-600 hover:bg-green-700 text-white';
                icon.className = 'fas fa-toggle-on mr-2';
                text.textContent = 'Form Açık';
            } else {
                btn.className = 'px-4 py-2 rounded-lg font-semibold transition duration-200 bg-red-600 hover:bg-red-700 text-white';
                icon.className = 'fas fa-toggle-off mr-2';
                text.textContent = 'Form Kapalı';
            }
            
            showToast(result.message, 'success');
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        console.error('Hata:', error);
        showToast('Form durumu güncellenirken bir hata oluştu', 'error');
    }
}

// Update form settings (attach listeners only if form exists)
['ortaokul', 'lise'].forEach(formType => {
    const formEl = document.getElementById(`${formType}-settings-form`);
    if (!formEl) return; // form not present on this page

    formEl.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        // Ensure CSRF and credentials are sent
        formData.append('csrf_token', '<?= csrf_token() ?>');

        try {
            const response = await fetch('<?= url('/admin/etut/update-settings') ?>', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            console.error('Hata:', error);
            showToast('Ayarlar güncellenirken bir hata oluştu', 'error');
        }
    });
});

// Toast notification
function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.animate-slide-out {
    animation: slide-out 0.3s ease-in;
}
</style>
