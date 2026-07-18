<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../../../main.php?frmid=7");
    exit;
}
include("../../../db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welfare Schemes | ONGC EWC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="interior-body">

    <header class="main-header">
        <div class="container header-container">
            <div class="brand-identity">
                <img src="../../assets/images/ongc_logo.png" alt="ONGC Logo" class="logo-ongc">
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
    <div class="registration-module-isolated">

        <div class="registration-header">
            <h2>Booking</h2>
        </div>

        <?php if (isset($_SESSION['booking_success'])): ?>
            <div class="booking-success-alert" style="color: #065f46; background-color: #d1fae5; border: 1px solid #a7f3d0; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['booking_success']); unset($_SESSION['booking_success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['booking_error'])): ?>
            <div class="booking-error-alert" style="color: #991b1b; background-color: #fee2e2; border: 1px solid #fecaca; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['booking_error']); unset($_SESSION['booking_error']); ?>
            </div>
        <?php endif; ?>

        <div class="registration-actions-box tab-bar">
            <button type="button" class="action-btn active-tab" onclick="switchBookingTab(event, 'view-panel')">View Bookings</button>
            <button type="button" class="action-btn" onclick="switchBookingTab(event, 'make-panel')">Make Booking</button>
            <button type="button" class="action-btn" onclick="switchBookingTab(event, 'cancel-panel')">Cancel Booking</button>
        </div>

        <!-- PANEL 1: VIEW BOOKINGS (TABLE) -->
        <div id="view-panel" class="registration-form-container booking-panel active-panel">
            <div class="booking-status-scoped" style="padding: 0;">
                <div class="status-table-container">
                    <table class="status-data-table">
                        <thead>
                            <tr>
                                <th class="col-id">Booking ID</th>
                                <th class="col-date">Booking Date</th>
                                <th>CPF No</th>
                                <th>Customer Name</th>
                                <th>Designation</th>
                                <th>Mobile</th>
                                <th>Facility</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_bookings = "SELECT id, booking_date, booking_status, id_no, customer_name, designation, mobile_number, booking_for FROM facility_bookings ORDER BY booking_date DESC, id DESC";
                            $res_bookings = mysqli_query($connect, $sql_bookings);
                            if ($res_bookings && mysqli_num_rows($res_bookings) > 0) {
                                while ($row = mysqli_fetch_assoc($res_bookings)) {
                                    $status_class = "";
                                    if ($row['booking_status'] === 'Booked') {
                                        $status_class = "color: #16a34a; font-weight: bold;";
                                    } elseif ($row['booking_status'] === 'Pending') {
                                        $status_class = "color: #d97706; font-weight: bold;";
                                    } else {
                                        $status_class = "color: #dc2626; font-weight: bold;";
                                    }
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['id_no']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['designation']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mobile_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['booking_for']) . "</td>";
                                    echo "<td style='" . $status_class . "'>" . htmlspecialchars($row['booking_status']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align: center;'>No bookings found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PANEL 2: MAKE BOOKING (FORM) -->
        <div id="make-panel" class="registration-form-container booking-panel" style="display: none;">
            <h3>Make a New Facility Booking:</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Fill in the details below to create a new booking entry.</p>

            <form action="../../make-booking-process.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; max-width: 780px;">

                    <div class="row-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="mb-idno" style="font-weight: 600; font-size: 14px;">CPF / ID Number: <span style="color: #dc2626;">*</span></label>
                        <input type="text" id="mb-idno" name="id_no" placeholder="e.g. 00123456" required
                            style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div class="row-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="mb-name" style="font-weight: 600; font-size: 14px;">Customer Name: <span style="color: #dc2626;">*</span></label>
                        <input type="text" id="mb-name" name="customer_name" placeholder="Full name of the employee" required
                            style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div class="row-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="mb-desig" style="font-weight: 600; font-size: 14px;">Designation: <span style="color: #dc2626;">*</span></label>
                        <input type="text" id="mb-desig" name="designation" placeholder="e.g. Senior Engineer" required
                            style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div class="row-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="mb-mobile" style="font-weight: 600; font-size: 14px;">Mobile Number: <span style="color: #dc2626;">*</span></label>
                        <input type="tel" id="mb-mobile" name="mobile_number" placeholder="10-digit mobile number" maxlength="10" required
                            style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div class="row-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="mb-date" style="font-weight: 600; font-size: 14px;">Booking Date: <span style="color: #dc2626;">*</span></label>
                        <input type="date" id="mb-date" name="booking_date" required
                            style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div class="row-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="mb-facility" style="font-weight: 600; font-size: 14px;">Booking For (Facility): <span style="color: #dc2626;">*</span></label>
                        <select id="mb-facility" name="booking_for" required
                            style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none; background: white;">
                            <option value="" disabled selected>-- Select Facility --</option>
                            <option value="Guest House">Guest House</option>
                            <option value="Holiday Home">Holiday Home</option>
                            <option value="Club">Club</option>
                            <option value="Stadium">Stadium</option>
                            <option value="Community Hall">Community Hall</option>
                        </select>
                    </div>

                    <div class="row-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="mb-status" style="font-weight: 600; font-size: 14px;">Booking Status:</label>
                        <select id="mb-status" name="booking_status"
                            style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none; background: white;">
                            <option value="Pending" selected>Pending</option>
                            <option value="Booked">Booked</option>
                        </select>
                    </div>

                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" name="submit_booking" class="btn-booking-action btn-confirm"
                        style="padding: 11px 28px; background-color: #166534; color: white; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; font-family: 'Outfit', sans-serif; cursor: pointer;">
                        <i class="fa-solid fa-calendar-check" style="margin-right: 8px;"></i>Confirm Booking
                    </button>
                    <button type="reset"
                        style="padding: 11px 22px; background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 15px; font-weight: 600; font-family: 'Outfit', sans-serif; cursor: pointer;">
                        Clear Form
                    </button>
                </div>
            </form>
        </div>

        <!-- PANEL 3: CANCEL BOOKING (FORM) -->
        <div id="cancel-panel" class="registration-form-container booking-panel" style="display: none;">
            <h3>Cancel Facility Booking:</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Select an active facility booking from the dropdown below to cancel it.</p>
            
            <form action="../../cancel-booking-process.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                <div class="row-group" style="max-width: 600px; display: flex; flex-direction: column; align-items: flex-start; gap: 8px;">
                    <label for="cancel-booking-id" style="font-weight: 600;">Select Booking to Cancel:</label>
                    <select id="cancel-booking-id" name="booking_id" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                        <option value="" disabled selected>-- Select Booking --</option>
                        <?php
                        $sql_active = "SELECT id, booking_date, id_no, customer_name, booking_for FROM facility_bookings WHERE booking_status IN ('Booked', 'Pending') ORDER BY booking_date DESC, id DESC";
                        $res_active = mysqli_query($connect, $sql_active);
                        if ($res_active && mysqli_num_rows($res_active) > 0) {
                            while ($row = mysqli_fetch_assoc($res_active)) {
                                echo '<option value="' . htmlspecialchars($row['id']) . '">' 
                                     . 'ID: ' . htmlspecialchars($row['id']) 
                                     . ' - ' . htmlspecialchars($row['customer_name']) 
                                     . ' (' . htmlspecialchars($row['booking_for']) . ' on ' . htmlspecialchars($row['booking_date']) . ')' 
                                     . '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>No active bookings found</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" name="submit_cancel" class="btn-booking-action btn-cancel" style="margin-top: 15px; width: max-content; display: inline-block;">Cancel Selected Booking</button>
                </div>
            </form>
        </div>
    </div>

    <!-- INTERACTIVE TAB SWITCHING SCRIPT -->
    <script>
        function switchBookingTab(evt, panelId) {
            // Hide all booking panels
            const panels = document.querySelectorAll('.registration-module-isolated .booking-panel');
            panels.forEach(panel => {
                panel.style.display = 'none';
                panel.classList.remove('active-panel');
            });

            // Remove active class from all tab buttons
            const tabs = document.querySelectorAll('.registration-module-isolated .tab-bar .action-btn');
            tabs.forEach(tab => {
                tab.classList.remove('active-tab');
            });

            // Show selected panel
            const targetPanel = document.getElementById(panelId);
            targetPanel.style.display = 'block';
            targetPanel.classList.add('active-panel');

            // Mark current tab as active
            evt.currentTarget.classList.add('active-tab');
        }
    </script>
    </div>

    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>