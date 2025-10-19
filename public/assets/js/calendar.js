/**
 * Calendar.js - Etkinlik Takvimi İşlevleri
 * FullCalendar entegrasyonu ve takvim yönetimi
 */

// Event Click - Etkinlik detaylarını göster
function handleEventClick(info) {
    info.jsEvent.preventDefault();
    
    const eventId = info.event.id;
    showLoading('Etkinlik yükleniyor...');
    
    fetch(`/vildan-portal/api/activities/${eventId}`)
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showEventDetails(data.data);
        } else {
            alert(data.message || 'Etkinlik yüklenemedi');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
}

// Date Click - Boş alana tıklama (yeni etkinlik)
function handleDateClick(info) {
    // Başlangıç ve bitiş zamanlarını ayarla
    const start = new Date(info.dateStr);
    const end = new Date(start.getTime() + 60 * 60 * 1000); // 1 saat sonra
    
    document.getElementById('create_start_datetime').value = start.toISOString();
    document.getElementById('create_end_datetime').value = end.toISOString();
    
    // Display için local format
    document.getElementById('start_datetime_display').value = formatDateTimeLocal(start);
    document.getElementById('end_datetime_display').value = formatDateTimeLocal(end);
    
    openCreateEventModal();
}

// Event Drop - Sürükle-bırak
function handleEventDrop(info) {
    const event = info.event;
    
    if (!confirm(`"${event.title}" etkinliğini ${event.start.toLocaleDateString('tr-TR')} tarihine taşımak istediğinizden emin misiniz?`)) {
        info.revert();
        return;
    }
    
    showLoading('Etkinlik taşınıyor...');
    
    const data = {
        start_datetime: event.start.toISOString(),
        end_datetime: event.end ? event.end.toISOString() : null,
        csrf_token: document.querySelector('input[name="csrf_token"]').value
    };
    
    fetch(`/vildan-portal/api/activities/${event.id}/move`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        hideLoading();
        if (result.success) {
            // Başarılı
            flashSuccess('Etkinlik başarıyla taşındı');
        } else {
            // Hata - geri al
            info.revert();
            alert(result.message || 'Etkinlik taşınamadı');
        }
    })
    .catch(error => {
        hideLoading();
        info.revert();
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
}

// Event Resize - Süre değiştirme
function handleEventResize(info) {
    const event = info.event;
    
    showLoading('Etkinlik güncelleniyor...');
    
    const data = {
        start_datetime: event.start.toISOString(),
        end_datetime: event.end.toISOString(),
        csrf_token: document.querySelector('input[name="csrf_token"]').value
    };
    
    fetch(`/vildan-portal/api/activities/${event.id}/resize`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        hideLoading();
        if (result.success) {
            flashSuccess('Etkinlik süresi güncellendi');
        } else {
            info.revert();
            alert(result.message || 'Süre güncellenemedi');
        }
    })
    .catch(error => {
        hideLoading();
        info.revert();
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
}

// Etkinlik Detaylarını Göster
function showEventDetails(event) {
    const html = `
        <div class="space-y-4">
            <!-- Etkinlik Başlığı -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">${escapeHtml(event.title)}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            ${escapeHtml(event.area_name)}
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg" style="background-color: ${event.area_color}"></div>
            </div>

            <!-- Tarih ve Saat -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">${formatDateTime(event.start_datetime)}</div>
                        <div class="text-gray-600">→ ${formatDateTime(event.end_datetime)}</div>
                    </div>
                </div>
            </div>

            <!-- Açıklama -->
            ${event.description ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                    <p class="text-sm text-gray-600 whitespace-pre-line">${escapeHtml(event.description)}</p>
                </div>
            ` : ''}

            <!-- Tekrar Bilgisi -->
            ${event.recurrence_type ? `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center gap-2 text-sm text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Tekrarlayan Etkinlik: ${getRecurrenceText(event.recurrence_type)}</span>
                    </div>
                </div>
            ` : ''}

            <!-- Oluşturan -->
            <div class="text-xs text-gray-500 flex items-center gap-2 pt-3 border-t">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>${escapeHtml(event.created_by_name)} tarafından oluşturuldu</span>
            </div>
        </div>

        <!-- Aksiyon Butonları -->
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
            <button 
                onclick="closeModal('eventDetailModal')"
                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                Kapat
            </button>
            <button 
                onclick="editEvent(${event.id})"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Düzenle
            </button>
            <button 
                onclick="deleteEvent(${event.id})"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
            >
                Sil
            </button>
        </div>
    `;
    
    document.getElementById('eventDetailModal-content').innerHTML = html;
    openModal('eventDetailModal');
}

// Yeni Etkinlik Modal Aç
function openCreateEventModal() {
    openModal('createEventModal');
}

// Yeni Etkinlik Oluştur
function handleCreateEvent(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    // Datetime değerlerini güncelle
    const startDisplay = form.start_datetime_display.value;
    const endDisplay = form.end_datetime_display.value;
    
    formData.set('start_datetime', new Date(startDisplay).toISOString());
    formData.set('end_datetime', new Date(endDisplay).toISOString());
    
    showLoading('Etkinlik oluşturuluyor...');
    
    fetch('/vildan-portal/api/activities/create', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            closeModal('createEventModal');
            form.reset();
            loadEvents(); // Takvimi yenile
            flashSuccess('Etkinlik başarıyla oluşturuldu');
        } else {
            alert(data.message || 'Etkinlik oluşturulamadı');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
    
    return false;
}

// Etkinlik Düzenle
function editEvent(eventId) {
    closeModal('eventDetailModal');
    window.location.href = `/vildan-portal/activities/${eventId}/edit`;
}

// Etkinlik Sil
function deleteEvent(eventId) {
    if (!confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    showLoading('Etkinlik siliniyor...');
    
    fetch(`/vildan-portal/api/activities/${eventId}`, {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            csrf_token: document.querySelector('input[name="csrf_token"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            closeModal('eventDetailModal');
            loadEvents();
            flashSuccess('Etkinlik silindi');
        } else {
            alert(data.message || 'Etkinlik silinemedi');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
}

// Tekrar Seçeneklerini Aç/Kapat
function toggleRecurring(enabled) {
    const options = document.getElementById('recurringOptions');
    if (enabled) {
        options.classList.remove('hidden');
    } else {
        options.classList.add('hidden');
    }
}

// İstatistikleri Güncelle
function updateStatistics() {
    const now = new Date();
    const filtered = filterEvents();
    
    // Bu ay
    const thisMonth = filtered.filter(e => {
        const eventDate = new Date(e.start);
        return eventDate.getMonth() === now.getMonth() && 
               eventDate.getFullYear() === now.getFullYear();
    }).length;
    
    // Bu hafta
    const startOfWeek = new Date(now);
    startOfWeek.setDate(now.getDate() - now.getDay());
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    
    const thisWeek = filtered.filter(e => {
        const eventDate = new Date(e.start);
        return eventDate >= startOfWeek && eventDate <= endOfWeek;
    }).length;
    
    // Bugün
    const today = filtered.filter(e => {
        const eventDate = new Date(e.start);
        return eventDate.toDateString() === now.toDateString();
    }).length;
    
    document.getElementById('totalEvents').textContent = thisMonth;
    document.getElementById('weekEvents').textContent = thisWeek;
    document.getElementById('todayEvents').textContent = today;
}

// Yardımcı Fonksiyonlar

function formatDateTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    return date.toLocaleString('tr-TR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDateTimeLocal(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function getRecurrenceText(type) {
    const types = {
        'daily': 'Günlük',
        'weekly': 'Haftalık',
        'monthly': 'Aylık'
    };
    return types[type] || type;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function flashSuccess(message) {
    // Geçici başarı mesajı göster
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 z-50 bg-green-100 border border-green-200 text-green-800 px-6 py-3 rounded-lg shadow-lg';
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transition = 'opacity 0.3s';
        setTimeout(() => alertDiv.remove(), 300);
    }, 3000);
}

// Dışa Aktar (Excel)
function exportToExcel() {
    showLoading('Excel dosyası hazırlanıyor...');
    
    window.location.href = '/vildan-portal/api/activities/export?format=excel';
    
    setTimeout(() => hideLoading(), 2000);
}

// Yazdır
function printCalendar() {
    window.print();
}

// Dökümanı yüklendiğinde dropdown'ları kapat
document.addEventListener('click', function(e) {
    if (!e.target.closest('[onclick*="toggleDropdown"]')) {
        document.querySelectorAll('[id$="Menu"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});