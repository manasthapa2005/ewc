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

    <div class="events-page-wrapper">

        <h2>Update Events</h2>

        <?php if (isset($_SESSION['event_success'])): ?>
            <div class="event-success-alert" style="color: #065f46; background-color: #d1fae5; border: 1px solid #a7f3d0; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['event_success']); unset($_SESSION['event_success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['event_error'])): ?>
            <div class="event-error-alert" style="color: #991b1b; background-color: #fee2e2; border: 1px solid #fecaca; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['event_error']); unset($_SESSION['event_error']); ?>
            </div>
        <?php endif; ?>

        <!-- TAB BUTTON LAYOUT NAVIGATION -->
        <div class="events-tab-container">
            <button type="button" class="tab-btn active-tab" onclick="switchEventTab(event, 'add-panel')">Add New
                Event</button>
            <button type="button" class="tab-btn" onclick="switchEventTab(event, 'delete-panel')">Delete Event</button>
        </div>

        <!-- MAIN INTERFACE BODY CONTAINER -->
        <div class="events-card-body">

            <!-- PANEL A: ADD NEW EVENT (VISIBLE BY DEFAULT) -->
            <div id="add-panel" class="tab-content-panel active-panel">
                <h3>Add the Detail of New Event:</h3>

                <form action="add-event-process.php" method="POST" enctype="multipart/form-data">

                    <div class="events-form-group">
                        <label for="event-date">*Date of Event:</label>
                        <input type="date" id="event-date" name="event_date" required>
                    </div>

                    <div class="events-form-group">
                        <label for="event-title">*Event Title:</label>
                        <input type="text" id="event-title" name="event_title"
                            placeholder="Enter full event headline name..." required>
                    </div>

                    <div class="events-form-group">
                        <label for="event-desc">*Event Description:</label>
                        <textarea id="event-desc" name="event_description" rows="8"
                            placeholder="Type or paste the main detailed text descriptions here..." required></textarea>
                    </div>

                    <div class="events-action-row">
                        <div class="upload-box">
                            <label for="event-file">Select File to Upload:</label>
                            <input type="file" id="event-file" name="event_file">
                        </div>

                        <button type="submit" class="btn-add-event">Add Event</button>
                    </div>

                </form>
            </div>

            <!-- PANEL B: DELETE EVENT (HIDDEN BY DEFAULT) -->
            <div id="delete-panel" class="tab-content-panel">
                <h3>Select Event to Remove:</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Choose an existing system entry from the
                    selector to remove it permanently.</p>
                <!-- A placeholder dropdown logic for removal actions -->
                <div class="events-form-group" style="max-width: 500px;">
                    <select name="delete_event_id" style="width: 100%; padding: 10px; border-radius: 4px;">
                        <option value="" disabled selected>-- Select Event to Delete --</option>
                        <option value="1">Sample Event Title One (2026)</option>
                        <option value="2">Sample Event Title Two (2026)</option>
                    </select>
                    <button type="button" class="btn-add-event"
                        style="background-color: #d32f2f; margin-top: 15px; width: max-content;">Delete
                        Selected</button>
                </div>
            </div>

        </div>

    </div>

    <!-- INTERACTIVE TAB SWITCHING SCRIPT -->
    <script>
        function switchEventTab(evt, panelId) {
            // Hide all panels inside the events container
            const panels = document.querySelectorAll('.events-page-wrapper .tab-content-panel');
            panels.forEach(panel => panel.classList.remove('active-panel'));

            // Remove active style marker from all tab buttons
            const tabs = document.querySelectorAll('.events-page-wrapper .tab-btn');
            tabs.forEach(tab => tab.classList.remove('active-tab'));

            // Display the targeted panel context and mark current button active
            document.getElementById(panelId).classList.add('active-panel');
            evt.currentTarget.classList.add('active-tab');
        }
    </script>


    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>