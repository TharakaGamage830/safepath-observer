<?php
// Check PHP PDO MySQL support
echo "<h2>PHP Database Support Check</h2>";

echo "<h3>1. PDO Support:</h3>";
if (extension_loaded('pdo')) {
    echo "✅ PDO is loaded<br>";
} else {
    echo "❌ PDO is NOT loaded<br>";
}

echo "<h3>2. Available PDO Drivers:</h3>";
$drivers = PDO::getAvailableDrivers();
if (empty($drivers)) {
    echo "❌ No PDO drivers available<br>";
} else {
    foreach ($drivers as $driver) {
        echo "✅ " . $driver . "<br>";
    }
}

echo "<h3>3. MySQL PDO Driver:</h3>";
if (in_array('mysql', PDO::getAvailableDrivers())) {
    echo "✅ MySQL PDO driver is available<br>";
} else {
    echo "❌ MySQL PDO driver is NOT available<br>";
}

echo "<h3>4. All Loaded Extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    if (strpos(strtolower($ext), 'mysql') !== false || strpos(strtolower($ext), 'pdo') !== false) {
        echo "✅ " . $ext . "<br>";
    }
}

echo "<h3>5. PHP Version:</h3>";
echo "PHP Version: " . phpversion() . "<br>";

echo "<h3>6. Test MySQL Connection:</h3>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");
    echo "✅ MySQL connection successful<br>";
} catch (PDOException $e) {
    echo "❌ MySQL connection failed: " . $e->getMessage() . "<br>";
}
?>
