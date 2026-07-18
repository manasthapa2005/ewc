
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

if (isset($_POST['event_title'])) {
    $event_date = trim($_POST['event_date']);
    $event_title = trim($_POST['event_title']);
    $event_description = trim($_POST['event_description']);

    if (empty($event_date) || empty($event_title) || empty($event_description)) {
        $_SESSION['event_error'] = "All mandatory fields (*Date of Event, *Event Title, *Event Description) must be filled.";
        header("Location: event.php");
        exit;
    }

    if (!$connect) {
        $_SESSION['event_error'] = "Database connection error.";
        header("Location: event.php");
        exit;
    }

    // Generate a unique random EVENTID that does not collide in the database
    do {
        $event_id = rand(10, 999999);
        $stmt_check = mysqli_prepare($connect, "SELECT EVENTID FROM events WHERE EVENTID = ?");
        if ($stmt_check) {
            mysqli_stmt_bind_param($stmt_check, "i", $event_id);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);
            $count = mysqli_stmt_num_rows($stmt_check);
            mysqli_stmt_close($stmt_check);
        } else {
            $count = 0;
        }
    } while ($count > 0);

    // Handle file upload if any
    $event_file_name = null;
    if (isset($_FILES['event_file']) && $_FILES['event_file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['event_file']['tmp_name'];
        $original_name = basename($_FILES['event_file']['name']);
        // sanitize filename
        $clean_name = preg_replace("/[^a-zA-Z0-9._-]/", "_", $original_name);
        $event_file_name = "event_" . $event_id . "_" . $clean_name;
        
        $dest_dir = dirname(__DIR__, 1) . '/assets/docs/';
        if (!is_dir($dest_dir)) {
            mkdir($dest_dir, 0777, true);
        }
        move_uploaded_file($file_tmp, $dest_dir . $event_file_name);
    }

    // Insert the new event into the events table
    $stmt_insert = mysqli_prepare($connect, "INSERT INTO events (EVENTID, POSTEDDATE, EVENTNAME, EVENTDETAIL, EVENTFILE) VALUES (?, ?, ?, ?, ?)");
    if ($stmt_insert) {
        mysqli_stmt_bind_param($stmt_insert, "issss", $event_id, $event_date, $event_title, $event_description, $event_file_name);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $_SESSION['event_success'] = "Event added successfully!";
        } else {
            $_SESSION['event_error'] = "Error saving event: " . mysqli_stmt_error($stmt_insert);
        }
        mysqli_stmt_close($stmt_insert);
    } else {
        $_SESSION['event_error'] = "Database error compiling event query.";
    }
} else {
    $_SESSION['event_error'] = "Invalid form submission.";
}

header("Location: event.php");
exit;
?>
