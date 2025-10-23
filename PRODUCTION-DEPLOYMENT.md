#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
PRODUCTION DEPLOYMENT INSTRUCTIONS
Vildan Portal v2 - Permission System & Shared Teacher Account

ADIM 1: Production'da Git Pull Yapın
=====================================
SSH ile production sunucusuna bağlanın:
cd /home/vildacgg/public_html/portalv2
git pull origin main

ADIM 2: Shared Teacher Account'ı Setup Edin
============================================
Aşağıdaki SQL scriptini production phpMyAdmin veya MySQL CLI üzerinden çalıştırın:

    mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < setup-teacher-account.sql
    
VEYA phpMyAdmin'de:
1. Database'i seçin: vildacgg_portalv2
2. SQL tab'ına tıklayın
3. setup-teacher-account.sql içeriğini yapıştırın
4. Execute butonuna tıklayın

ADIM 3: Test Edin - Giriş Yapın
===============================
Admin Hesabı:
- URL: https://vldn.in/portalv2
- Kullanıcı: tarihci20
- Şifre: aci2406717

Teacher (Öğretmen) Hesabı:
- Kullanıcı: teacher
- Şifre: 12345678

ADIM 4: Teacher Hesabıyla Kontrol Edin
========================================
Teacher olarak login yapıp aşağıdakileri doğrulayın:

1. ❌ Dashboard açılmAMALI (403 Forbidden veya redirect)
   - URL: https://vldn.in/portalv2/dashboard
   - Beklenen: Hata veya dashboard redirect

2. ✅ Öğrenci Bilgileri açılmalı
   - URL: https://vldn.in/portalv2/students
   - Beklenen: Öğrenci listesi görülmeli

3. ❌ Etkinlikler sayfası açılmAMALI
   - URL: https://vldn.in/portalv2/activities
   - Beklenen: Hata veya redirect

4. ✅ Sidebar'da sadece "Öğrenci Bilgileri" menüsü görülmeli
   - Sol menüde diğer sayfalar görünmEMELİ

5. ✅ 70 Öğretmen Aynı Hesabı Kullanabilir
   - 70 öğretmen aynı username/password ile aynı anda login yapabilir
   - handleConcurrentSessions() zaten teacher rolü için multi-session destekliyor

ADIM 5: Teacher Şifresini Değiştirin (Opsiyonel)
================================================
Daha güvenli bir şifre kullanmak istiyorsanız:
1. Admin olarak login yapın
2. Admin > Kullanıcılar'a gidin
3. Teacher hesabını bulup şifresini değiştirin

ADIM 6: Cleanup
===============
Production'da debug dosyalarını kaldırın:
- rm /home/vildacgg/public_html/portalv2/debug-import.php
- rm /home/vildacgg/public_html/portalv2/create-admin.php

SORUN GIDERICILER
=================

Sorun: Teacher Dashboard'u görebiliyor
Çözüm: 
- vp_role_page_permissions'da dashboard row'u kontrol edin
- Teacher role_id=3 için page_id=1 (dashboard) can_view=0 olmalı
- SQL: SELECT * FROM vp_role_page_permissions WHERE role_id=3;

Sorun: Teacher Students sayfasını göremyor
Çözüm:
- vp_role_page_permissions'da students row'u kontrol edin
- Teacher role_id=3 için page_id=2 (students) can_view=1 olmalı
- SQL: SELECT * FROM vp_role_page_permissions WHERE role_id=3 AND page_id=2;

Sorun: Teacher login yapamıyor
Çözüm:
- vp_users tablosunda teacher hesabını kontrol edin
- Status: active olmalı
- role_id: 3 (teacher) olmalı
- SQL: SELECT * FROM vp_users WHERE username='teacher';

Sorun: Permission middleware hatasız ama izin yok
Çözüm:
- error_log dosyasını kontrol edin
- PermissionMiddleware debug logs'u kontrol edin
- hasPermission() helper'ı kontrol edin

MULTIPL SESSION TESTI
====================
70 öğretmen'in aynı hesapla login yapabilmesi test edilsin:
1. Browser 1'den login yapın: teacher / 12345678
2. Browser 2'den veya Incognito'dan yeniden login yapın: teacher / 12345678
3. Her iki session'da aktif olmalı (önceki session sonlanmaz)
4. Sidebar'da Öğrenci Bilgileri menüsü görülmeli
5. Dashboard linkine tıklandığında hata veya redirect olmalı

NOTES
=====
- Permission system: Role-Based Access Control (RBAC)
- Teacher hesabı: Shared account (70 öğretmen paylaşıyor)
- Concurrent Sessions: Teacher rolü çoklu simultaneous session destekliyor
- Page Keys: dashboard, students, activities, activity_areas, etut, admin, users
- Permissions: can_view, can_create, can_edit, can_delete
"""
print(__doc__)
