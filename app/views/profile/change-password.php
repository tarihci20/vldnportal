<?php
/**
 * Şifre Değiştirme Sayfası
 * Vildan Portal
 */

use Core\Auth;

$pageTitle = 'Şifre Değiştir';
$user = Auth::user();
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
                        <a href="<?= url('/profile') ?>" class="text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">Profil</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Şifre Değiştir</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Şifre Değiştir</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Hesap güvenliğiniz için güçlü bir şifre kullanın
            </p>
        </div>
        
        <!-- Alert Messages -->
        <div id="alertContainer" class="mb-4"></div>
        
        <div class="max-w-2xl">
            
            <!-- Password Requirements Card -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Güçlü Şifre Gereksinimleri
                </h3>
                <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-1 ml-6 list-disc">
                    <li>En az 8 karakter uzunluğunda olmalı</li>
                    <li>En az bir büyük harf içermeli (A-Z)</li>
                    <li>En az bir küçük harf içermeli (a-z)</li>
                    <li>En az bir rakam içermeli (0-9)</li>
                    <li>Özel karakter kullanılması önerilir (!@#$%)</li>
                </ul>
            </div>
            
            <!-- Change Password Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <form id="passwordForm" method="POST" action="<?= url('/profile/update-password') ?>" class="p-6">
                    
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <!-- Mevcut Şifre -->
                    <div class="mb-6">
                        <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Mevcut Şifre <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Mevcut şifrenizi girin"
                                required
                                autocomplete="current-password"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('current_password')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 dark:text-gray-400"
                            >
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Yeni Şifre -->
                    <div class="mb-6">
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Yeni Şifre <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Yeni şifrenizi girin"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('new_password')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 dark:text-gray-400"
                            >
                                <i class="fas fa-eye" id="new_password_icon"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                <div id="passwordStrength" class="h-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p id="passwordStrengthText" class="text-xs mt-1 text-gray-500 dark:text-gray-400"></p>
                        </div>
                    </div>
                    
                    <!-- Yeni Şifre Tekrar -->
                    <div class="mb-6">
                        <label for="new_password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Yeni Şifre (Tekrar) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="new_password_confirmation" 
                                name="new_password_confirmation" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Yeni şifrenizi tekrar girin"
                                required
                                autocomplete="new-password"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('new_password_confirmation')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 dark:text-gray-400"
                            >
                                <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                            </button>
                        </div>
                        <p id="passwordMatchText" class="text-xs mt-1"></p>
                    </div>
                    
                    <!-- Form Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a 
                            href="<?= url('/profile') ?>" 
                            class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700"
                        >
                            <i class="fas fa-times mr-2"></i> İptal
                        </a>
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50"
                            disabled
                        >
                            <i class="fas fa-key mr-2"></i> Şifreyi Güncelle
                        </button>
                    </div>
                    
                </form>
            </div>
            
            <!-- Security Tips -->
            <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-yellow-900 dark:text-yellow-300 mb-2">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Güvenlik İpuçları
                </h3>
                <ul class="text-sm text-yellow-800 dark:text-yellow-400 space-y-1 ml-6 list-disc">
                    <li>Şifrenizi kimseyle paylaşmayın</li>
                    <li>Farklı hesaplar için farklı şifreler kullanın</li>
                    <li>Şifrenizi düzenli olarak değiştirin</li>
                    <li>Kolay tahmin edilebilir şifreler kullanmayın</li>
                </ul>
            </div>
            
        </div>
        
    </div>
</div>

<!-- Scripts -->
<script>
// Toggle Password Visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password Strength Checker
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strength = calculatePasswordStrength(password);
    updatePasswordStrengthUI(strength);
    checkPasswordMatch();
});

function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 10;
    if (/[a-z]/.test(password)) strength += 15;
    if (/[A-Z]/.test(password)) strength += 15;
    if (/[0-9]/.test(password)) strength += 15;
    if (/[^a-zA-Z0-9]/.test(password)) strength += 20;
    
    return Math.min(strength, 100);
}

function updatePasswordStrengthUI(strength) {
    const bar = document.getElementById('passwordStrength');
    const text = document.getElementById('passwordStrengthText');
    
    bar.style.width = strength + '%';
    
    if (strength < 30) {
        bar.className = 'h-full transition-all duration-300 bg-red-500';
        text.textContent = 'Çok zayıf';
        text.className = 'text-xs mt-1 text-red-600 dark:text-red-400';
    } else if (strength < 50) {
        bar.className = 'h-full transition-all duration-300 bg-orange-500';
        text.textContent = 'Zayıf';
        text.className = 'text-xs mt-1 text-orange-600 dark:text-orange-400';
    } else if (strength < 75) {
        bar.className = 'h-full transition-all duration-300 bg-yellow-500';
        text.textContent = 'Orta';
        text.className = 'text-xs mt-1 text-yellow-600 dark:text-yellow-400';
    } else {
        bar.className = 'h-full transition-all duration-300 bg-green-500';
        text.textContent = 'Güçlü';
        text.className = 'text-xs mt-1 text-green-600 dark:text-green-400';
    }
}

// Password Match Checker
document.getElementById('new_password_confirmation').addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const matchText = document.getElementById('passwordMatchText');
    const submitBtn = document.getElementById('submitBtn');
    
    if (confirmPassword.length === 0) {
        matchText.textContent = '';
        submitBtn.disabled = true;
        return;
    }
    
    if (newPassword === confirmPassword) {
        matchText.textContent = 'Şifreler eşleşiyor ✓';
        matchText.className = 'text-xs mt-1 text-green-600 dark:text-green-400';
        submitBtn.disabled = false;
    } else {
        matchText.textContent = 'Şifreler eşleşmiyor ✗';
        matchText.className = 'text-xs mt-1 text-red-600 dark:text-red-400';
        submitBtn.disabled = true;
    }
}

// Form Validation
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        showAlert('error', 'Şifreler eşleşmiyor!');
        return false;
    }
    
    if (newPassword.length < 8) {
        e.preventDefault();
        showAlert('error', 'Şifre en az 8 karakter olmalıdır!');
        return false;
    }
    
    // Submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Güncelleniyor...';
});

function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    const alertClass = type === 'success' 
        ? 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800' 
        : 'bg-red-100 text-red-800 border-red-300 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800';
    
    alertContainer.innerHTML = `
        <div class="${alertClass} border rounded-lg p-4 mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}
</script>