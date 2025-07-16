<?php
/**
 * Plants Handler - Manages plant data and operations
 * In a real application, this would interact with a database
 */

class PlantsHandler
{   
    private static ?PDO $pdo = null;

    /**
     * Initialize database connection
     * 
     * @return PDO
     */
    private static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            global $pgConfig;
            $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
            self::$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }

    /**
     * Get all available plants
     * @return array Array of plant data
     */
    public static function getAllPlants()
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                SELECT 
                    plant_id as id,
                    name,
                    price,
                    image_url as img,
                    description,
                    stock_quantity
                FROM plants 
                WHERE isDeleted = FALSE 
                ORDER BY plant_id
            ");
            $stmt->execute();
            $plants = $stmt->fetchAll();

            // If database is empty, return empty array (don't use fallback)
            if (empty($plants)) {
                error_log('[PlantsHandler::getAllPlants] No plants found in database');
                return [];
            }

            // Transform data for frontend compatibility
            $transformedPlants = [];
            foreach ($plants as $plant) {
                $transformedPlants[] = [
                    'id' => $plant['id'],
                    'name' => $plant['name'],
                    'price' => (float)$plant['price'],
                    'desc' => $plant['description'] ?? self::getPlantDescription($plant['name']),
                    'img' => $plant['img'] ?: 'assets/img/plants/default.png',
                    'imgClass' => self::generateImageClass($plant['name']),
                    'stock_quantity' => $plant['stock_quantity']
                ];
            }

            return $transformedPlants;
        } catch (PDOException $e) {
            error_log('[PlantsHandler::getAllPlants] Database connection error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get plant description based on name
     * @param string $name Plant name
     * @return string Plant description
     */
    private static function getPlantDescription($name)
    {
        return 'An exotic cosmic plant with unique properties.';
    }

    /**
     * Generate CSS class for plant image
     * @param string $name Plant name
     * @return string CSS class
     */
    private static function generateImageClass($name)
    {
        return strtolower(str_replace(' ', '-', $name));
    }

    /**
     * Get plant by ID
     * @param int $id Plant ID
     * @return array|null Plant data or null if not found
     */
    public static function getPlantById($id)
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                SELECT 
                    plant_id as id,
                    name,
                    price,
                    image_url as img,
                    stock_quantity
                FROM plants 
                WHERE plant_id = :id AND isDeleted = FALSE
            ");
            $stmt->execute([':id' => $id]);
            $plant = $stmt->fetch();

            if (!$plant) {
                return null;
            }

            // Transform data for frontend compatibility
            return [
                'id' => $plant['id'],
                'name' => $plant['name'],
                'price' => (float)$plant['price'],
                'desc' => self::getPlantDescription($plant['name']),
                'img' => $plant['img'] ?: 'assets/img/plants/default.png',
                'imgClass' => self::generateImageClass($plant['name']),
                'stock_quantity' => $plant['stock_quantity']
            ];

        } catch (PDOException $e) {
            error_log('[PlantsHandler::getPlantById] Database error: ' . $e->getMessage());
            return null;
        }
    }
}
?>