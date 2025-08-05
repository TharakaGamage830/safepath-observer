@echo off
echo Setting up SafePath Observer with XAMPP...

echo.
echo Step 1: Checking XAMPP installation...
if exist "C:\xampp\htdocs" (
    echo ✓ XAMPP found at C:\xampp\
) else (
    echo ✗ XAMPP not found at C:\xampp\
    echo Please install XAMPP first or check installation path
    pause
    exit
)

echo.
echo Step 2: Copying project files...
if exist "E:\third year\safepath-observer" (
    xcopy "E:\third year\safepath-observer" "C:\xampp\htdocs\safepath-observer\" /E /I /Y
    echo ✓ Project copied to C:\xampp\htdocs\safepath-observer\
) else (
    echo ✗ Source project not found
    pause
    exit
)

echo.
echo Step 3: Opening required URLs...
start http://localhost/phpmyadmin
timeout /t 2 /nobreak >nul
start http://localhost/safepath-observer/db_check.php
timeout /t 2 /nobreak >nul
start http://localhost/safepath-observer/app/Views/student/student-dashboard.php

echo.
echo Setup Complete!
echo.
echo Next steps:
echo 1. In phpMyAdmin (first browser tab): Create database 'safepath_observer'
echo 2. Check db_check.php (second tab) to verify PDO MySQL is working
echo 3. View your dashboard (third tab)
echo.
pause
