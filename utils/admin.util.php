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


}
?>