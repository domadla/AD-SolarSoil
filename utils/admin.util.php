<?php
declare(strict_types=1);

include_once UTILS_PATH . "/envSetter.util.php";
class Admin{
    public static function display_users(PDO $pdo){
        try{
            $stmt = $pdo->prepare("
                SELECT
                    user_id as id,
                    firstname,
                    lastname,
                    username,
                    role
                FROM USERS
                WHERE isDeleted = FALSE
            ");
            $stmt->execute();
            $users = $stmt->fetchAll();
            if(empty($users)){
                error_log("[Admin::display_users] No users found in database");
            }
            return $users;
        }catch(PDOException $e){
            error_log("[Admin::display_users] Database connection error: " . $e->getMessage());
            return [];
        }
    }

    public static function display_plants(PDO $pdo){
        try{
            $stmt = $pdo->prepare("
                SELECT
                    plant_id as id,
                    name,
                    stock_quantity,
                    price
                FROM plants
                WHERE isDeleted = FALSE
                ORDER BY plant_id
            ");
            $stmt->execute();
            $plants = $stmt->fetchAll();
            if(empty($plants)){
                error_log("[Admin::display_plants] No plants found in database");
            }
            return $plants;
        }catch(PDOException $e){
            error_log("[Admin::display_plants] Database connection error: " . $e->getMessage());
            return [];
        }
    }

    public static function display_orders(PDO $pdo){
        try{
            $stmt = $pdo->prepare("
                SELECT
                    o.id,
                    u.firstname,
                    u.lastname,
                    o.completed,
                    STRING_AGG(p.name || ' (Qty: ' || ct.quantity || ')', '; ') AS items
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                LEFT JOIN cart_items ct ON o.id = ct.order_id
                LEFT JOIN plants p ON ct.plant_id = p.plant_id
                GROUP BY o.id, u.firstname, u.lastname, o.completed
                ORDER BY o.id
            ");
            $stmt->execute();
            $orders = $stmt->fetchAll();
            if(empty($orders)){
                error_log("[Admin::display_orders] No orders found in database");
            }
            return $orders;
        }catch(PDOException $e){
            error_log("[Admin::display_orders] Database connection error: " . $e->getMessage());
            return [];
        }
    }

    public static function add_plant(PDO $pdo, string $name, float $price, int $stock_quantity, string $image_url, string $description){
        try {
            $stmt = $pdo->prepare("
            SELECT
                plant_id
            FROM PLANTS
            WHERE name = :name");
            $stmt->execute([':name' => $name]);
            if ($stmt->fetch()) {
                error_log("[Admin::add_plant] Plant already exists: {$name}");
                return ['error' => 'PlantAlreadyExists'];
            }
        } catch (\PDOException $e) {
            error_log('[Admin::add_plant] PDOException on check: ' . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO PLANTS (name, price, stock_quantity, image_url, description)
                VALUES (:name, :price, :stock_quantity, :image_url, :description)"
            );
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':stock_quantity' => $stock_quantity,
                ':image_url' => $image_url,
                ':description' => $description
            ]);

            error_log("[Admin::add_plant] New Plant added: {$name}");
            return ['success' => 'PlantAdded'];
        } catch (\PDOException $e) {
            error_log('[Admin::add_plant] PDOException on insert: ' . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }
    }

    public static function count_users(PDO $pdo): int{
        try {
            $stmt = $pdo->prepare("
            SELECT
                COUNT(user_id)
            FROM USERS
            WHERE isDeleted = FALSE AND role = 'user'
            ");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[Admin::count_users] PDOException on count: ' . $e->getMessage());
            return 0;
        }
    }

    public static function count_plants(PDO $pdo): int{
        try {
            $stmt = $pdo->prepare("
            SELECT
                COUNT(plant_id)
            FROM PLANTS
            WHERE isDeleted = FALSE");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[Admin::count_plants] PDOException on count: ' . $e->getMessage());
            return 0;
        }
    }

    public static function count_orders(PDO $pdo): int{
        try {
            $stmt = $pdo->prepare("
            SELECT
                COUNT(id)
            FROM ORDERS");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[Admin::count_orders] PDOException on count: ' . $e->getMessage());
            return 0;
        }
    }

    public static function update_orders(PDO $pdo, int $id, bool $completed){
        try {
            $stmt = $pdo->prepare("
            UPDATE ORDERS
            SET completed = :completed
            WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':completed' => $completed
            ]);
        } catch (\PDOException $e) {
            error_log('[Admin::update_orders] PDOException on update: ' . $e->getMessage());
        }
    }

    public static function edit_plant(PDO $pdo, int $id, array $data) {
        try {
            $updates = [];
            $params = [':id' => $id];

            if (isset($data['name'])) {
                $updates[] = 'name = :name';
                $params[':name'] = $data['name'];
            }
            if (isset($data['description'])) {
                $updates[] = 'description = :description';
                $params[':description'] = $data['description'];
            }
            if (isset($data['price'])) {
                $updates[] = 'price = :price';
                $params[':price'] = $data['price'];
            }
            if (isset($data['stock_quantity'])) {
                $updates[] = 'stock_quantity = :stock_quantity';
                $params[':stock_quantity'] = $data['stock_quantity'];
            }
            if (isset($data['image_url'])) {
                $updates[] = 'image_url = :image_url';
                $params[':image_url'] = $data['image_url'];
            }

            if (empty($updates)) {
                return ['error' => 'NoFieldsToUpdate'];
            }

            $sql = "UPDATE plants SET " . implode(', ', $updates) . " WHERE plant_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            return ['success' => 'PlantUpdatedSuccessfully'];
        } catch (\PDOException $e) {
            error_log('[Admin::edit_plant] PDOException on update: ' . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }
    }

    public static function delete_user(PDO $pdo, int $id){
        try{
            $stmt = $pdo->prepare("
                UPDATE USERS
                SET isDeleted = TRUE
                WHERE user_id = :id
            ");
            $stmt->execute([':id' => $id]);
            error_log("[Admin::delete_user] User deleted: {$id}");
            return ['success' => 'UserDeleted'];
        }catch(PDOException $e){
            error_log("[Admin::delete_user] Database connection error: " . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }
    }

    public static function delete_plant(PDO $pdo, int $id){
        try{
            $stmt = $pdo->prepare("
                UPDATE PLANTS
                SET isDeleted = TRUE
                WHERE plant_id = :id
            ");
            $stmt->execute([':id' => $id]);
            error_log("[Admin::delete_plant] Plant deleted: {$id}");
            return ['success' => 'PlantDeleted'];
        }catch(PDOException $e){
            error_log("[Admin::delete_plant] Database connection error: " . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }
    }
}
?>