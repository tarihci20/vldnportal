# PRODUCTION DEPLOYMENT KONTROL LİSTESİ

## ❌ SORUN: Git pull yapılmamış!

emine kullanıcısı sidebar'da sadece 3 menü görüyor:
- Ana Sayfa ✅
- Öğrenci Bilgileri ✅
- Etkinlikler ✅

**EKSIK**: Etkinlik Alanları, Etüt, Raporlar, Admin menüleri

## ✅ DATABASE HAZIR
- ✅ 11 temiz sayfa eklendi
- ✅ Vice Principal (Role 5) → 11 sayfa FULL ACCESS
- ✅ Duplicate kayıtlar temizlendi

## ❌ CODE GÜNCELLEME EKSİK
Production'da eski sidebar kodu çalışıyor!

---

## 🚀 HEMEN YAPIN:

### 1. SSH ile production'a bağlanın

```bash
ssh vildacgg@vldn.in
```

### 2. Git pull yapın

```bash
cd /home/vildacgg/vldn.in/portalv2
git pull origin main
```

**BEKLENEN OUTPUT**:
```
Updating XXXXXXX..7e4b4a5c
Fast-forward
 app/views/layouts/sidebar.php | XX +++++++++++++++
 database/REBUILD-SIMPLE.sql   | XX ++++++++++++++++
 ...
```

### 3. Son commit kontrolü

```bash
git log --oneline -1
```

**BEKLENEN**:
```
7e4b4a5c FINAL: Basitleştirilmiş rebuild script - sadece data, constraint yok
```

VEYA daha yeni bir commit (68a715e9 - Etüt fix)

### 4. Browser'da HARD REFRESH

- **Windows**: Ctrl + Shift + R
- **Mac**: Cmd + Shift + R

### 5. Logout → Login (emine)

### 6. Sidebar kontrol

**GÖRMELİSİNİZ** (11 menü):
1. ✅ Ana Sayfa
2. ✅ Öğrenci Ara (yeni!)
3. ✅ Öğrenci Bilgileri
4. ✅ Etkinlikler
5. ✅ Etkinlik Alanları (yeni!)
6. ✅ Etüt (dropdown - yeni!)
   - Ortaokul Etüt
   - Lise Etüt
7. ✅ Raporlar (yeni!)
8. ✅ **Yönetim** (başlık - yeni!)
9. ✅ Kullanıcılar (yeni!)
10. ✅ Rol İzinleri (yeni!)
11. ✅ Sistem Ayarları (yeni!)

---

## 🔧 EĞER GIT PULL ÇALIŞMAZSA:

```bash
# Git durumunu kontrol et
git status

# Eğer local changes varsa:
git stash

# Sonra tekrar pull
git pull origin main

# Local changes'i geri getir
git stash pop
```

---

## 📊 DEBUG: Hangi commit çalışıyor?

Production'da şu dosyayı kontrol edin:

```bash
cat /home/vildacgg/vldn.in/portalv2/app/views/layouts/sidebar.php | grep -A 2 "Etüt Yönetimi"
```

**ESKI KOD** (Hatalı):
```php
<?php if (hasPermission('etut', 'can_view') || hasPermission('etut-lise', 'can_view') || hasPermission('etut-ortaokul', 'can_view')): ?>
```

**YENİ KOD** (Doğru - Commit 68a715e9):
```php
<?php if (hasPermission('etut-ortaokul', 'can_view') || hasPermission('etut-lise', 'can_view')): ?>
```

---

## ⚠️ KRİTİK: Git pull ZORUNLU!

Database düzeltildi ama kod güncellenmeden sidebar çalışmaz!
