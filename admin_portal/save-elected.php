<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

include("../db.php");

header('Content-Type: application/json');

if (!isset($_POST['cpf_no']) || !isset($_POST['designation_select']) || !isset($_POST['term_year'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required inputs.']);
    exit;
}

$cpf_no = trim($_POST['cpf_no']);
$position = trim($_POST['designation_select']);
$term_year = trim($_POST['term_year']);

if (empty($cpf_no) || empty($position) || empty($term_year)) {
    echo json_encode(['success' => false, 'message' => 'CPF Number, Designation, and Term Year are mandatory.']);
    exit;
}

if (!$connect) {
    echo json_encode(['success' => false, 'message' => 'Database connection error.']);
    exit;
}

$stmt_admin = mysqli_prepare($connect, "SELECT name, phone_no FROM admin_credentials WHERE cpf_no = ?");
if (!$stmt_admin) {
    echo json_encode(['success' => false, 'message' => 'Database query preparation error.']);
    exit;
}
mysqli_stmt_bind_param($stmt_admin, "s", $cpf_no);
mysqli_stmt_execute($stmt_admin);
$res_admin = mysqli_stmt_get_result($stmt_admin);
$new_member = mysqli_fetch_assoc($res_admin);
mysqli_stmt_close($stmt_admin);

if (!$new_member) {
    echo json_encode(['success' => false, 'message' => 'CPF Number not found in database.']);
    exit;
}

$new_name = $new_member['name'];
$new_phone = $new_member['phone_no'] ? $new_member['phone_no'] : '';

mysqli_begin_transaction($connect);

try {
    $stmt_prev = mysqli_prepare($connect, "SELECT member_name FROM executive_members WHERE position = ?");
    mysqli_stmt_bind_param($stmt_prev, "s", $position);
    mysqli_stmt_execute($stmt_prev);
    $res_prev = mysqli_stmt_get_result($stmt_prev);
    $prev_member = mysqli_fetch_assoc($res_prev);
    mysqli_stmt_close($stmt_prev);

    if ($prev_member) {
        $prev_name = $prev_member['member_name'];
        $stmt_clear = mysqli_prepare($connect, "UPDATE admin_credentials SET designation = '' WHERE name = ?");
        mysqli_stmt_bind_param($stmt_clear, "s", $prev_name);
        mysqli_stmt_execute($stmt_clear);
        mysqli_stmt_close($stmt_clear);
    }

    $stmt_check_pos = mysqli_prepare($connect, "SELECT id FROM executive_members WHERE position = ?");
    mysqli_stmt_bind_param($stmt_check_pos, "s", $position);
    mysqli_stmt_execute($stmt_check_pos);
    $res_check_pos = mysqli_stmt_get_result($stmt_check_pos);
    $pos_exists = mysqli_fetch_assoc($res_check_pos);
    mysqli_stmt_close($stmt_check_pos);

    if ($pos_exists) {
        $stmt_sync = mysqli_prepare($connect, "UPDATE executive_members SET member_name = ?, contact_number = ?, term_year = ? WHERE position = ?");
        mysqli_stmt_bind_param($stmt_sync, "ssss", $new_name, $new_phone, $term_year, $position);
        mysqli_stmt_execute($stmt_sync);
        mysqli_stmt_close($stmt_sync);
    } else {
        $stmt_sync = mysqli_prepare($connect, "INSERT INTO executive_members (member_name, position, contact_number, term_year) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_sync, "ssss", $new_name, $position, $new_phone, $term_year);
        mysqli_stmt_execute($stmt_sync);
        mysqli_stmt_close($stmt_sync);
    }

    $stmt_update_new = mysqli_prepare($connect, "UPDATE admin_credentials SET designation = ? WHERE cpf_no = ?");
    mysqli_stmt_bind_param($stmt_update_new, "ss", $position, $cpf_no);
    mysqli_stmt_execute($stmt_update_new);
    mysqli_stmt_close($stmt_update_new);

    mysqli_commit($connect);

    echo json_encode(['success' => true, 'message' => 'Elected Member updated successfully.']);
} catch (Exception $e) {
    mysqli_rollback($connect);
    echo json_encode(['success' => false, 'message' => 'Failed to save elected member: ' . $e->getMessage()]);
}
?>
