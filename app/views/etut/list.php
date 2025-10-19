<div class="p-6">
    <!-- Başlık ve Butonlar -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <?= $formType === 'ortaokul' ? '🏫 Ortaokul' : '🎓 Lise' ?> Etüt Başvuruları
            </h1>
            <p class="text-gray-600 mt-2">Toplam <?= count($applications) ?> başvuru</p>
        </div>
        <div class="flex gap-3 items-center">
            <?php if (isAdmin()): ?>
            <button id="formToggleBtn" onclick="toggleFormOpen('<?= $formType ?>', this)" data-active="<?= ($formActive ?? false) ? '1' : '0' ?>" class="px-4 py-2 rounded-lg font-semibold transition duration-200 <?= ($formActive ?? false) ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-red-600 hover:bg-red-700 text-white' ?>">
                <i class="fas <?= ($formActive ?? false) ? 'fa-toggle-on' : 'fa-toggle-off' ?> mr-2"></i>
                <span><?= ($formActive ?? false) ? 'Form Açık' : 'Form Kapalı' ?></span>
            </button>
            <a 
                href="<?= url('/admin/etut-settings/' . $formType) ?>" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200"
            >
                <i class="fas fa-cog mr-2"></i>
                Form Ayarları
            </a>
            <?php endif; ?>
            <a 
                href="<?= url('/etut') ?>" 
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-200"
            >
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>
    
    <?php if (isAdmin()): ?>
    <div class="mb-4 flex items-center gap-3">
        <button onclick="exportSelectedEtut()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Seçilenleri Excel'e Aktar</button>
        <form id="exportByDateForm" method="POST" action="<?= url('/admin/etut/export-by-date') ?>">
            <input type="date" name="date" id="exportDateInput" class="border rounded px-2 py-1">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Tarihe Göre Excel</button>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if (empty($applications)): ?>
        <!-- Boş Durum -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Henüz Başvuru Yok</h3>
            <p class="text-gray-500 mb-6">
                <?= $formType === 'ortaokul' ? 'Ortaokul' : 'Lise' ?> için henüz etüt başvurusu bulunmuyor.
            </p>
            <a 
                href="<?= url('/etut/' . $formType . '-basvuru') ?>" 
                target="_blank"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200"
            >
                <i class="fas fa-external-link-alt mr-2"></i>
                Public Formu Aç
            </a>
        </div>
    <?php else: ?>
        <!-- Tablo -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 flex items-center justify-between border-b">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" class="mr-2">
                        Tümünü Seç
                    </label>
                    <button id="bulkDeleteBtn" onclick="bulkDelete()" class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm hidden">Seçilenleri Sil</button>
                </div>
                <div class="text-sm text-gray-600">Toplam: <?= count($applications) ?></div>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">&nbsp;</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">TARİH</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">AD SOYAD</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ÖĞRENCİ NO</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SINIFI</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SEÇTİĞİ DERS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">KONU/KAZANIM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ÖĞRENCİ MESAJI</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ETÜT DURUMU</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                        <?php foreach ($applications as $app): ?>
                        <?php
                        // Normalize fields: prefer etut_applications columns, fall back to any student-derived keys
                        $displayDate = isset($app['created_at']) ? date('d.m.Y H:i:s', strtotime($app['created_at'])) : '-';
                        $displayName = $app['full_name'] ?? ($app['first_name'] . ' ' . $app['last_name']) ?? '-';
                        $displayTc = $app['tc_no'] ?? $app['student_no'] ?? '-';
                        $displayGrade = $app['grade'] ?? ($app['class'] ?? '-');
                        $displaySubject = $app['subject'] ?? '-';
                        $displayNotes = $app['notes'] ?? $app['topic'] ?? '-';
                        $displayAddress = $app['address'] ?? $app['student_message'] ?? '-';
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <input type="checkbox" class="row-checkbox" data-id="<?= $app['id'] ?>" onchange="document.getElementById('bulkDeleteBtn').classList.toggle('hidden', !anyRowChecked())">
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= $displayDate ?></td>
                            <td class="px-4 py-3"><div class="font-medium text-gray-900"><?= htmlspecialchars($displayName) ?></div></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($displayTc) ?></td>
                            <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><?= htmlspecialchars($displayGrade) ?></span></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($displaySubject) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($displayNotes) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($displayAddress) ?></td>
                            <td class="px-4 py-3">
                                <?php
                                $isGiven = ($app['status'] ?? 'pending') === 'completed';
                                ?>
                                <div class="flex items-center gap-2">
                                    <button onclick="toggleEtutStatus(<?= $app['id'] ?>, this)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold focus:outline-none transition-shadow <?= $isGiven ? 'bg-green-50 shadow-sm text-green-600' : 'bg-red-50 shadow-sm text-red-600' ?>" aria-pressed="<?= $isGiven ? 'true' : 'false' ?>">
                                    <span class="relative inline-flex items-center">
                                        <span class="w-8 h-5 rounded-full <?= $isGiven ? 'bg-green-300' : 'bg-green-100' ?> inline-block mr-3 shadow-inner"></span>
                                        <span class="absolute left-0 top-0 transform translate-x-1 translate-y-1 w-4 h-4 rounded-full bg-white shadow"></span>
                                    </span>
                                    <span class="ml-1" style="color: <?= $isGiven ? '#16A34A' : '#EF4444' ?>"><?= $isGiven ? 'Verildi' : 'Verilmedi' ?></span>
                                    </button>
                                    <button onclick="deleteSingle(<?= $app['id'] ?>)" class="px-2 py-1 bg-red-50 text-red-600 rounded-md text-sm">Sil</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Detay Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900">Başvuru Detayı</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            <script>
            function toggleEtutStatus(id, btn) {
                const current = btn.textContent.trim();
                const currentLabel = btn.querySelector('span.ml-1') ? btn.querySelector('span.ml-1').textContent.trim() : btn.textContent.trim();
                const newStatus = currentLabel === 'Verilmedi' ? 'completed' : 'pending';
                btn.disabled = true;
                const apiUrl = '<?= url('/api/etut/') ?>' + id + '/status';
                const meta = document.querySelector('meta[name="csrf-token"]');
                const csrf = meta ? meta.content : null;
                if (!csrf) console.warn('CSRF meta tag not found on page; API may reject the request.');

                // Debug log to console
                console.debug('ToggleEtutStatus ->', { id, apiUrl, newStatus, csrfPresent: !!csrf });

                fetch(apiUrl, {
                    method: 'POST',
                    credentials: 'same-origin', // send session cookie so server can authenticate
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    // If server responded with non-OK, read text and throw (will be caught below)
                    const contentType = response.headers.get('content-type') || '';
                    if (!response.ok) {
                        return response.text().then(text => {
                            // If auth related, mark it so UI can show a helpful message
                            const auth = response.status === 401 || response.status === 403;
                            throw { status: response.status, text, auth };
                        });
                    }

                    // For OK responses, ensure content-type is JSON before parsing
                    if (contentType.indexOf('application/json') !== -1) {
                        return response.json();
                    }

                    // If not JSON (eg. HTML page), read text and surface as an error
                    return response.text().then(text => { throw { status: response.status, text }; });
                })
                .then(data => {
                    if (data && data.success) {
                        const given = newStatus === 'completed';
                        btn.setAttribute('aria-pressed', given ? 'true' : 'false');
                        btn.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold focus:outline-none transition-shadow ' + (given ? 'bg-green-50 shadow-sm text-green-600' : 'bg-red-50 shadow-sm text-red-600');
                        btn.innerHTML = `
                            <span class="relative inline-flex items-center">
                                <span class="w-8 h-5 rounded-full ${given ? 'bg-green-300' : 'bg-green-100'} inline-block mr-3 shadow-inner"></span>
                                <span class="absolute left-0 top-0 transform translate-x-1 translate-y-1 w-4 h-4 rounded-full bg-white shadow"></span>
                            </span>
                            <span class="ml-1" style="color: ${given ? '#16A34A' : '#EF4444'}">${given ? 'Verildi' : 'Verilmedi'}</span>
                        `;
                    } else {
                        alert('Durum güncellenemedi: ' + (data && data.message ? data.message : 'Hata'));
                    }
                    btn.disabled = false;
                })
                .catch(err => {
                    console.error('Etut status toggle error:', err);
                    if (err && err.text) {
                        // show first part of server response for debugging
                        const snippet = err.text.length > 800 ? err.text.slice(0,800) + '...' : err.text;
                        if (err.auth) {
                            alert('Yetkilendirme hatası; lütfen yönetici olarak giriş yapın.\n\nSunucu mesajı:\n' + snippet);
                        } else {
                            alert('Sunucu hatası (' + (err.status || '') + '):\n' + snippet);
                        }
                    } else if (err && err.status) {
                        alert('Sunucu hatası (' + err.status + '). Konsola bakın.');
                    } else {
                        alert('Sunucu hatası! Konsol ve server loglarına bakın.');
                    }
                    btn.disabled = false;
                });
            }
            </script>
        </div>
        <div id="modalContent" class="p-6">
            <!-- İçerik buraya gelecek -->
        </div>
    </div>
