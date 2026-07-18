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

if (!isset($_GET['cpf_no']) || empty(trim($_GET['cpf_no']))) {
    echo json_encode(['success' => false, 'message' => 'CPF Number is required.']);
    exit;
}

$cpf_no = trim($_GET['cpf_no']);

$query = "SELECT name, designation, phone_no, email, epbax_office, status FROM admin_credentials WHERE cpf_no = ?";
$stmt = mysqli_prepare($connect, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $cpf_no);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['status'] !== 'Active') {
            echo json_encode(['success' => false, 'message' => 'This account is inactive.']);
        } else {
            echo json_encode([
                'success' => true,
                'employee' => [
                    'cpf_no' => $cpf_no,
                    'name' => $row['name'],
                    'designation' => $row['designation'],
                    'phone_no' => $row['phone_no'],
                    'email' => $row['email'],
                    'epbax_office' => $row['epbax_office']
                ]
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'CPF Number not found in database.']);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Database query preparation failed.']);
}
?>
