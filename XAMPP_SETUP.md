# XAMPP Setup Guide for SafePath Observer

## Current Status
✅ XAMPP is installed and running
✅ Student dashboard created with temporary layout
❌ Database connection needs to be configured

## Step-by-Step Setup:

### 1. Copy Project to XAMPP
- Copy your entire project folder from: `E:\third year\safepath-observer`
- To: `C:\xampp\htdocs\safepath-observer`

### 2. Create Database
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click "New" to create a new database
3. Name it: `safepath_observer`
4. Click "Create"
5. Import your database schema if you have a .sql file

### 3. Test the Dashboard
- Open: http://localhost/safepath-observer/app/Views/student/student-dashboard.php
- You should see the student dashboard working

### 4. Enable Database Connection (After database is created)
- Edit: `app/Views/student/student-dashboard.php`
- Uncomment the database check lines
- Replace `layout_simple.php` with `layout.php`

## Quick Test URLs:
- phpMyAdmin: http://localhost/phpmyadmin
- Student Dashboard: http://localhost/safepath-observer/app/Views/student/student-dashboard.php
- Database Check: http://localhost/safepath-observer/db_check.php

## Troubleshooting:
- Make sure XAMPP Apache and MySQL are both running (green status)
- Check that your project is in C:\xampp\htdocs\
- Verify database name matches in config files

## Files Modified for Temporary Solution:
- student-dashboard.php (database check commented out)
- layout_simple.php (created without database dependency)
