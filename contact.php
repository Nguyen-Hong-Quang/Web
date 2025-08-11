<?php
session_start();
$page_title = 'Liên hệ - Quang Trong Hang Shop';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Ở đây bạn có thể lưu vào database hoặc gửi email
    // Tạm thời chỉ hiển thị thông báo thành công
    $success = 'Thank you for contacting us! We will get back to you as soon as possible.';
}
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-5">Contact Us</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Contact Information</h5>

                            <div class="contact-info">
                                <div class="contact-item mb-3">
                                    <i class="fas fa-map-marker-alt text-red me-3"></i>
                                    <div>
                                        <strong>Address:</strong><br>
                                        113 Cho Giuong Street, Hanoi
                                    </div>
                                </div>

                                <div class="contact-item mb-3">
                                    <i class="fas fa-phone text-red me-3"></i>
                                    <div>
                                        <strong>Phone:</strong><br>
                                        0394169411
                                    </div>
                                </div>

                                <div class="contact-item mb-3">
                                    <i class="fas fa-envelope text-red me-3"></i>
                                    <div>
                                        <strong>Email:</strong><br>
                                        quangdegea13@gmail.com
                                    </div>
                                </div>

                                <div class="contact-item mb-3">
                                    <i class="fas fa-clock text-red me-3"></i>
                                    <div>
                                        <strong>Working Hours:</strong><br>
                                        Monday - Sunday: 8:00 AM - 10:00 PM
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Send Message</h5>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-red bg-danger text-white w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>