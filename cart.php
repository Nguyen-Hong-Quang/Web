<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Cart - Quang Trong Hang Shop';
$user_id = $_SESSION['user_id'];

// Lấy sản phẩm trong giỏ hàng
$stmt = $pdo->prepare("
    SELECT c.*, p.title, p.price, p.images 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Your Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>Cart is empty</h4>
            <p>You have no products in your shopping cart.</p>
            <a href="homepage.php" class="btn btn-red bg-danger">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <?php foreach ($cart_items as $item): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <?php
                                    $images = explode(',', $item['images']);
                                    $main_image = trim($images[0]);
                                    ?>
                                    <img src="<?php echo $main_image; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="img-fluid rounded">
                                </div>
                                <div class="col-md-4">
                                    <h5><?php echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="text-muted">Size: <?php echo $item['size']; ?></p>
                                </div>
                                <div class="col-md-2">
                                    <strong><?php echo number_format($item['price']); ?> VND</strong>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary btn-sm quantity-btn" data-action="decrease" data-cart-id="<?php echo $item['id']; ?>">-</button>
                                        <input type="text" class="form-control form-control-sm text-center quantity-input" value="<?php echo $item['quantity']; ?>" readonly>
                                        <button class="btn btn-outline-secondary btn-sm quantity-btn" data-action="increase" data-cart-id="<?php echo $item['id']; ?>">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-danger btn-sm remove-from-cart" data-cart-id="<?php echo $item['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Order</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal"><?php echo number_format($total); ?> VND</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping Fee:</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total"><?php echo number_format($total); ?> VND</strong>
                        </div>
                        <button class="btn btn-red bg-danger text-white w-100 mb-2" id="checkout-btn">Checkout</button>
                        <div class="text-center mt-3">
                            <p class="mb-2">Please scan the QR code below to transfer:</p>
                            <img src="assets/img/qr.png" alt="SePay payment QR" style="width: 250px;">
                            <p class="mt-2 text-muted">Transfer content: <strong><?php echo 'ORDER-' . $user_id . '-' . time(); ?></strong></p>
                        </div>

                        <a href="homepage.php" class="btn btn-outline-secondary bg-danger text-white w-100">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>