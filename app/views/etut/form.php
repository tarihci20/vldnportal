<?php
/**
 * Etüt Başvuru Formu
 * Öğrenciler için etüt başvuru formu
 */

require_once VIEW_PATH . '/layouts/header.php';

// Öğrenci bilgisi varsa
$student = $student ?? null;
$subjects = $subjects ?? ['Matematik', 'Fizik', 'Kimya', 'Biyoloji', 'Türkçe', 'İngilizce', 'Tarih', 'Coğrafya'];
?>

<div class="container mx-auto px-4 py-6">
    <!-- Başlık -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Etüt Başvuru Formu</h1>
        <p class="text-gray-600 mt-1">Etüt programına katılmak için formu doldurun</p>
    </div>

    <!-- Bilgilendirme -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex gap-3">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="font-semibold text-blue-900 mb-1">Etüt Programı Hakkında</h3>
                <p class="text-sm text-blue-800">
                    Etüt programı, öğrencilerin derslerinde başarılı olmalarını sağlamak için 
                    öğretmenler gözetiminde çalışma imkanı sunar. Başvurunuz onaylandıktan sonra 
                    etüt saatleri hakkında bilgilendirileceksiniz.
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form 
        action="<?= url('etut/store') ?>" 
        method="POST"
        class="max-w-3xl mx-auto"
        onsubmit="return validateEtutForm(this)"
    >
        <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
        <?php if ($student): ?>
            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
        <?php endif; ?>

        <div class="space-y-6">
            <!-- Öğrenci Bilgileri -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                    <h2 class="text-lg font-semibold">Öğrenci Bilgileri</h2>
                </div>
                <div class="p-6 space-y-4">
                    <?php if ($student): ?>
                        <!-- Öğrenci seçilmişse sadece göster -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                    <?= strtoupper(mb_substr($student['first_name'], 0, 1) . mb_substr($student['last_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <?= e($student['first_name'] . ' ' . $student['last_name']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-600">Sınıf: <?= e($student['class']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Öğrenci seçimi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Öğrenci Seç <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="student_id" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                onchange="loadStudentInfo(this.value)"
                            >
                                <option value="">Öğrenci seçin...</option>
                                <!-- Öğrenciler dinamik olarak yüklenecek -->
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Etüt Bilgileri -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white">
                    <h2 class="text-lg font-semibold">Etüt Tercihleri</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Ders Seçimi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Yardım Almak İstediğiniz Dersler <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <?php foreach ($subjects as $subject): ?>
                                <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="subjects[]" 
                                        value="<?= e($subject) ?>"
                                        class="w-4 h-4 text-indigo-600"
                                    >
                                    <span class="text-sm text-gray-700"><?= e($subject) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Birden fazla ders seçebilirsiniz</p>
                    </div>

                    <!-- Tercih Edilen Günler -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tercih Edilen Günler <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <?php 
                            $days = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
                            foreach ($days as $day): 
                            ?>
                                <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="preferred_days[]" 
                                        value="<?= e($day) ?>"
                                        class="w-4 h-4 text-green-600"
                                    >
                                    <span class="text-sm text-gray-700"><?= e($day) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Tercih Edilen Saat Aralığı -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tercih Edilen Saat Aralığı
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="preferred_time" 
                                    value="Sabah (08:00-12:00)"
                                    class="w-4 h-4 text-green-600"
                                >
                                <span class="text-sm text-gray-700">🌅 Sabah (08:00-12:00)</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="preferred_time" 
                                    value="Öğle (12:00-16:00)"
                                    class="w-4 h-4 text-green-600"
                                    checked
                                >
                                <span class="text-sm text-gray-700">☀️ Öğle (12:00-16:00)</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="preferred_time" 
                                    value="Akşam (16:00-20:00)"
                                    class="w-4 h-4 text-green-600"
                                >
                                <span class="text-sm text-gray-700">🌆 Akşam (16:00-20:00)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Başlangıç Tarihi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Etüte Başlama Tarihi <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="start_date" 
                            required
                            min="<?= date('Y-m-d') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                        >
                    </div>
                </div>
            </div>

            <!-- Veli Onayı ve İletişim -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                    <h2 class="text-lg font-semibold">Veli Onayı ve İletişim</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Veli Adı Soyadı <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="parent_name" 
                                required
                                value="<?= old('parent_name', $student['father_name'] ?? $student['mother_name'] ?? '') ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Veli Telefon <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                name="parent_phone" 
                                required
                                pattern="[0-9]{11}"
                                maxlength="11"
                                value="<?= old('parent_phone', $student['father_phone'] ?? $student['mother_phone'] ?? '') ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                                placeholder="05XXXXXXXXX"
                            >
                        </div>
                    </div>

                    <!-- Özel Notlar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Özel Notlar / İstekler
                        </label>
                        <textarea 
                            name="notes" 
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                            placeholder="Öğrenci hakkında bilmemiz gereken özel durumlar veya istekleriniz varsa yazabilirsiniz..."
                        ><?= old('notes') ?></textarea>
                    </div>

                    <!-- Onay Checkboxları -->
                    <div class="space-y-3 pt-4 border-t">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="parent_consent" 
                                required
                                class="w-5 h-5 text-purple-600 mt-1"
                            >
                            <span class="text-sm text-gray-700">
                                <strong>Veli Onayı:</strong> Çocuğumun etüt programına katılmasını onaylıyorum. 
                                Etüt saatleri ve kuralları hakkında bilgilendirilmek istiyorum.
                                <span class="text-red-500">*</span>
                            </span>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="data_consent" 
                                required
                                class="w-5 h-5 text-purple-600 mt-1"
                            >
                            <span class="text-sm text-gray-700">
                                <strong>KVKK Onayı:</strong> Kişisel verilerimin etüt programı kapsamında işlenmesini kabul ediyorum.
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Butonları -->
            <div class="flex items-center justify-end gap-3 pb-6">
                <a 
                    href="<?= url('etut') ?>" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    İptal
                </a>
                <button 
                    type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Başvuruyu Gönder
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function validateEtutForm(form) {
    // En az bir ders seçildi mi?
    const subjects = form.querySelectorAll('input[name="subjects[]"]:checked');
    if (subjects.length === 0) {
        alert('Lütfen en az bir ders seçin!');
        return false;
    }

    // En az bir gün seçildi mi?
    const days = form.querySelectorAll('input[name="preferred_days[]"]:checked');
    if (days.length === 0) {
        alert('Lütfen en az bir gün seçin!');
        return false;
    }

    // Telefon kontrolü
    const phone = form.parent_phone.value;
    if (phone.length !== 11) {
        alert('Telefon numarası 11 haneli olmalıdır!');
        return false;
    }

    // Onaylar kontrol
    if (!form.parent_consent.checked) {
        alert('Lütfen veli onayını işaretleyin!');
        return false;
    }

    if (!form.data_consent.checked) {
        alert('Lütfen KVKK onayını işaretleyin!');
        return false;
    }

    // Loading göster
    showLoading('Başvuru gönderiliyor...');
    
    return true;
}

function loadStudentInfo(studentId) {
    if (!studentId) return;
    
    // AJAX ile öğrenci bilgilerini yükle ve veli bilgilerini otomatik doldur
    fetch(`<?= url('api/students/') ?>${studentId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const student = data.data;
            // Veli bilgilerini doldur
            if (student.father_name) {
                document.querySelector('input[name="parent_name"]').value = student.father_name;
            } else if (student.mother_name) {
                document.querySelector('input[name="parent_name"]').value = student.mother_name;
            }
            
            if (student.father_phone) {
                document.querySelector('input[name="parent_phone"]').value = student.father_phone;
            } else if (student.mother_phone) {
                document.querySelector('input[name="parent_phone"]').value = student.mother_phone;
            }
        }
    });
}
</script>

<?php require_once VIEW_PATH . '/layouts/footer.php'; ?>