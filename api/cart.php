<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'] ?? 1;

    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
    $stmt->execute([$user_id, $product_id, $size]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Cập nhật số lượng
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
        $result = $stmt->execute([$quantity, $existing['id']]);
    } else {
        // Thêm mới
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, size, quantity) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$user_id, $product_id, $size, $quantity]);
    }

    echo json_encode(['success' => $result, 'message' => $result ? 'Added to cart' : 'An error occurred']);
} elseif ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    if ($quantity <= 0) {
        // Xóa sản phẩm khỏi giỏ hàng
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$cart_id, $user_id]);
    } else {
        // Cập nhật số lượng
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$quantity, $cart_id, $user_id]);
    }

    echo json_encode(['success' => $result]);
} elseif ($action == 'remove' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];

    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $result = $stmt->execute([$cart_id, $user_id]);

    echo json_encode(['success' => $result]);
} elseif ($action == 'checkout' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // Lấy thông tin giỏ hàng
        $stmt = $pdo->prepare("
            SELECT c.*, p.price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll();

        if (empty($cart_items)) {
            throw new Exception('Cart is empty');
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Lấy địa chỉ người dùng
        $stmt = $pdo->prepare("SELECT address FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        // Tạo đơn hàng
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $total, $user['address']]);
        $order_id = $pdo->lastInsertId();

        // Thêm chi tiết đơn hàng
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['product_id'], $item['size'], $item['quantity'], $item['price']]);

            // Subtract product inventory
            $updateStock = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - :qty WHERE id = :product_id AND stock_quantity >= :qty");
            $updateStock->execute([
                ':qty' => $item['quantity'],
                ':product_id' => $item['product_id']
            ]);

            //If there is not enough stock (error data), rollback the order
            if ($updateStock->rowCount() === 0) {
                throw new Exception('Product "' . $item['product_id'] . '" does not have enough stock.');
            }
        }



        // Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order_id]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
