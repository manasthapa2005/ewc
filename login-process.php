<?php
include("db.php");


if (isset($_POST['submit_login'])) {
    $cpf_no = trim($_POST['cpf_no']);
    $password = trim($_POST['password']);

    if (empty($cpf_no) || empty($password)) {
        $_SESSION['login_error'] = "Please enter both CPF No. and Password.";
        header("Location: main.php?frmid=7");
        exit;
    }

    if (!$connect) {
        $_SESSION['login_error'] = "Database connection error.";
        header("Location: main.php?frmid=7");
        exit;
    }
	
	$sql_check="SELECT cpf_no, name, password_hash, status FROM admin_credentials WHERE cpf_no = '$cpf_no'";
	$s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
	
	$stmt=mysqli_num_rows($s_check);
	
    if ($stmt>0) {
       

        if ($row = mysqli_fetch_assoc($s_check)) {
            if ($row['status'] !== 'Active') {
                $_SESSION['login_error'] = "This administrator account is inactive.";
                header("Location: main.php?frmid=7");
                exit;
            }
                // Successful login
			$_SESSION['admin_logged_in'] = true;
			$_SESSION['admin_cpf'] = $row['cpf_no'];
			$_SESSION['admin_name'] = $row['name'];
			$_SESSION['login_success_msg'] = "You have successfully logged in!";
			header("Location: admin_portal/admin.php");
			exit;
            
        } else {
            $_SESSION['login_error'] = "Invalid CPF Number or Password.";
            header("Location: main.php?frmid=7");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "System error processing request.";
        header("Location: main.php?frmid=7");
        exit;
    }
} else {
    header("Location: main.php?frmid=0");
    exit;
}
?>
