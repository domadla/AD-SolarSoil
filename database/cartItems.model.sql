CREATE TABLE IF NOT EXISTS CartItems (
    cart_item_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL,
    plant_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    FOREIGN KEY (cart_id) REFERENCES Carts(cart_id),
    FOREIGN KEY (plant_id) REFERENCES Plants(plant_id)
);
