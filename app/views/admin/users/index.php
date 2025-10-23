<?php
/** @var array $users */
/** @var array $pagination */
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">


        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">
                <i class="fas fa-users mr-2"></i>Kullanıcı Yönetimi
            </h1>
            <a href="<?= url('/admin/users/create') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Yeni Kullanıcı
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if(hasFlashMessage()): 
            $flash = getFlashMessage();
            $type = $flash['type'] ?? 'info';
            $message = $flash['message'] ?? '';
        ?>
            <div class="mb-6 p-4 rounded-lg <?= $type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table id="usersTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-posta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Şifre Değiştirme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Kullanıcı bulunamadı.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= e($user['full_name']) ?></div>
                                            <div class="text-sm text-gray-500">@<?= e($user['username']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= e($user['email']) ?></div>
                                    <?php if (!empty($user['phone'])): ?>
                                        <div class="text-sm text-gray-500"><?= e($user['phone']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'teacher' => 'bg-blue-100 text-blue-800',
                                            'secretary' => 'bg-green-100 text-green-800',
                                            'principal' => 'bg-purple-100 text-purple-800',
                                            'vice_principal' => 'bg-yellow-100 text-yellow-800'
                                        ];
                                        echo $roleColors[$user['role_name']] ?? 'bg-gray-100 text-gray-800';
                                        ?>">
                                        <?= e($user['role_display_name']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($user['is_active']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Pasif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($user['can_change_password']): ?>
                                        <span class="text-green-600">✓ İzinli</span>
                                    <?php else: ?>
                                        <span class="text-red-600">✗ Yasak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d.m.Y', strtotime($user['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?= url("/admin/users/{$user['id']}/edit") ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Düzenle
                                    </a>
                                    <?php if ($user['id'] != getCurrentUserId()): ?>
                                        <button onclick="deleteUser(<?= $user['id'] ?>, '<?= e($user['username']) ?>')" class="text-red-600 hover:text-red-900">
                                            Sil
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Silme Onay Modalı -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Kullanıcı Sil</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    <strong id="deleteUsername"></strong> kullanıcısını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" disabled>
                    Evet, Sil
                </button>
                <button onclick="closeDeleteModal()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    İptal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery ve DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
// DataTables başlat
$(document).ready(function() {
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json'
        },
        columnDefs: [
            { targets: 0, title: 'Kullanıcı', width: '20%' },
            { targets: 1, title: 'E-posta', width: '20%' },
            { targets: 2, title: 'Rol', width: '12%' },
            { targets: 3, title: 'Durum', width: '10%' },
            { targets: 4, title: 'Şifre Değiştirme', width: '15%' },
            { targets: 5, title: 'Kayıt Tarihi', width: '12%' },
            { targets: 6, title: 'İşlemler', width: '11%', orderable: false }
        ],
        order: [[5, 'desc']], // Kayıt tarihine göre sırala
        pageLength: 25,
        autoWidth: false
    });
});

// Silme işlemi
let userToDelete = null;

function deleteUser(userId, username) {
    userToDelete = userId;
    document.getElementById('deleteUsername').textContent = username;
    document.getElementById('deleteModal').classList.remove('hidden');
    // Button'u aktif yap
    document.getElementById('confirmDelete').disabled = false;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    userToDelete = null;
}

document.getElementById('confirmDelete').addEventListener('click', async function() {
    if (!userToDelete) {
        console.error('No user selected for deletion');
        return;
    }
    
    console.log('Delete button clicked for user:', userToDelete);
    
    // Disable button to prevent double clicks
    this.disabled = true;
    const originalText = this.textContent;
    this.textContent = 'Siliniyor...';
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Debug log
        console.log('Delete User ID:', userToDelete);
        console.log('CSRF Token exists:', !!csrfToken);
        console.log('CSRF Token preview:', csrfToken ? csrfToken.substring(0, 20) + '...' : 'EMPTY');
        
        if (!csrfToken) {
            throw new Error('CSRF token bulunamadı - sayfayı yenileyin');
        }
        
        const deleteUrl = `${window.location.origin}/portalv2/admin/users/${userToDelete}/delete`;
        console.log('Fetch URL:', deleteUrl);
        
        const requestBody = {
            id: userToDelete,
            csrf_token: csrfToken
        };
        console.log('Request body:', requestBody);
        
        const response = await fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                // Don't set Accept-Encoding - let browser handle it
            },
            body: JSON.stringify(requestBody)
        });
        
        console.log('Response Status:', response.status);
        console.log('Response Headers:', {
            'content-type': response.headers.get('content-type'),
            'content-encoding': response.headers.get('content-encoding')
        });
        
        // Response'u text olarak oku (compression'ı browser automatic handle eder)
        const responseText = await response.text();
        console.log('Response Text (first 200 chars):', responseText.substring(0, 200));
        console.log('Response Text Length:', responseText.length);
        
        // Text'i JSON'a parse et
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('JSON Parse Error:', parseError);
            console.error('Response text:', responseText);
            throw new Error('Sunucu geçersiz JSON döndürdü: ' + responseText.substring(0, 100));
        }
        
        console.log('Parsed JSON:', data);
        
        if (data.success) {
            console.log('Delete successful, reloading page...');
            window.location.reload();
        } else {
            console.error('Delete failed:', data.message);
            alert(data.message || 'Kullanıcı silinemedi');
            closeDeleteModal();
        }
    } catch (error) {
        console.error('Error during delete:', error);
        console.error('Error stack:', error.stack);
        alert('Bir hata oluştu: ' + error.message);
        closeDeleteModal();
    } finally {
        // Re-enable button
        this.disabled = false;
        this.textContent = originalText;
    }
});

// Modal dışına tıklandığında kapat
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
