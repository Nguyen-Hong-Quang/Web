<?php
session_start();
require_once 'config/database.php';

$page_title = 'Products - Quang Trong Hang Shop';

// Lấy danh sách sản phẩm
$color = isset($_GET['color']) ? trim($_GET['color']) : '';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($color)) {
    $sql .= " AND color = :color";
    $params[':color'] = $color;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();


?>

<?php include 'includes/header.php'; ?>

<section id="products" class="py-5">
    <div class="container">
        <h2 class="text-center mt-4 mb-5">Featured Products</h2>
        <form method="GET" action="product.php" class="row g-3 mb-4 justify-content-end">
            <div class="col-md-3">
                <select class="form-select" name="color" aria-label="Select Color">
                    <option value="">-- Select Color --</option>
                    <option value="Red" <?php if (isset($_GET['color']) && $_GET['color'] == 'Red') echo 'selected'; ?>>Red</option>
                    <option value="White" <?php if (isset($_GET['color']) && $_GET['color'] == 'White') echo 'selected'; ?>>White</option>
                    <option value="Black" <?php if (isset($_GET['color']) && $_GET['color'] == 'Black') echo 'selected'; ?>>Black</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100">Search</button>
            </div>
        </form>

        <div class="row ">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-wrapper">
                        <div class="product-card">
                            <div class="product-image">
                                <?php
                                $images = explode(',', $product['images']);
                                $main_image = trim($images[0]);
                                ?>
                                <img src="<?php echo $main_image; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                <?php if ($product['title'] == 'Manchester United 2007/2008 season jersey'): ?>
                                    <div class="product-badge">Limited Edition</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h5 class="product-title fs-5 fw-bold"><?php echo htmlspecialchars($product['title']); ?></h5>
                                <p class="product-price"><?php echo number_format($product['price']); ?> VND</p>
                                <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                                <ul class="list-unstyled mb-2">
                                    <li><strong>Color:</strong> <?php echo htmlspecialchars($product['color']); ?></li>
                                    <li><strong>Material:</strong> <?php echo htmlspecialchars($product['material']); ?></li>
                                    <li><strong>Stock:</strong> <?php echo (int)$product['stock_quantity']; ?></li>
                                </ul>

                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form class="add-to-cart-form" data-product-id="<?php echo $product['id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Select Size:</label>
                                            <div class="size-options">
                                                <?php
                                                $sizes = explode(',', $product['size']);
                                                foreach ($sizes as $size):
                                                    $size = trim($size);
                                                ?>
                                                    <input type="radio" class="btn-check" name="size" id="size_<?php echo $product['id']; ?>_<?php echo $size; ?>" value="<?php echo $size; ?>" required>
                                                    <label class="btn btn-outline-red btn-sm" for="size_<?php echo $product['id']; ?>_<?php echo $size; ?>"><?php echo $size; ?></label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-red bg-danger text-white w-100">
                                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-red bg-danger text-white w-100">Login to Purchase</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>