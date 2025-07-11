CREATE TABLE IF NOT EXISTS Orders (
    id SERIAL PRIMARY KEY,
    user_id INT,
    cart_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (cart_id) REFERENCES Carts(cart_id)
);
