CREATE TABLE IF NOT EXISTS plants (
    plant_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    stock INT DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_url TEXT,
    isDeleted BOOLEAN DEFAULT FALSE
);