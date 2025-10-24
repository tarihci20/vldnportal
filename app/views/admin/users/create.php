<?php
/** @var array $roles */
/** @var array $pages */
?>
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="<?= url('/admin/users') ?>" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold">Yeni Kullanıcı Ekle</h1>
        </div>

        <!-- Form -->
        <form method="POST" action="<?= url('/admin/users') ?>" class="space-y-6">
            <?= csrfField() ?>

            <!-- Kullanıcı Bilgileri -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Kullanıcı Bilgileri</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad *</label>
                        <input type="text" id="full_name" name="full_name" required 
                               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı Adı *</label>
                        <input type="text" id="username" name="username" required 
                               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-posta *</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                        <input type="tel" id="phone" name="phone" 
                               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Şifre *</label>
                        <input type="password" id="password" name="password" required minlength="6"
                               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">En az 6 karakter</p>
                    </div>
                    
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
                        <select id="role_id" name="role_id" required onchange="loadRolePermissions(this.value); toggleEtutTypeField()"
                                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Rol Seçin</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= e($role['display_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div id="etut_type_field" style="display: none;">
                        <label for="etut_type" class="block text-sm font-medium text-gray-700 mb-1">Etüt Türü (Müdür Yardımcısı)</label>
                        <select id="etut_type" name="etut_type"
                                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Seç...</option>
                            <option value="ortaokul">Ortaokul</option>
                            <option value="lise">Lise</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Müdür yardımcısının erişim sağlayacağı etüt alanını seçin</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="can_change_password" value="1" checked class="rounded">
                        <span class="ml-2 text-sm text-gray-700">Kullanıcı şifresini değiştirebilir</span>
                    </label>
                </div>
            </div>

            <!-- Sayfa İzinleri -->
            <div class="bg-white p-6 rounded-lg shadow" id="permissions-section" style="display: none;">
                <h2 class="text-xl font-semibold mb-4">Sayfa İzinleri</h2>
                <p class="text-sm text-gray-600 mb-4">Bu kullanıcının seçilen rolüne göre erişebileceği sayfalar:</p>
                
                <div id="permissions-list" class="space-y-2">
                    <!-- Permissions will be loaded here via AJAX -->
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
                <a href="<?= url('/admin/users') ?>" class="px-6 py-2 border rounded hover:bg-gray-50">
                    İptal
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Kullanıcı Ekle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadRolePermissions(roleId) {
    if (!roleId) {
        document.getElementById('permissions-section').style.display = 'none';
        return;
    }
    
    fetch(`<?= url('/admin/users/permissions') ?>?role_id=${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPermissions(data.permissions);
                document.getElementById('permissions-section').style.display = 'block';
            }
        })
        .catch(error => console.error('Error:', error));
}

function displayPermissions(permissions) {
    const container = document.getElementById('permissions-list');
    container.innerHTML = '';
    
    permissions.forEach(page => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded';
        
        const canView = page.can_view == 1;
        const canCreate = page.can_create == 1;
        const canEdit = page.can_edit == 1;
        const canDelete = page.can_delete == 1;
        
        let perms = [];
        if (canView) perms.push('Görüntüleme');
        if (canCreate) perms.push('Ekleme');
        if (canEdit) perms.push('Düzenleme');
        if (canDelete) perms.push('Silme');
        
        div.innerHTML = `
            <span class="font-medium">${page.page_name}</span>
            <span class="text-sm text-gray-600">${perms.length > 0 ? perms.join(', ') : 'İzin yok'}</span>
        `;
        
        container.appendChild(div);
    });
}

// Müdür yardımcısı seçilirse etut_type field'ını göster
function toggleEtutTypeField() {
    const roleSelect = document.getElementById('role_id');
    const selectedOption = roleSelect.options[roleSelect.selectedIndex];
    const selectedText = selectedOption.text.toLowerCase();
    const etutTypeField = document.getElementById('etut_type_field');
    
    // Eğer seçili rol "müdür yardımcısı" ise göster
    if (selectedText.includes('müdür yardımcı') || selectedText.includes('vice_principal')) {
        etutTypeField.style.display = 'block';
    } else {
        etutTypeField.style.display = 'none';
    }
}

// Sayfa yüklenmesinde kontrol et
document.addEventListener('DOMContentLoaded', toggleEtutTypeField);
</script>
