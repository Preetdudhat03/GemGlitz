<?php
// Database Connection Configuration for GemGlitz (XAMPP MySQL)

$db_host = 'localhost';
$db_name = 'gemglitz';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // Graceful error display if database is not running or not imported
    die("<div style='font-family:sans-serif; text-align:center; padding:50px; background:#111; color:#C8A96A; min-height:100vh;'>
            <h2>GemGlitz Database Connection Error</h2>
            <p style='color:#ccc;'>Unable to connect to MySQL database <strong>{$db_name}</strong>.</p>
            <p style='color:#888;'>Please ensure MySQL service is running in XAMPP and <code>database/gemglitz.sql</code> is imported.</p>
            <small style='color:#666;'>Error Details: " . htmlspecialchars($e->getMessage()) . "</small>
         </div>");
}
?>
