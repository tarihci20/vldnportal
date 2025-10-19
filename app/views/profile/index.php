<?php
/** @var array $user */
/** @var bool $canChangePassword */
/** @var bool $canChangeUsername */
?>
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14 max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Profilim</h1>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Kullanıcı Bilgileri</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-gray-500">Ad Soyad</dt>
                    <dd class="text-base font-medium text-gray-900"><?= e($user['full_name'] ?? '-') ?></dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Kullanıcı Adı</dt>
                    <dd class="text-base font-medium text-gray-900">
                        <?= e($user['username'] ?? '-') ?>
                        <?php if (!$canChangeUsername): ?>
                            <span class="ml-2 text-xs text-red-600">
                                <i class="fas fa-lock"></i> Değiştirilemez
                            </span>
                        <?php endif; ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">E-posta</dt>
                    <dd class="text-base font-medium text-gray-900"><?= e($user['email'] ?? '-') ?></dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Rol</dt>
                    <dd class="text-base font-medium text-gray-900 capitalize"><?= e($user['role'] ?? '-') ?></dd>
                </div>
            </dl>
            
            <?php if (!$canChangePassword && isTeacher()): ?>
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                        <div>
                            <p class="text-sm text-blue-800">
                                <strong>Öğretmen hesapları için:</strong> Kullanıcı adınızı ve şifrenizi değiştirmek için lütfen okul yönetimine başvurunuz.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($canChangePassword): ?>
            <div id="change-password" class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Şifre Değiştir</h2>
                <form method="POST" action="<?= url('/profile/change-password') ?>" class="space-y-4">
                    <?= csrfField() ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="current_password">Mevcut Şifre</label>
                        <input type="password" id="current_password" name="current_password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="new_password">Yeni Şifre</label>
                        <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="confirm_password">Yeni Şifre (Tekrar)</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">Şifreyi Güncelle</button>
                </form>
            </div>
        <?php else: ?>
            <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-1">Şifre Değiştirme İzniniz Yok</h3>
                        <p class="text-yellow-700">Şifrenizi değiştirmek için sistem yöneticisi ile iletişime geçin.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>