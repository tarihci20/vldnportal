<?php
/**
 * Etüt Başvuruları Listesi
 * Vildan Portal
 */

$pageTitle = 'Etüt Başvuruları';
$applications = $data['applications'] ?? [];
$pagination = $data['pagination'] ?? [];
?>

<!-- Main Content -->

        
        <!-- Page Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Etüt Başvuruları</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Öğrenci etüt başvurularını yönetin
                </p>
            </div>
            <a href="<?= url('/etut/create') ?>" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fas fa-plus mr-2"></i> Yeni Başvuru
            </a>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <!-- Applications Table (Görseldeki gibi) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <div class="flex gap-2 p-4">
                            <button class="px-3 py-2 bg-gray-200 rounded text-xs font-semibold" onclick="selectAllRows()">Hepsini Seç</button>
                            <button class="px-3 py-2 bg-gray-200 rounded text-xs font-semibold" onclick="deselectAllRows()">Hepsini Kaldır</button>
                            <button class="px-3 py-2 bg-red-600 text-white rounded text-xs font-semibold" onclick="bulkDelete()">Toplu Sil</button>
                        </div>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-2 py-3"><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                                    <th class="px-2 py-3">#</th>
                                    <th class="px-2 py-3">Tarih</th>
                                    <th class="px-2 py-3">Ad Soyad</th>
                                    <th class="px-2 py-3">Öğrenci No</th>
                                    <th class="px-2 py-3">Sınıf</th>
                                    <th class="px-2 py-3">Alınan Ders</th>
                                    <th class="px-2 py-3">Konu Kazanım</th>
                                    <th class="px-2 py-3">Öğrenci Mesajı</th>
                                    <th class="px-2 py-3">Etüt Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($applications)): ?>
                                    <tr>
                                        <td colspan="10" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-inbox text-4xl mb-3"></i>
                                            <p>Henüz başvuru bulunmuyor.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($applications as $app): ?>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-2 py-3"><input type="checkbox" class="rowCheckbox" value="<?= $app['id'] ?>"></td>
                                            <td class="px-2 py-3"><?= $app['id'] ?></td>
                                            <td class="px-2 py-3"><?= date('d.m.Y H:i:s', strtotime($app['created_at'])) ?></td>
                                            <td class="px-2 py-3"><?= esc($app['student_name']) ?></td>
                                            <td class="px-2 py-3"><?= esc($app['student_no']) ?></td>
                                            <td class="px-2 py-3"><?= esc($app['student_class']) ?></td>
                                            <td class="px-2 py-3"><?= esc($app['subject']) ?></td>
                                            <td class="px-2 py-3"><?= esc($app['topic']) ?></td>
                                            <td class="px-2 py-3"><?= esc($app['student_message']) ?></td>
                                            <td class="px-2 py-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Verilmedi</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <?php if (!empty($pagination)): ?>
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700 dark:text-gray-400">
                                    <span class="font-medium"><?= $pagination['from'] ?? 0 ?></span>
                                    -
                                    <span class="font-medium"><?= $pagination['to'] ?? 0 ?></span>
                                    /
                                    <span class="font-medium"><?= $pagination['total'] ?? 0 ?></span>
                                    kayıt
                                </div>
                                <div class="flex space-x-2">
                                    <?php if ($pagination['current_page'] > 1): ?>
                                        <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Önceki</a>
                                    <?php endif; ?>
                                    <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                        <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Sonraki</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                </div>
                
            </form>
        </div>
        
        <!-- Applications Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Öğrenci</th>
                            <th scope="col" class="px-6 py-3">Sınıf</th>
                            <th scope="col" class="px-6 py-3">Ders</th>
                            <th scope="col" class="px-6 py-3">Tarih</th>
                            <th scope="col" class="px-6 py-3">Saat</th>
                            <th scope="col" class="px-6 py-3">Durum</th>
                            <th scope="col" class="px-6 py-3">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p>Henüz başvuru bulunmuyor.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">
                                        <a href="<?= url('/students/' . $app['student_id']) ?>" class="text-primary-600 hover:underline font-medium">
                                            <?= esc($app['student_name']) ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4"><?= esc($app['student_class']) ?></td>
                                    <td class="px-6 py-4"><?= esc($app['subject']) ?></td>
                                    <td class="px-6 py-4"><?= formatDate($app['date']) ?></td>
                                    <td class="px-6 py-4"><?= esc($app['start_time']) ?> - <?= esc($app['end_time']) ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClass = match($app['status']) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'
                                        };
                                        
                                        $statusText = match($app['status']) {
                                            'pending' => 'Bekliyor',
                                            'approved' => 'Onaylandı',
                                            'rejected' => 'Reddedildi',
                                            default => 'Bilinmiyor'
                                        };
                                        ?>
                                        <span class="<?= $statusClass ?> text-xs font-medium px-2.5 py-0.5 rounded">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <?php if ($app['status'] === 'pending'): ?>
                                                <button onclick="updateStatus(<?= $app['id'] ?>, 'approved')" class="text-green-600 hover:text-green-900" title="Onayla">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button onclick="updateStatus(<?= $app['id'] ?>, 'rejected')" class="text-red-600 hover:text-red-900" title="Reddet">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?= url('/etut/' . $app['id']) ?>" class="text-blue-600 hover:text-blue-900" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button onclick="deleteApplication(<?= $app['id'] ?>)" class="text-red-600 hover:text-red-900" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination)): ?>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400">
                            <span class="font-medium"><?= $pagination['from'] ?? 0 ?></span>
                            -
                            <span class="font-medium"><?= $pagination['to'] ?? 0 ?></span>
                            /
                            <span class="font-medium"><?= $pagination['total'] ?? 0 ?></span>
                            kayıt
                        </div>
                        
                        <div class="flex space-x-2">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                                    Önceki
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                                    Sonraki
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
</div>

<!-- Scripts -->
<script>
function updateStatus(id, status) {
    const confirmText = status === 'approved' ? 'onaylamak' : 'reddetmek';
    
    if (confirm(`Bu başvuruyu ${confirmText} istediğinizden emin misiniz?`)) {
        fetch(`<?= url('/api/etut/') ?>${id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        });
    }
}

function deleteApplication(id) {
    if (confirm('Bu başvuruyu silmek istediğinizden emin misiniz?')) {
        fetch(`<?= url('/etut/') ?>${id}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        });
    }
}
</script>