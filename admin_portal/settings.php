<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../main.php?frmid=7");
    exit;
}
include("../db.php");

$popup_message = '';
$popup_type    = ''; // 'success' or 'error'

// Handle password change form submission
if (isset($_POST['submit_change_password'])) {
    $new_password     = trim($_POST['new_password']     ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $admin_cpf        = $_SESSION['admin_cpf'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $popup_message = 'Please fill in both password fields.';
        $popup_type    = 'error';
    } elseif (strlen($new_password) < 6) {
        $popup_message = 'Password must be at least 6 characters long.';
        $popup_type    = 'error';
    } elseif ($new_password !== $confirm_password) {
        $popup_message = 'Passwords do not match. Please try again.';
        $popup_type    = 'error';
    } elseif (empty($admin_cpf)) {
        $popup_message = 'Session error. Please log in again.';
        $popup_type    = 'error';
    } else {
        // Update password in admin_credentials table
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($connect, "UPDATE admin_credentials SET password_hash = ? WHERE cpf_no = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $hashed, $admin_cpf);
            if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
                $popup_message = 'Password saved successfully!';
                $popup_type    = 'success';
            } else {
                $popup_message = 'No changes made. Please try a different password.';
                $popup_type    = 'error';
            }
            mysqli_stmt_close($stmt);
        } else {
            $popup_message = 'Database error. Please try again.';
            $popup_type    = 'error';
        }
    }
}
include("head.php");
?>
<body class="interior-body">
<?php include("menu.php"); ?>

    <!-- Unique parent wrapper to completely isolate all styles -->
    <div class="password-module-scoped">

        <!-- Title Header Section -->
        <div class="password-header">
            <h2>Change Password</h2>
        </div>

        <!-- Main Outer Brown Border Box Panel Container -->
        <div class="password-panel">
            <form method="POST" action="settings.php" id="change-password-form">

                <!-- Enter New Password Field Row -->
                <div class="form-group row-group">
                    <label for="new-password">Enter New Password:</label>
                    <input type="password" id="new-password" name="new_password" required minlength="6" placeholder="Minimum 6 characters">
                </div>

                <!-- Confirm Password Field Row -->
                <div class="form-group row-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm_password" required placeholder="Re-enter new password">
                </div>

                <!-- Submit Action Row Alignment -->
                <div class="form-action-row">
                    <button type="submit" name="submit_change_password" class="btn-change-password">Change Password</button>
                </div>

            </form>
        </div>

    </div>

    <!-- ========== POPUP TOAST NOTIFICATION ========== -->
    <?php if (!empty($popup_message)): ?>
    <div id="pw-popup-overlay" style="
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.35);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;">
        <div id="pw-popup-box" style="
            background: #fff;
            border-radius: 12px;
            padding: 36px 40px;
            max-width: 420px; width: 90%;
            text-align: center;
            box-shadow: 0 8px 40px rgba(0,0,0,0.22);
            font-family: 'Outfit', sans-serif;
            animation: popIn 0.25s ease;">

            <?php if ($popup_type === 'success'): ?>
                <div style="font-size: 52px; margin-bottom: 14px;">✅</div>
                <h3 style="color: #15803d; font-size: 20px; margin: 0 0 10px;">Success!</h3>
                <p style="color: #374151; font-size: 15px; margin: 0 0 24px;"><?php echo htmlspecialchars($popup_message); ?></p>
            <?php else: ?>
                <div style="font-size: 52px; margin-bottom: 14px;">❌</div>
                <h3 style="color: #dc2626; font-size: 20px; margin: 0 0 10px;">Error</h3>
                <p style="color: #374151; font-size: 15px; margin: 0 0 24px;"><?php echo htmlspecialchars($popup_message); ?></p>
            <?php endif; ?>

            <button onclick="document.getElementById('pw-popup-overlay').style.display='none';"
                style="
                    padding: 10px 32px;
                    background-color: <?php echo $popup_type === 'success' ? '#15803d' : '#dc2626'; ?>;
                    color: white;
                    border: none;
                    border-radius: 7px;
                    font-size: 15px;
                    font-weight: 600;
                    font-family: 'Outfit', sans-serif;
                    cursor: pointer;">
                OK
            </button>
        </div>
    </div>
    <style>
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.85); }
            to   { opacity: 1; transform: scale(1); }
        }
    </style>
    <?php endif; ?>

    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Client-side validation before submit
        document.getElementById('change-password-form').addEventListener('submit', function(e) {
            const np = document.getElementById('new-password').value;
            const cp = document.getElementById('confirm-password').value;
            if (np !== cp) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
            }
        });
    </script>

</body>
</html>