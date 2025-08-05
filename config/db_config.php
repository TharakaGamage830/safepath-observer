<?php
// Alternative database configuration with better error handling
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'safepath_observer');

function createDatabaseConnection() {
    // Check if PDO is available
    if (!extension_loaded('pdo')) {
        return ['error' => 'PDO extension is not loaded. Please install PHP PDO extension.'];
    }
    
    // Check if MySQL driver is available
    if (!in_array('mysql', PDO::getAvailableDrivers())) {
        return ['error' => 'PDO MySQL driver is not available. Please enable pdo_mysql extension.'];
    }
    
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", 
            DB_USER, 
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return ['success' => true, 'connection' => $pdo];
    } catch (PDOException $e) {
        return ['error' => 'Database connection failed: ' . $e->getMessage()];
    }
}

// Create connection
$dbResult = createDatabaseConnection();

if (isset($dbResult['error'])) {
    // Show user-friendly error page instead of dying
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Configuration Required</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h4><i class="bi bi-exclamation-triangle"></i> Database Configuration Required</h4>
                        </div>
                        <div class="card-body">
                            <h5 class="text-danger">Error: ' . htmlspecialchars($dbResult['error']) . '</h5>
                            
                            <h6 class="mt-4">To fix this issue:</h6>
                            <ol>
                                <li><strong>Install XAMPP:</strong> Download from <a href="https://www.apachefriends.org/" target="_blank">https://www.apachefriends.org/</a></li>
                                <li><strong>Start Services:</strong> Start Apache and MySQL from XAMPP Control Panel</li>
                                <li><strong>Create Database:</strong> Go to <a href="http://localhost/phpmyadmin" target="_blank">phpMyAdmin</a> and create database "safepath_observer"</li>
                                <li><strong>Import Schema:</strong> Import the database_schema.sql file</li>
                            </ol>
                            
                            <div class="alert alert-info mt-3">
                                <strong>Alternative:</strong> If you have PHP installed separately, enable the <code>pdo_mysql</code> extension in your php.ini file.
                            </div>
                            
                            <a href="db_check.php" class="btn btn-primary">Check Database Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
    exit();
}

$pdo = $dbResult['connection'];
?>
