<?php
/**
 * Kullanıcı Yönetimi Sayfası
 * Vildan Portal
 */

$pageTitle = 'Kullanıcı Yönetimi';
$users = $data['users'] ?? [];
$roles = $data['roles'] ?? [];
?>

<!-- Page Header -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kullanıcı Yönetimi</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Sistem kullanıcılarını yönetin
        </p>
    </div>
    <button 
        onclick="openUserModal()" 
        class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2"
    >
        <i class="fas fa-plus mr-2"></i> Yeni Kullanıcı
    </button>
</div>        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Kullanıcı</th>
                            <th scope="col" class="px-6 py-3">E-posta</th>
                            <th scope="col" class="px-6 py-3">Rol</th>
                            <th scope="col" class="px-6 py-3">Durum</th>
                            <th scope="col" class="px-6 py-3">Son Giriş</th>
                            <th scope="col" class="px-6 py-3">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php if ($user['profile_photo']): ?>
                                            <img class="w-10 h-10 rounded-full mr-3" src="<?= asset('uploads/profiles/' . $user['profile_photo']) ?>" alt="<?= esc($user['name']) ?>">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold mr-3">
                                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white"><?= esc($user['name']) ?></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">ID: <?= $user['id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4"><?= esc($user['email']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900/20 dark:text-blue-400">
                                        <?= esc($user['role_name']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($user['is_active']): ?>
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900/20 dark:text-green-400">
                                            <i class="fas fa-check-circle mr-1"></i> Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900/20 dark:text-red-400">
                                            <i class="fas fa-times-circle mr-1"></i> Pasif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $user['last_login'] ? formatDateTime($user['last_login']) : '-' ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editUser(<?= $user['id'] ?>)" class="text-blue-600 hover:text-blue-900" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="toggleUserStatus(<?= $user['id'] ?>, <?= $user['is_active'] ? 'false' : 'true' ?>)" class="text-yellow-600 hover:text-yellow-900" title="<?= $user['is_active'] ? 'Pasif Yap' : 'Aktif Yap' ?>">
                                            <i class="fas fa-<?= $user['is_active'] ? 'ban' : 'check' ?>"></i>
                                        </button>
                                        <button onclick="deleteUser(<?= $user['id'] ?>)" class="text-red-600 hover:text-red-900" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

<!-- User Modal -->
<div id="userModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">Yeni Kullanıcı</h3>
                    <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="userForm" class="space-y-4">
                    <input type="hidden" id="user_id" name="user_id">
                    
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Ad Soyad <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                    </div>
                    
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            E-posta <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                    </div>
                    
                    <div>
                        <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Rol <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="role_id" 
                            name="role_id" 
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= esc($role['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Şifre <span class="text-red-500" id="passwordRequired">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                        <p class="mt-1 text-xs text-gray-500" id="passwordHint">En az 8 karakter</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            onclick="closeUserModal()" 
                            class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5"
                        >
                            İptal
                        </button>
                        <button 
                            type="submit" 
                            class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5"
                        >
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
function openUserModal(userId = null) {
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userForm').reset();
    
    if (userId) {
        document.getElementById('modalTitle').textContent = 'Kullanıcı Düzenle';
        document.getElementById('passwordRequired').classList.add('hidden');
        document.getElementById('passwordHint').textContent = 'Boş bırakılırsa değiştirilmez';
        document.getElementById('password').removeAttribute('required');
        
        // Load user data
        fetch(`<?= url('/api/users/') ?>${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('user_id').value = data.id;
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('role_id').value = data.role_id;
            });
    } else {
        document.getElementById('modalTitle').textContent = 'Yeni Kullanıcı';
        document.getElementById('passwordRequired').classList.remove('hidden');
        document.getElementById('passwordHint').textContent = 'En az 8 karakter';
        document.getElementById('password').setAttribute('required', '');
    }
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function editUser(userId) {
    openUserModal(userId);
}

function toggleUserStatus(userId, status) {
    fetch(`<?= url('/api/users/') ?>${userId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ is_active: status })
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

function deleteUser(userId) {
    if (confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`<?= url('/api/users/') ?>${userId}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: userId,
                csrf_token: csrfToken
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hata: ' + error.message);
        });
    }
}

// Form submit
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userId = document.getElementById('user_id').value;
    const url = userId ? `<?= url('/api/users/') ?>${userId}/update` : '<?= url('/api/users/create') ?>';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Hata: ' + data.message);
        }
    });
});
</script>