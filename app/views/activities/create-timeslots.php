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
                                value="<?= date('Y-m-d', strtotime('+1 day')) ?>"
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
                                <option value="gunluk_3">İlk 3 gün tekrarla</option>
                                <option value="gunluk_7">İlk 7 gün tekrarla</option>
                                <option value="haftalik_3">3 hafta tekrarla</option>
                                <option value="belirli_gunler">Haftalık belirli günlerde</option>
                                <option value="ay_sonu">Ay sonuna kadar</option>
                                <option value="tarih_araligi">Belirli tarih aralığında</option>
                            </select>
                        </div>
                        
                        <!-- Belirli Günler (Haftalık için) -->
                        <div id="belirliGunlerDiv" class="hidden">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Hangi günler:
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="secilen_gunler[]" value="1" class="w-4 h-4 text-blue-600">
                                    <span class="ml-2 text-sm">Pazartesi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="secilen_gunler[]" value="2" class="w-4 h-4 text-blue-600">
                                    <span class="ml-2 text-sm">Salı</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="secilen_gunler[]" value="3" class="w-4 h-4 text-blue-600">
                                    <span class="ml-2 text-sm">Çarşamba</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="secilen_gunler[]" value="4" class="w-4 h-4 text-blue-600">
                                    <span class="ml-2 text-sm">Perşembe</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="secilen_gunler[]" value="5" class="w-4 h-4 text-blue-600">
                                    <span class="ml-2 text-sm">Cuma</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="secilen_gunler[]" value="6" class="w-4 h-4 text-blue-600">
                                    <span class="ml-2 text-sm">Cumartesi</span>
                                </label>
                            </div>
                            
                            <div class="mt-3">
                                <label for="hafta_sayisi" class="block mb-1 text-sm font-medium text-gray-700">
                                    Kaç hafta:
                                </label>
                                <select id="hafta_sayisi" name="hafta_sayisi" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="2">2 hafta</option>
                                    <option value="3">3 hafta</option>
                                    <option value="4" selected>4 hafta</option>
                                    <option value="6">6 hafta</option>
                                    <option value="8">8 hafta</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Bitiş Tarihi (Tarih aralığı için) -->
                        <div id="bitisTarihiDiv" class="hidden">
                            <label for="bitis_tarihi" class="block mb-2 text-sm font-medium text-gray-700">
                                Bitiş Tarihi:
                            </label>
                            <input 
                                type="date" 
                                id="bitis_tarihi" 
                                name="bitis_tarihi"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
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
        belirliGunlerDiv.classList.add('hidden');
        bitisTarihiDiv.classList.add('hidden');
        
        if (this.value === 'belirli_gunler') {
            belirliGunlerDiv.classList.remove('hidden');
        } else if (this.value === 'tarih_araligi') {
            bitisTarihiDiv.classList.remove('hidden');
        }
    });
    
    // Çakışma kontrolü
    checkConflictBtn.addEventListener('click', async function() {
        await checkConflicts();
    });
    
    // Form submit
    form.addEventListener('submit', function(e) {
        if (selectedSlots.length === 0) {
            e.preventDefault();
            alert('Lütfen en az bir saat dilimi seçin.');
            return;
        }
        
        // Seçilen slot ID'lerini form'a ekle
        selectedSlots.forEach(slotId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'time_slots[]';
            input.value = slotId;
            form.appendChild(input);
        });
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
    
    // Çakışma kontrolü
    async function checkConflicts() {
        if (selectedSlots.length === 0) {
            alert('Lütfen önce saat dilimi seçin.');
            return;
        }
        
        try {
            const areaId = areaSelect.value;
            const date = dateInput.value;
            
            const response = await fetch('<?= url('/api/activities/check-slots-conflict') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    area_id: areaId,
                    date: date,
                    time_slot_ids: selectedSlots
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                displayConflictResult(data);
            } else {
                throw new Error(data.error || 'Çakışma kontrolü yapılamadı');
            }
            
        } catch (error) {
            console.error('Çakışma kontrol hatası:', error);
            alert('Çakışma kontrolü sırasında hata oluştu: ' + error.message);
        }
    }
    
    // Çakışma sonucunu göster
    function displayConflictResult(data) {
        conflictResult.classList.remove('hidden');
        
        if (data.has_conflict) {
            conflictResult.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-red-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-red-800">
                            <h3 class="text-sm font-medium">Çakışma Tespit Edildi</h3>
                            <p class="text-sm mt-1">${data.conflict_count} saat diliminde çakışma var!</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            conflictResult.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-green-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-green-800">
                            <h3 class="text-sm font-medium">Rezervasyon Uygun</h3>
                            <p class="text-sm mt-1">Seçilen saat dilimlerinde çakışma yok.</p>
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