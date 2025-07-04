<?php
if (!function_exists('displayPlants')) {
    function displayPlants($plants)
    {
        foreach ($plants as $plant) {
            echo "<article class='plant-card' tabindex='0' aria-label='{$plant['name']} Plant'>";
            if (!empty($plant['img'])) {
                echo "<img src='{$plant['img']}' alt='{$plant['name']}' class='plant-image {$plant['imgClass']}' title='{$plant['name']}' />";
            } else {
                echo "<div class='plant-image {$plant['imgClass']}' role='img' aria-hidden='true' title='{$plant['name']}'></div>";
            }
            echo "<h3 class='plant-name'>{$plant['name']}</h3>";
            echo "<p class='plant-description'>{$plant['desc']}</p>";
            echo "<p class='plant-price'>Price: {$plant['price']} Galactic Credits</p>";
            echo "</article>";
        }
    }
}