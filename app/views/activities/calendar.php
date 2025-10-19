<?php
/**
 * Etkinlik Takvimi
 * FullCalendar ile gelişmiş takvim görünümü
 */

$pageTitle = 'Etkinlik Takvimi';
$activityAreas = $data['activityAreas'] ?? [];
?>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<!-- Main Content -->
<div class="max-w-7xl mx-auto">
    <!-- Başlık ve Aksiyonlar -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Etkinlik Takvimi</h1>
            <p class="text-gray-600 mt-1">Etkinlikleri görüntüleyin, düzenleyin ve yönetin</p>
        </div>
        
        <div class="flex items-center gap-2">
            <!-- Bugüne Git -->
            <button 
                onclick="calendar.today()"
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
            >
                Bugün
            </button>
            
            <!-- Yeni Etkinlik -->
            <button 
                onclick="openCreateEventModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Yeni Etkinlik
            </button>

            <!-- Görünüm Seçenekleri -->
            <div class="relative">
                <button 
                    onclick="toggleDropdown('viewMenu')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-white"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span id="currentView">Ay</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div id="viewMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                    <button onclick="changeView('dayGridMonth')" class="w-full px-4 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-white rounded-t-lg">📅 Ay Görünümü</button>
                    <button onclick="changeView('timeGridWeek')" class="w-full px-4 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-white">📆 Hafta Görünümü</button>
                    <button onclick="changeView('timeGridDay')" class="w-full px-4 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-white">📋 Gün Görünümü</button>
                    <button onclick="changeView('listWeek')" class="w-full px-4 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-white rounded-b-lg">📝 Liste Görünümü</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <?php include VIEW_PATH . '/components/alert.php'; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sol Panel - Filtreler ve Açıklama -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Alan Filtreleri -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Etkinlik Alanları
                </h3>
                
                <div class="space-y-2">
                    <!-- Tümünü Seç/Kaldır -->
                    <label class="flex items-center gap-2 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded cursor-pointer">
                        <input 
                            type="checkbox" 
                            id="selectAllAreas"
                            checked
                            onchange="toggleAllAreas(this.checked)"
                            class="w-4 h-4 text-indigo-600"
                        >
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Tümünü Göster</span>
                    </label>
                    
                    <div class="border-t pt-2 space-y-1">
                        <?php if (!empty($areas)): ?>
                            <?php foreach ($areas as $area): ?>
                                <label class="flex items-center gap-2 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded cursor-pointer area-filter">
                                    <input 
                                        type="checkbox" 
                                        class="area-checkbox w-4 h-4"
                                        data-area-id="<?= $area['id'] ?>"
                                        checked
                                        onchange="filterCalendar()"
                                    >
                                    <div class="w-3 h-3 rounded-full" style="background-color: <?= e($area['color'] ?? '#3B82F6') ?>"></div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 flex-1"><?= e($area['name']) ?></span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500"><?= $area['event_count'] ?? 0 ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">Alan bulunamadı</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Kullanıcı Filtreleri (Admin/Müdür için) -->
            <?php if (hasRole([1, 4])): // Admin veya Müdür ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Kullanıcı Filtresi
                </h3>
                
                <select 
                    id="userFilter"
                    onchange="filterCalendar()"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm"
                >
                    <option value="">Tüm Kullanıcılar</option>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= e($user['full_name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Bilgilendirme -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    İpuçları
                </h3>
                <ul class="text-xs text-blue-800 dark:text-blue-300 space-y-1">
                    <li>• Etkinlikleri sürükleyerek taşıyabilirsiniz</li>
                    <li>• Etkinliğe tıklayarak detayları görün</li>
                    <li>• Boş alana tıklayarak yeni etkinlik ekleyin</li>
                    <li>• Renk = Etkinlik alanı</li>
                </ul>
            </div>

            <!-- İstatistikler -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Bu Ay</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Toplam Etkinlik:</span>
                        <span id="totalEvents" class="font-semibold text-gray-900 dark:text-white">-</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Bu Hafta:</span>
                        <span id="weekEvents" class="font-semibold text-gray-900 dark:text-white">-</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Bugün:</span>
                        <span id="todayEvents" class="font-semibold text-indigo-600 dark:text-indigo-400">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Takvim -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div id="calendar" class="p-4 dark:text-white"></div>
            </div>
        </div>
    </div>
</div>

<!-- Etkinlik Detay Modal -->
<?php 
$modalId = 'eventDetailModal';
$modalTitle = 'Etkinlik Detayları';
$modalSize = 'lg';
include VIEW_PATH . '/components/modal.php';
?>

<div id="eventDetailModal-content">
    <!-- İçerik AJAX ile yüklenecek -->
</div>

<!-- Yeni Etkinlik Modal -->
<?php 
$modalId = 'createEventModal';
$modalTitle = 'Yeni Etkinlik';
$modalSize = 'xl';
include VIEW_PATH . '/components/modal.php';
?>

<div id="createEventModal-content">
    <form id="createEventForm" onsubmit="return handleCreateEvent(event)">
        <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
        <input type="hidden" name="start_datetime" id="create_start_datetime">
        <input type="hidden" name="end_datetime" id="create_end_datetime">
        
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Etkinlik Adı <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="Etkinlik başlığı"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Etkinlik Alanı <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="area_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Alan seçin...</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?= $area['id'] ?>"><?= e($area['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Başlangıç <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="datetime-local" 
                        name="start_datetime_display" 
                        id="start_datetime_display"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bitiş <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="datetime-local" 
                        name="end_datetime_display" 
                        id="end_datetime_display"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Açıklama
                </label>
                <textarea 
                    name="description" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Etkinlik açıklaması (opsiyonel)"
                ></textarea>
            </div>

            <!-- Tekrar Kuralları -->
            <div class="border-t pt-4">
                <label class="flex items-center gap-2 mb-3">
                    <input 
                        type="checkbox" 
                        id="enableRecurring"
                        onchange="toggleRecurring(this.checked)"
                        class="w-4 h-4 text-indigo-600"
                    >
                    <span class="text-sm font-medium text-gray-700">Tekrarlayan Etkinlik</span>
                </label>

                <div id="recurringOptions" class="hidden space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tekrar Sıklığı</label>
                            <select 
                                name="recurrence_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                            >
                                <option value="daily">Günlük</option>
                                <option value="weekly" selected>Haftalık</option>
                                <option value="monthly">Aylık</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tekrar Sayısı</label>
                            <input 
                                type="number" 
                                name="recurrence_count"
                                value="4"
                                min="1"
                                max="52"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
            <button 
                type="button"
                onclick="closeModal('createEventModal')"
                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                İptal
            </button>
            <button 
                type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
            >
                Oluştur
            </button>
        </div>
    </form>
</div>

<!-- Loading Overlay -->
<?php 
$loadingType = 'spinner';
$loadingText = 'Yükleniyor...';
include VIEW_PATH . '/components/loading.php';
?>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js"></script>

<style>
/* FullCalendar Dark Mode */
.dark #calendar {
    --fc-border-color: #374151;
    --fc-button-bg-color: #4B5563;
    --fc-button-border-color: #4B5563;
    --fc-button-hover-bg-color: #6B7280;
    --fc-button-hover-border-color: #6B7280;
    --fc-button-active-bg-color: #374151;
    --fc-button-active-border-color: #374151;
    --fc-today-bg-color: rgba(79, 70, 229, 0.1);
    --fc-neutral-bg-color: #1F2937;
    --fc-page-bg-color: #111827;
}

.dark .fc {
    background-color: #1F2937;
}

.dark .fc-theme-standard td,
.dark .fc-theme-standard th {
    border-color: #374151;
}

.dark .fc-col-header-cell {
    background-color: #374151;
    color: #F9FAFB;
}

.dark .fc-daygrid-day-number,
.dark .fc-timegrid-slot-label,
.dark .fc-list-day-text,
.dark .fc-list-day-side-text {
    color: #E5E7EB;
}

.dark .fc .fc-button {
    background-color: #4B5563;
    border-color: #4B5563;
    color: #F9FAFB;
}

.dark .fc .fc-button:hover {
    background-color: #6B7280;
    border-color: #6B7280;
}

.dark .fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #4F46E5;
    border-color: #4F46E5;
}

.dark .fc-toolbar-title {
    color: #F9FAFB;
}

.dark .fc-daygrid-day.fc-day-today {
    background-color: rgba(79, 70, 229, 0.15) !important;
}

.dark .fc-list-event:hover td {
    background-color: #374151;
}

.dark .fc-event {
    border-color: transparent;
}

/* Light mode optimizations */
.fc-event {
    border-radius: 4px;
    padding: 2px 4px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.fc-event:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.fc-daygrid-event {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.fc .fc-button {
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.fc-toolbar-title {
    font-size: 1.5rem;
    font-weight: 700;
}
</style>

<script>
let calendar;
let currentEvents = [];

document.addEventListener('DOMContentLoaded', function() {
    initializeCalendar();
    loadEvents();
});

function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'tr',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        buttonText: {
            today: 'Bugün',
            month: 'Ay',
            week: 'Hafta',
            day: 'Gün',
            list: 'Liste'
        },
        height: 'auto',
        editable: true,
        droppable: true,
        eventDrop: handleEventDrop,
        eventResize: handleEventResize,
        eventClick: handleEventClick,
        dateClick: handleDateClick,
        events: [],
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    });
    
    calendar.render();
}

function loadEvents() {
    showLoading('Etkinlikler yükleniyor...');
    
    fetch('<?= url('api/activities/calendar') ?>')
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            currentEvents = data.data;
            updateCalendar();
            updateStatistics();
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error loading events:', error);
    });
}