</div>

<script>
function viewApplication(id) {
    // Modal göster
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('modalContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i></div>';
    
    // Detayları yükle
    fetch(`<?= url('/api/etut/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const app = data.data;
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Öğrenci Adı:</label>
                                <p class="text-gray-900">${app.full_name}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">TC Kimlik No:</label>
                                <p class="text-gray-900">${app.tc_no}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Sınıf:</label>
                                <p class="text-gray-900">${app.grade}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Form Tipi:</label>
                                <p class="text-gray-900">${app.form_type === 'ortaokul' ? 'Ortaokul' : 'Lise'}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Veli Telefon:</label>
                                <p class="text-gray-900">${app.parent_phone}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Öğrenci Telefon:</label>
                                <p class="text-gray-900">${app.student_phone || '-'}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Seçilen Dersler:</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded">${app.subject || '-'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Konu/Kazanım:</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-wrap">${app.notes || '-'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Mesaj:</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-wrap">${app.address || '-'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Başvuru Tarihi:</label>
                            <p class="text-gray-900">${new Date(app.created_at).toLocaleString('tr-TR')}</p>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('modalContent').innerHTML = '<div class="text-center py-8 text-red-600">Detaylar yüklenemedi.</div>';
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            document.getElementById('modalContent').innerHTML = '<div class="text-center py-8 text-red-600">Bir hata oluştu.</div>';
        });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function approveApplication(id) {
    if (!confirm('Bu başvuruyu onaylamak istediğinizden emin misiniz?')) return;
    
    fetch(`<?= url('/api/etut/') ?>${id}/approve`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Başvuru onaylandı!');
                window.location.reload();
            } else {
                alert('Hata: ' + (data.message || 'Başvuru onaylanamadı'));
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('Bir hata oluştu');
        });
}

function rejectApplication(id) {
    if (!confirm('Bu başvuruyu reddetmek istediğinizden emin misiniz?')) return;
    
    fetch(`<?= url('/api/etut/') ?>${id}/reject`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Başvuru reddedildi!');
                window.location.reload();
            } else {
                alert('Hata: ' + (data.message || 'Başvuru reddedilemedi'));
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('Bir hata oluştu');
        });
}

// Modal dışına tıklayınca kapat
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<script>
// Bulk/select helpers
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    document.getElementById('bulkDeleteBtn').classList.toggle('hidden', !anyRowChecked());
}

function anyRowChecked() {
    return Array.from(document.querySelectorAll('.row-checkbox')).some(cb => cb.checked);
}

function collectSelectedIds() {
    return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.dataset.id);
}

function bulkDelete() {
    const ids = collectSelectedIds();
    if (ids.length === 0) return alert('Önce silinecek kayıtları seçin');
    if (!confirm(ids.length + ' başvuruyu silmek istediğinize emin misiniz?')) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch('<?= url('/api/etut/batch-delete') ?>', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ ids: ids, csrf_token: csrf })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Silindi');
            window.location.reload();
        } else {
            alert('Hata: ' + (data.message || 'Silme başarısız'));
        }
    })
    .catch(err => { console.error(err); alert('Sunucu hatası'); });
}

function exportSelectedEtut() {
    const ids = collectSelectedIds();
    if (ids.length === 0) return alert('Önce kayıt seçin');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= url('/admin/etut/export') ?>';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = 'csrf_token';
    csrfInput.value = csrf;
    form.appendChild(csrfInput);

    ids.forEach(id => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'ids[]';
        inp.value = id;
        form.appendChild(inp);
    });

    document.body.appendChild(form);
    form.submit();
    form.remove();
}

function deleteSingle(id) {
    if (!confirm('Bu başvuruyu silmek istediğinize emin misiniz?')) return;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch('<?= url('/api/etut/') ?>' + id + '/delete', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ csrf_token: csrf })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Silindi');
            window.location.reload();
        } else {
            alert('Hata: ' + (data.message || 'Silme başarısız'));
        }
    })
    .catch(err => { console.error(err); alert('Sunucu hatası'); });
}

// Toggle public form open/close via admin endpoint
function toggleFormOpen(formType, btn) {
    if (!confirm('Form durumunu değiştirmek istediğinize emin misiniz?')) return;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const formData = new FormData();
    formData.append('form_type', formType);
    formData.append('csrf_token', csrf);

    fetch('<?= url('/admin/etut/toggle') ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update button state to match admin settings style
            const currentlyActive = btn.getAttribute('data-active') === '1';
            const newActive = !currentlyActive;
            btn.setAttribute('data-active', newActive ? '1' : '0');
            // Update classes and icon/text
            const icon = btn.querySelector('i.fas');
            const textSpan = btn.querySelector('span');
            if (newActive) {
                btn.className = 'px-4 py-2 rounded-lg font-semibold transition duration-200 bg-green-600 hover:bg-green-700 text-white';
                if (icon) icon.className = 'fas fa-toggle-on mr-2';
                if (textSpan) textSpan.textContent = 'Form Açık';
            } else {
                btn.className = 'px-4 py-2 rounded-lg font-semibold transition duration-200 bg-red-600 hover:bg-red-700 text-white';
                if (icon) icon.className = 'fas fa-toggle-off mr-2';
                if (textSpan) textSpan.textContent = 'Form Kapalı';
            }
            alert(data.message || 'Form durumu güncellendi');
        } else {
            alert('Hata: ' + (data.message || 'Güncelleme başarısız'));
        }
    })
    .catch(err => { console.error(err); alert('Sunucu hatası'); });
}
</script>
