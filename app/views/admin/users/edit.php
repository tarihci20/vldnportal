<?php
/** @var array $user */
/** @var array $roles */
/** @var array $pages */
/** @var array $rolePermissions */
$permissions = $rolePermissions ?? []; // Backward compatibility

// UTF-8 encoding kontrol et ve düzelt
foreach ($permissions as &$perm) {
    if (isset($perm['page_name']) && !mb_check_encoding($perm['page_name'], 'UTF-8')) {
        $perm['page_name'] = mb_convert_encoding($perm['page_name'], 'UTF-8', 'ISO-8859-9,UTF-8');
    }
}
unset($perm);
?>
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14 max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="<?= url('/admin/users') ?>" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold">Kullanıcı Düzenle: <?= e($user['full_name']) ?></h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kullanıcı Bilgileri -->
            <div class="lg:col-span-2">
                <form method="POST" action="<?= url("/admin/users/{$user['id']}") ?>" class="space-y-6">
                    <?= csrfField() ?>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold mb-4">Kullanıcı Bilgileri</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad *</label>
                                <input type="text" id="full_name" name="full_name" value="<?= e($user['full_name']) ?>" required 
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı Adı *</label>
                                <input type="text" id="username" name="username" value="<?= e($user['username']) ?>" required 
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-posta *</label>
                                <input type="email" id="email" name="email" value="<?= e($user['email']) ?>" required 
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                                <input type="tel" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>" 
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifre</label>
                                <input type="password" id="password" name="password" minlength="6"
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">Değiştirmek istemiyorsanız boş bırakın</p>
                            </div>
                            
                            <div>
                                <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
                                <select id="role_id" name="role_id" required onchange="loadRolePermissions(this.value)"
                                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                                            <?= e($role['display_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-4 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?> class="rounded">
                                <span class="ml-2 text-sm text-gray-700">Kullanıcı aktif</span>
                            </label>
                            <br>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="can_change_password" value="1" <?= $user['can_change_password'] ? 'checked' : '' ?> class="rounded">
                                <span class="ml-2 text-sm text-gray-700">Kullanıcı şifresini değiştirebilir</span>
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3">
                        <a href="<?= url('/admin/users') ?>" class="px-6 py-2 border rounded hover:bg-gray-50">
                            İptal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rol İzinleri -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow sticky top-20">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Rol İzinleri</h2>
                        <button onclick="showPermissionsModal()" class="text-indigo-600 hover:text-indigo-700 text-sm">
                            Düzenle
                        </button>
                    </div>
                    
                    <div id="current-permissions" class="space-y-2 max-h-96 overflow-y-auto">
                        <?php foreach ($permissions as $perm): ?>
                            <?php if ($perm['can_view']): ?>
                                <div class="p-2 bg-gray-50 rounded text-sm">
                                    <div class="font-medium"><?= e($perm['page_name']) ?></div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        <?php
                                        $perms = [];
                                        if ($perm['can_view']) $perms[] = 'Görüntüleme';
                                        if ($perm['can_create']) $perms[] = 'Ekleme';
                                        if ($perm['can_edit']) $perms[] = 'Düzenleme';
                                        if ($perm['can_delete']) $perms[] = 'Silme';
                                        echo implode(', ', $perms);
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- İzin Düzenleme Modal -->
<div id="permissionsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold">Rol İzinlerini Düzenle</h3>
                <button onclick="closePermissionsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
            <table class="min-w-full">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sayfa</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Görüntüleme</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ekleme</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Düzenleme</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Silme</th>
                    </tr>
                </thead>
                <tbody id="permissionsTable" class="divide-y divide-gray-200">
                    <!-- Will be populated via JS -->
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t flex justify-end gap-3">
            <button onclick="closePermissionsModal()" class="px-6 py-2 border rounded hover:bg-gray-50">
                İptal
            </button>
            <button onclick="savePermissions()" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Kaydet
            </button>
        </div>
    </div>
</div>

<script>
const currentRoleId = <?= $user['role_id'] ?>;
let permissionsData = <?= json_encode($permissions) ?>;

function loadRolePermissions(roleId) {
    fetch(`<?= url('/admin/users/permissions') ?>?role_id=${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                permissionsData = data.permissions;
                updatePermissionsDisplay();
            }
        });
}

function updatePermissionsDisplay() {
    const container = document.getElementById('current-permissions');
    container.innerHTML = '';
    
    permissionsData.forEach(perm => {
        if (!perm.can_view) return;
        
        const div = document.createElement('div');
        div.className = 'p-2 bg-gray-50 rounded text-sm';
        
        let perms = [];
        if (perm.can_view) perms.push('Görüntüleme');
        if (perm.can_create) perms.push('Ekleme');
        if (perm.can_edit) perms.push('Düzenleme');
        if (perm.can_delete) perms.push('Silme');
        
        div.innerHTML = `
            <div class="font-medium">${perm.page_name}</div>
            <div class="text-xs text-gray-600 mt-1">${perms.join(', ')}</div>
        `;
        
        container.appendChild(div);
    });
}

function showPermissionsModal() {
    const tbody = document.getElementById('permissionsTable');
    tbody.innerHTML = '';
    
    permissionsData.forEach(page => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-3 text-sm">${page.page_name}</td>
            <td class="px-4 py-3 text-center">
                <input type="checkbox" data-page="${page.id}" data-perm="view" ${page.can_view ? 'checked' : ''} class="rounded">
            </td>
            <td class="px-4 py-3 text-center">
                <input type="checkbox" data-page="${page.id}" data-perm="create" ${page.can_create ? 'checked' : ''} class="rounded">
            </td>
            <td class="px-4 py-3 text-center">
                <input type="checkbox" data-page="${page.id}" data-perm="edit" ${page.can_edit ? 'checked' : ''} class="rounded">
            </td>
            <td class="px-4 py-3 text-center">
                <input type="checkbox" data-page="${page.id}" data-perm="delete" ${page.can_delete ? 'checked' : ''} class="rounded">
            </td>
        `;
        tbody.appendChild(row);
    });
    
    document.getElementById('permissionsModal').classList.remove('hidden');
}

function closePermissionsModal() {
    document.getElementById('permissionsModal').classList.add('hidden');
}

function savePermissions() {
    const permissions = [];
    const checkboxes = document.querySelectorAll('#permissionsTable input[type="checkbox"]');
    
    const grouped = {};
    checkboxes.forEach(cb => {
        const pageId = cb.dataset.page;
        const perm = cb.dataset.perm;
        
        if (!grouped[pageId]) {
            grouped[pageId] = { page_id: pageId, can_view: 0, can_create: 0, can_edit: 0, can_delete: 0 };
        }
        
        if (perm === 'view') grouped[pageId].can_view = cb.checked ? 1 : 0;
        if (perm === 'create') grouped[pageId].can_create = cb.checked ? 1 : 0;
        if (perm === 'edit') grouped[pageId].can_edit = cb.checked ? 1 : 0;
        if (perm === 'delete') grouped[pageId].can_delete = cb.checked ? 1 : 0;
    });
    
    Object.values(grouped).forEach(p => permissions.push(p));
    
    // Update local data
    permissionsData = Object.values(grouped);
    updatePermissionsDisplay();
    closePermissionsModal();
    
    // Update form - add hidden inputs for permissions
    let form = document.querySelector('form');
    let existingPerms = form.querySelectorAll('input[name^="permissions"]');
    existingPerms.forEach(p => p.remove());
    
    permissions.forEach(perm => {
        Object.keys(perm).forEach(key => {
            if (key !== 'page_id') {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = `permissions[${perm.page_id}][${key}]`;
                input.value = perm[key];
                form.appendChild(input);
            }
        });
    });
    
    // Show success message
    const msg = document.createElement('div');
    msg.className = 'p-4 bg-green-100 text-green-700 rounded mb-4';
    msg.textContent = '✓ İzinler güncellendi, formu kaydet';
    document.body.insertBefore(msg, document.body.firstChild);
    setTimeout(() => msg.remove(), 3000);
}
</script>
