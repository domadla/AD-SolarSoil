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
            echo "<div class='plant-footer'>";
            echo "<p class='plant-price'>{$plant['price']} GC</p>";
            echo "<button class='add-to-cart-btn' onclick='addToCart({$plant['id']}, \"{$plant['name']}\", {$plant['price']}, \"{$plant['img']}\")' aria-label='Add {$plant['name']} to cart'>";
            echo "<span class='cart-icon'>🛒</span> Add to Cart";
            echo "</button>";
            echo "</div>";
            echo "</article>";
        }
    }
}