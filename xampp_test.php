<!DOCTYPE html>
<html>
<head>
    <title>XAMPP PHP Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h1>XAMPP PHP Configuration Test</h1>
    
    <h2>1. PHP Information:</h2>
    <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
    <p><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
    
    <h2>2. PDO Support:</h2>
    <?php if (extension_loaded('pdo')): ?>
        <p class="success">✓ PDO is loaded</p>
    <?php else: ?>
        <p class="error">✗ PDO is NOT loaded</p>
    <?php endif; ?>
    
    <h2>3. Available PDO Drivers:</h2>
    <?php 
    $drivers = PDO::getAvailableDrivers();
    if (empty($drivers)): ?>
        <p class="error">✗ No PDO drivers available</p>
    <?php else: ?>
        <?php foreach ($drivers as $driver): ?>
            <p class="success">✓ <?php echo $driver; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <h2>4. MySQL PDO Driver:</h2>
    <?php if (in_array('mysql', PDO::getAvailableDrivers())): ?>
        <p class="success">✓ MySQL PDO driver is available</p>
    <?php else: ?>
        <p class="error">✗ MySQL PDO driver is NOT available</p>
    <?php endif; ?>
    
    <h2>5. MySQL Connection Test:</h2>
    <?php
    try {
        $pdo = new PDO("mysql:host=localhost", "root", "");
        echo '<p class="success">✓ MySQL connection successful</p>';
        
        // Test database creation
        $pdo->exec("CREATE DATABASE IF NOT EXISTS safepath_observer");
        echo '<p class="success">✓ Database "safepath_observer" created/verified</p>';
        
    } catch (PDOException $e) {
        echo '<p class="error">✗ MySQL connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p class="info">Make sure MySQL is running in XAMPP Control Panel</p>';
    }
    ?>
    
    <h2>6. File Permissions:</h2>
    <?php
    $testFile = 'test_write.txt';
    if (file_put_contents($testFile, 'test')) {
        echo '<p class="success">✓ File write permissions OK</p>';
        unlink($testFile);
    } else {
        echo '<p class="error">✗ File write permissions failed</p>';
    }
    ?>
    
    <hr>
    <h3>Next Steps:</h3>
    <ol>
        <li>If MySQL PDO driver shows ✓, your XAMPP is configured correctly</li>
        <li>Go to <a href="http://localhost/phpmyadmin" target="_blank">phpMyAdmin</a> to manage databases</li>
        <li>Test your <a href="app/Views/student/student-dashboard.php">Student Dashboard</a></li>
    </ol>
    
    <p><em>This test file: <?php echo __FILE__; ?></em></p>
</body>
</html>
