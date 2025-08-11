<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Profile  - Quang Trong Hang Shop';
$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Lấy đơn hàng của người dùng
$stmt = $pdo->prepare("
    SELECT o.*, COUNT(oi.id) as item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    if ($stmt->execute([$full_name, $email, $phone, $address, $user_id])) {
        $success = 'Profile updated successfully!';
        $_SESSION['full_name'] = $full_name;
        // Refresh user data
        $user['full_name'] = $full_name;
        $user['email'] = $email;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        $error = 'An error occurred while updating!';
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user-circle fa-3x text-red mb-3"></i>
                    <h5><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#profile-info">Personal Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#order-history">Order History</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profile-info">
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>

                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-red bg-danger text-white">Update Profile</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="order-history">
                            <?php if (empty($orders)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5>No Orders Found</h5>
                                    <p>You have no orders yet. Start shopping now!</p>
                                    <a href="homepage.php" class="btn btn-red">Shop Now</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <div class="border rounded p-3 mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <strong>Order #<?php echo $order['id']; ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></small>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="badge bg-<?php echo $order['status'] == 'delivered' ? 'success' : ($order['status'] == 'cancelled' ? 'danger' : 'warning'); ?>">
                                                    <?php
                                                    $status_text = [
                                                        'pending' => 'Pending',
                                                        'processing' => 'Processing',
                                                        'shipped' => 'Shipped',
                                                        'delivered' => 'Delivered',
                                                        'cancelled' => 'Cancelled'
                                                    ];
                                                    echo $status_text[$order['status']] ?? $order['status'];
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <?php echo $order['item_count']; ?> items
                                            </div>
                                            <div class="col-md-3">
                                                <strong><?php echo number_format($order['total_amount']); ?> VND</strong>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>