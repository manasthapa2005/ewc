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

if (isset($_POST['submit_cancel']) && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);

    if (empty($booking_id)) {
        $_SESSION['booking_error'] = "Please select a valid booking to cancel.";
        header("Location: booking.php");
        exit;
    }

    if (!$connect) {
        $_SESSION['booking_error'] = "Database connection error.";
        header("Location: booking.php");
        exit;
    }

    // Get the booking details before updating so we can find and cancel it in booking_requests as well
    $details_query = "SELECT id_no, booking_date, booking_for FROM facility_bookings WHERE id = ?";
    $details_stmt = mysqli_prepare($connect, $details_query);
    $booking_details = null;
    if ($details_stmt) {
        mysqli_stmt_bind_param($details_stmt, "i", $booking_id);
        mysqli_stmt_execute($details_stmt);
        $res = mysqli_stmt_get_result($details_stmt);
        if ($res) {
            $booking_details = mysqli_fetch_assoc($res);
        }
        mysqli_stmt_close($details_stmt);
    }

    // Update status to Cancelled using a prepared statement
    $stmt = mysqli_prepare($connect, "UPDATE facility_bookings SET booking_status = 'Cancelled' WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $booking_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['booking_success'] = "Booking (ID: $booking_id) was successfully cancelled.";

            // If details found, sync with booking_requests table
            if ($booking_details) {
                $req_cancel_stmt = mysqli_prepare($connect, "UPDATE booking_requests SET booking_status = 'CANCELLED' WHERE cpf_no = ? AND booking_date = ? AND purpose = ? AND booking_status = 'APPROVED' LIMIT 1");
                if ($req_cancel_stmt) {
                    mysqli_stmt_bind_param($req_cancel_stmt, "sss", $booking_details['id_no'], $booking_details['booking_date'], $booking_details['booking_for']);
                    mysqli_stmt_execute($req_cancel_stmt);
                    mysqli_stmt_close($req_cancel_stmt);
                }
            }
        } else {
            $_SESSION['booking_error'] = "Error cancelling booking: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['booking_error'] = "Database error preparing cancellation query.";
    }
} else {
    $_SESSION['booking_error'] = "Invalid cancellation request.";
}

header("Location: booking.php");
exit;
?>
