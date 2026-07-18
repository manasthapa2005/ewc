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

if (isset($_POST['submit_delete']) && isset($_POST['delete_event_id'])) {
    $event_id = intval($_POST['delete_event_id']);

    if (empty($event_id)) {
        $_SESSION['event_error'] = "Please select a valid event to delete.";
        header("Location: event.php");
        exit;
    }

    if (!$connect) {
        $_SESSION['event_error'] = "Database connection error.";
        header("Location: event.php");
        exit;
    }

    // Delete the event using a prepared statement
    $stmt = mysqli_prepare($connect, "DELETE FROM events WHERE EVENTID = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $event_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['event_success'] = "Event (ID: $event_id) deleted successfully!";
        } else {
            $_SESSION['event_error'] = "Error deleting event: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['event_error'] = "Database error preparing delete query.";
    }
} else {
    $_SESSION['event_error'] = "Invalid delete request.";
}

header("Location: event.php");
exit;
?>
