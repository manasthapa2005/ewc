<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Secure page - check admin session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../main.php?frmid=7");
    exit;
}

include("../db.php");

if (isset($_POST['submit_booking'])) {

    $id_no                  = trim($_POST['id_no'] ?? '');
    $customer_name          = trim($_POST['customer_name'] ?? '');
    $designation            = trim($_POST['designation'] ?? '');
    $mobile_number          = trim($_POST['mobile_number'] ?? '');
    $epbax_office           = trim($_POST['epbax_office'] ?? '');
    $email                  = trim($_POST['email'] ?? '');
    $residence_no           = trim($_POST['residence_no'] ?? '');
    $booking_for            = trim($_POST['booking_for'] ?? '');
    $relation_with_employee = trim($_POST['relation_with_employee'] ?? '');
    $booking_date           = trim($_POST['booking_date'] ?? '');
    $no_of_days             = intval($_POST['no_of_days'] ?? 0);
    $amount                 = !empty($_POST['amount']) ? floatval($_POST['amount']) : null;
    $payment_by             = trim($_POST['payment_by'] ?? '');
    $payment_date           = !empty($_POST['payment_date']) ? trim($_POST['payment_date']) : null;
    $bank_name              = !empty($_POST['bank_name']) ? trim($_POST['bank_name']) : null;
    $cheque_transaction_no  = !empty($_POST['cheque_transaction_no']) ? trim($_POST['cheque_transaction_no']) : null;

    // Basic validation
    if (empty($id_no) || empty($customer_name) || empty($booking_date) || empty($booking_for)) {
        $_SESSION['booking_error'] = "All required fields must be filled in.";
        header("Location: booking.php");
        exit;
    }

    if (!$connect) {
        $_SESSION['booking_error'] = "Database connection error.";
        header("Location: booking.php");
        exit;
    }

    // Availability Check in facility_bookings
    // If selecting 'Community Hall', we check for 'Community Hall' OR 'Community Center' (as they are functionally the same)
    $facility_pattern_1 = $booking_for;
    $facility_pattern_2 = ($booking_for === 'Community Hall') ? 'Community Center' : $booking_for;

    $check_query = "SELECT id FROM facility_bookings 
                    WHERE booking_date = ? 
                      AND booking_status = 'Booked' 
                      AND (
                           booking_for = ? OR booking_for LIKE CONCAT(?, ' - %')
                           OR booking_for = ? OR booking_for LIKE CONCAT(?, ' - %')
                      )";
    $check_stmt = mysqli_prepare($connect, $check_query);
    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "sssss", $booking_date, $facility_pattern_1, $facility_pattern_1, $facility_pattern_2, $facility_pattern_2);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $_SESSION['booking_error'] = "The facility is already booked for the selected date.";
            mysqli_stmt_close($check_stmt);
            header("Location: booking.php");
            exit;
        }
        mysqli_stmt_close($check_stmt);
    }

    // Map payment type to database enum: enum('CASH','CHEQUE','ONLINE_TRANSFER','UPI')
    $payment_map = [
        'Cash' => 'CASH',
        'Cheque' => 'CHEQUE',
        'DD' => 'ONLINE_TRANSFER',
        'UPI' => 'UPI',
        'NEFT' => 'ONLINE_TRANSFER'
    ];
    $db_payment_by = $payment_map[$payment_by] ?? 'CASH';

    // Insert into booking_requests table first
    // Note: status is set to 'APPROVED' because the admin has directly booked it
    $req_status = 'APPROVED';
    $req_stmt = mysqli_prepare($connect, "INSERT INTO booking_requests (cpf_no, relation_with_employee, purpose, no_of_days, booking_date, payment_by, amount, bank_name, transaction_no, payment_date, booking_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($req_stmt) {
        mysqli_stmt_bind_param($req_stmt, "sssisdsssss", $id_no, $relation_with_employee, $booking_for, $no_of_days, $booking_date, $db_payment_by, $amount, $bank_name, $cheque_transaction_no, $payment_date, $req_status);
        
        if (mysqli_stmt_execute($req_stmt)) {
            // Save to facility_bookings table as well for status tracking & calendar compatibility
            $facility_status = 'Booked';
            $fac_stmt = mysqli_prepare($connect, "INSERT INTO facility_bookings (id_no, customer_name, designation, mobile_number, booking_date, booking_for, booking_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($fac_stmt) {
                mysqli_stmt_bind_param($fac_stmt, "sssssss", $id_no, $customer_name, $designation, $mobile_number, $booking_date, $booking_for, $facility_status);
                
                if (mysqli_stmt_execute($fac_stmt)) {
                    $new_id = mysqli_insert_id($connect);
                    $_SESSION['booking_success'] = "Booking created successfully! Booking ID: $new_id";
                } else {
                    $_SESSION['booking_error'] = "Error saving facility booking: " . mysqli_stmt_error($fac_stmt);
                }
                mysqli_stmt_close($fac_stmt);
            } else {
                $_SESSION['booking_error'] = "Error preparing facility booking query.";
            }
        } else {
            $_SESSION['booking_error'] = "Error saving booking request: " . mysqli_stmt_error($req_stmt);
        }
        mysqli_stmt_close($req_stmt);
    } else {
        $_SESSION['booking_error'] = "Database error preparing booking request query.";
    }

} else {
    $_SESSION['booking_error'] = "Invalid booking request.";
}

header("Location: booking.php");
exit;
?>
