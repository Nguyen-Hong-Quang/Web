// JavaScript cho Quang Trong Hang Shop

document.addEventListener("DOMContentLoaded", function () {
  // Add product to cart
  const addToCartForms = document.querySelectorAll(".add-to-cart-form");
  addToCartForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const productId = this.dataset.productId;
      const sizeInput = this.querySelector('input[name="size"]:checked');

      if (!sizeInput) {
        showAlert("Please select size!", "warning");
        return;
      }

      const formData = new FormData();
      formData.append("product_id", productId);
      formData.append("size", sizeInput.value);
      formData.append("quantity", 1);

      const button = this.querySelector('button[type="submit"]');
      const originalText = button.innerHTML;
      button.innerHTML = '<span class="loading"></span> Adding...';
      button.disabled = true;

      fetch("api/cart.php?action=add", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showAlert(data.message, "success");
            // Reset form
            this.reset();
          } else {
            showAlert(data.message, "danger");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showAlert("An error occurred!", "danger");
        })
        .finally(() => {
          button.innerHTML = originalText;
          button.disabled = false;
        });
    });
  });

  // Cập nhật số lượng trong giỏ hàng
  const quantityButtons = document.querySelectorAll(".quantity-btn");
  quantityButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const action = this.dataset.action;
      const cartId = this.dataset.cartId;
      const quantityInput = this.parentElement.querySelector(".quantity-input");
      let currentQuantity = parseInt(quantityInput.value);

      if (action === "increase") {
        currentQuantity++;
      } else if (action === "decrease" && currentQuantity > 1) {
        currentQuantity--;
      }

      updateCartQuantity(cartId, currentQuantity);
    });
  });

  // Xóa sản phẩm khỏi giỏ hàng
  const removeButtons = document.querySelectorAll(".remove-from-cart");
  removeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (confirm("Are you sure you want to remove this item?")) {
        const cartId = this.dataset.cartId;
        removeFromCart(cartId);
      }
    });
  });

  // Thanh toán
  const checkoutButton = document.getElementById("checkout-btn");
  if (checkoutButton) {
    checkoutButton.addEventListener("click", function () {
      if (confirm("Confirm order?")) {
        checkout();
      }
    });
  }

  // Smooth scrolling for anchor links
  const anchorLinks = document.querySelectorAll('a[href^="#"]');
  anchorLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href").substring(1);
      const targetElement = document.getElementById(targetId);

      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Animate on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in-up");
      }
    });
  }, observerOptions);

  // Observe product cards
  const productCards = document.querySelectorAll(".product-card");
  productCards.forEach((card) => {
    observer.observe(card);
  });

  // Form validation enhancement
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const requiredFields = this.querySelectorAll("[required]");
      let isValid = true;

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          field.classList.add("is-invalid");
          isValid = false;
        } else {
          field.classList.remove("is-invalid");
        }
      });

      if (!isValid) {
        e.preventDefault();
        showAlert("Please fill in all required information!", "warning");
      }
    });

    // Remove invalid class on input
    const inputs = form.querySelectorAll("input, textarea, select");
    inputs.forEach((input) => {
      input.addEventListener("input", function () {
        if (this.classList.contains("is-invalid") && this.value.trim()) {
          this.classList.remove("is-invalid");
        }
      });
    });
  });
});

//Function to update cart quantity
function updateCartQuantity(cartId, quantity) {
  const formData = new FormData();
  formData.append("cart_id", cartId);
  formData.append("quantity", quantity);

  fetch("api/cart.php?action=update", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        //Update interface
        const quantityInput = document
          .querySelector(`[data-cart-id="${cartId}"]`)
          .parentElement.querySelector(".quantity-input");
        quantityInput.value = quantity;

        //Update total (implementation needed)
        updateCartTotal();
      } else {
        showAlert("An error occurred while updating!", "danger");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showAlert("An error occurred!", "danger");
    });
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(cartId) {
  const formData = new FormData();
  formData.append("cart_id", cartId);

  fetch("api/cart.php?action=remove", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Xóa sản phẩm khỏi giao diện
        const cartItem = document
          .querySelector(`[data-cart-id="${cartId}"]`)
          .closest(".card");
        cartItem.remove();

        // Cập nhật tổng tiền
        updateCartTotal();

        // Kiểm tra giỏ hàng trống
        const remainingItems = document.querySelectorAll(".card").length;
        if (remainingItems === 0) {
          location.reload();
        }

        showAlert("Removed item from cart!", "success");
      } else {
        showAlert("An error occurred while removing!", "danger");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showAlert("An error occurred!", "danger");
    });
}

// Hàm thanh toán
function checkout() {
  const checkoutButton = document.getElementById("checkout-btn");
  const originalText = checkoutButton.innerHTML;
  checkoutButton.innerHTML = '<span class="loading"></span> Processing...';
  checkoutButton.disabled = true;

  fetch("api/cart.php?action=checkout", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showAlert(data.message, "success");
        setTimeout(() => {
          window.location.href = "profile.php";
        }, 2000);
      } else {
        showAlert(data.message, "danger");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showAlert("An error occurred while placing the order!", "danger");
    })
    .finally(() => {
      checkoutButton.innerHTML = originalText;
      checkoutButton.disabled = false;
    });
}

// Hàm cập nhật tổng tiền giỏ hàng
function updateCartTotal() {
  // Implementation để tính lại tổng tiền
  // Có thể gọi API hoặc tính toán trực tiếp từ DOM
}

// Hàm hiển thị thông báo
function showAlert(message, type = "info") {
  // Tạo alert element
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  alertDiv.style.cssText =
    "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  // Thêm vào body
  document.body.appendChild(alertDiv);

  // Tự động ẩn sau 5 giây
  setTimeout(() => {
    if (alertDiv.parentNode) {
      alertDiv.remove();
    }
  }, 5000);
}

// Hàm format số tiền
function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN").format(amount) + " VND";
}

// Hàm lazy loading cho hình ảnh
function initLazyLoading() {
  const images = document.querySelectorAll("img[data-src]");

  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.classList.remove("lazy");
        imageObserver.unobserve(img);
      }
    });
  });

  images.forEach((img) => imageObserver.observe(img));
}

// Khởi tạo lazy loading khi DOM ready
document.addEventListener("DOMContentLoaded", initLazyLoading);
