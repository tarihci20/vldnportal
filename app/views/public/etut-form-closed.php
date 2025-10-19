<div class="text-center space-y-6">
    <!-- Icon -->
    <div class="flex justify-center">
        <div class="bg-red-100 rounded-full p-6">
            <i class="fas fa-lock text-red-500 text-6xl"></i>
        </div>
    </div>
    
    <!-- Başlık -->
    <h2 class="text-3xl font-bold text-gray-900">
        <?= htmlspecialchars($title) ?>
    </h2>
    
    <!-- Mesaj -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 text-left">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-lg text-gray-700 leading-relaxed">
                    <?= nl2br(htmlspecialchars($message)) ?>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Bilgi -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-800">
            <i class="fas fa-info-circle mr-2"></i>
            Form açıldığında bu sayfadan başvuru yapabilirsiniz. 
            Lütfen düzenli olarak kontrol ediniz.
        </p>
    </div>
    
    <!-- Sayfayı Yenile Butonu -->
    <div class="pt-4">
        <button 
            type="button"
            onclick="window.location.reload()"
            class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition duration-200"
        >
            <i class="fas fa-sync-alt mr-2"></i>
            Sayfayı Yenile
        </button>
    </div>
</div>
