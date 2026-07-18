<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../db.php");

if (!$connect) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Read parameters
$cpf_no                  = trim($_POST['cpf_no'] ?? '');
$customer_name          = trim($_POST['customer_name'] ?? '');
$designation            = trim($_POST['designation'] ?? '');
$phone_no                = trim($_POST['phone_no'] ?? '');
$relation_with_employee = trim($_POST['relation_with_employee'] ?? '');
$purpose                = trim($_POST['purpose'] ?? ''); // Maps to facility type requested
$no_of_days             = intval($_POST['no_of_days'] ?? 0);
$booking_date           = trim($_POST['booking_date'] ?? '');
$remarks                = trim($_POST['remarks'] ?? '');

// Simple validation
if (empty($cpf_no) || empty($customer_name) || empty($relation_with_employee) || empty($purpose) || empty($booking_date) || $no_of_days <= 0) {
    echo json_encode(['success' => false, 'message' => 'All mandatory fields (CPF No, Relation, Facility, Booking Date, and Days) must be filled in.']);
    exit;
}

// 1. Verify CPF number exists in admin_credentials first
$verify_query = "SELECT cpf_no FROM admin_credentials WHERE cpf_no = ? AND status = 'Active'";
$v_stmt = mysqli_prepare($connect, $verify_query);
if ($v_stmt) {
    mysqli_stmt_bind_param($v_stmt, "s", $cpf_no);
    mysqli_stmt_execute($v_stmt);
    mysqli_stmt_store_result($v_stmt);
    if (mysqli_stmt_num_rows($v_stmt) === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid or inactive CPF Number. Verification failed.']);
        mysqli_stmt_close($v_stmt);
        exit;
    }
    mysqli_stmt_close($v_stmt);
}

// 2. Check availability: is this facility already booked on the selected date in facility_bookings?
// If a booking is confirmed (status = 'Booked'), we prevent submitting another request for the same day.
$facility_pattern_1 = $purpose;
$facility_pattern_2 = ($purpose === 'Community Hall' || $purpose === 'Community Center Slot') ? 'Community Center' : $purpose;

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
        echo json_encode(['success' => false, 'message' => 'The selected facility is already booked for this date. Please check the Booking Status calendar.']);
        mysqli_stmt_close($check_stmt);
        exit;
    }
    mysqli_stmt_close($check_stmt);
}

// Start MySQL transaction to ensure consistency
mysqli_begin_transaction($connect);

try {
    // 3. Insert into booking_requests with 'PENDING' status
    $booking_status = 'PENDING';
    $payment_by = 'CASH';
    $amount = 0.00;

    $req_insert_query = "INSERT INTO booking_requests 
                         (cpf_no, relation_with_employee, purpose, no_of_days, booking_date, payment_by, amount, remarks, booking_status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $req_stmt = mysqli_prepare($connect, $req_insert_query);
    if (!$req_stmt) {
        throw new Exception("Failed to prepare booking request query.");
    }
    
    mysqli_stmt_bind_param($req_stmt, "sssisdsss", $cpf_no, $relation_with_employee, $purpose, $no_of_days, $booking_date, $payment_by, $amount, $remarks, $booking_status);
    if (!mysqli_stmt_execute($req_stmt)) {
        throw new Exception("Error saving booking request: " . mysqli_stmt_error($req_stmt));
    }
    $booking_request_id = mysqli_insert_id($connect);
    mysqli_stmt_close($req_stmt);

    // 4. Insert into facility_bookings with 'Pending' status (so it shows up on status calendar & admin lists)
    $facility_status = 'Pending';
    $fac_insert_query = "INSERT INTO facility_bookings 
                         (id_no, customer_name, designation, mobile_number, booking_date, booking_for, booking_status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
    $fac_stmt = mysqli_prepare($connect, $fac_insert_query);
    if (!$fac_stmt) {
        throw new Exception("Failed to prepare facility booking query.");
    }
    
    mysqli_stmt_bind_param($fac_stmt, "sssssss", $cpf_no, $customer_name, $designation, $phone_no, $booking_date, $purpose, $facility_status);
    if (!mysqli_stmt_execute($fac_stmt)) {
        throw new Exception("Error saving facility booking: " . mysqli_stmt_error($fac_stmt));
    }
    $facility_booking_id = mysqli_insert_id($connect);
    mysqli_stmt_close($fac_stmt);

    mysqli_commit($connect);

    echo json_encode([
        'success' => true,
        'message' => "Your booking request for $purpose has been successfully submitted! Your Request ID is $facility_booking_id. It is now listed as 'Pending' on the Booking Status calendar.",
        'booking_id' => $facility_booking_id
    ]);

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
