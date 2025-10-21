<?php
/**
 * Web Routes - URL Tanımlamaları
 * Vildan Portal - Okul Yönetim Sistemi
 */

// ============================================
// AUTH ROUTES (Kimlik Doğrulama)
// ============================================

// Giriş sayfası
$router->get('/', 'AuthController@loginPage', 'home');
$router->get('/login', 'AuthController@loginPage', 'login');
$router->post('/login', 'AuthController@login');

// Şifremi unuttum
$router->get('/forgot-password', 'AuthController@forgotPasswordPage');
$router->post('/forgot-password', 'AuthController@forgotPassword');

// Şifre sıfırlama
$router->get('/reset-password/{token}', 'AuthController@resetPasswordPage');
$router->post('/reset-password', 'AuthController@resetPassword');

// Google OAuth
$router->get('/auth/google', 'AuthController@googleRedirect');
$router->get('/auth/google/callback', 'AuthController@googleCallback');

// Çıkış
$router->get('/logout', 'AuthController@logout', 'logout');

// ============================================
// DASHBOARD
// ============================================

$router->get('/dashboard', 'DashboardController@index', 'dashboard');

// ============================================
// ÖĞRENCİ ROUTES
// ============================================

// Öğrenci arama (Ana sayfa)
$router->get('/student-search', 'StudentController@search', 'student.search');
$router->get('/api/students/search', 'StudentController@ajaxSearch');
$router->post('/api/students/search', 'StudentController@ajaxSearch');

// Öğrenci listesi ve CRUD
$router->get('/students', 'StudentController@index', 'students.index');

// ÖNEMLİ: Statik route'lar dinamik route'lardan ÖNCE tanımlanmalı!
$router->get('/students/create', 'StudentController@create', 'students.create');
$router->get('/students/export/excel', 'StudentController@exportExcel', 'students.export');
$router->get('/students/download/template', 'StudentController@downloadTemplate', 'students.template');
$router->post('/students/import/excel', 'StudentController@importExcel', 'students.import');
$router->post('/students/delete-all', 'StudentController@deleteAll');

// Yeni basit öğrenci ekleme sistemi
$router->get('/simple-students/create', 'SimpleStudentController@create', 'simple.students.create');
$router->post('/simple-students', 'SimpleStudentController@store', 'simple.students.store');

// Dinamik route'lar (ID ile) en sonda!
$router->post('/students', 'StudentController@store');
$router->get('/students/{id}', 'StudentController@detail', 'students.detail');
$router->get('/students/{id}/edit', 'StudentController@edit', 'students.edit');
$router->post('/students/{id}', 'StudentController@update');
$router->post('/students/{id}/delete', 'StudentController@delete');

// ============================================
// ETKİNLİK ROUTES
// ============================================

// Etkinlik listesi
$router->get('/activities', 'ActivityController@index', 'activities.index');

// ÖNEMLİ: Statik route'lar dinamik route'lardan ÖNCE tanımlanmalı!
$router->get('/activities/current', 'ActivityController@current', 'activities.current');
$router->get('/activities/past', 'ActivityController@past', 'activities.past');
$router->get('/activities/create', 'ActivityController@create', 'activities.create');
$router->post('/activities/bulk-delete', 'ActivityController@bulkDelete');

// Dinamik route'lar (ID ile) en sonda!
$router->post('/activities', 'ActivityController@store');
$router->get('/activities/{id}', 'ActivityController@detail', 'activities.detail');
$router->get('/activities/{id}/edit', 'ActivityController@edit', 'activities.edit');
$router->post('/activities/{id}', 'ActivityController@update');
$router->post('/activities/{id}/delete', 'ActivityController@delete');

// Çakışma kontrolü
$router->post('/api/activities/check-conflict', 'ActivityController@checkConflict');

// Yeni Rezervasyon Sistemi API'leri
$router->post('/api/activities/cakisma-kontrol', 'ActivityController@cakismaKontrolAPI');
$router->post('/api/activities/coklu-cakisma-kontrol', 'ActivityController@cokluCakismaKontrolAPI');
$router->post('/api/activities/tarih-dizisi-olustur', 'ActivityController@tarihDizisiOlusturAPI');

// Boş saat dilimlerini getir
$router->get('/api/activities/available-slots', 'ActivityController@getAvailableTimeSlots');

// Saat dilimi çakışma kontrolü
$router->post('/api/activities/check-timeslots-conflict', 'ActivityController@checkTimeSlotsConflict');

// Tekrar kuralları
$router->post('/api/activities/recurring', 'ActivityController@createRecurring');

// ============================================
// ETKİNLİK ALANLARI ROUTES
// ============================================

$router->get('/activity-areas', 'ActivityAreaController@index', 'activity-areas.index');
$router->get('/activity-areas/create', 'ActivityAreaController@create');
$router->post('/activity-areas', 'ActivityAreaController@store');
$router->get('/activity-areas/{id}/edit', 'ActivityAreaController@edit');
$router->post('/activity-areas/{id}', 'ActivityAreaController@update');
$router->put('/activity-areas/{id}', 'ActivityAreaController@update');
$router->get('/activity-areas/{id}/delete', 'ActivityAreaController@delete');
$router->post('/activity-areas/{id}/delete', 'ActivityAreaController@delete');

// ============================================
// ETÜT ROUTES (PUBLIC - GİRİŞ GEREKTİRMEDEN)
// ============================================