function updateCalendar() {
    const filtered = filterEvents();
    calendar.removeAllEvents();
    calendar.addEventSource(filtered);
}

function filterEvents() {
    // Alan filtreleri
    const selectedAreas = [];
    document.querySelectorAll('.area-checkbox:checked').forEach(cb => {
        selectedAreas.push(parseInt(cb.dataset.areaId));
    });
    
    // Kullanıcı filtresi
    const selectedUser = document.getElementById('userFilter')?.value;
    
    return currentEvents.filter(event => {
        if (selectedAreas.length > 0 && !selectedAreas.includes(event.extendedProps.area_id)) {
            return false;
        }
        
        if (selectedUser && event.extendedProps.created_by != selectedUser) {
            return false;
        }
        
        return true;
    });
}

function filterCalendar() {
    updateCalendar();
    updateStatistics();
}

function toggleAllAreas(checked) {
    document.querySelectorAll('.area-checkbox').forEach(cb => {
        cb.checked = checked;
    });
    filterCalendar();
}

function changeView(viewName) {
    calendar.changeView(viewName);
    
    const viewNames = {
        'dayGridMonth': 'Ay',
        'timeGridWeek': 'Hafta',
        'timeGridDay': 'Gün',
        'listWeek': 'Liste'
    };
    
    document.getElementById('currentView').textContent = viewNames[viewName];
    document.getElementById('viewMenu').classList.add('hidden');
}

function toggleDropdown(id) {
    document.getElementById(id).classList.toggle('hidden');
}

// Event handlers devam edecek...
</script>

<script src="<?= asset('js/calendar.js') ?>"></script>