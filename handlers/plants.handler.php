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
                "desc" => "A radiant flower that glows with the colors of cosmic clouds.",
                'img' => 'assets/img/plants/NebulaBloom.png',
                "imgClass" => "nebula-bloom",
                "stock" => 12
            ],
            [
                "id" => 2,
                "name" => "Lunar Cactus",
                "price" => 28,
                "desc" => "Tough and resilient, it absorbs moonlight to bloom at night.",
                'img' => 'assets/img/plants/LunarCactus.png',
                "imgClass" => "lunar-cactus",
                "stock" => 8
            ],
            [
                "id" => 3,
                "name" => "Meteor Fern",
                "price" => 42,
                "desc" => "Feathers shimmer like meteors streaking through space dust.",
                'img' => 'assets/img/plants/MeteorFern.png',
                "imgClass" => "meteor-fern",
                "stock" => 15
            ],
            [
                "id" => 4,
                "name" => "Solar Vine",
                "price" => 30,
                "desc" => "Golden tendrils that sway toward sunlight, storing solar energy.",
                'img' => 'assets/img/plants/SolarVine.png',
                "imgClass" => "solar-vine",
                "stock" => 20
            ],
            [
                "id" => 5,
                "name" => "Galaxy Orchid",
                "price" => 55,
                "desc" => "Petals with shifting star-like specks, a true interstellar marvel.",
                'img' => 'assets/img/plants/GalaxyOrchid.png',
                "imgClass" => "galaxy-orchid",
                "stock" => 5
            ],
            [
                "id" => 6,
                "name" => "Comet Ivy",
                "price" => 26,
                "desc" => "Fast-growing vine that leaves glowing trails in the dark.",
                'img' => 'assets/img/plants/CometIvy.png',
                "imgClass" => "comet-ivy",
                "stock" => 18
            ],
            [
                "id" => 7,
                "name" => "Asteroid Moss",
                "price" => 18,
                "desc" => "Spongy moss that thrives in zero-gravity and rocky terrain.",
                'img' => 'assets/img/plants/AsteroidMoss.png',
                "imgClass" => "asteroid-moss",
                "stock" => 10
            ],
            [
                "id" => 8,
                "name" => "Venus Bell",
                "price" => 33,
                "desc" => "Bell-shaped bloom that hums gently when touched.",
                'img' => 'assets/img/plants/VenusBell.png',
                "imgClass" => "venus-bell",
                "stock" => 7
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