// Public formlar (giriş gerektirmez)
$router->get('/etut/ortaokul-basvuru', 'PublicEtutController@ortaokul', 'etut.ortaokul.public');
$router->get('/etut/lise-basvuru', 'PublicEtutController@lise', 'etut.lise.public');
$router->post('/etut/public/submit', 'PublicEtutController@submit');

// Etüt Alanları (giriş gerektirir)
$router->get('/etut', 'EtutController@index', 'etut.index'); // Ana sayfa (kare kartlar)
$router->get('/etut/ortaokul', 'EtutController@ortaokul', 'etut.ortaokul'); // Ortaokul listesi
$router->get('/etut/lise', 'EtutController@lise', 'etut.lise'); // Lise listesi

// ÖNEMLİ: Statik route'lar dinamik route'lardan ÖNCE tanımlanmalı!
$router->get('/etut/form', 'EtutController@form', 'etut.form');
$router->get('/etut/create', 'EtutController@create', 'etut.create');

// Dinamik route'lar (ID ile) en sonda!
$router->post('/etut', 'EtutController@store');
$router->get('/etut/{id}', 'EtutController@detail');
$router->get('/etut/{id}/edit', 'EtutController@edit');
$router->post('/etut/{id}', 'EtutController@update');
$router->post('/etut/{id}/delete', 'EtutController@delete');

// ============================================
// ADMIN PANEL ROUTES
// ============================================

// Kullanıcı yönetimi
$router->get('/admin/users', 'AdminController@users', 'admin.users');
$router->get('/admin/users/create', 'AdminController@createUser');
$router->post('/admin/users/store', 'AdminController@storeUser');
$router->post('/admin/users', 'AdminController@storeUser'); // Alternative route
$router->get('/admin/users/{id}/edit', 'AdminController@editUser');
$router->post('/admin/users/{id}', 'AdminController@updateUser');
$router->post('/admin/users/delete', 'AdminController@deleteUser');
$router->get('/admin/users/permissions', 'AdminController@getUserPermissionsAjax');

// Rol yönetimi
$router->get('/admin/roles', 'AdminController@roles', 'admin.roles');
$router->get('/admin/roles/permissions', 'AdminController@getRolePermissions');
$router->post('/admin/roles/update-permissions', 'AdminController@updateRolePermissions');

// Etüt form ayarları
$router->get('/admin/etut-settings', 'AdminController@etutSettings', 'admin.etut-settings');
// Per-form settings (e.g. /admin/etut-settings/ortaokul)
$router->get('/admin/etut-settings/{form_type}', 'AdminController@etutSettings', 'admin.etut-settings.form');
$router->post('/admin/etut/toggle', 'AdminController@toggleEtutForm');
$router->post('/admin/etut/update-settings', 'AdminController@updateEtutFormSettings');

// Etkinlik türleri
$router->get('/admin/activity-types', 'ActivityTypeController@index', 'admin.activity-types');
$router->post('/admin/activity-types', 'ActivityTypeController@store');
$router->post('/admin/activity-types/{id}', 'ActivityTypeController@update');
$router->post('/admin/activity-types/{id}/delete', 'ActivityTypeController@delete');

// İzin verilen Google hesapları
$router->get('/admin/google-emails', 'GoogleEmailController@index', 'admin.google-emails');
$router->post('/admin/google-emails', 'GoogleEmailController@store');
$router->post('/admin/google-emails/{id}/delete', 'GoogleEmailController@delete');

// Sistem ayarları
$router->get('/admin/settings', 'AdminController@settings', 'admin.settings');
$router->post('/admin/settings/change-password', 'AdminController@changePassword');
$router->post('/admin/settings/add-user', 'AdminController@addUser');
$router->post('/admin/settings/delete-all-students', 'AdminController@deleteAllStudents');

// Saat Dilimi Yönetimi
$router->get('/admin/time-slots', 'TimeSlotController@index', 'admin.time-slots');
$router->post('/admin/time-slots/generate', 'TimeSlotController@generateSlots');
$router->post('/admin/time-slots/update', 'TimeSlotController@update');
$router->post('/admin/time-slots/delete', 'TimeSlotController@delete');
$router->post('/admin/time-slots/area', 'TimeSlotController@setAreaTimeSlot');
$router->post('/admin/time-slots', 'TimeSlotController@store');

// Activity logs
$router->get('/admin/logs', 'LogController@index', 'admin.logs');
$router->get('/admin/logs/{id}', 'LogController@detail');

// ============================================
// PROFİL ROUTES
// ============================================

$router->get('/profile', 'ProfileController@index', 'profile');
$router->post('/profile/change-password', 'ProfileController@changePassword');
$router->get('/profile/change-password', 'ProfileController@index');

// ============================================
// API ROUTES (AJAX)
// ============================================

// Dashboard API
$router->get('/api/dashboard/stats', 'DashboardController@stats');
$router->get('/api/dashboard/weekly-chart', 'DashboardController@weeklyChart');

// Öğrenci API
$router->get('/api/students/{id}', 'StudentController@apiGet');
$router->get('/api/students/class/list', 'StudentController@classList');

// Etkinlik API
$router->get('/api/activities/areas', 'ActivityAreaController@apiList');
$router->get('/api/time-slots', 'TimeSlotController@apiList');
$router->get('/api/activities/area-time-slots/{id}', 'ActivityController@getAreaTimeSlots');

// ============================================
// TEMA DEĞİŞTİRME
// ============================================

$router->post('/api/theme/toggle', 'ThemeController@toggle');

// ============================================
// 404 - CATCH ALL
// ============================================
// Router otomatik olarak 404 döndürür, ekstra bir şey yapmaya gerek yok