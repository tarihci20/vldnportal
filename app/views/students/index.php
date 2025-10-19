<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Öğrenci Bilgileri</h1>
        <p class="mt-2 text-sm text-gray-600"><?= number_format($pagination['total']) ?> öğrenci kayıtlı</p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-3">
        <a href="<?= url('/students/download/template') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
            </svg>
            Şablon İndir
        </a>
        <button onclick="document.getElementById('excelUpload').click()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Excel Yükle
        </button>
        <a href="<?= url('/students/export/excel') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel'e Aktar
        </a>
        <?php if (hasPermission('students', 'can_create')): ?>
        <a href="<?= url('/students/create') ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Yeni Öğrenci
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Hidden Excel Upload Form -->
<form id="excelUploadForm" action="<?= url('/students/import/excel') ?>" method="POST" enctype="multipart/form-data" class="hidden">
    <?= csrfField() ?>
    <input type="hidden" name="import_mode" id="importMode" value="skip">
    <input type="file" id="excelUpload" name="excel_file" accept=".xlsx,.xls,.csv" onchange="showImportOptions()">
</form>

<!-- Import Options Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Excel İçe Aktarma Seçenekleri</h3>
        <p class="text-sm text-gray-600 mb-4">Aynı TC kimlik numarasına sahip öğrenciler bulunursa ne yapılsın?</p>
        
        <div class="space-y-3 mb-6">
            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="import_option" value="skip" checked class="mr-3" onchange="document.getElementById('importMode').value='skip'">
                <div>
                    <div class="font-medium text-gray-900">Atla</div>
                    <div class="text-sm text-gray-500">Mevcut öğrencileri atla, sadece yenileri ekle</div>
                </div>
            </label>
            
            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="import_option" value="update" class="mr-3" onchange="document.getElementById('importMode').value='update'">
                <div>
                    <div class="font-medium text-gray-900">Güncelle</div>
                    <div class="text-sm text-gray-500">Mevcut öğrencilerin bilgilerini güncelle</div>
                </div>
            </label>
        </div>
        
        <div class="flex gap-3">
            <button type="button" onclick="cancelImport()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                İptal
            </button>
            <button type="button" onclick="confirmImport()" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Devam Et
            </button>
        </div>
    </div>
</div>

<script>
function showImportOptions() {
    document.getElementById('importModal').classList.remove('hidden');
}

function cancelImport() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('excelUpload').value = '';
}

function confirmImport() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('excelUploadForm').submit();
}
</script>

<!-- Filters -->
<div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                placeholder="Öğrenci ara (İsim, TC, Öğretmen...)" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent"
                value="<?= e($search ?? '') ?>"
            >
        </div>
        <div class="sm:w-48">
            <select name="class" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                <option value="">Tüm Sınıflar</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= e($class) ?>" <?= ($classFilter ?? '') === $class ? 'selected' : '' ?>>
                        <?= e($class) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
            Filtrele
        </button>
        <?php if (!empty($search) || !empty($classFilter)): ?>
            <a href="<?= url('/students') ?>" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center">
                Temizle
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Students Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İsim Soyisim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sınıfı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baba Telefon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anne Telefon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğretmen</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= e($student['tc_no'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= e($student['first_name']) ?> <?= e($student['last_name']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= e($student['class'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if (!empty($student['father_phone'])): ?>
                                    <a href="tel:<?= e($student['father_phone']) ?>" class="text-indigo-600 hover:text-indigo-900">
                                        <?= e($student['father_phone']) ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if (!empty($student['mother_phone'])): ?>
                                    <a href="tel:<?= e($student['mother_phone']) ?>" class="text-indigo-600 hover:text-indigo-900">
                                        <?= e($student['mother_phone']) ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= e($student['teacher_name'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= url('/students/' . $student['id']) ?>" class="text-indigo-600 hover:text-indigo-900" title="Detay">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <?php if (hasPermission('students', 'can_edit')): ?>
                                    <a href="<?= url('/students/' . $student['id'] . '/edit') ?>" class="text-yellow-600 hover:text-yellow-900" title="Düzenle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (hasPermission('students', 'can_delete')): ?>
                                    <button onclick="deleteStudent(<?= $student['id'] ?>)" class="text-red-600 hover:text-red-900" title="Sil">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2">Öğrenci bulunamadı</p>
                            <a href="<?= url('/students/create') ?>" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Yeni Öğrenci Ekle
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['last_page'] > 1): ?>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    <?= $pagination['from'] ?> - <?= $pagination['to'] ?> arası, toplam <?= $pagination['total'] ?> kayıt
                </div>
                <div class="flex gap-2">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?page=<?= $pagination['current_page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($classFilter) ? '&class=' . urlencode($classFilter) : '' ?>" 
                           class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                            Önceki
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++): ?>
                        <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($classFilter) ? '&class=' . urlencode($classFilter) : '' ?>" 
                           class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50 <?= $i == $pagination['current_page'] ? 'bg-indigo-600 text-white border-indigo-600' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                        <a href="?page=<?= $pagination['current_page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($classFilter) ? '&class=' . urlencode($classFilter) : '' ?>" 
                           class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                            Sonraki
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function deleteStudent(id) {
    if (!confirm('Bu öğrenciyi silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    fetch('<?= url('/students') ?>/' + id + '/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            csrf_token: document.querySelector('meta[name="csrf-token"]').content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Silme işlemi başarısız');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
}
</script>