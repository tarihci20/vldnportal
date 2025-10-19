<?php
/**
 * Yeni Etüt Başvurusu Sayfası
 * Vildan Portal
 */

$pageTitle = 'Yeni Etüt Başvurusu';
?>

<!-- Main Content -->

        
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= url('/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                        <i class="fas fa-home mr-2"></i> Ana Sayfa
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="<?= url('/etut') ?>" class="text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">Etüt</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Yeni Başvuru</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Etüt Başvurusu</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Öğrenci etüt başvurusu oluşturun
            </p>
        </div>
        
        <!-- Alert Messages -->
        <div id="alertContainer" class="mb-4"></div>
        
        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <form id="etutForm" method="POST" action="<?= url('/etut/store') ?>" class="p-6">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <!-- Öğrenci Seçimi -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-user-graduate mr-2 text-primary-600"></i>
                        Öğrenci Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        
                        <!-- Öğrenci Arama -->
                        <div>
                            <label for="student_search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Öğrenci Ara <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="student_search" 
                                    placeholder="İsim, soyisim veya TC ile arayın..."
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    autocomplete="off"
                                >
                                <div id="searchResults" class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                            </div>
                            <input type="hidden" id="student_id" name="student_id" required>
                            <div id="selectedStudent" class="mt-2 hidden">
                                <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            <span id="selectedStudentName"></span>
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Sınıf: <span id="selectedStudentClass"></span>
                                        </p>
                                    </div>
                                    <button type="button" onclick="clearStudent()" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Etüt Detayları -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-book-reader mr-2 text-green-600"></i>
                        Etüt Detayları
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Ders -->
                        <div>
                            <label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Ders <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="subject" 
                                name="subject"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                                <option value="">Seçiniz...</option>
                                <option value="Matematik">Matematik</option>
                                <option value="Türkçe">Türkçe</option>
                                <option value="İngilizce">İngilizce</option>
                                <option value="Fen Bilimleri">Fen Bilimleri</option>
                                <option value="Sosyal Bilgiler">Sosyal Bilgiler</option>
                                <option value="Fizik">Fizik</option>
                                <option value="Kimya">Kimya</option>
                                <option value="Biyoloji">Biyoloji</option>
                                <option value="Tarih">Tarih</option>
                                <option value="Coğrafya">Coğrafya</option>
                                <option value="Diğer">Diğer</option>
                            </select>
                        </div>
                        
                        <!-- Tarih -->
                        <div>
                            <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Tarih <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="date" 
                                name="date"
                                min="<?= date('Y-m-d') ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                        </div>
                        
                        <!-- Başlangıç Saati -->
                        <div>
                            <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Başlangıç Saati <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="time" 
                                id="start_time" 
                                name="start_time"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                        </div>
                        
                        <!-- Bitiş Saati -->
                        <div>
                            <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Bitiş Saati <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="time" 
                                id="end_time" 
                                name="end_time"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                            >
                        </div>
                        
                        <!-- Öğretmen -->
                        <div class="md:col-span-2">
                            <label for="teacher" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Öğretmen (Opsiyonel)
                            </label>
                            <input 
                                type="text" 
                                id="teacher" 
                                name="teacher"
                                placeholder="Etüt verecek öğretmen adı"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            >
                        </div>
                        
                    </div>
                </div>
                
                <!-- Açıklama/Notlar -->
                <div class="mb-8">
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Açıklama/Notlar
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Etüt ile ilgili açıklama veya özel notlar..."
                    ></textarea>
                </div>
                
                <!-- Form Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a 
                        href="<?= url('/etut') ?>" 
                        class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700"
                    >
                        <i class="fas fa-times mr-2"></i> İptal
                    </a>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    >
                        <i class="fas fa-save mr-2"></i> Başvuru Oluştur
                    </button>
                </div>
                
            </form>
        </div>
        
    </div>
</div>

<!-- Scripts -->
<script>
// Öğrenci Arama (Debounce)
let searchTimeout;
const studentSearch = document.getElementById('student_search');
const searchResults = document.getElementById('searchResults');

studentSearch.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        searchStudents(query);
    }, 300);
});

function searchStudents(query) {
    fetch(`<?= url('/api/students/search') ?>?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.students && data.students.length > 0) {
                showSearchResults(data.students);
            } else {
                searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">Öğrenci bulunamadı</div>';
                searchResults.classList.remove('hidden');
            }
        });
}

function showSearchResults(students) {
    searchResults.innerHTML = students.map(student => `
        <div class="p-3 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer border-b last:border-0 dark:border-gray-600" onclick="selectStudent(${student.id}, '${student.first_name} ${student.last_name}', '${student.class}')">
            <p class="text-sm font-medium text-gray-900 dark:text-white">${student.first_name} ${student.last_name}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Sınıf: ${student.class} | TC: ${student.tc_no || '-'}</p>
        </div>
    `).join('');
    searchResults.classList.remove('hidden');
}

function selectStudent(id, name, studentClass) {
    document.getElementById('student_id').value = id;
    document.getElementById('selectedStudentName').textContent = name;
    document.getElementById('selectedStudentClass').textContent = studentClass;
    document.getElementById('selectedStudent').classList.remove('hidden');
    document.getElementById('student_search').value = '';
    searchResults.classList.add('hidden');
}

function clearStudent() {
    document.getElementById('student_id').value = '';
    document.getElementById('selectedStudent').classList.add('hidden');
}

// Form Validation
document.getElementById('etutForm').addEventListener('submit', function(e) {
    const studentId = document.getElementById('student_id').value;
    if (!studentId) {
        e.preventDefault();
        alert('Lütfen bir öğrenci seçin!');
        return false;
    }
    
    // Saat kontrolü
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (startTime >= endTime) {
        e.preventDefault();
        alert('Bitiş saati başlangıç saatinden sonra olmalıdır!');
        return false;
    }
    
    // Submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Oluşturuluyor...';
});
</script>