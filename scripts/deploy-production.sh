#!/bin/bash
# =================================================================
# PRODUCTION DEPLOYMENT SCRIPT
# =================================================================
# Bu script'i production server'da çalıştırın
# =================================================================

echo "==================================================================="
echo "VILDAN PORTAL - PERMISSION SYSTEM DEPLOYMENT"
echo "==================================================================="
echo ""

# Proje dizinine git
cd /home/vildacgg/vldn.in/portalv2

echo "1. Git durumu kontrol ediliyor..."
git status

echo ""
echo "2. Son commit kontrol ediliyor..."
git log --oneline -5

echo ""
echo "3. Git pull yapılıyor..."
git pull origin main

echo ""
echo "4. Son commit doğrulanıyor..."
git log --oneline -1

echo ""
echo "==================================================================="
echo "BEKLENEN COMMIT: 7e4b4a5c - FINAL: Basitleştirilmiş rebuild script"
echo "==================================================================="
echo ""
echo "Şimdi yapılacaklar:"
echo "1. Browser'da HARD REFRESH (Ctrl+Shift+R)"
echo "2. Logout → Login (emine)"
echo "3. Sidebar'da 11 menü görmelisiniz!"
echo ""
echo "==================================================================="
