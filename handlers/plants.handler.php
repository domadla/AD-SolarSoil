<?php
/**
 * Plants Handler - Manages plant data and operations
 * In a real application, this would interact with a database
 */

class PlantsHandler
{

    /**
     * Get all available plants
     * @return array Array of plant data
     */
    public static function getAllPlants()
    {
        return [
            [
                "id" => 1,
                "name" => "Nebula Bloom",
                "price" => 35,
                "stock" => 25,
                'img' => 'assets/img/plants/NebulaBloom.png',
                "imgClass" => "nebula-bloom"
            ],
            [
                "id" => 2,
                "name" => "Lunar Cactus",
                "price" => 28,
                "stock" => 40,
                'img' => 'assets/img/plants/LunarCactus.png',
                "imgClass" => "lunar-cactus"
            ],
            [
                "id" => 3,
                "name" => "Meteor Fern",
                "price" => 42,
                "stock" => 15,
                'img' => 'assets/img/plants/MeteorFern.png',
                "imgClass" => "meteor-fern"
            ],
            [
                "id" => 4,
                "name" => "Solar Vine",
                "price" => 30,
                "stock" => 30,
                'img' => 'assets/img/plants/SolarVine.png',
                "imgClass" => "solar-vine"
            ],
            [
                "id" => 5,
                "name" => "Galaxy Orchid",
                "price" => 55,
                "stock" => 10,
                'img' => 'assets/img/plants/GalaxyOrchid.png',
                "imgClass" => "galaxy-orchid"
            ],
            [
                "id" => 6,
                "name" => "Comet Ivy",
                "price" => 26,
                "stock" => 50,
                'img' => 'assets/img/plants/CometIvy.png',
                "imgClass" => "comet-ivy"
            ],
            [
                "id" => 7,
                "name" => "Asteroid Moss",
                "price" => 18,
                "stock" => 60,
                'img' => 'assets/img/plants/AsteroidMoss.png',
                "imgClass" => "asteroid-moss"
            ],
            [
                "id" => 8,
                "name" => "Venus Bell",
                "price" => 33,
                "stock" => 20,
                'img' => 'assets/img/plants/VenusBell.png',
                "imgClass" => "venus-bell"
            ]
        ];
    }

    /**
     * Get plant by ID
     * @param int $id Plant ID
     * @return array|null Plant data or null if not found
     */
    public static function getPlantById($id)
    {
        $plants = self::getAllPlants();
        foreach ($plants as $plant) {
            if ($plant['id'] == $id) {
                return $plant;
            }
        }
        return null;
    }
}
?>