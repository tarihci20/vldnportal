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
        </div>
    </div>
</div>


