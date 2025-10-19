<?php
/**
 * Rol Yönetimi
 * Sistem rollerini ve izinlerini yönetme
 */

require_once VIEW_PATH . '/layouts/header.php';

// Roller controller'dan gelmeli
$roles = $roles ?? [];
$permissions = $permissions ?? [];
?>

<div class="container mx-auto px-4 py-6">
    <!-- Başlık -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rol Yönetimi</h1>
            <p class="text-gray-600 mt-1">Kullanıcı rollerini ve yetkilerini düzenleyin</p>
        </div>
        
        <button 
            onclick="openModal('createRoleModal')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Rol Ekle
        </button>
    </div>

    <!-- Alert -->
    <?php include VIEW_PATH . '/components/alert.php'; ?>

    <!-- Roller Listesi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol Adı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Açıklama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı Sayısı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $role): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <!-- Rol İkonu -->
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                                        <?= strtoupper(mb_substr($role['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= e($role['name']) ?></div>
                                        <?php if ($role['is_system']): ?>
                                            <span class="text-xs text-gray-500">Sistem Rolü</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    <?= e($role['description'] ?: '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= formatNumber($role['user_count'] ?? 0) ?> kullanıcı
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($role['is_active']): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                        Pasif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        onclick="viewPermissions(<?= $role['id'] ?>)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                        title="Yetkiler"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    
                                    <?php if (!$role['is_system']): ?>
                                        <button 
                                            onclick="editRole(<?= $role['id'] ?>)"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Düzenle"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        
                                        <button 
                                            onclick="deleteRole(<?= $role['id'] ?>)"
                                            class="text-red-600 hover:text-red-900"
                                            title="Sil"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 ml-2">Sistem rolü silinemez</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Henüz rol tanımlanmamış
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Rol Açıklamaları -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">Varsayılan Roller</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">👑 Admin</h4>
                <p class="text-sm text-gray-600">Tüm sistem yetkilerine sahiptir. Kullanıcı, rol ve sistem ayarlarını yönetebilir.</p>
            </div>
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">👨‍🏫 Öğretmen</h4>
                <p class="text-sm text-gray-600">Öğrenci ve etkinlik bilgilerini görüntüleyebilir. Etkinlik rezervasyonu yapabilir.</p>
            </div>
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">📋 Sekreter</h4>
                <p class="text-sm text-gray-600">Öğrenci kaydı yapabilir, etkinlikleri yönetebilir. Raporları görüntüleyebilir.</p>
            </div>
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">🎓 Müdür</h4>
                <p class="text-sm text-gray-600">Tüm verilere okuma yetkisi vardır. Onay gerektiren işlemleri onaylayabilir.</p>
            </div>
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">👔 Müdür Yardımcısı</h4>
                <p class="text-sm text-gray-600">Müdür ile benzer yetkiler ancak bazı kritik işlemler için müdür onayı gerekir.</p>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Rol Modal -->
<?php 
$modalId = 'createRoleModal';
$modalTitle = 'Yeni Rol Oluştur';
$modalSize = 'lg';
include VIEW_PATH . '/components/modal.php';
?>

<div id="createRoleModal-content">
    <form id="createRoleForm" onsubmit="return handleCreateRole(event)">
        <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Rol Adı <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Örn: Veli, Yönetici Asistanı"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Açıklama
                </label>
                <textarea 
                    name="description" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Rol hakkında kısa açıklama"
                ></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    checked
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded"
                >
                <label class="text-sm font-medium text-gray-700">Aktif</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
            <button 
                type="button"
                onclick="closeModal('createRoleModal')"
                class="px-4 py-2 border border-gray-300 rounded-lg"
            >
                İptal
            </button>
            <button 
                type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg"
            >
                Oluştur
            </button>
        </div>
    </form>
</div>

<!-- Yetkileri Görüntüle Modal -->
<?php 
$modalId = 'viewPermissionsModal';
$modalTitle = 'Rol Yetkileri';
$modalSize = 'xl';
include VIEW_PATH . '/components/modal.php';
?>

<div id="viewPermissionsModal-content">
    <!-- İçerik AJAX ile yüklenecek -->
</div>

<script>
function handleCreateRole(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    showLoading('Rol oluşturuluyor...');
    
    fetch('<?= url('api/roles/create') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            closeModal('createRoleModal');
            window.location.reload();
        } else {
            alert(data.message || 'Bir hata oluştu');
        }
    })
    .catch(error => {
        hideLoading();
        alert('Bir hata oluştu');
    });
    
    return false;
}

function editRole(roleId) {
    window.location.href = `<?= url('admin/roles/') ?>${roleId}/edit`;
}

function viewPermissions(roleId) {
    showLoading('Yetkiler yükleniyor...');
    
    fetch(`<?= url('api/roles/') ?>${roleId}/permissions`)
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            const permissions = data.data.permissions;
            let html = '<div class="space-y-4">';
            
            // Yetkileri grupla
            const grouped = {};
            permissions.forEach(perm => {
                const module = perm.module || 'Genel';
                if (!grouped[module]) grouped[module] = [];
                grouped[module].push(perm);
            });
            
            // Her modül için
            for (const [module, perms] of Object.entries(grouped)) {
                html += `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">${module}</h4>
                        <div class="grid grid-cols-2 gap-2">
                            ${perms.map(p => `
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" ${p.has_permission ? 'checked' : ''} disabled class="w-4 h-4">
                                    <span class="text-sm text-gray-700">${p.name}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }
            
            html += '</div>';
            document.getElementById('viewPermissionsModal-content').innerHTML = html;
            openModal('viewPermissionsModal');
        }
    });
}

function deleteRole(roleId) {
    if (!confirm('Bu rolü silmek istediğinizden emin misiniz?')) return;
    
    showLoading('Rol siliniyor...');
    
    fetch(`<?= url('api/roles/') ?>${roleId}`, {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({csrf_token: '<?= getCsrfToken() ?>'})
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Silme başarısız');
        }
    });
}
</script>

<?php require_once VIEW_PATH . '/layouts/footer.php'; ?>