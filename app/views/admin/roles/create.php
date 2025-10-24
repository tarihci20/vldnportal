<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="<?= url('/admin/roles') ?>" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold">Yeni Rol Oluştur</h1>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST" action="<?= url('/admin/roles') ?>" class="space-y-6">
                <?= csrfField() ?>

                <div>
                    <label for="role_name" class="block text-sm font-medium text-gray-700 mb-1">Rol Adı *</label>
                    <input type="text" id="role_name" name="role_name" placeholder="editor, moderator, vb..." required
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Sistem tarafından kullanılacak adı (benzersiz, küçük harf)</p>
                </div>

                <div>
                    <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">Gösterim Adı *</label>
                    <input type="text" id="display_name" name="display_name" placeholder="Editör, Moderatör, vb..." required
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Kullanıcı arayüzünde gösterilecek ad</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="<?= url('/admin/roles') ?>" class="px-6 py-2 border rounded hover:bg-gray-50">
                        İptal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Oluştur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
