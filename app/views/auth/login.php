<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Giriş Yap' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-card {
            background: rgba(255,255,255,0.85);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(8px);
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.18);
        }
        .logo-shadow {

            <html lang="tr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?= $title ?? 'Giriş Yap' ?></title>
                <script src="https://cdn.tailwindcss.com"></script>
                <style>
                    .gradient-bg {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    }
                    .glass-card {
                        background: rgba(255,255,255,0.85);
                        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
                        backdrop-filter: blur(8px);
                        border-radius: 24px;
                        border: 1px solid rgba(255,255,255,0.18);
                    }
                    .logo-shadow {
                        box-shadow: 0 4px 24px 0 rgba(102,126,234,0.25);
                    }
                </style>
            </head>
            <body class="gradient-bg min-h-screen flex items-center justify-center p-4">
                <div class="w-full max-w-md mx-auto">
                    <!-- Logo ve Başlık -->
                    <div class="text-center mb-8">
                        <div class="bg-white w-24 h-24 rounded-full mx-auto mb-4 flex items-center justify-center logo-shadow overflow-hidden">
                            <img src="<?= asset('img/logo.png') ?>" alt="Logo" class="w-20 h-20 object-contain">
                        </div>
                        <h1 class="text-4xl font-extrabold text-white mb-2 tracking-tight drop-shadow">Vildan Portal</h1>
                    </div>
                    <!-- Login Card -->
                    <div class="glass-card p-10">
                        <?php $flash = getFlashMessage(); if ($flash): ?>
                            <?php if ($flash['type'] === 'success'): ?>
                                <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200 flex items-center gap-3">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                    </svg>
                                    <span class="font-semibold">Giriş başarılı</span>
                                </div>
                            <?php else: ?>
                                <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-800 border-red-200 border flex items-center gap-3">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9l-6 6M9 9l6 6" />
                                    </svg>
                                    <span class="font-semibold"><?= e($flash['message']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <form method="POST" action="<?= url('/login') ?>" class="space-y-7">
                            <?= csrfField() ?>
                            <!-- Kullanıcı Adı -->
                            <div>
                                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kullanıcı Adı veya E-posta
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="username" 
                                        id="username" 
                                        required
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent bg-gray-50"
                                        value="<?= old('username') ?>"
                                    >
                                </div>
                            </div>
                            <!-- Şifre -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Şifre
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        required
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent bg-gray-50"
                                        placeholder="••••••••"
                                    >
                                </div>
                            </div>
                            <!-- Beni Hatırla ve Şifremi Unuttum -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="remember" 
                                        id="remember"
                                        value="1"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    >
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                                        Beni hatırla
                                    </label>
                                </div>
                                <a href="<?= url('/forgot-password') ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Şifremi unuttum
                                </a>
                            </div>
                            <!-- Giriş Butonu -->
                            <button 
                                type="submit" 
                                class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-semibold text-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150"
                            >
                                Giriş Yap
                            </button>
                            <?php if ($google_enabled ?? false): ?>
                                <!-- Divider -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white text-gray-500">veya</span>
                                    </div>
                                </div>
                                <!-- Google ile Giriş -->
                                <a 
                                    href="<?= url('/auth/google') ?>" 
                                    class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition duration-150 shadow"
                                >
                                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    Google ile Giriş
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <!-- Footer -->
                    <div class="text-center mt-6 text-white text-sm drop-shadow">
                        <p>&copy; <?= date('Y') ?> Vildan Portal. Tüm hakları saklıdır.</p>
                    </div>
                </div>
            </body>
            </html>