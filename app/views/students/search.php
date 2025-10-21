<!-- Page Header -->
<div class="mb-8 text-center">
    <h1 class="text-4xl font-bold text-gray-900 mb-2">Öğrenci Ara</h1>
    <p class="text-gray-600">Öğrenci ismi, soyismi, TC kimlik no veya sınıfı ile arama yapın</p>
</div>

<!-- Search Box -->
<div class="max-w-3xl mx-auto mb-8">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input 
            type="text" 
            id="searchInput"
            placeholder="Öğrenci ara..." 
            class="block w-full pl-12 pr-4 py-4 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent shadow-lg"
            autocomplete="off"
        >
        <div id="searchLoading" class="hidden absolute inset-y-0 right-0 pr-4 flex items-center">
            <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Results -->
<div id="searchResults" class="max-w-5xl mx-auto">
    <!-- Initial State -->
    <div id="initialState" class="text-center py-12">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <p class="mt-4 text-lg text-gray-600">Aramaya başlamak için en az 2 karakter girin</p>
    </div>

    <!-- Results Container -->
    <div id="resultsContainer" class="hidden">
        <div class="mb-4 text-sm text-gray-600">
            <span id="resultCount">0</span> sonuç bulundu
        </div>
        
        <div class="space-y-3" id="resultsList"></div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-6 hidden">
            <div class="flex items-center justify-center gap-2">
                <button id="prevPage" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Önceki
                </button>
                <span id="pageInfo" class="text-sm text-gray-600"></span>
                <button id="nextPage" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sonraki
                </button>
            </div>
        </div>
    </div>

    <!-- No Results -->
    <div id="noResults" class="hidden text-center py-12">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-4 text-lg text-gray-600">Sonuç bulunamadı</p>
        <p class="mt-2 text-sm text-gray-500">Farklı bir arama terimi deneyin</p>
    </div>
</div>

<script>
let currentPage = 1;
let searchTimeout;
const searchInput = document.getElementById('searchInput');
const searchLoading = document.getElementById('searchLoading');
const initialState = document.getElementById('initialState');
const resultsContainer = document.getElementById('resultsContainer');
const resultsList = document.getElementById('resultsList');
const noResults = document.getElementById('noResults');
const resultCount = document.getElementById('resultCount');
const paginationContainer = document.getElementById('paginationContainer');
const prevPageBtn = document.getElementById('prevPage');
const nextPageBtn = document.getElementById('nextPage');
const pageInfo = document.getElementById('pageInfo');

// Debounce search
searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        showInitialState();
        return;
    }
    
    searchTimeout = setTimeout(() => {
        currentPage = 1;
        performSearch(query);
    }, 500);
});

function performSearch(query) {
    showLoading(true);
    
    fetch('<?= url('/api/students/search') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest' // AJAX olduğunu belirt
        },
        credentials: 'same-origin', // Cookie'leri gönder
        body: JSON.stringify({
            query: query,
            page: currentPage,
            csrf_token: document.querySelector('meta[name="csrf-token"]').content
        })
    })
    .then(response => {
        // Response kontrolü
        if (!response.ok) {
            throw new Error('Arama başarısız oldu');
        }
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new Error('Oturum süresi dolmuş olabilir. Lütfen sayfayı yenileyin.');
        }
        return response.json();
    })
    .then(data => {
        showLoading(false);
        displayResults(data);
    })
    .catch(error => {
        console.error('Error:', error);
        showLoading(false);
        alert(error.message || 'Bir hata oluştu. Lütfen tekrar deneyin.');
    });
}

function displayResults(data) {
    initialState.classList.add('hidden');
    
    if (data.data.length === 0) {
        resultsContainer.classList.add('hidden');
        noResults.classList.remove('hidden');
        return;
    }
    
    noResults.classList.add('hidden');
    resultsContainer.classList.remove('hidden');
    
    resultCount.textContent = data.pagination.total;
    
    const studentBaseUrl = '<?= url('/students') ?>';
    resultsList.innerHTML = data.data.map(student => `
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all duration-200 p-6 border border-gray-100">
            <!-- Başlık -->
            <h3 class="text-xl font-bold text-gray-900 mb-3">
                ${escapeHtml(student.first_name)} ${escapeHtml(student.last_name)}
            </h3>
            
            <!-- Öğrenci Bilgileri -->
            <div class="mb-4 space-y-1.5 text-sm text-gray-600">
                ${student.class ? `
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="font-medium">Sınıf:</span>
                        <span>${escapeHtml(student.class)}</span>
                    </p>
                ` : ''}
                ${student.tc_no ? `
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                        <span class="font-medium">TC:</span>
                        <span>${escapeHtml(student.tc_no)}</span>
                    </p>
                ` : ''}
                ${student.teacher_name ? `
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium">Öğretmen:</span>
                        <span>${escapeHtml(student.teacher_name)}</span>
                    </p>
                ` : ''}
            </div>
            
            <!-- İletişim Butonları -->
            <div class="flex flex-wrap gap-2 mb-4">
                ${student.father_phone ? `
                    <a href="tel:${escapeHtml(student.father_phone)}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Baba Ara</span>
                    </a>
                ` : ''}
                ${student.mother_phone ? `
                    <a href="tel:${escapeHtml(student.mother_phone)}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-pink-500 hover:bg-pink-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Anne Ara</span>
                    </a>
                ` : ''}
                ${student.teacher_phone ? `
                    <a href="tel:${escapeHtml(student.teacher_phone)}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Öğretmeni Ara</span>
                    </a>
                ` : ''}
            </div>
            
            <!-- Detay Linki -->
            <a href="${studentBaseUrl}/${student.id}" 
               class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium group">
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                <span>Öğrenci Detayı İçin Tıklayınız</span>
            </a>
        </div>
    `).join('');
    
    // Pagination
    if (data.pagination.last_page > 1) {
        paginationContainer.classList.remove('hidden');
        pageInfo.textContent = `Sayfa ${data.pagination.current_page} / ${data.pagination.last_page}`;
        prevPageBtn.disabled = data.pagination.current_page <= 1;
        nextPageBtn.disabled = data.pagination.current_page >= data.pagination.last_page;
    } else {
        paginationContainer.classList.add('hidden');
    }
}

function showLoading(show) {
    if (show) {
        searchLoading.classList.remove('hidden');
    } else {
        searchLoading.classList.add('hidden');
    }
}

function showInitialState() {
    initialState.classList.remove('hidden');
    resultsContainer.classList.add('hidden');
    noResults.classList.add('hidden');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Pagination handlers
prevPageBtn.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        performSearch(searchInput.value);
    }
});

nextPageBtn.addEventListener('click', () => {
    currentPage++;
    performSearch(searchInput.value);
});

// Focus search input on load
searchInput.focus();
</script>