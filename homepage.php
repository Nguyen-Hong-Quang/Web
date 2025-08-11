<?php
session_start();
require_once 'config/database.php';

$page_title = 'Homepage - Quang Trong Hang Shop';

// Get product list
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex flex-column ">
  <div class="bg-dark">
    <!-- Banner Section -->
    <div class="container-fluid p-0">
      <div id="main-banner" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#main-banner" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#main-banner" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#main-banner" data-bs-slide-to="2"></button>
          <button type="button" data-bs-target="#main-banner" data-bs-slide-to="3"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="assets/img/banner9.jpg" class="d-block w-100 img-fluid banner-img" alt="MU1">
          </div>
          <div class="carousel-item">
            <img src="assets/img/banner8.jpg" class="d-block w-100 img-fluid banner-img" alt="MU2">
          </div>
          <div class="carousel-item">
            <img src="assets/img/banner7.jpg" class="d-block w-100 img-fluid banner-img" alt="MU3">
          </div>
          <div class="carousel-item">
            <img src="assets/img/banner8.2.jpg" class="d-block w-100 img-fluid banner-img" alt="MU4">
          </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#main-banner" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#main-banner" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>

  </div>
</div>

<section id="products" class="pt-5">
  <div class="container">
    <h2 class="text-center mt-5">Featured Products</h2>
    <div class="row">
      <?php foreach ($products as $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
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
                    <label class="form-label">Choose size:</label>
                    <div class="size-options">
                      <?php
                      $sizes = explode(',', $product['size']);
                      foreach ($sizes as $size):
                        $size = trim($size);
                      ?>
                        <input type="radio" class="btn-check" name="size" id="size_<?php echo $product['id']; ?>_<?php echo $size; ?>" value="<?php echo $size; ?>" required>
                        <label class="btn btn-outline-red btn-sm border border-danger" for="size_<?php echo $product['id']; ?>_<?php echo $size; ?>"><?php echo $size; ?></label>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                  </button>
                </form>
              <?php else: ?>
                <a href="login.php" class="btn btn-red bg-danger text-white w-100">Login to Purchase</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>