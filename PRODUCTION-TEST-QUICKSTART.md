# PRODUCTION TEST - QUICK START

**Tarih:** October 25, 2025  
**Commit:** cb744dd3 (CRITICAL FIX)  
**Test:** Permission system bugları

---

## 1️⃣ Database Durumu Kontrol (ZORUNLU)

### Option A: MySQL / phpMyAdmin Üzerinden
1. phpMyAdmin'i aç
2. Database: `vildacgg_portalv2`
3. SQL editor'ü aç
4. Şu dosyayı kopyala ve çalıştır:
   - Dosya: `/database/PRODUCTION-PERMISSION-DIAGNOSTICS.sql`
   
5. **Sonuçları kontrol et:**
   - "PERMISSIONS PER ROLE" tablosunda Role 5 kaç permission var?
   - "MISSING PERMISSIONS" tablosu çok sayıda kayıt gösteriyor mu?

### Option B: PHP Script Üzerinden (Eğer MySQL erişimi yoksa)
```
URL: https://vldn.in/portalv2/public/test-permissions-debug.php
```

---

## 2️⃣ Eğer Role 5 = 0 Permission Varsa → SQL Çalıştır

**Şu SQL'i phpMyAdmin SQL editor'ünde çalıştır:**

```sql
-- Role 5 (Vice Principal) için tüm sayfaların izinlerini ekle
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
VALUES 
(5, 1, 1, 1, 1, 1),   -- Dashboard
(5, 2, 1, 1, 1, 1),   -- Users Management
(5, 3, 1, 1, 1, 1),   -- Roles Management
(5, 4, 1, 1, 1, 1),   -- Activities
(5, 5, 1, 1, 1, 1),   -- Activity Areas
(5, 6, 1, 1, 1, 1),   -- Activity Time Slots
(5, 7, 1, 1, 1, 1),   -- Time Slots
(5, 8, 1, 1, 1, 1),   -- Students
(5, 9, 1, 1, 1, 1),   -- Etüt Management
(5, 11, 1, 1, 1, 1),  -- Etüt Form Ayarları
(5, 12, 1, 1, 1, 1),  -- Ortaokul Etüt Başvuruları
(5, 13, 1, 1, 1, 1);  -- Lise Etüt Başvuruları
```

**Sonuç:** "11 rows inserted" görmeli

---

## 3️⃣ Form Test (Admin Panel)

1. **Admin Panel'e git:**
   - URL: `https://vldn.in/portalv2/admin`

2. **Roles sayfasına git:**
   - Sidebar: "Roller" → "Rolü Düzenle"

3. **Vice Principal seç:**
   - Listeden "Müdür Yardımcısı" veya "Vice Principal" seç
   - **"Düzenle" butonuna tıkla**

4. **Form Kontrolü:**
   ```
   ✅ Form boş mu?         → OLMAMALI (≥12 sayfa görülmeli)
   ✅ Etüt sayfaları var?   → "Etüt Form Ayarları", "Ortaokul Etüt", "Lise Etüt" görülmeli
   ✅ Checkboxlar var?      → 4 kolon: Görüntüleme, Ekleme, Düzenleme, Silme
   ```

5. **Bir sayfa seçip test et:**
   - Örneğin: "Dashboard" sayfası
   - Tüm 4 checkboxu işaretle (✓ View, ✓ Create, ✓ Edit, ✓ Delete)
   - **"Güncelle" butonuna tıkla**

6. **Sonuç Kontrol:**
   - Sayfa yenilendi mi?
   - Flash message: "Rol başarıyla güncellendi" göründü mü?
   - Page yenile (F5)
   - **Aynı checkboxlar hala işaretli mi?** → ✅ BAŞARILI

---

## 4️⃣ Detaylı Test (Optional)

### Test Kombinasyonları:
```
Test 1: Sadece "Görüntüleme" (View only)
Test 2: "Görüntüleme" + "Ekleme" (View + Create)
Test 3: Tüm 4'ü (Full access)
Test 4: KALDIRMA testi: Hepsini işaretle → Kaydet → 
        Sonra 1-2 tanesini UNcheck → Kaydet → Yenile
        → Unchecked olanlar hala UNCHECKED mi?
```

