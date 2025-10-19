<?php
/**
 * Yeni Etkinlik Rezervasyon Formu - Yeni Sistem
 * Vildan Portal
 */

$pageTitle = 'Yeni Etkinlik Rezervasyonu';
$activityAreas = $data['activityAreas'] ?? [];
?>

<!-- Main Content -->
<div class="max-w-7xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-4">
        <div class="bg-teal-100 border border-teal-200 rounded-lg p-3">
            <h1 class="text-lg font-semibold text-teal-800">YENİ ETKİNLİK REZERVASYONU</h1>
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
                        <input 
                            type="date" 
                            id="activity_date" 
                            name="activity_date"
                            min="<?= date('Y-m-d') ?>"
                            value="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>
                    
                    <!-- Etkinlik Saati -->
                    <div>
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-700">
                            Etkinlik Saati: <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="start_time" 
                            name="start_time"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="">Saat seçiniz...</option>
                            <option value="08:00">08:00</option>
                            <option value="08:30">08:30</option>
                            <option value="09:00">09:00</option>
                            <option value="09:30">09:30</option>
                            <option value="10:00">10:00</option>
                            <option value="10:30">10:30</option>
                            <option value="11:00">11:00</option>
                            <option value="11:30">11:30</option>
                            <option value="12:00">12:00</option>
                            <option value="12:30">12:30</option>
                            <option value="13:00">13:00</option>
                            <option value="13:30">13:30</option>
                            <option value="14:00">14:00</option>
                            <option value="14:30">14:30</option>
                            <option value="15:00">15:00</option>
                            <option value="15:30">15:30</option>
                            <option value="16:00">16:00</option>
                            <option value="16:30">16:30</option>
                            <option value="17:00">17:00</option>
                        </select>
                    </div>
                    
                    <!-- Etkinlik Süresi -->
                    <div>
                        <label for="duration" class="block mb-2 text-sm font-medium text-gray-700">
                            Etkinlik Süresi:
                        </label>
                        <select 
                            id="duration" 
                            name="duration"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="30">30 dakika</option>
                            <option value="60" selected>1 saat</option>
                            <option value="90">1.5 saat</option>
                            <option value="120">2 saat</option>
                            <option value="180">3 saat</option>
                        </select>
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
                
                <!-- Sağ Kolon - Tekrar Ayarları -->
                <div class="space-y-6">
                    
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
                    
                    <!-- Tekrar Seçenekleri (Gizli - JavaScript ile gösterilir) -->
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
                        
                        <!-- Tekrar Önizleme -->
                        <div id="tekrarOnizleme" class="bg-blue-50 p-4 rounded-md hidden">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Oluşturulacak Rezervasyonlar:</h4>
                            <div id="tekrarListesi" class="text-sm text-blue-700"></div>
                            <button type="button" id="onizlemeBtn" class="mt-2 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                Önizleme Göster
                            </button>
                        </div>
                    </div>
                    
                    <!-- Çakışma Kontrolü Sonuçları -->
                    <div id="cakismaKontrolSonucu" class="hidden">
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
                    id="cakismaKontrolBtn" 
                    class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 hidden"
                >
                    Çakışma Kontrolü
                </button>
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                    Rezervasyonu Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Yeni Rezervasyon Sistemi JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // DOM Elements
    const tekrarRadios = document.querySelectorAll('input[name="tekrar_durumu"]');
    const tekrarOptions = document.getElementById('tekrarOptions');
    const tekrarTuru = document.getElementById('tekrar_turu');
    const belirliGunlerDiv = document.getElementById('belirliGunlerDiv');
    const bitisTarihiDiv = document.getElementById('bitisTarihiDiv');
    const tekrarOnizleme = document.getElementById('tekrarOnizleme');
    const onizlemeBtn = document.getElementById('onizlemeBtn');
    const cakismaKontrolBtn = document.getElementById('cakismaKontrolBtn');
    const form = document.getElementById('reservationForm');
    
    // Tekrar seçimi değişince
    tekrarRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'evet') {
                tekrarOptions.classList.remove('hidden');
                cakismaKontrolBtn.classList.remove('hidden');
            } else {
                tekrarOptions.classList.add('hidden');
                cakismaKontrolBtn.classList.add('hidden');
                tekrarOnizleme.classList.add('hidden');
            }
        });
    });
    
    // Tekrar türü değişince
    tekrarTuru.addEventListener('change', function() {
        // Tüm ek alanları gizle
        belirliGunlerDiv.classList.add('hidden');
        bitisTarihiDiv.classList.add('hidden');
        tekrarOnizleme.classList.add('hidden');
        
        // Seçime göre göster
        if (this.value === 'belirli_gunler') {
            belirliGunlerDiv.classList.remove('hidden');
        } else if (this.value === 'tarih_araligi') {
            bitisTarihiDiv.classList.remove('hidden');
        }
        
        // Önizleme butonunu göster
        if (this.value) {
            tekrarOnizleme.classList.remove('hidden');
        }
    });
    
    // Önizleme butonu
    onizlemeBtn.addEventListener('click', async function() {
        await tarihOnizlemesiGoster();
    });
    
    // Çakışma kontrol butonu
    cakismaKontrolBtn.addEventListener('click', async function() {
        await cakismaKontroluYap();
    });
    
    // Form submit
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Tekrar seçimi varsa çakışma kontrolü zorunlu
        const tekrarDurumu = document.querySelector('input[name="tekrar_durumu"]:checked').value;
        
        if (tekrarDurumu === 'evet') {
            // Önce çakışma kontrolü yap
            const cakismaVar = await cakismaKontroluYap();
            
            if (cakismaVar === null) {
                alert('Çakışma kontrolü yapılamadı. Lütfen tekrar deneyin.');
                return;
            }
            
            if (cakismaVar) {
                if (!confirm('Çakışan tarihler var. Yine de devam etmek istiyor musunuz?')) {
                    return;
                }
            }
        }
        
        // Form gönder
        this.submit();
    });
    
    // Tarih önizlemesi göster
    async function tarihOnizlemesiGoster() {
        const baslangicTarihi = document.getElementById('activity_date').value;
        const tekrarTuruValue = tekrarTuru.value;
        
        if (!baslangicTarihi || !tekrarTuruValue) {
            alert('Lütfen önce tarih ve tekrar türü seçin.');
            return;
        }
        
        // Ekstra parametreler
        const ekstraParametreler = {};
        
        if (tekrarTuruValue === 'belirli_gunler') {
            const secilenGunler = Array.from(document.querySelectorAll('input[name="secilen_gunler[]"]:checked'))
                .map(cb => parseInt(cb.value));
            const haftaSayisi = parseInt(document.getElementById('hafta_sayisi').value);
            
            if (secilenGunler.length === 0) {
                alert('Lütfen en az bir gün seçin.');
                return;
            }
            
            ekstraParametreler.gunler = secilenGunler;
            ekstraParametreler.hafta_sayisi = haftaSayisi;
            
        } else if (tekrarTuruValue === 'tarih_araligi') {
            const bitisTarihi = document.getElementById('bitis_tarihi').value;
            if (!bitisTarihi) {
                alert('Lütfen bitiş tarihi seçin.');
                return;
            }
            ekstraParametreler.bitis_tarihi = bitisTarihi;
        }
        
        try {
            const formData = new FormData();
            formData.append('baslangic_tarihi', baslangicTarihi);
            formData.append('tekrar_turu', tekrarTuruValue);
            formData.append('ekstra_parametreler', JSON.stringify(ekstraParametreler));
            
            const response = await fetch('<?= url('/api/activities/tarih-dizisi-olustur') ?>', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.error) {
                alert('Hata: ' + data.error);
                return;
            }
            
            // Tarih listesini göster
            const listDiv = document.getElementById('tekrarListesi');
            if (data.formatted_tarihler.length === 0) {
                listDiv.innerHTML = '<span class="text-red-600">Hiç tekrar tarihi oluşturulamadı.</span>';
            } else {
                listDiv.innerHTML = `
                    <div class="mb-2"><strong>Ana tarih:</strong> ${formatDateTurkish(baslangicTarihi)}</div>
                    <div><strong>Tekrar tarihleri (${data.toplam_tarih} adet):</strong></div>
                    <div class="mt-1">${data.formatted_tarihler.join(', ')}</div>
                `;
            }
            
        } catch (error) {
            console.error('Önizleme hatası:', error);
            alert('Önizleme alınamadı.');
        }
    }
    
    // Çakışma kontrolü yap
    async function cakismaKontroluYap() {
        const areaId = document.getElementById('activity_area_id').value;
        const baslangicTarihi = document.getElementById('activity_date').value;
        const saat = document.getElementById('start_time').value;
        const tekrarDurumu = document.querySelector('input[name="tekrar_durumu"]:checked').value;
        
        if (!areaId || !baslangicTarihi || !saat) {
            alert('Lütfen alan, tarih ve saat seçin.');
            return null;
        }
        
        try {
            if (tekrarDurumu === 'hayir') {
                // Tek tarih kontrolü
                return await tekTarihCakismaKontrol(areaId, baslangicTarihi, saat);
            } else {
                // Çoklu tarih kontrolü
                return await cokluTarihCakismaKontrol(areaId, saat);
            }
            
        } catch (error) {
            console.error('Çakışma kontrol hatası:', error);
            return null;
        }
    }
    
    // Tek tarih çakışma kontrolü
    async function tekTarihCakismaKontrol(areaId, tarih, saat) {
        const formData = new FormData();
        formData.append('area_id', areaId);
        formData.append('tarih', tarih);
        formData.append('saat', saat);
        
        const response = await fetch('<?= url('/api/activities/cakisma-kontrol') ?>', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        // Sonucu göster
        const sonucDiv = document.getElementById('cakismaKontrolSonucu');
        sonucDiv.classList.remove('hidden');
        
        if (data.cakisma_var) {
            sonucDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-red-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-red-800">
                            <h3 class="text-sm font-medium">Çakışma Tespit Edildi</h3>
                            <p class="text-sm mt-1">${formatDateTurkish(tarih)} ${saat} saatinde alan zaten rezerve edilmiş.</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            sonucDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-green-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-green-800">
                            <h3 class="text-sm font-medium">Rezervasyon Uygun</h3>
                            <p class="text-sm mt-1">${formatDateTurkish(tarih)} ${saat} saati rezerve edilebilir.</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        return data.cakisma_var;
    }
    
    // Çoklu tarih çakışma kontrolü
    async function cokluTarihCakismaKontrol(areaId, saat) {
        // Önce tarih listesi oluştur
        await tarihOnizlemesiGoster();
        
        // Tarih listesini al (API'den)
        const baslangicTarihi = document.getElementById('activity_date').value;
        const tekrarTuruValue = tekrarTuru.value;
        
        // Ekstra parametreleri topla
        const ekstraParametreler = {};
        if (tekrarTuruValue === 'belirli_gunler') {
            ekstraParametreler.gunler = Array.from(document.querySelectorAll('input[name="secilen_gunler[]"]:checked'))
                .map(cb => parseInt(cb.value));
            ekstraParametreler.hafta_sayisi = parseInt(document.getElementById('hafta_sayisi').value);
        } else if (tekrarTuruValue === 'tarih_araligi') {
            ekstraParametreler.bitis_tarihi = document.getElementById('bitis_tarihi').value;
        }
        
        // Tarih listesi oluştur
        const formData1 = new FormData();
        formData1.append('baslangic_tarihi', baslangicTarihi);
        formData1.append('tekrar_turu', tekrarTuruValue);
        formData1.append('ekstra_parametreler', JSON.stringify(ekstraParametreler));
        
        const response1 = await fetch('<?= url('/api/activities/tarih-dizisi-olustur') ?>', {
            method: 'POST',
            body: formData1
        });
        
        const tarihData = await response1.json();
        
        if (tarihData.error || tarihData.tarih_listesi.length === 0) {
            alert('Tarih listesi oluşturulamadı.');
            return null;
        }
        
        // Ana tarih + tekrar tarihleri
        const tumTarihler = [baslangicTarihi, ...tarihData.tarih_listesi];
        
        // Çoklu çakışma kontrolü
        const formData2 = new FormData();
        formData2.append('area_id', areaId);
        formData2.append('saat', saat);
        formData2.append('tarih_listesi', JSON.stringify(tumTarihler));
        
        const response2 = await fetch('<?= url('/api/activities/coklu-cakisma-kontrol') ?>', {
            method: 'POST',
            body: formData2
        });
        
        const cakismaData = await response2.json();
        
        // Sonucu göster
        const sonucDiv = document.getElementById('cakismaKontrolSonucu');
        sonucDiv.classList.remove('hidden');
        
        if (cakismaData.cakisan_sayisi > 0) {
            const cakisanTarihlerText = cakismaData.cakisan_tarihler
                .map(item => `${item.tarih_formatted} ${saat}`)
                .join(', ');
                
            sonucDiv.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-yellow-800">
                            <h3 class="text-sm font-medium">Kısmi Çakışma Tespit Edildi</h3>
                            <p class="text-sm mt-1">
                                ${cakismaData.toplam_kontrol} tarihten ${cakismaData.cakisan_sayisi} tanesi çakışıyor:<br>
                                <strong>${cakisanTarihlerText}</strong>
                            </p>
                            <p class="text-sm mt-2 text-yellow-700">
                                Çakışan tarihler atlanacak, diğerleri rezerve edilecek.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            sonucDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-green-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-green-800">
                            <h3 class="text-sm font-medium">Tüm Rezervasyonlar Uygun</h3>
                            <p class="text-sm mt-1">
                                ${cakismaData.toplam_kontrol} tarihin tümü rezerve edilebilir.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        return cakismaData.cakisan_sayisi > 0;
    }
    
    // Yardımcı fonksiyon
    function formatDateTurkish(dateStr) {
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('tr-TR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
});
</script>