<?php
/** @var array $users */
/** @var int $studentCount */
?>
<!-- Admin Ayarlar -->
<h1 class="text-3xl font-bold mb-6">Admin Ayarlar</h1>

<!-- Yönetim Paneli Hızlı Erişim -->
<div class="mb-8">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Yönetim Paneli</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                
                <!-- Kullanıcı Yönetimi -->
                <a href="<?= url('/admin/users') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-indigo-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Kullanıcı Yönetimi</h3>
                            <p class="text-sm text-gray-500">Kullanıcı ekle, düzenle, sil</p>
                        </div>
                    </div>
                </a>

                <!-- Rol İzinleri -->
                <a href="<?= url('/admin/roles') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-purple-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Rol İzinleri</h3>
                            <p class="text-sm text-gray-500">Rol bazlı yetki ayarları</p>
                        </div>
                    </div>
                </a>

                <!-- Öğrenci Yönetimi -->
                <a href="<?= url('/students') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-blue-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Öğrenci Yönetimi</h3>
                            <p class="text-sm text-gray-500">Öğrenci kayıtları ve işlemler</p>
                        </div>
                    </div>
                </a>

                <!-- Etüt Takibi -->
                <a href="<?= url('/etut') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-green-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Etüt Takibi</h3>
                            <p class="text-sm text-gray-500">Etüt başvuruları ve planları</p>
                        </div>
                    </div>
                </a>

                <!-- Aktivite Alanları -->
                <a href="<?= url('/activity-areas') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-yellow-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Aktivite Alanları</h3>
                            <p class="text-sm text-gray-500">Alan tanımları ve yönetimi</p>
                        </div>
                    </div>
                </a>

                <!-- Aktiviteler -->
                <a href="<?= url('/activities') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-pink-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-pink-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Aktiviteler</h3>
                            <p class="text-sm text-gray-500">Aktivite oluştur ve düzenle</p>
                        </div>
                    </div>
                </a>

                <!-- Bildirim Gönder -->
                <a href="<?= url('/admin/push-notifications') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-orange-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Push Bildirim</h3>
                            <p class="text-sm text-gray-500">Kullanıcılara bildirim gönder</p>
                        </div>
                    </div>
                </a>

                <!-- Raporlar -->
                <a href="<?= url('/admin/reports') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-teal-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-teal-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Raporlar</h3>
                            <p class="text-sm text-gray-500">İstatistik ve analiz raporları</p>
                        </div>
                    </div>
                </a>

                <!-- Saat Dilimi Ayarları -->
                <a href="<?= url('/admin/time-slots') ?>" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition-shadow border-l-4 border-red-500">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Saat Dilimi Ayarları</h3>
                            <p class="text-sm text-gray-500">Etkinlik randevu saatleri</p>
                        </div>
                    </div>
                </a>

                <!-- Sistem Ayarları (Mevcut Sayfa) -->
                <a href="<?= url('/admin/settings') ?>" class="bg-gradient-to-r from-indigo-500 to-purple-600 p-5 rounded-lg shadow-lg text-white">
                    <div class="flex items-center gap-3">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Sistem Ayarları</h3>
                            <p class="text-sm text-white text-opacity-90">Mevcut sayfa</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Ayarlar ve Formlar -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section id="change-password" class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Şifre Değiştir</h2>
                <form method="POST" action="<?= url('/admin/settings/change-password') ?>" class="space-y-3">
                    <?= csrfField() ?>
                    <input type="password" name="current_password" placeholder="Mevcut Şifre" class="w-full px-4 py-2 border rounded" required>
                    <input type="password" name="new_password" placeholder="Yeni Şifre" class="w-full px-4 py-2 border rounded" required>
                    <input type="password" name="confirm_password" placeholder="Yeni Şifre (Tekrar)" class="w-full px-4 py-2 border rounded" required>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Şifreyi Değiştir</button>
                </form>
            </section>

            <section class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Kullanıcı Ekle</h2>
                <form method="POST" action="<?= url('/admin/settings/add-user') ?>" class="space-y-3">
                    <?= csrfField() ?>
                    <input type="text" name="username" placeholder="Kullanıcı Adı" class="w-full px-4 py-2 border rounded" required>
                    <input type="email" name="email" placeholder="E-posta" class="w-full px-4 py-2 border rounded" required>
                    <input type="password" name="password" placeholder="Şifre" class="w-full px-4 py-2 border rounded" required>
                    <select name="role" class="w-full px-4 py-2 border rounded">
                        <option value="teacher">Öğretmen</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Ekle</button>
                </form>
            </section>

            <section class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                <h2 class="text-xl font-bold mb-4">Kullanıcılar</h2>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($users as $userItem): ?>
                        <div class="flex items-center justify-between py-2">
                            <span class="font-medium text-gray-700"><?= e($userItem['username']) ?></span>
                            <span class="text-xs px-2 py-1 rounded <?= ($userItem['role_name'] ?? '') === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' ?>">
                                <?= e($userItem['role_display_name'] ?? $userItem['role_name'] ?? 'N/A') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="bg-red-50 p-6 rounded-lg shadow border-2 border-red-200 lg:col-span-2">
                <h2 class="text-xl font-bold mb-4 text-red-900">Tehlikeli Bölge</h2>
                <p class="mb-4">Sistemde <b><?= (int) $studentCount ?></b> öğrenci var.</p>
                <button type="button" onclick="deleteAllStudents()" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                    Tüm Öğrencileri Sil
                </button>
            </section>
        </div>
    </div>
</div>

<script>
function deleteAllStudents() {
    const confirmation = prompt('Tüm öğrencileri silmek için "eminim" yazın:');
    if (confirmation !== 'eminim') {
        alert('İptal edildi');
        return;
    }

    fetch('<?= url('/admin/settings/delete-all-students') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            csrf_token: document.querySelector('meta[name="csrf-token"]').content,
            confirmation
        })
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(() => alert('Beklenmeyen bir hata oluştu.'));
}
</script>
