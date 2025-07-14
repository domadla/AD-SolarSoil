<?php
declare(strict_types=1);

require_once 'bootstrap.php';
// require VENDORS_PATH . 'autoload.php';
require_once UTILS_PATH . 'envSetter.util.php';

//Static datas
$users = require_once DUMMIES_PATH . 'user.staticData.php';
$plants = require_once DUMMIES_PATH . 'plant.staticData.php';
$carts = require_once DUMMIES_PATH . 'cart.staticData.php';
$orders = require_once DUMMIES_PATH . 'order.staticData.php';
$cart_items = require_once DUMMIES_PATH . 'cart_items.staticData.php';


echo "✅ Connected to PostgreSQL.\n";
// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// ——— Apply schemas before truncating ———
echo "📦 Applying schema files...\n";
$schemaFiles = [
    'database/users.model.sql',
    'database/plants.model.sql',
    'database/carts.model.sql',
    'database/orders.model.sql',
    'database/cartItems.model.sql'
];

foreach ($schemaFiles as $file) {
    echo "📄 Applying $file...\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read $file");
    }
    $pdo->exec($sql);
}

//For query
$stmtUsers = $pdo->prepare("
    INSERT INTO users (username, password, firstname, lastname, role, address, created_at)
    VALUES (:user, :pass, :fn, :ln, :role, :add, :cre_at)
");

$stmtPlants = $pdo->prepare("
    INSERT INTO plants (name, stock, price, image_url, description)
    VALUES (:name, :stock, :price, :url, :desc)
");

$stmtCarts = $pdo->prepare("
    INSERT INTO carts (user_id)
    VALUES (:u_id)
");

$stmtOrders = $pdo->prepare("
    INSERT INTO orders (user_id, cart_id, created_at)
    VALUES (:u_id, :c_id, :cre_at)
");

$stmtCartItems = $pdo->prepare("
    INSERT INTO cart_items (cart_id, plant_id, quantity)
    VALUES (:c_id, :p_id, :quantity)
");


$allSeeded = true;

echo "🔁 Seeding Users\n";
try {
    foreach ($users as $u) {
        $stmtUsers->execute([
            ':user' => $u['username'],
            ':pass' => password_hash($u['password'], PASSWORD_DEFAULT),
            ':fn' => $u['firstname'],
            ':ln' => $u['lastname'],
            ':role' => $u['role'],
            ':add' => $u['address'],
            ':cre_at' => $u['created_at']
            
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding users: " . $e->getMessage() . "\n";
    $allSeeded = false;
}


echo "🔁 Seeding Plants\n";
try {
foreach ($plants as $p) {
    $stmtPlants->execute([
        ':name' => $p['name'],
        ':stock' => $p['stock'],
        ':price' => $p['price'],
        ':url' => $p['image_url'],
        ':desc' => $p['description']
    ]);
}
} catch (PDOException $e) {
    echo "❌ Error seeding plants: " . $e->getMessage() . "\n";
    $allSeeded = false;
}

echo "🔁 Seeding Carts\n";
try {
    foreach ($carts as $c) {
        $stmtCarts->execute([
            ':u_id' => $c['user_id'],
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding carts: " . $e->getMessage() . "\n";
    $allSeeded = false;
}

echo "🔁 Seeding Orders\n";
try {
    foreach ($orders as $o) {
        $stmtOrders->execute([
            ':u_id' => $o['user_id'],
            ':c_id' => $o['cart_id'],
            ':cre_at' => $o['created_at'],
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding meeting_users: " . $e->getMessage() . "\n";
    $allSeeded = false;
}
 
echo "🔁 Seeding Cart Items\n";
try {
    foreach ($cart_items as $o) {
        $stmtCartItems->execute([
            ':c_id' => $o['cart_id'],
            ':p_id' => $o['plant_id'],
            ':quantity' => $o['quantity'],
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding Cart Items: " . $e->getMessage() . "\n";
    $allSeeded = false;
}

if ($allSeeded) {
    echo "✅ Tables have been populated!\n";
}