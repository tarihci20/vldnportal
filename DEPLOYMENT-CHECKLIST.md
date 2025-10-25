# 🚀 PRODUCTION DEPLOYMENT CHECKLIST

**Status:** Ready for Production Deployment  
**Branch:** main  
**Latest Commit:** 8c7ce4f2  
**Date:** October 25, 2025

---

## ✅ PRE-DEPLOYMENT

- [x] Code changes reviewed and tested locally
- [x] Critical bug fixes applied (cb744dd3)
- [x] Debug script created and accessible (.htaccess bypass added)
- [x] SQL diagnostics script prepared
- [x] Documentation completed
- [x] .htaccess routes configured for direct PHP access
- [x] All commits pushed to main branch

---

## 📦 DEPLOYMENT STEPS

### Step 1: Pull Latest Code
```bash
cd /home/vildacgg/vldn.in/portalv2
git pull origin main
# Should reach commit: 8c7ce4f2 or later
```

### Step 2: Verify Deployment
```bash
git log --oneline -3
# Output should show:
# 8c7ce4f2 Fix: Improved debug script UI and .htaccess bypass
# 48a9a464 Docs & Fix: Production test guide, SQL diagnostics
# c7813fac Docs: Deployment summary
```

### Step 3: Clear Cache (if needed)
```bash
# Browser cache: Ctrl+Shift+Delete or Cmd+Shift+Delete
# Or: Hard refresh: Ctrl+F5 or Cmd+Shift+R
```

---

## 🧪 TESTING PHASE

### Test 1: Debug Script Access
**URL:** `https://vldn.in/portalv2/test-permissions-debug.php`

**Expected Output:**
- ✅ Database Connection: OK
- ✅ Roles table listed
- ✅ Pages table listed  
- ✅ Permission records count shown
- ✅ Vice Principal permissions detailed

**If Error:**
- [ ] Check .htaccess modification in public folder
- [ ] Verify file exists: `public/test-permissions-debug.php`
- [ ] Check server error log: `/storage/logs/error.log`

---

### Test 2: Form Access & Display
**URL:** `https://vldn.in/portalv2/admin`

1. Navigate: Admin → Roller (Roles)
2. Select: "Müdür Yardımcısı" (Vice Principal)
3. Click: "Düzenle" (Edit)

**Expected:**
- [ ] Form loads without errors
- [ ] Page list shows ≥ 12 pages
- [ ] Etüt pages visible (11, 12, 13)
- [ ] Checkboxes responsive to clicks
- [ ] No JavaScript console errors (F12)

**If Empty Form:**
- [ ] Check debug script output
- [ ] Run SQL setup from PRODUCTION-PERMISSION-DIAGNOSTICS.sql
- [ ] Verify database connection

---

### Test 3: Permission Assignment & Save
1. Select one page (e.g., Dashboard)
2. Check all 4 permission boxes (View, Create, Edit, Delete)
3. Click "Güncelle" (Update)

**Expected:**
- [ ] Form submitted successfully
- [ ] Flash message: "Rol başarıyla güncellendi"
- [ ] Page refreshes automatically
- [ ] Checkboxes remain checked after refresh

**If Not Saving:**
- [ ] Check browser console (F12 → Console)
- [ ] Check Network tab → POST request
- [ ] Check server log: `/storage/logs/error.log`
- [ ] Verify database permissions

---

### Test 4: Permission Removal (Unchecking)
1. Uncheck 1-2 permission boxes from previously saved permissions
2. Click "Güncelle" (Update)
3. Refresh page (F5)

**Expected:**
- [ ] Unchecked boxes remain unchecked after refresh
- [ ] Save successful message appears

**If Bug (boxes checked again):**
- [ ] This indicates OLD BUG not fixed
- [ ] Verify code deployment (should be cb744dd3 or later)
- [ ] Hard refresh: Ctrl+Shift+Delete then Ctrl+F5
- [ ] Rollback if persists

---

### Test 5: All Roles Test
Repeat Tests 3-4 for:
- [ ] Admin (Role 1)
- [ ] Teacher (Role 2)  
- [ ] Secretary (Role 3)
- [ ] Principal (Role 4)
- [ ] Vice Principal (Role 5)

---

## 🔍 FINAL VERIFICATION

