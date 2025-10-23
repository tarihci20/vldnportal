# Database Schema - Missing PRIMARY KEYS & AUTO_INCREMENT

## Problem

**Error:** `SQLSTATE[HY000]: General error: 1364 Field 'id' doesn't have a default value`

This error occurs when inserting into tables without PRIMARY KEY or AUTO_INCREMENT.

## Root Cause

Production database tables created WITHOUT:
- PRIMARY KEY on `id` columns
- AUTO_INCREMENT constraints

Example:
```sql
-- WRONG (Production)
CREATE TABLE vp_activity_areas (
  `id` int(11) NOT NULL,  ← No PRIMARY KEY, no AUTO_INCREMENT!
  ...
);

-- CORRECT (Should be)
CREATE TABLE vp_activity_areas (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  ...
);
```

## Which Tables Affected

All tables are affected (same pattern):
- ✗ vp_activity_areas
- ✗ vp_activities
- ✗ vp_etut_applications
- ✗ vp_etut_form_settings
- ✗ vp_role_page_permissions
- ✗ vp_students
- ✗ vp_time_slots
- ✗ vp_pages
- ✗ vp_recurring_rules
- ✗ vp_roles
- ✗ vp_system_settings
- ✗ vp_user_sessions (partially fixed)
- ✗ vp_users (partially fixed)
- ✗ vp_password_resets

## Solution

### Option 1: Quick Fix (Activity Areas Only)
If only adding activity areas is failing:

```sql
-- phpMyAdmin SQL Tab
-- File: database/fix-vp-activity-areas-table.sql

ALTER TABLE `vp_activity_areas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vp_activity_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```

### Option 2: Complete Fix (All Tables)
For permanent solution - fix ALL tables at once:

**File:** `database/fix-all-tables-primary-keys.sql`

```bash
# Via MySQL CLI
mysql -u cpses_vizka7fxkm -p cpses_portal < fix-all-tables-primary-keys.sql

# Via phpMyAdmin
# Go to SQL tab, copy contents of fix-all-tables-primary-keys.sql, execute
```

## How to Apply in Production

### Step 1: Backup Database (IMPORTANT!)
```bash
# Via cPanel Backup
# Or manually:
mysqldump -u cpses_vizka7fxkm -p cpses_portal > backup_$(date +%s).sql
```

### Step 2: Run Fix Script
**Via phpMyAdmin (Easiest):**
1. Log into cPanel → phpMyAdmin
2. Select database: `cpses_portal`
3. Click SQL tab
4. Copy entire contents of: `database/fix-all-tables-primary-keys.sql`
5. Paste into SQL editor
6. Click Execute

**Via MySQL CLI:**
```bash
mysql -u cpses_vizka7fxkm -p cpses_portal < fix-all-tables-primary-keys.sql
```

### Step 3: Verify
Check DESCRIBE output shows AUTO_INCREMENT:
```sql
DESCRIBE vp_activity_areas;
-- Column `id` should show: int(11) | ... | auto_increment
```

### Step 4: Test Insert
Try adding new activity area:
1. Go to Activities → Activity Areas (admin page)
2. Click "Add Area"
3. Fill form and Save
4. Should work without error

## Error Messages You Might See

### "Duplicate key name 'PRIMARY'"
**Meaning:** PRIMARY KEY already exists  
**Action:** Safe to ignore, it means structure already correct

### "Multiple primary key defined"
**Meaning:** Table already has PRIMARY KEY  
**Action:** Script will skip, that's OK

### "Access denied to INFORMATION_SCHEMA"
**Meaning:** VERIFY queries will fail but ALTERs work  
**Action:** OK, your hosting restricts INFORMATION_SCHEMA access

## After Fix

Once all PRIMARY KEYS & AUTO_INCREMENT are added:

✅ Can insert new activity areas
✅ Can insert new activities
✅ Can insert new students
✅ Can insert new etut applications
✅ All INSERT operations will work

## Prevention

For future tables, always use:

```sql
CREATE TABLE vp_new_table (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## Next Production Deployment

1. Pull latest code (includes this guide)
2. Run `fix-all-tables-primary-keys.sql` in phpMyAdmin
3. Test all add/create operations
4. Monitor error logs for 24 hours
5. Document the fix

---

**Created:** October 24, 2025  
**Severity:** CRITICAL - All INSERT operations blocked  
**Fix Time:** 2-3 minutes  
**Downtime:** None (can apply while system running)
