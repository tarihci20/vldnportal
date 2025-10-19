<?php
/**
 * Yeni Etkinlik Oluşturma Sayfası - Saat Dilimi Sistemi
 * Vildan Portal
 */

$pageTitle = 'Yeni Etkinlik Rezervasyonu';
$activityAreas = $data['activityAreas'] ?? [];
$timeSlots = $data['timeSlots'] ?? [];
?>

<!-- Main Content -->
<div class="max-w-7xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-4">
        <div class="bg-teal-100 border border-teal-200 rounded-lg p-3">
            <h1 class="text-lg font-semibold text-teal-800">YENİ ETKİNLİK REZERVASYONU</h1>
            <p class="text-sm text-teal-700 mt-1">Saat dilimi bazlı rezervasyon sistemi</p>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <div id="alertContainer" class="mb-4"></div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <form id="reservationForm" method="POST" action="<?= url('/activities') ?>" class="p-6">
            
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Sol Kolon - Ana Bilgiler -->
                <div class="space-y-6">
                    
                    <!-- Etkinlik Alanı -->
                    <div>
                        <label for="activity_area_id" class="block mb-2 text-sm font-medium text-gray-700">
                            Etkinlik Alanı: <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="activity_area_id" 
                            name="activity_area_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="">Seçiniz...</option>
                            <?php foreach ($activityAreas as $area): ?>
                                <option value="<?= $area['id'] ?>">
                                    <?= esc($area['area_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Etkinlik Adı -->
                    <div>
                        <label for="activity_name" class="block mb-2 text-sm font-medium text-gray-700">
                            Etkinlik Adı: <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="activity_name" 
                            name="activity_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Örn: Matematik Semineri"
                            required
                        >
                    </div>
                    
                    <!-- Etkinlik Tarihi -->
                    <div>
                        <label for="activity_date" class="block mb-2 text-sm font-medium text-gray-700">
                            Etkinlik Tarihi: <span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <input 
                                type="date" 
                                id="activity_date" 
                                name="activity_date"
                                min="<?= date('Y-m-d') ?>"
                                value="<?= date('Y-m-d') ?>"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                            <button 
                                type="button" 
                                id="loadSlotsBtn"
                                class="px-4 py-2 bg-blue-500 text-white text-sm rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Saatleri Yükle
                            </button>
                        </div>
                    </div>
                    
                    <!-- Sorumlu Kişi -->
                    <div>
                        <label for="staff" class="block mb-2 text-sm font-medium text-gray-700">
                            Sorumlu Kişi:
                        </label>
                        <input 
                            type="text" 
                            id="staff" 
                            name="staff"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Sorumlu kişi adı"
                        >
                    </div>
                    
                    <!-- Notlar -->
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">
                            Notlar:
                        </label>
                        <textarea 
                            id="notes" 
                            name="notes"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ek açıklamalar..."
                        ></textarea>
                    </div>
                </div>
                
                <!-- Sağ Kolon - Saat Dilimleri -->
                <div class="space-y-6">
                    
                    <!-- Saat Dilimi Seçimi -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Saat Dilimleri: <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Duruma göre mesaj -->
                        <div id="slotsStatus" class="mb-3 p-3 rounded-md bg-gray-50 border">
                            <p class="text-sm text-gray-600">
                                Önce etkinlik alanı ve tarihi seçin, ardından "Saatleri Yükle" butonuna tıklayın.
                            </p>
                        </div>
                        
                        <!-- Saat Dilimi Listesi -->
                        <div id="timeSlotsContainer" class="hidden">
                            <div class="max-h-80 overflow-y-auto border border-gray-300 rounded-md">
                                <div id="timeSlotsList" class="divide-y divide-gray-200">
                                    <!-- JavaScript ile doldurulacak -->
                                </div>
                            </div>
                            
                            <!-- Seçim Bilgisi -->
                            <div id="selectionInfo" class="mt-2 text-sm text-gray-600">
                                <span id="selectedCount">0</span> saat dilimi seçildi
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tekrar Seçimi -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Bu etkinlik tekrar edilsin mi?
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="tekrar_durumu" 
                                    value="hayir" 
                                    checked
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-900">Hayır, tek seferlik</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="tekrar_durumu" 
                                    value="evet"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-900">Evet, tekrar et</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Tekrar Seçenekleri (Gizli) -->
                    <div id="tekrarOptions" class="hidden space-y-4">
                        
                        <!-- Tekrar Türü -->
                        <div>
                            <label for="tekrar_turu" class="block mb-2 text-sm font-medium text-gray-700">
                                Tekrar Türü:
                            </label>
                            <select 
                                id="tekrar_turu" 
                                name="tekrar_turu"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Seçiniz...</option>
                                <option value="gunluk_2">2 gün tekrarla (bugün + 2 gün = 3 gün)</option>
                                <option value="gunluk_3">3 gün tekrarla (bugün + 3 gün = 4 gün)</option>
                                <option value="gunluk_4">4 gün tekrarla (bugün + 4 gün = 5 gün)</option>
                                <option value="gunluk_5">5 gün tekrarla (bugün + 5 gün = 6 gün)</option>
                                <option value="gunluk_7">7 gün tekrarla (bugün + 7 gün = 8 gün)</option>
                                <option value="tarihe_kadar">Belirli bir tarihe kadar (her gün)</option>
                            </select>
                        </div>
                        
                        <!-- Bitiş Tarihi (Tarihe kadar seçeneği için) -->
                        <div id="bitisTarihiDiv" class="hidden mt-3">
                            <label for="bitis_tarihi" class="block mb-2 text-sm font-medium text-gray-700">
                                Bitiş Tarihi: <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="bitis_tarihi" 
                                name="bitis_tarihi"
                                min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle"></i> Başlangıç tarihinden bu tarihe kadar (dahil) her gün aynı saatlerde rezerve edilecek
                            </p>
                        </div>
                    </div>
                    
                    <!-- Çakışma Kontrolü Sonuçları -->
                    <div id="conflictResult" class="hidden">
                        <!-- AJAX ile doldurulacak -->
                    </div>
                </div>
            </div>
            
            <!-- Form Butonları -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="<?= url('/activities') ?>" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    İptal
                </a>
                <button 
                    type="button" 
                    id="checkConflictBtn" 
                    class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 hidden"
                >
                    Çakışma Kontrolü
                </button>
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                    disabled
                >
                    Rezervasyonu Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Saat Dilimi Sistemi JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Flatpickr ile Modern Tarih Seçici (Türkçe)
    const datePickerConfig = {
        locale: 'tr',
        dateFormat: 'Y-m-d',
        minDate: 'today',
        defaultDate: 'today',
        enableTime: false,
        altInput: true,
        altFormat: 'd.m.Y',
        allowInput: false,
        disableMobile: false,
        theme: 'material_blue'
    };
    
    // Ana tarih için Flatpickr
    const activityDatePicker = flatpickr('#activity_date', datePickerConfig);
    
    // Bitiş tarihi için Flatpickr (tekrar eden etkinlikler için)
    const endDatePicker = flatpickr('#bitis_tarihi', {
        ...datePickerConfig,
        minDate: 'today',
        onChange: function(selectedDates, dateStr, instance) {
            // Bitiş tarihi seçildiğinde minimum tarihi güncelle
            if (selectedDates.length > 0) {
                const tomorrow = new Date(selectedDates[0]);
                tomorrow.setDate(tomorrow.getDate() + 1);
            }
        }
    });
    
    // DOM Elements
    const areaSelect = document.getElementById('activity_area_id');
    const dateInput = document.getElementById('activity_date');
    const loadSlotsBtn = document.getElementById('loadSlotsBtn');
    const slotsStatus = document.getElementById('slotsStatus');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeSlotsList = document.getElementById('timeSlotsList');
    const selectedCount = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const tekrarRadios = document.querySelectorAll('input[name="tekrar_durumu"]');
    const tekrarOptions = document.getElementById('tekrarOptions');
    const tekrarTuru = document.getElementById('tekrar_turu');
    const belirliGunlerDiv = document.getElementById('belirliGunlerDiv');
    const bitisTarihiDiv = document.getElementById('bitisTarihiDiv');
    const conflictResult = document.getElementById('conflictResult');
    const checkConflictBtn = document.getElementById('checkConflictBtn');
    const form = document.getElementById('reservationForm');
    
    let availableSlots = [];
    let selectedSlots = [];
    
    // Saat dilimlerini yükle
    loadSlotsBtn.addEventListener('click', async function() {
        const areaId = areaSelect.value;
        const date = dateInput.value;
        
        if (!areaId || !date) {
            alert('Lütfen önce etkinlik alanı ve tarihi seçin.');
            return;
        }
        
        await loadTimeSlots(areaId, date);
    });
    
    // Tekrar seçenekleri
    tekrarRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'evet') {
                tekrarOptions.classList.remove('hidden');
                checkConflictBtn.classList.remove('hidden');
            } else {
                tekrarOptions.classList.add('hidden');
                checkConflictBtn.classList.add('hidden');
            }
        });
    });
    
    // Tekrar türü değişimi
    tekrarTuru.addEventListener('change', function() {
        bitisTarihiDiv.classList.add('hidden');
        
        if (this.value === 'tarihe_kadar') {
            bitisTarihiDiv.classList.remove('hidden');
        }
    });
    
    // Çakışma kontrolü
    checkConflictBtn.addEventListener('click', async function() {
        await checkConflicts();
    });
    
    // Form submit - AJAX ile
    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // Default submit'i durdur
        
        if (selectedSlots.length === 0) {
            alert('Lütfen en az bir saat dilimi seçin.');
            return;
        }
        
        try {
            // Submit butonunu devre dışı bırak
            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Kaydediliyor...';
            submitBtn.classList.add('opacity-75');
            
            // Form verilerini topla
            const formData = new FormData(form);
            
            // Seçilen saat dilimlerini ekle
            selectedSlots.forEach(slotId => {
                formData.append('time_slots[]', slotId);
            });
            
            // AJAX ile gönder
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Başarılı mesaj göster
                alert(data.message || '✅ Etkinlik başarıyla oluşturuldu!');
                
                // Liste sayfasına yönlendir
                window.location.href = '<?= url('/activities') ?>';
            } else {
                // Hata mesajı göster
                throw new Error(data.message || data.error || 'Bir hata oluştu');
            }
            
        } catch (error) {
            console.error('Form gönderme hatası:', error);
            alert('❌ Hata: ' + error.message);
            
            // Submit butonunu tekrar aktif et
            submitBtn.disabled = false;
            submitBtn.textContent = 'Rezervasyonu Oluştur';
            submitBtn.classList.remove('opacity-75');
        }
    });
    
    // Saat dilimlerini yükle
    async function loadTimeSlots(areaId, date) {
        try {
            loadSlotsBtn.disabled = true;
            loadSlotsBtn.textContent = 'Yükleniyor...';
            
            const response = await fetch(`<?= url('/api/activities/available-slots') ?>?area_id=${areaId}&date=${date}`);
            const data = await response.json();
            
            if (data.success) {
                availableSlots = data.all_slots;
                displayTimeSlots();
                
                // Status güncelle
                const freeCount = data.free_count;
                const reservedCount = data.reserved_count;
                
                if (freeCount > 0) {
                    slotsStatus.innerHTML = `
                        <div class="flex items-center text-green-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            ${freeCount} boş saat dilimi mevcut. (${reservedCount} rezerve edilmiş)
                        </div>
                    `;
                } else {
                    slotsStatus.innerHTML = `
                        <div class="flex items-center text-red-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Bu tarihte hiç boş saat dilimi yok. (${reservedCount} rezerve edilmiş)
                        </div>
                    `;
                }
                
                timeSlotsContainer.classList.remove('hidden');
                
            } else {
                throw new Error(data.error || 'Saat dilimleri alınamadı');
            }
            
        } catch (error) {
            console.error('Saat dilimi yükleme hatası:', error);
            alert('Saat dilimleri yüklenirken hata oluştu: ' + error.message);
        } finally {
            loadSlotsBtn.disabled = false;
            loadSlotsBtn.textContent = 'Saatleri Yükle';
        }
    }
    
    // Saat dilimlerini göster
    function displayTimeSlots() {
        timeSlotsList.innerHTML = '';
        
        availableSlots.forEach(slot => {
            const div = document.createElement('div');
            div.className = `p-3 hover:bg-gray-50 ${slot.is_reserved == 1 ? 'bg-red-50' : ''}`;
            
            const isReserved = slot.is_reserved == 1;
            
            div.innerHTML = `
                <label class="flex items-center cursor-pointer ${isReserved ? 'opacity-50 cursor-not-allowed' : ''}">
                    <input 
                        type="checkbox" 
                        value="${slot.id}" 
                        ${isReserved ? 'disabled' : ''}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="ml-3 flex-1">
                        <span class="font-medium text-gray-900">${slot.display_time}</span>
                        ${isReserved ? '<span class="ml-2 text-xs text-red-600 font-medium">DOLU</span>' : '<span class="ml-2 text-xs text-green-600 font-medium">BOŞ</span>'}
                    </span>
                </label>
            `;
            
            // Checkbox event listener
            const checkbox = div.querySelector('input[type="checkbox"]');
            if (checkbox && !isReserved) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedSlots.push(parseInt(this.value));
                    } else {
                        selectedSlots = selectedSlots.filter(id => id !== parseInt(this.value));
                    }
                    updateSelectionInfo();
                });
            }
            
            timeSlotsList.appendChild(div);
        });
    }
    
    // Seçim bilgisini güncelle
    function updateSelectionInfo() {
        selectedCount.textContent = selectedSlots.length;
        
        // Submit butonu durumu
        if (selectedSlots.length > 0) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Çakışma kontrolü (tek veya tekrarlı)
    async function checkConflicts() {
        if (selectedSlots.length === 0) {
            alert('Lütfen önce saat dilimi seçin.');
            return;
        }
        
        try {
            const areaId = areaSelect.value;
            const date = dateInput.value;
            const tekrarDurumu = document.querySelector('input[name="tekrar_durumu"]:checked')?.value || 'hayir';
            
            // Tekrarlı ise tarih listesi oluştur
            let datesToCheck = [date];
            
            if (tekrarDurumu !== 'hayir') {
                const tekrarTuru = document.querySelector('select[name="tekrar_turu"]')?.value;
                datesToCheck = generateDateListForCheck(date, tekrarDurumu, tekrarTuru);
            }
            
            // Tüm tarihleri kontrol et
            const allConflicts = [];
            let conflictCount = 0;
            
            for (const checkDate of datesToCheck) {
                const response = await fetch('<?= url('/api/activities/check-timeslots-conflict') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        area_id: areaId,
                        date: checkDate,
                        time_slot_ids: selectedSlots
                    })
                });
                
                const data = await response.json();
                
                if (data.success && data.has_conflict) {
                    allConflicts.push({
                        date: checkDate,
                        conflict_count: data.conflict_count
                    });
                    conflictCount++;
                }
            }
            
            // Sonucu göster
            displayConflictResult({
                has_conflict: allConflicts.length > 0,
                conflict_count: conflictCount,
                total_dates: datesToCheck.length,
                conflicting_dates: allConflicts
            });
            
        } catch (error) {
            console.error('Çakışma kontrol hatası:', error);
            alert('Çakışma kontrolü sırasında hata oluştu: ' + error.message);
        }
    }
    
    // Tarih listesi oluştur (kontrol için)
    function generateDateListForCheck(startDate, tekrarDurumu, tekrarTuru) {
        const dates = [];
        const start = new Date(startDate + 'T00:00:00');
        
        // Başlangıç tarihini ekle
        dates.push(startDate);
        
        // Tekrar durumu "evet" ise işlem yap
        if (tekrarDurumu === 'evet' && tekrarTuru) {
            let dayCount = 0;
            
            // Günlük tekrar seçenekleri
            if (tekrarTuru.startsWith('gunluk_')) {
                switch(tekrarTuru) {
                    case 'gunluk_2': dayCount = 2; break;
                    case 'gunluk_3': dayCount = 3; break;
                    case 'gunluk_4': dayCount = 4; break;
                    case 'gunluk_5': dayCount = 5; break;
                    case 'gunluk_7': dayCount = 7; break;
                }
                
                for (let i = 1; i <= dayCount; i++) {
                    const newDate = new Date(start);
                    newDate.setDate(start.getDate() + i);
                    const dateStr = newDate.toISOString().split('T')[0];
                    dates.push(dateStr);
                }
            }
            // Belirli tarihe kadar tekrar
            else if (tekrarTuru === 'belirli_tarihe') {
                const endDate = document.querySelector('input[name="tekrar_bitis_tarihi"]')?.value;
                
                if (endDate) {
                    const end = new Date(endDate + 'T00:00:00');
                    // Her gün mi haftalık mı kontrol et
                    const intervalType = document.querySelector('select[name="tekrar_aralik"]')?.value || 'her_gun';
                    const interval = intervalType === 'her_gun' ? 1 : 7;
                    
                    let current = new Date(start);
                    current.setDate(current.getDate() + interval);
                    
                    while (current <= end) {
                        const dateStr = current.toISOString().split('T')[0];
                        dates.push(dateStr);
                        current.setDate(current.getDate() + interval);
                    }
                }
            }
        }
        
        return dates;
    }
    
    // Çakışma sonucunu göster
    function displayConflictResult(data) {
        conflictResult.classList.remove('hidden');
        
        if (data.has_conflict) {
            // Tarihleri formatla
            const dateFormatter = (dateObj) => {
                const date = new Date(dateObj.date + 'T00:00:00');
                const days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
                return date.getDate().toString().padStart(2, '0') + '.' + 
                       (date.getMonth() + 1).toString().padStart(2, '0') + '.' + 
                       date.getFullYear() + ' ' + days[date.getDay()];
            };
            
            const conflictList = data.conflicting_dates
                .map(d => `<li class="text-sm">• ${dateFormatter(d)}</li>`)
                .join('');
            
            conflictResult.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="text-red-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-red-800 flex-1">
                            <h3 class="text-sm font-medium">❌ Çakışma Tespit Edildi!</h3>
                            <p class="text-sm mt-1 font-semibold">${data.conflict_count}/${data.total_dates} tarihte çakışma var</p>
                            <ul class="mt-2 space-y-1">
                                ${conflictList}
                            </ul>
                            <p class="text-sm mt-3 font-medium text-red-900">
                                ⚠️ Bu şekilde kaydedemezsiniz! Lütfen farklı saat dilimleri seçin.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            const message = data.total_dates > 1 
                ? `${data.total_dates} tarihte de çakışma yok. ✅` 
                : 'Seçilen saat dilimlerinde çakışma yok. ✅';
                
            conflictResult.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-green-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-green-800">
                            <h3 class="text-sm font-medium">✅ Rezervasyon Uygun</h3>
                            <p class="text-sm mt-1">${message}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    // Tarih formatı
    function formatDateTurkish(dateStr) {
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('tr-TR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
    
    // Başlangıç durumu
    updateSelectionInfo();
});
</script>