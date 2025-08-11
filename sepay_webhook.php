<?php
require_once 'config/database.php';

$data = json_decode(file_get_contents('php://input'), true);

// Ghi log để kiểm tra webhook
file_put_contents('log.json', json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

// Sửa: dùng 'content' và 'transferAmount' từ payload thực tế
if (isset($data['content']) && isset($data['transferAmount'])) {
    $content = $data['content'];
    $amount = $data['transferAmount'];

    // Kiểm tra định dạng ORDER <user_id>
    if (preg_match('/ORDER[\s-]+(\d+)/', $content, $matches)) {
        $user_id = $matches[1];

        // Kiểm tra xem đơn hàng đã tồn tại chưa
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND total_amount = ? AND status = 'pending'");
        $stmt->execute([$user_id, $amount]);
        $exists = $stmt->fetchColumn();

        if (!$exists) {
            // Thêm đơn hàng mới
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total_amount, status, created_at)
                VALUES (?, ?, 'pending', NOW())
            ");
            $stmt->execute([$user_id, $amount]);

            // Xóa giỏ hàng của người dùng
            $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

            http_response_code(200);
            echo json_encode(['status' => 'success']);
            exit;
        } else {
            http_response_code(200);
            echo json_encode(['status' => 'duplicate']);
            exit;
        }
    }
}

http_response_code(400);
echo json_encode(['error' => 'Invalid payload']);