Run this SQL to verify database state:
```sql
-- Production database check
USE vildacgg_portalv2;

-- Check total permission records
SELECT COUNT(*) as total_permissions FROM vp_role_page_permissions;

-- Check Role 5 permissions (should NOT be 0)
SELECT COUNT(*) as role5_permissions 
FROM vp_role_page_permissions 
WHERE role_id = 5;

-- Show recent changes
SELECT role_id, page_id, can_view, can_create, can_edit, can_delete
FROM vp_role_page_permissions
WHERE role_id = 5
ORDER BY page_id;
```

**Expected Results:**
- total_permissions: > 0
- role5_permissions: 11 or more
- Page records show correct permission combinations

---

## 📋 DEPLOYMENT CHECKLIST

**Before Going Live:**
- [ ] Code deployed (git pull successful)
- [ ] Debug script accessible and working
- [ ] Form loads without errors
- [ ] All 5 roles can be edited
- [ ] Permissions save correctly
- [ ] Unchecked boxes persist
- [ ] Etüt pages visible
- [ ] No errors in `/storage/logs/error.log`
- [ ] Database shows correct permission records

**Documentation to Clean Up (Optional, Post-Deployment):**
- [ ] Remove test files: `public/test-permissions-debug.php` (after testing complete)
- [ ] Or keep for ongoing diagnosis and monitoring

**After Testing Complete:**
- [ ] Announce success to team
- [ ] Monitor error logs for 24 hours
- [ ] Gather user feedback
- [ ] Document any issues found

---

## 🆘 TROUBLESHOOTING

### Issue: Debug Script Returns Error
**Solution:**
1. Check `/storage/logs/error.log` for details
2. Verify `.htaccess` in public folder
3. Check file permissions: `public/test-permissions-debug.php`
4. Test direct URL encoding: `https://vldn.in/portalv2/public/test-permissions-debug.php`

### Issue: Form Still Empty
**Solution:**
1. Run: `https://vldn.in/portalv2/test-permissions-debug.php`
2. Check "Permissions per Role" section
3. If Role 5 = 0, run SQL setup:
   ```sql
   -- From PRODUCTION-PERMISSION-DIAGNOSTICS.sql
   INSERT INTO vp_role_page_permissions ...
   ```

### Issue: Checkboxes Not Saving
**Solution:**
1. Browser console (F12): Check for JS errors
2. Network tab: Verify POST request being sent
3. Server log: `/storage/logs/error.log`
4. Verify code: `git log --oneline -1` should show 8c7ce4f2 or later

### Issue: Unchecked Boxes Still Show Checked
**Solution:**
1. Hard browser refresh: Ctrl+Shift+Delete (cache) → Ctrl+F5
2. Verify code deployment: Check commit hash
3. If still broken: `git revert cb744dd3` (rollback)

---

## 🔄 ROLLBACK PROCEDURE (Emergency Only)

If critical issues found:
```bash
# Option 1: Revert last fix
git revert 8c7ce4f2

# Option 2: Go back 3 commits
git reset --hard HEAD~3

# Option 3: Revert specific fix
git revert cb744dd3
```

---

## 📞 SUPPORT

**Issues Found:**
1. Document exact error
2. Check `/storage/logs/error.log`
3. Run debug script and capture output
4. Share SQL query results
5. Contact: [dev team]

**Files to Share for Debugging:**
- [ ] `/storage/logs/error.log` (last 50 lines)
- [ ] Debug script output (screenshot or copy-paste)
- [ ] `git log --oneline -5` (current commits)
- [ ] Browser console error messages (F12)

---

## ✨ SUCCESS INDICATORS

All of these MUST be true:
1. ✅ Debug script loads without errors
2. ✅ Form displays ≥ 12 pages  
3. ✅ Etüt pages (11, 12, 13) visible
4. ✅ Checkboxes can be toggled
5. ✅ Permissions save on form submit
6. ✅ Saved permissions persist after refresh
7. ✅ Unchecked boxes remain unchecked
8. ✅ All 5 roles work correctly
9. ✅ Database records update correctly
10. ✅ No errors in production logs

**THEN:** 🎉 Deployment is successful!

---

**Deployment Started:** ___________  
**Deployment Completed:** ___________  
**Deployed By:** ___________  
**Verified By:** ___________  

**Notes:**
```
_________________________________________________
_________________________________________________
_________________________________________________
```

