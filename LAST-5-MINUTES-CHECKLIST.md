# 🚀 PRODUCTION - SON 5 DAKİKA CHECKLIST

Debug script URL çalışmıyor mu? Sorun değil! Şu stepleri takip et:

---

## ADIM 1: Kodu Deploy Et (2 dakika)
```bash
cd /home/vildacgg/vldn.in/portalv2
git pull origin main
```
**Kontrol:** Hata vermedi mi? Commit: cb7d7e02 veya daha yeni?

---

## ADIM 2: MySQL Database'i Kontrol Et (1 dakika)

phpMyAdmin'i aç:
1. Database: `vildacgg_portalv2`
2. SQL tab'ı aç
3. Şu komutu çalıştır:

```sql
SELECT role_id, COUNT(*) as cnt FROM vp_role_page_permissions 
GROUP BY role_id ORDER BY role_id;
```

**SONUÇ OKU:**
- Role 5 satırında kaç yazıyor? → `___ permissions`
- 0 ise → **Aşağıdaki SQL'i çalıştır**
- 10+ ise → **GEÇTI! Adım 3'e git**

---

## ADIM 2B: Eğer Role 5 = 0 İse (Setup SQL)

Şu SQL'i çalıştır:

```sql
INSERT INTO vp_role_page_permissions (role_id, page_id, can_view, can_create, can_edit, can_delete)
VALUES 
(5, 1, 1, 1, 1, 1), (5, 2, 1, 1, 1, 1), (5, 3, 1, 1, 1, 1), (5, 4, 1, 1, 1, 1),
(5, 5, 1, 1, 1, 1), (5, 6, 1, 1, 1, 1), (5, 7, 1, 1, 1, 1), (5, 8, 1, 1, 1, 1),
(5, 9, 1, 1, 1, 1), (5, 11, 1, 1, 1, 1), (5, 12, 1, 1, 1, 1), (5, 13, 1, 1, 1, 1);
```

**SONUÇ:** "11 rows inserted" görmeli

---

## ADIM 3: Admin Panel Testi (2 dakika)

1. **URL:** `https://vldn.in/portalv2/admin`
2. **Sidebar:** Roller → "Müdür Yardımcısı" → "Düzenle"
3. **Kontrol:** Form boş mu?
   - ❌ Boş → **HATA! Adım 2'yi tekrar yap**
   - ✅ Dolu (12+ sayfa) → **BAŞARILI!**

---

## ADIM 4: Permission Save Testi (1 dakika)

1. Form'da "Dashboard" satırını bul
2. 4 checkbox'u işaretle (View ✓, Create ✓, Edit ✓, Delete ✓)
3. "Güncelle" butonuna tıkla
4. Sayfa yenilendi mi?
5. Checkboxlar hala işaretli mi?
   - ❌ Tidak → **HATA**
   - ✅ Evet → **BAŞARILI!**

---

## ADIM 5: Permission Removal Testi (1 dakika)

1. Form'da "Dashboard" satırına geri dön
2. 1 checkbox'u UNCECK (örneğin Delete)
3. "Güncelle" tıkla
4. Sayfa yenilendi
5. Delete checkbox unchecked kaldı mı?
   - ❌ Hala checked → **OLD BUG! Redeploy code**
   - ✅ Unchecked → **BAŞARILI!**

---

## SONUÇ

Hepsi ✅ ise:

🎉 **PERMISSION SYSTEM FIXED!**

Tüm roller için çalışacak, etüt sayfaları görülecek, izin atama/kaldırma yapılabilecek.

---

**Sorun olursa:**

1. Error log: `/storage/logs/error.log`
2. Git status: `git log --oneline -1`
3. Database: Yukarıdaki SQL'i tekrar çalıştır

