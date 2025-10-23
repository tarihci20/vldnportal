# Production Database Repair - Step-by-Step Guide

## Problem Summary

Production `vp_users` table is missing critical database structures that are present in local environment:

| Structure | Status | Impact |
|-----------|--------|--------|
| PRIMARY KEY (`id`) | ✅ Exists | Table structure OK |
| AUTO_INCREMENT on `id` | ❌ **MISSING** | **User creation fails - can't generate new IDs** |
| UNIQUE INDEX (username) | ❌ **MISSING** | Duplicate usernames possible |
| UNIQUE INDEX (email) | ❌ **MISSING** | Duplicate emails possible |
| FK CONSTRAINT (role_id) | ❌ **MISSING** | Orphaned records possible |

**Result:** User creation and deletion operations fail with generic error messages.

---

## Solution: 3-Step Process

### Step 1: Inspection (No Changes)
Run this first to see exactly what's missing:

```bash
# Via phpMyAdmin:
# 1. Go to SQL tab
# 2. Paste contents of: inspect-vp-users-structure.sql
# 3. Click Execute

# Via MySQL CLI:
mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < inspect-vp-users-structure.sql
```

**What to look for:**
- `EXTRA` column should show "auto_increment" for id column
- Should see indexes: PRIMARY, unique_username, unique_email
- Should see FK constraint: fk_vp_users_role_id

**Error "Duplicate key name"?** Means it already exists (good!)

---

### Step 2: Apply Fixes (Two Options)

#### Option A: Safe Version (Recommended - Shows Errors Clearly)
```bash
# phpMyAdmin: Paste contents of fix-vp-users-table-safe.sql and execute
# MySQL CLI:
mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < fix-vp-users-table-safe.sql
```

**Advantages:**
- Shows all verification queries
- Clear error messages
- You can see exactly what was added
- Safe to run multiple times

**What to expect:**
- Some statements may show warnings (duplicate key name = already exists, ignore)
- At end, should see DESCRIBE output with AUTO_INCREMENT present

---

#### Option B: Quick Fix (Minimal Output)
```bash
# phpMyAdmin: Paste contents of fix-vp-users-table.sql and execute
# MySQL CLI:
mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < fix-vp-users-table.sql
```

**Advantages:**
- Minimal output
- Faster execution
- Less noise in logs

---

### Step 3: Verify Success

After running fixes, manually run these queries to confirm:

```sql
-- Check AUTO_INCREMENT is present
DESCRIBE vp_users;
-- Should show "auto_increment" in EXTRA column for id

-- Check UNIQUE indexes exist
SHOW INDEXES FROM vp_users;
-- Should show: PRIMARY, unique_username, unique_email

-- Check FK constraint
SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'vp_users' AND REFERENCED_TABLE_NAME = 'vp_roles';
-- Should show: fk_vp_users_role_id
```

---

## How to Execute

### Via phpMyAdmin (Easiest)

1. Log into hosting control panel
2. Go to phpMyAdmin
3. Select database: `vildacgg_portalv2`
4. Click "SQL" tab at top
5. Copy & paste content of one of the .sql files
6. Click "Execute"

### Via MySQL CLI (If SSH Access)

```bash
# Connect to server via SSH, then:

# Run inspection first (safe, no changes)
mysql -u vildacgg_tarihci20 -p vildacgg_portalv2 < fix-vp-users-table-safe.sql

# Check output, then run fix
# When prompted for password, enter your MySQL password

# Or use password in command (less secure):
mysql -u vildacgg_tarihci20 -pYOUR_PASSWORD vildacgg_portalv2 < fix-vp-users-table-safe.sql
```

---

## Troubleshooting

### Error: "Duplicate key name"
**Meaning:** That index/constraint already exists  
**Action:** Safe to ignore, it means the structure is already correct

### Error: "Multiple primary key defined"
**Meaning:** Tried to add PRIMARY KEY when one already exists  
**Action:** Should not happen with corrected scripts - if it does, check script date

### Error: "Cannot add or update a child row"
**Meaning:** Foreign key constraint would be violated  
**Action:** You have orphaned role_id values pointing to non-existent roles
```sql
-- Check what roles are referenced:
SELECT DISTINCT role_id FROM vp_users ORDER BY role_id;

-- Check what roles exist:
SELECT id FROM vp_roles ORDER BY id;

-- If you have orphaned roles, either:
-- A) Update vp_users to valid role_id
-- B) Skip FK constraint (less safe)
```

### Error: "Cannot alter table"
**Meaning:** Table is locked or in use  
**Action:** 
- Ensure no users are logged in
- Restart MySQL service if needed
- Try again

---

## After Database Repair: Test Operations

### Test 1: Create User
1. Go to `/portalv2/admin/users`
2. Click "Create User" button
3. Fill form:
   - Username: `test_user_123`
   - Email: `test@example.local`
   - Password: `Test123!`
   - Role: Select any role
4. Click "Save"
5. Should see success message and new user in list

**Expected:** New user appears in database with auto-generated ID

### Test 2: Delete User
1. Go to `/portalv2/admin/users`
2. Find the test user created above
3. Click delete button (trash icon)
4. Confirm deletion
5. Should see success message

**Expected:** User removed from database

### Test 3: Verify Database
```sql
-- Should see test_user_123 if it failed to delete
SELECT * FROM vp_users WHERE username = 'test_user_123';

-- Check AUTO_INCREMENT by creating and deleting:
-- Each new user should get next available ID
INSERT INTO vp_users (username, email, password_hash, role_id, is_active, created_at, updated_at)
VALUES ('test_' . UNIX_TIMESTAMP(), 'test@test.com', 'hash', 1, 1, NOW(), NOW());

SELECT id FROM vp_users ORDER BY id DESC LIMIT 5;
-- IDs should be sequential: 10, 11, 12, 13, etc. (no gaps)
```

---

## Files Included

| File | Purpose | When to Use |
|------|---------|------------|
| `inspect-vp-users-structure.sql` | Check current database structure | First - diagnose what's missing |
| `fix-vp-users-table-safe.sql` | Safe version with verification queries | Second - apply fixes with clear output |
| `fix-vp-users-table.sql` | Quick version, minimal output | Alternate to safe version |

---

## Next Steps (If Issues Continue)

After applying these fixes, if user creation/deletion still fails:

1. Check PHP error logs: `/home/vildacgg/logs/error_log`
2. Check browser console for JavaScript errors (F12)
3. Check CSRF token in meta tag: `<meta name="csrf-token">`
4. Verify role_id is valid: Check that selected roles exist in vp_roles

---

## Prevention: Check Other Tables

The same issue may exist in other tables. Check if other tables have:
- ✅ AUTO_INCREMENT on id columns
- ✅ UNIQUE constraints where needed
- ✅ FK constraints for foreign keys

Tables to check:
- vp_roles
- vp_students
- vp_activities
- vp_activity_areas
- vp_etut_applications
- All others with `id` PRIMARY KEY

---

## Reference: Complete vp_users Structure (Local - Working)

```sql
CREATE TABLE `vp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`),
  UNIQUE KEY `unique_email` (`email`),
  FOREIGN KEY (`role_id`) REFERENCES `vp_roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

**Updated:** October 24, 2025  
**Status:** Ready for Production Deployment  
**Estimated Duration:** 5-10 minutes
