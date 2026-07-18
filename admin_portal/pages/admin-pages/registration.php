<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../../../main.php?frmid=7");
    exit;
}
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

        <!-- Header Section -->
        <div class="registration-header">
            <h2>Registration</h2>
        </div>

        <!-- Navigational Tab Bar (Directly connects to panel below) -->
        <div class="tab-bar">
            <button type="button" class="action-btn active-tab">Add</button>
            <button type="button" class="action-btn">Make Registration</button>
            <button type="button" class="action-btn">Renew Registration</button>
            <button type="button" class="action-btn">Update Registration</button>
        </div>

        <!-- Main Form Panel Display -->
        <div class="registration-form-container booking-panel">
            <form>

                <!-- 2-Column Responsive Layout Grid -->
                <div class="form-grid booking-form-grid">

                    <!-- Left Column Fields -->
                    <div class="grid-column left-column">
                        <div class="form-group row-group">
                            <label for="cpf-no">Enter CPF No. :</label>
                            <div class="search-input-group">
                                <input type="text" id="cpf-no" name="cpf_no" placeholder="e.g., 12345">
                                <button type="button" class="btn-search">Search</button>
                            </div>
                        </div>

                        <div class="form-group row-group">
                            <label for="member-name">Member Name:</label>
                            <input type="text" id="member-name" name="member_name">
                        </div>

                        <div class="form-group row-group">
                            <label for="mobile-number">Mobile Number:</label>
                            <input type="tel" id="mobile-number" name="mobile_number">
                        </div>

                        <div class="form-group row-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email">
                        </div>

                        <div class="form-group row-group">
                            <label for="purpose">Purpose:</label>
                            <select id="purpose" name="purpose">
                                <option value="" disabled selected>--Select--</option>
                                <option value="vacation">Vacation</option>
                                <option value="business">Business</option>
                                <option value="training">Training</option>
                            </select>
                        </div>

                        <div class="form-group row-group">
                            <label for="booking-date">Booking Date:</label>
                            <input type="date" id="booking-date" name="booking_date">
                        </div>

                        <div class="form-group row-group">
                            <label for="amount">Amount:</label>
                            <input type="number" id="amount" name="amount">
                        </div>

                        <div class="form-group row-group">
                            <label for="transaction-date">Dated:</label>
                            <input type="date" id="transaction-date" name="transaction_date">
                        </div>

                        <div class="form-group row-group">
                            <label for="transaction-no">Draft/Cheque/Transaction No:</label>
                            <input type="text" id="transaction-no" name="transaction_no">
                        </div>
                    </div>

                    <!-- Right Column Fields -->
                    <div class="grid-column right-column">
                        <div class="form-group row-group">
                            <label for="designation-text">Designation:</label>
                            <input type="text" id="designation-text" name="designation_text">
                        </div>

                        <div class="form-group row-group">
                            <label for="office-epbax">EPBAX-Office:</label>
                            <input type="text" id="office-epbax" name="office_epbax">
                        </div>

                        <div class="form-group row-group">
                            <label for="residence-no">Residence No:</label>
                            <input type="text" id="residence-no" name="residence_no">
                        </div>

                        <div class="form-group row-group">
                            <label for="relation-employee">Relation with Employee:</label>
                            <select id="relation-employee" name="relation_employee">
                                <option value="" disabled selected>--Select--</option>
                                <option value="self">Self</option>
                                <option value="spouse">Spouse</option>
                                <option value="child">Child</option>
                                <option value="parent">Parent</option>
                            </select>
                        </div>

                        <div class="form-group row-group">
                            <label for="no-days">No of Days:</label>
                            <select id="no-days" name="no_days">
                                <option value="" disabled selected>--Select--</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select>
                        </div>

                        <div class="form-group row-group">
                            <label for="payment-by">Payment By:</label>
                            <select id="payment-by" name="payment_by">
                                <option value="" disabled selected>--Select--</option>
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="net_banking">Net Banking</option>
                            </select>
                        </div>

                        <div class="form-group row-group">
                            <label for="bank-name">Bank Name:</label>
                            <input type="text" id="bank-name" name="bank_name">
                        </div>
                    </div>
                </div>

                <!-- Footer Operations Toolbar -->
                <div class="form-action-row booking-action-row">
                    <button type="submit" class="action-btn btn-booking-action">Book</button>
                    <button type="button" class="action-btn btn-booking-action btn-cancel">Cancel</button>
                    <button type="button" class="action-btn btn-booking-action btn-print">Print</button>
                </div>

            </form>
        </div>
    </div>


    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>