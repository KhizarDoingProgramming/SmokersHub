<?php
// setup_db.php - Run this with 'sudo php setup_db.php'
// This script attempts to connect via the Unix socket to create the app user.

$sockets = [
    '/var/lib/mysql/mysql.sock',
    '/run/mariadb/mariadb.sock',
    '/tmp/mysql.sock'
];

$success = false;
foreach ($sockets as $sock) {
    if (!file_exists($sock)) continue;

    try {
        echo "Trying socket: $sock ... ";
        $pdo = new PDO("mysql:unix_socket=$sock;charset=utf8mb4", 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS smokers_hub");
        $pdo->exec("CREATE USER IF NOT EXISTS 'smoker_app'@'127.0.0.1' IDENTIFIED BY 'Smoker@123'");
        $pdo->exec("GRANT ALL PRIVILEGES ON smokers_hub.* TO 'smoker_app'@'127.0.0.1'");
        $pdo->exec("FLUSH PRIVILEGES");

        echo "✅ SUCCESS! User 'smoker_app' created.\n";
        $success = true;
        break;
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "\n";
    }
}

if (!$success) {
    echo "\n❌ Could not connect via any standard socket.\n";
    echo "Please try: sudo mysql -u root\n";
    echo "If that asks for a password, you must use the password you set during MariaDB installation.\n";
}
?>
