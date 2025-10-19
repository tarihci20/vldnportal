<div class="space-y-6">
    <!-- Uyarı Mesajı -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-sm text-gray-700">
            <strong>Değerli Öğrencimiz,</strong><br>
            Hafta içi saat <strong>16.00 - 17.10</strong> arasında yapılacak olan etütlerden faydalanmak için lütfen etüt almak istediğiniz dersi seçerek formu doldurunuz. Etüdünüz onaylandıktan sonra size gün ve saat bilgisi verilecektir.
        </p>
    </div>
    
    <!-- Form -->
    <form id="etutForm" class="space-y-6">
        <input type="hidden" name="form_type" value="<?= htmlspecialchars($formType) ?>">
        
        <!-- Öğrenci Adı Soyadı (üstte) -->
        <div>
            <label class="block text-sm font-semibold text-green-700 mb-2 uppercase">Öğrenci Adı Soyadı:</label>
            <input 
                type="text" 
                name="full_name" 
                required
                class="w-full px-3 py-2 border-2 border-gray-300 rounded focus:border-green-500 focus:outline-none"
                placeholder="Lütfen bu alanı doldurun."
            >
        </div>
        <!-- Öğrenci No (altında) -->
        <div>
            <label class="block text-sm font-semibold text-green-700 mb-2 uppercase">Öğrenci No:</label>
            <input 
                type="text" 
                name="tc_no" 
                required
                class="w-full px-3 py-2 border-2 border-gray-300 rounded focus:border-green-500 focus:outline-none"
                placeholder="Lütfen bu alanı doldurun. Sadece rakam giriniz."
            >
        </div>
        <!-- Sınıfınız (alt alta radio) -->
        <div>
            <label class="block text-sm font-semibold text-green-700 mb-2 uppercase">Sınıfınız:</label>
            <div class="space-y-2">
                <?php if ($formType === 'ortaokul'): ?>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="5.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">5.SINIF</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="6.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">6.SINIF</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="7.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">7.SINIF</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="8.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">8.SINIF</span>
                    </label>
                <?php else: ?>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="9.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">9.SINIF</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="10.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">10.SINIF</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="11.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">11.SINIF</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="grade" value="12.SINIF" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">12.SINIF</span>
                    </label>
                <?php endif; ?>
            </div>
        </div>
        <!-- Almak İstediğiniz Ders (alt alta radio) -->
        <div>
            <label class="block text-sm font-semibold text-green-700 mb-2 uppercase">Almak İstediğiniz Ders:</label>
            <div class="space-y-2">
                <?php if ($formType === 'ortaokul'): ?>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="TÜRKÇE" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">TÜRKÇE</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="MATEMATİK" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">MATEMATİK</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="FEN BİLİMLERİ" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">FEN BİLİMLERİ</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="SOSYAL BİLGİLER / İNKILAP TARİHİ" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">SOSYAL BİLGİLER / İNKILAP TARİHİ</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="İNGİLİZCE" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">İNGİLİZCE</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="DİN KÜLTÜRÜ" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">DİN KÜLTÜRÜ</span>
                    </label>
                <?php else: ?>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="TÜRK DİLİ VE EDEBİYATI" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">TÜRK DİLİ VE EDEBİYATI</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="MATEMATİK" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">MATEMATİK</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="FİZİK" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">FİZİK</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="KİMYA" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">KİMYA</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="BİYOLOJİ" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">BİYOLOJİ</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="TARİH" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">TARİH</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="COĞRAFYA" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">COĞRAFYA</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="İNGİLİZCE" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">İNGİLİZCE</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="DİN KÜLTÜRÜ" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">DİN KÜLTÜRÜ</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="subject" value="FELSEFE" required class="w-4 h-4 text-green-600">
                        <span class="text-sm">FELSEFE</span>
                    </label>
                <?php endif; ?>
            </div>
            <p class="text-xs text-red-600 mt-2 italic">
                <strong>Mutlaka Yazınız:</strong><br>
                ETÜT ALMAK İSTEDİĞİNİZ KONU/KAZANIM:
            </p>
            <textarea 
                name="notes" 
                rows="3"
                required
                class="w-full mt-2 px-3 py-2 border-2 border-gray-300 rounded focus:border-green-500 focus:outline-none"
                placeholder="Konu veya kazanım yazınız..."
            ></textarea>
        </div>
        
        <!-- VARSA EKLEMEK İSTEDİĞİNİZ MESAJ -->
        <div>
            <label class="block text-sm font-semibold text-green-700 mb-2 uppercase">
                Varsa Eklemek İstediğiniz Mesaj:
            </label>
            <textarea 
                name="address" 
                rows="3"
                class="w-full px-3 py-2 border-2 border-gray-300 rounded focus:border-green-500 focus:outline-none"
                placeholder="Mesajınız (Opsiyonel)"
            ></textarea>
        </div>
        
        <!-- Submit Button -->
        <div class="pt-4">
            <button 
                type="submit" 
                id="submitBtn"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition duration-200"
            >
                Formu Gönder
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('etutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Butonu devre dışı bırak
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Gönderiliyor...';
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('<?= url('/etut/public/submit') ?>', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show custom modal with success message
            showSuccessModal(result.message || 'Başvurunuz başarıyla alındı. En kısa sürede size dönüş yapılacaktır.');
            // Formu sıfırla
            this.reset();
            // Restore submit button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        } else {
            // Hata mesajı göster
            alert(result.message || 'Başvuru gönderilirken bir hata oluştu.');
            // Butonu tekrar aktif et
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Hata:', error);
        showFlashMessage('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
        
        // Butonu tekrar aktif et
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Sadece rakam girişine izin ver
document.querySelector('input[name="tc_no"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// No reload button on open form (reload exists only on closed/error pages)
</script>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-green-500 text-4xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2">Kayıt Başarılı!</h3>
            <p id="successModalMessage" class="text-gray-700 mb-6">Başvurunuz başarıyla alındı. En kısa sürede size dönüş yapılacaktır.</p>
            <div class="flex justify-center">
                <button id="successModalNew" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">YENİ BAŞVURU YAP</button>
            </div>
    </div>
</div>

<script>
function showSuccessModal(message) {
    const modal = document.getElementById('successModal');
    const msg = document.getElementById('successModalMessage');
    if (!modal) return;
    msg.textContent = message;
    modal.classList.remove('hidden');
}

function hideSuccessModal() {
    const modal = document.getElementById('successModal');
    if (!modal) return;
    modal.classList.add('hidden');
}

document.getElementById('successModalNew').addEventListener('click', function() {
    hideSuccessModal();
    // Reset the form and focus first input so a new submission can be made
    const form = document.getElementById('etutForm');
    if (form) {
        form.reset();
        const firstInput = form.querySelector('input, textarea, select');
        if (firstInput) {
            firstInput.focus();
        }
    }
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Formu Gönder';
    }
});
</script>
