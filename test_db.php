<?php
require_once 'php/config.php';

echo "<h3>Fedora MariaDB Connection Test</h3>";

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "✅ <b>SUCCESS!</b> Connected as user: <code>" . DB_USER . "</code><br>";
    echo "Database: <code>" . DB_NAME . "</code><br>";


    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Found tables: " . (empty($tables) ? "None (Make sure you imported the SQL!)" : implode(', ', $tables));

} catch (PDOException $e) {
    echo "❌ <b>CONNECTION FAILED</b><br>";
    echo "Error: " . $e->getMessage();
}
?>
