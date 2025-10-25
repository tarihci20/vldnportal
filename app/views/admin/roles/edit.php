<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="<?= url('/admin/roles') ?>" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold">Rol Düzenle: <?= e($role['display_name']) ?></h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Rol Bilgileri -->
            <div class="lg:col-span-2">
                <form method="POST" action="<?= url("/admin/roles/{$role['id']}") ?>" class="space-y-6">
                    <?= csrfField() ?>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold mb-4">Rol Bilgileri</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="role_name" class="block text-sm font-medium text-gray-700 mb-1">Rol Adı</label>
                                <input type="text" id="role_name" value="<?= e($role['role_name']) ?>" disabled
                                       class="w-full px-4 py-2 border bg-gray-50 rounded text-gray-600">
                                <p class="text-xs text-gray-500 mt-1">Sistem adı (değiştirilemez)</p>
                            </div>

                            <div>
                                <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">Gösterim Adı *</label>
                                <input type="text" id="display_name" name="display_name" value="<?= e($role['display_name']) ?>" required
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                                <textarea id="description" name="description" rows="3"
                                          class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"><?= e($role['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- İzinler -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold mb-4">Rol İzinleri</h2>
                        
                        <!-- FAZA 2 NOTE: Bu form artık yalnızca erişilebilir sayfaları gösteriyor
                             vp_role_page_permissions tablosunda tanımlanan sayfalar.
                             Filtreleme Controller değil, Veritabanda yapılıyor. -->
                        <p class="text-sm text-gray-600 mb-4">
                            Aşağıda bu rol için tanımlı sayfalar listeleniyor. Her sayfa için izinleri belirleyiniz.
                        </p>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Sayfa</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Görüntüleme</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Ekleme</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Düzenleme</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Silme</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($pages as $page): ?>
                                        <?php 
                                        $perm = null;
                                        foreach ($permissions as $p) {
                                            if (isset($p['page_id']) && $p['page_id'] == $page['id']) {
                                                $perm = $p;
                                                break;
                                            }
                                        }
                                        $pageName = $page['page_name'] ?? '';
                                        if (!mb_check_encoding($pageName, 'UTF-8')) {
                                            $pageName = mb_convert_encoding($pageName, 'UTF-8', 'ISO-8859-9,UTF-8');
                                        }
                                        ?>
                                        <tr>
                                            <td class="px-4 py-3 text-sm"><?= e($pageName) ?></td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" name="permissions[<?= $page['id'] ?>][can_view]" value="1"
                                                       <?= ($perm && $perm['can_view']) ? 'checked' : '' ?> class="rounded">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" name="permissions[<?= $page['id'] ?>][can_create]" value="1"
                                                       <?= ($perm && $perm['can_create']) ? 'checked' : '' ?> class="rounded">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" name="permissions[<?= $page['id'] ?>][can_edit]" value="1"
                                                       <?= ($perm && $perm['can_edit']) ? 'checked' : '' ?> class="rounded">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" name="permissions[<?= $page['id'] ?>][can_delete]" value="1"
                                                       <?= ($perm && $perm['can_delete']) ? 'checked' : '' ?> class="rounded">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3">
                        <a href="<?= url('/admin/roles') ?>" class="px-6 py-2 border rounded hover:bg-gray-50">
                            İptal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Güncelle
                        </button>
                    </div>
                    
                    <!-- CRITICAL FIX: Hidden inputs untuk unchecked checkboxes -->
                    <script>
                    document.querySelector('form').addEventListener('submit', function(e) {
                        const form = this;
                        const pageIds = new Set();
                        
                        // Tüm checkbox'lar için page ID'leri topla
                        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                            const match = checkbox.name.match(/permissions\[(\d+)\]/);
                            if (match) {
                                pageIds.add(match[1]);
                            }
                        });
                        
                        // Her sayfa için, eğer hiçbir checkbox seçilmemişse 0 değerlendirmesi gönder
                        pageIds.forEach(pageId => {
                            const permTypes = ['can_view', 'can_create', 'can_edit', 'can_delete'];
                            permTypes.forEach(permType => {
                                const checkbox = form.querySelector(`input[name="permissions[${pageId}][${permType}]"]`);
                                if (checkbox && !checkbox.checked) {
                                    // Unchecked checkbox, hidden input ekle
                                    const hidden = document.createElement('input');
                                    hidden.type = 'hidden';
                                    hidden.name = `permissions[${pageId}][${permType}]`;
                                    hidden.value = '0';
                                    form.appendChild(hidden);
                                }
                            });
                        });
                    });
                    </script>
                </form>
            </div>

            <!-- Sil Kartı -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-semibold text-red-600 mb-4">Rolü Sil</h2>
                    <p class="text-sm text-gray-600 mb-4">Bu işlem geri alınamaz ve bu role sahip tüm kullanıcılar etkilenecek.</p>
                    
                    <?php if (!in_array($role['role_name'], ['admin', 'teacher', 'student', 'secretary', 'principal', 'vice_principal'])): ?>
                        <button onclick="deleteRole(<?= $role['id'] ?>)" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Rolü Sil
                        </button>
                    <?php else: ?>
                        <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-600 rounded cursor-not-allowed">
                            Sistem Rolü (Silinemez)
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteRole(roleId) {
    if (!confirm('Bu rolü silmek istediğinize emin misiniz?')) {
        return;
    }

    fetch(`<?= url('/admin/roles') ?>/${roleId}/delete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `csrf_token=${document.querySelector('input[name="csrf_token"]').value}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Rol silindi');
            window.location.href = '<?= url('/admin/roles') ?>';
        } else {
            alert('Hata: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert('Bir hata oluştu');
    });
}
</script>
