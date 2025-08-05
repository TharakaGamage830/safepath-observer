<?php
// Database Constants (update these with your actual database credentials)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'safepath_observer');

// Application Constants
define('APP_ROOT', dirname(dirname(__FILE__)));
define('PUBLIC_ROOT', APP_ROOT . '/public');
define('URL_ROOT', 'http://localhost/SafePathObserver/public');

// Session Constants
define('SESSION_TIMEOUT', 1800); // 30 minutes

// Check if PDO MySQL is available
if (!extension_loaded('pdo')) {
    die("PDO extension is not loaded. Please enable PDO in your PHP configuration.");
}

if (!in_array('mysql', PDO::getAvailableDrivers())) {
    die("PDO MySQL driver is not available. Please enable pdo_mysql in your PHP configuration.");
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}