### Diğer Roller Test Et:
- [ ] Admin (Role 1)
- [ ] Teacher (Role 2)
- [ ] Secretary (Role 3)
- [ ] Principal (Role 4)

---

## 5️⃣ Database Verification (Final)

Değişiklikleri SQL'de kontrol et:
```sql
SELECT page_id, can_view, can_create, can_edit, can_delete
FROM vp_role_page_permissions
WHERE role_id = 5
ORDER BY page_id;
```

**Beklenen:** Son kaydettiğin kombinasyonlar görülmeli

---

## ✅ TEST BAŞARILI IŞARETLERI

- [x] Form boş değil, en az 12 sayfa görülüyor
- [x] Etüt sayfaları (11, 12, 13) görülüyor
- [x] Checkbox işaretlendi → Kaydet → Yenile → Hala işaretli
- [x] Checkbox işaretini kaldırdı → Kaydet → Yenile → Hala işaretsiz
- [x] Tüm roller için form çalışıyor
- [x] Database'de değişiklikler kaydediliyor

---

## ❌ HATA DURUMUNDA

### Hata 1: Form hala boş geliyor
```
Çözüm:
1. Database'de Role 5 permission kaydı var mı? 
   SQL: SELECT COUNT(*) FROM vp_role_page_permissions WHERE role_id = 5;
2. Eğer 0 ise, yukarıdaki INSERT SQL'i çalıştır
3. Page refresh: Ctrl+F5
```

### Hata 2: Kayıt yapılmıyor
```
Çözüm:
1. Browser console'u aç (F12 → Console)
2. JS hata var mı kontrol et
3. Network tab'ında POST request gösteriliyor mu?
4. Server error log kontrol et: storage/logs/error.log
```

### Hata 3: Unchecked box işaretli kalıyor
```
Çözüm:
1. Bu ESKI BUG'ı işaret ettirir
2. Code deployment kontrol: cb744dd3 commit'i push'u oldu mu?
3. Browser cache: Ctrl+Shift+Delete (Clear Cache)
4. Hard refresh: Ctrl+F5
```

### Hata 4: Etüt sayfaları görülmüyor
```
Çözüm:
1. vp_pages tablosunda etüt sayfaları var mı?
   SQL: SELECT * FROM vp_pages WHERE etut_type IS NOT NULL;
2. is_active = 1 mi?
3. Role 5'te o sayfaların permission kaydı var mı?
```

---

## 📋 TEST LOG TEMPLATE

```
TEST DATE: ___________
TESTER NAME: ___________
PORTAL VERSION: cb744dd3

1. DATABASE CHECK:
   - Total permission records: ___
   - Role 5 permissions: ___
   - Missing permissions: Yes/No

2. FORM TEST:
   - Form empty? Yes / No
   - Pages shown: ___ (should be ≥12)
   - Etüt pages visible? Yes / No
   
3. SAVE TEST:
   - Check 4 boxes, Save, Refresh, Still checked? Yes / No
   - Uncheck 1, Save, Refresh, Still unchecked? Yes / No
   
4. ALL ROLES TEST:
   - Admin (1): ✓ / ✗
   - Teacher (2): ✓ / ✗
   - Secretary (3): ✓ / ✗
   - Principal (4): ✓ / ✗
   - Vice Principal (5): ✓ / ✗

5. ISSUES FOUND:
   _______________________________________

6. CONCLUSION:
   PASS / FAIL
```

---

## 🚀 SONRAKI ADIMLAR (Test Başarılı Ise)

1. ✅ Confirm to dev team: "Permission system fixed and verified in production"
2. ✅ Monitor error logs for 24 hours
3. ✅ Notify users: Permission assignment now working
4. ✅ Close GitHub issue (if exists)

---

## 🔄 ROLLBACK (Acil Durum)

Eğer kritik bug çıkarsa:
```bash
git revert cb744dd3
# veya
git reset --hard HEAD~1
```

---

**Need help?** Check error log: `/storage/logs/error.log`

