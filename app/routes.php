<?php
/**
 * Vildan Portal - Route Tanımlamaları
 * Path: /home/vildacgg/vldn.in/portalv2/app/routes.php
 *
 * Düzeltme Notu: Ana sayfa rotası ('/') geçici olarak basit bir metin döndürecek
 * şekilde güncellendi. Bu, router'ın kök yolu doğru bir şekilde eşleştirip
 * eşleştirmediğini kontrol etmemizi sağlayacak.
 */

// Test Routes (Geçici - Kurulum için)
$router->get('/test', 'Test@index', 'test.index');
$router->get('/test/info', 'Test@info', 'test.info');
$router->get('/test/db', 'Test@db', 'test.db');
$router->get('/test/helpers', 'Test@helpers', 'test.helpers');

// Ana sayfa (test sayfasına yönlendir - geçici)
// GEÇİCİ DÜZELTME: Router'ın kök yolu eşleştirip eşleştirmediğini görmek için
$router->get('/', function() {
 	echo "<h1>Vildan Portal Yüklendi! (Router OK)</h1>";
    // Normalde redirect('test'); olacaktı
}, 'home');

/*
// PRODUCTION İÇİN (Test tamamlandıktan sonra aktif edin):

// Ana sayfa (login'e yönlendir)
$router->get('/', function() {
    redirect('login');
}, 'home');

// Auth Routes (Guest middleware - giriş yapmamış kullanıcılar için)
$router->group(['prefix' => '/auth', 'middleware' => ['Guest']], function($router) {
    // Login
    $router->get('/login', 'Auth@showLogin', 'login');
    $router->post('/login', 'Auth@login', 'login.post');
    
    // Google OAuth
    $router->get('/google', 'Auth@googleRedirect', 'auth.google');
    $router->get('/google/callback', 'Auth@googleCallback', 'auth.google.callback');
    
    // Şifre sıfırlama
    $router->get('/forgot-password', 'Auth@showForgotPassword', 'password.forgot');
    $router->post('/forgot-password', 'Auth@forgotPassword', 'password.forgot.post');
    $router->get('/reset-password/{token}', 'Auth@showResetPassword', 'password.reset');
    $router->post('/reset-password', 'Auth@resetPassword', 'password.reset.post');
});

// Logout (Auth middleware gerekli)
$router->post('/logout', 'Auth@logout', 'logout')->middleware('Auth');

// Dashboard (Auth middleware gerekli)
$router->group(['prefix' => '/dashboard', 'middleware' => ['Auth']], function($router) {
    $router->get('/', 'Dashboard@index', 'dashboard');
    $router->get('/stats', 'Dashboard@stats', 'dashboard.stats');
});

// Öğrenci Arama (Auth middleware)
$router->group(['prefix' => '/student-search', 'middleware' => ['Auth']], function($router) {
    $router->get('/', 'Student@search', 'student.search');
    $router->post('/ajax', 'Student@ajaxSearch', 'student.search.ajax');
});

// Öğrenci İşlemleri (Auth middleware)
$router->group(['prefix' => '/students', 'middleware' => ['Auth']], function($router) {
    $router->get('/', 'Student@index', 'students.index');
    $router->get('/create', 'Student@create', 'students.create')->middleware('Role:1,2');
    $router->post('/store', 'Student@store', 'students.store')->middleware('Role:1,2');
    $router->get('/{id}', 'Student@show', 'students.show');
    $router->get('/{id}/edit', 'Student@edit', 'students.edit')->middleware('Role:1,2');
    $router->post('/{id}/update', 'Student@update', 'students.update')->middleware('Role:1,2');
    $router->post('/{id}/delete', 'Student@delete', 'students.delete')->middleware('Role:1');
});

// ... diğer route'lar
*/
