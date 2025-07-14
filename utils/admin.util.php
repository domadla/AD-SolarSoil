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
            ");
            $stmt->execute();
            $plants = $stmt->fetchAll();
            if(empty($users)){
                error_log("[Admin::display_plants] No plants found in database");
            }
            return $plants;
        }catch(PDOException $e){
            error_log("[Admin::display_users] Database connection error: " . $e->getMessage());
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
                    p.name,
                    ct.quantity,
                    o.completed
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                JOIN cart_items ct ON o.cart_id = ct.cart_id
                JOIN plants p ON ct.plant_id = p.plant_id;
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

}
?>