<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

include("../../../db.php");

header('Content-Type: application/json');

if (!isset($_GET['cpf_no'])) {
    echo json_encode(['success' => false, 'message' => 'CPF Number is required.']);
    exit;
}

$cpf_no = trim($_GET['cpf_no']);

if (empty($cpf_no)) {
    echo json_encode(['success' => false, 'message' => 'CPF Number cannot be empty.']);
    exit;
}

if (!$connect) {
    echo json_encode(['success' => false, 'message' => 'Database connection error.']);
    exit;
}

$stmt = mysqli_prepare($connect, "SELECT name, designation, phone_no, epbax_office FROM admin_credentials WHERE cpf_no = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $cpf_no);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'success' => true,
            'message' => 'Employee details loaded successfully.',
            'data' => [
                'name' => $row['name'],
                'designation' => $row['designation'],
                'phone_no' => $row['phone_no'] ? $row['phone_no'] : '',
                'epbax_office' => $row['epbax_office'] ? $row['epbax_office'] : ''
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Employee not found.']);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'System query preparation error.']);
}
?>
