-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS quang_trong_hang_shop;
USE quang_trong_hang_shop;

-- Bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10,0) NOT NULL,
    color VARCHAR(100),
    material VARCHAR(100),
    size VARCHAR(100), -- Lưu dạng chuỗi phân cách bằng dấu phẩy: 'S,M,L'
    description TEXT,
    images TEXT, -- Lưu nhiều ảnh phân cách bằng dấu phẩy
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng cart
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    size VARCHAR(10),
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bảng orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,0) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Bảng order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    size VARCHAR(10),
    quantity INT NOT NULL,
    price DECIMAL(10,0) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Sample data for products (translated to English)
INSERT INTO products (title, price, color, material, size, description, images, stock_quantity) VALUES
('MU Jersey 24/25 Short Sleeve Version', 800000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United short sleeve jersey for the 24/25 season, modern design', 'https://images.pexels.com/photos/2294344/pexels-photo-2294344.jpeg', 100),
('MU Jersey 25/26 Long Sleeve Version', 1000000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United long sleeve jersey for the 25/26 season, suitable for winter', 'https://images.pexels.com/photos/1884584/pexels-photo-1884584.jpeg', 30),
('MU Jersey 23/24', 1000000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United jersey for the 23/24 season, classic design', 'https://images.pexels.com/photos/2294342/pexels-photo-2294342.jpeg', 25),
('MU Jersey 2007/2008', 5000000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United jersey for the 2007/2008 season - legendary season', 'https://images.pexels.com/photos/1884581/pexels-photo-1884581.jpeg', 10),
('MU Away Jersey 24/25', 1000000, 'White', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United away jersey for the 24/25 season, elegant white color', 'https://images.pexels.com/photos/2294341/pexels-photo-2294341.jpeg', 40),
('MU Third Away Jersey 24/25', 1000000, 'Black', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United third away jersey for the 24/25 season, stylish black', 'https://images.pexels.com/photos/1884582/pexels-photo-1884582.jpeg', 35),
('MU Away Jersey 25/26', 1000000, 'White', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United away jersey for the 25/26 season, latest design', 'https://images.pexels.com/photos/274422/pexels-photo-274422.jpeg', 45),
('Manchester United Hoodie', 1000000, 'Red', 'Cotton', 'S,M,L,XL,2XL', 'Manchester United hoodie, soft and warm cotton material', 'https://images.pexels.com/photos/1884583/pexels-photo-1884583.jpeg', 60);
