<?php
include("head.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}
?>

<body class="interior-body">

    <header class="main-header">
        <div class="container header-container">
            <div class="brand-identity">
                <img src="../assets/images/ongc_logo.png" alt="ONGC Logo" class="logo-ongc">
                <div class="brand-text">
                    <h1>ONGC EWC-DEHRADUN</h1>
                    <p>EMPLOYEE WELFARE COMMITTEE</p>
                </div>
            </div>

            <nav class="main-nav">
                <div class="nav-links">
                    <a href="admin.php" class="nav-item current"><i class="fa-solid fa-house"></i> Home</a>
                    <a href="add-exec.php" class="nav-item"><i class="fa-regular fa-address-book"></i>
                        Executive Member</a>
                    <a href="album.php" class="nav-item"><i class="fa-regular fa-calendar-check"></i>
                        New Album</a>
                    <a href="event.php" class="nav-item">New Event</a>
                    <a href="registration.php" class="nav-item"><i class="fa-regular fa-image"></i>
                        Registration</a>
                    <a href="booking.php" class="nav-item"><i class="fa-solid fa-user-tie"></i> Bookings</a>
                    <a href="book-req.php" class="nav-item bg-pill">View Booking Request</a>

                    <a href="settings.php" class="admin-login-btn"><i class="fa-regular fa-circle-user"></i>Account Settings <i class="fa fa-chevron-down"></i></a>
                    <a href="logout.php" class="admin-login-btn" style="background-color: #dc2626; color: white;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <?php if (isset($_SESSION['login_success_msg'])): ?>
        <div class="login-success-banner" style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin: 20px auto; max-width: 600px; text-align: center; border: 1px solid #a7f3d0; font-weight: 500; font-family: 'Outfit', sans-serif; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
            <?php 
            echo htmlspecialchars($_SESSION['login_success_msg']); 
            unset($_SESSION['login_success_msg']);
            ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin: 40px 0; font-family: 'Outfit', sans-serif;">
        <h2 style="font-size: 2.5rem; color: #3b2314; font-weight: 600;">Hello, <span style="color: #854d0e;"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>!</h2>
        <p style="color: #6b7280; font-size: 1.1rem; margin-top: 10px;">Welcome back to the ONGC Employee Welfare Committee Admin Portal.</p>
    </div>

    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>