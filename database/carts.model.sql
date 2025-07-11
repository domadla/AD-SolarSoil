CREATE TABLE IF NOT EXISTS Carts (
    cart_id SERIAL PRIMARY KEY,
    user_id INT UNIQUE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);