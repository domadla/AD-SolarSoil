<?php
/**
 * Order Handler - Demo data for orders
 */
class OrderHandler
{
    public static function getAllOrders()
    {
        return [
            ["order_no" => "GX-4471", "customer" => "Ian Ramirez", "item" => "Bio-Enhanced Wheat", "qty" => 500, "status" => "Delivered"],
            ["order_no" => "GX-4472", "customer" => "Roldan Edwardo", "item" => "Quantum Tomato", "qty" => 20, "status" => "Processing"],
            ["order_no" => "GX-4473", "customer" => "Kalbo Calleja", "item" => "Asteroid Moss", "qty" => 10, "status" => "Processing"],
        ];
    }
}
