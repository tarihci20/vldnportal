#!/bin/bash
# PRODUCTION DEPLOYMENT INSTRUCTIONS
# Vildan Portal - Permission System

echo "=========================================="
echo "PRODUCTION DEPLOYMENT CHECKLIST"
echo "=========================================="
echo ""

echo "STEP 1: Pull latest code"
echo "$ cd /home/vildacgg/public_html/portalv2"
echo "$ git pull origin main"
echo ""

echo "STEP 2: Setup Teacher Account (Vildan)"
echo "$ mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < setup-vildan-teacher.sql"
echo ""

echo "STEP 3: Test with Vildan account"
echo "URL: https://vldn.in/portalv2/login"
echo "Username: vildan"
echo "Password: (vildan's existing password)"
echo ""

echo "TESTS:"
echo "✅ https://vldn.in/portalv2/students → Should work"
echo "❌ https://vldn.in/portalv2/dashboard → Should redirect/error"
echo "❌ https://vldn.in/portalv2/activities → Should redirect/error"
echo "✅ Sidebar should only show 'Öğrenci Bilgileri' menu"
echo ""

echo "STEP 4: Multiple session test"
echo "- Open Browser 1: login as vildan"
echo "- Open Browser 2: login as vildan (same time)"
echo "- Both should be active simultaneously"
echo ""

echo "=========================================="
echo "Setup complete!"
echo "=========================================="
