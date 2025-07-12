<?php
declare(strict_types=1);

require_once 'bootstrap.php';
// require VENDORS_PATH . 'autoload.php';
require_once UTILS_PATH . 'envSetter.util.php';

echo "✅ Connected to PostgreSQL.\n";

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$dbName = $pdo->query("SELECT current_database()")->fetchColumn();
echo "🧭 Currently connected to database: {$dbName}\n";

// ——— Apply schemas before truncating ———
echo "📦 Applying schema files...\n";
$schemaFiles = [
    DATABASE_PATH . '/users.model.sql',
    DATABASE_PATH . '/plants.model.sql',
    DATABASE_PATH . '/carts.model.sql',
    DATABASE_PATH . '/orders.model.sql',
    DATABASE_PATH . '/cartItems.model.sql'
];
foreach ($schemaFiles as $file) {
    echo "📄 Applying $file...\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read $file");
    }
    $pdo->exec($sql);
}

echo "🔁 Truncating tables…\n";
$tables = ['carts', 'orders', 'plants', 'users', 'cart_items'];
foreach ($tables as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

echo "✅ Tables reset successfully.\n";
