<?php
/** @var array $roles */
/** @var array $pages */
/** @var array $rolePermissions */
?>

<!-- Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rol İzinleri</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Rol bazlı erişim kontrolü</p>
    </div>
    <a href="<?= url('/admin/roles/create') ?>" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        + Yeni Rol
    </a>
</div>

<!-- Flash Messages -->
<?php include VIEW_PATH . '/components/alert.php'; ?>

<!-- Rol Seçimi -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
    <label for="role_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rol:</label>
    <select id="role_select" onchange="loadRolePermissions(this.value)" 
            class="w-full md:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        <option value="">Rol seçin...</option>
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id'] ?>" data-role-name="<?= htmlspecialchars($role['display_name']) ?>">
                <?= htmlspecialchars($role['display_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- İzinler Tablosu -->
<div id="permissions-container" class="hidden">
    <form id="permissions-form" method="POST" action="<?= url('/admin/roles/update-permissions') ?>">
        <?= csrfField() ?>
        <input type="hidden" name="role_id" id="selected_role_id">
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white" id="role-title">İzinler</h2>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="selectAll()" 
                            class="px-3 py-1.5 text-sm text-green-700 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors border border-green-300 dark:border-green-700">
                        ✓ Tümünü Seç
                    </button>
                    <button type="button" onclick="clearAll()" 
                            class="px-3 py-1.5 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors border border-red-300 dark:border-red-700">
                        ✗ Tümünü Kaldır
                    </button>
                    <button type="button" onclick="resetForm()" 
                            class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors border border-gray-300 dark:border-gray-600">
                        ↺ Geri Al
                    </button>
                    <button type="submit" 
                            class="px-4 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-sm">
                        <span class="flex items-center gap-1.5">
                            <span>💾</span>
                            <span>Kaydet</span>
                        </span>
                    </button>
                </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sayfa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-24">
                                <div class="flex flex-col items-center gap-1">
                                    <span>Görüntüle</span>
                                    <input type="checkbox" id="check_all_view" class="rounded text-primary-600" onclick="checkAllColumn('view')">
                                </div>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-24">
                                <div class="flex flex-col items-center gap-1">
                                    <span>Ekle</span>
                                    <input type="checkbox" id="check_all_create" class="rounded text-primary-600" onclick="checkAllColumn('create')">
                                </div>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-24">
                                <div class="flex flex-col items-center gap-1">
                                    <span>Düzenle</span>
                                    <input type="checkbox" id="check_all_edit" class="rounded text-primary-600" onclick="checkAllColumn('edit')">
                                </div>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-24">
                                <div class="flex flex-col items-center gap-1">
                                    <span>Sil</span>
                                    <input type="checkbox" id="check_all_delete" class="rounded text-primary-600" onclick="checkAllColumn('delete')">
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="permissions-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- AJAX ile yüklenecek -->
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<!-- Boş Durum -->
<div id="empty-state" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </div>
    <p class="text-gray-500 dark:text-gray-400">Bir rol seçin</p>
</div>

<script>
let currentPages = <?= json_encode($pages) ?>;
let originalPermissions = {};
const BASE_URL = '<?= rtrim(BASE_URL ?? '', '/') ?>';

// Flash message helper
function setFlashMessage(message, type) {
    sessionStorage.setItem('flash_message', message);
    sessionStorage.setItem('flash_type', type);
}

function loadRolePermissions(roleId) {
    if (!roleId) {
        document.getElementById('permissions-container').classList.add('hidden');
        document.getElementById('empty-state').classList.remove('hidden');
        return;
    }
    
    fetch(`${BASE_URL}/admin/roles/permissions?role_id=${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPermissions(roleId, data.permissions, data.role_name);
                originalPermissions = JSON.parse(JSON.stringify(data.permissions));
                document.getElementById('permissions-container').classList.remove('hidden');
                document.getElementById('empty-state').classList.add('hidden');
            } else {
                alert('❌ Hata: ' + (data.message || 'İzinler yüklenemedi'));
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('❌ İzinler yüklenirken bir hata oluştu: ' + error.message);
        });
}

function displayPermissions(roleId, permissions, roleName) {
    document.getElementById('selected_role_id').value = roleId;
    document.getElementById('role-title').textContent = roleName;
    
    displayPermissionsTable(permissions);
    updateHeaderCheckboxes();
}

function displayPermissionsTable(permissions) {
    const tbody = document.getElementById('permissions-table-body');
    if (!tbody) {
        console.error('permissions-table-body element not found!');
        return;
    }
    
    tbody.innerHTML = '';
    
    currentPages.forEach(page => {
        const perm = permissions.find(p => p.page_id == page.id) || {
            can_view: 0, can_create: 0, can_edit: 0, can_delete: 0
        };
        
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50';
        row.innerHTML = `
            <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-white">${page.page_name}</td>
            <td class="px-6 py-3 text-center">
                <input type="checkbox" name="permissions[${page.id}][view]" value="1" 
                       class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500 perm-view"
                       onchange="updateHeaderCheckboxes()"
                       ${perm.can_view == 1 ? 'checked' : ''}>
            </td>
            <td class="px-6 py-3 text-center">
                <input type="checkbox" name="permissions[${page.id}][create]" value="1" 
                       class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500 perm-create"
                       onchange="updateHeaderCheckboxes()"
                       ${perm.can_create == 1 ? 'checked' : ''}>
            </td>
            <td class="px-6 py-3 text-center">
                <input type="checkbox" name="permissions[${page.id}][edit]" value="1" 
                       class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500 perm-edit"
                       onchange="updateHeaderCheckboxes()"
                       ${perm.can_edit == 1 ? 'checked' : ''}>
            </td>
            <td class="px-6 py-3 text-center">
                <input type="checkbox" name="permissions[${page.id}][delete]" value="1" 
                       class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500 perm-delete"
                       onchange="updateHeaderCheckboxes()"
                       ${perm.can_delete == 1 ? 'checked' : ''}>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function checkAllColumn(columnType) {
    const checkbox = document.getElementById(`check_all_${columnType}`);
    document.querySelectorAll(`.perm-${columnType}`).forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

function selectAll() {
    // Tüm checkbox'ları seç
    document.querySelectorAll('#permissions-table-body input[type="checkbox"]').forEach(cb => {
        cb.checked = true;
    });
    // Başlıktaki checkbox'ları güncelle
    updateHeaderCheckboxes();
}

function clearAll() {
    // Tüm checkbox'ları temizle
    document.querySelectorAll('#permissions-table-body input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });
    // Başlıktaki checkbox'ları güncelle
    updateHeaderCheckboxes();
}

function resetForm() {
    const roleId = document.getElementById('selected_role_id').value;
    if (roleId) {
        // Rol izinlerini tekrar yükle (sunucudan)
        loadRolePermissions(roleId);
    }
}

function updateHeaderCheckboxes() {
    ['view', 'create', 'edit', 'delete'].forEach(type => {
        const checkboxes = document.querySelectorAll(`.perm-${type}`);
        const headerCheckbox = document.getElementById(`check_all_${type}`);
        if (headerCheckbox && checkboxes.length > 0) {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            headerCheckbox.checked = allChecked;
        }
    });
}

document.getElementById('permissions-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const roleId = formData.get('role_id');
    const csrfToken = formData.get('csrf_token');
    
    // Tüm sayfalar için izinleri topla (checkbox işaretli olsun veya olmasın)
    const permissions = {};
    
    currentPages.forEach(page => {
        const pageId = page.id;
        permissions[pageId] = {
            view: document.querySelector(`input[name="permissions[${pageId}][view]"]`)?.checked ? '1' : '0',
            create: document.querySelector(`input[name="permissions[${pageId}][create]"]`)?.checked ? '1' : '0',
            edit: document.querySelector(`input[name="permissions[${pageId}][edit]"]`)?.checked ? '1' : '0',
            delete: document.querySelector(`input[name="permissions[${pageId}][delete]"]`)?.checked ? '1' : '0'
        };
    });
    
    try {
        const response = await fetch(`${BASE_URL}/admin/roles/update-permissions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                role_id: roleId,
                csrf_token: csrfToken,
                permissions: permissions
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ ' + data.message);
            setTimeout(() => location.reload(), 800);
        } else {
            alert('❌ Hata: ' + (data.message || 'İzinler güncellenirken bir hata oluştu'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Bir hata oluştu: ' + error.message);
    }
});
</script>
