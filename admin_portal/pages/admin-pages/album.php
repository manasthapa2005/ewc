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

    <div class="gallery-page-wrapper">

        <!-- COMPACT ADD ALBUM FORM -->
        <div class="gallery-form-container">
            <h2>Update Photo Gallery</h2>
            <div class="form-card">
                <h3>Add New Album</h3>
                <form action="/add-album" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group flex-2">
                            <label for="album-name">*Album Name:</label>
                            <input type="text" id="album-name" name="album_name" placeholder="e.g., Athletic Meet 2026"
                                required>
                        </div>

                        <div class="form-group flex-2">
                            <label for="cover-image">*Select Cover Image:</label>
                            <input type="file" id="cover-image" name="cover_image" required>
                        </div>

                        <div class="form-group flex-2">
                            <label for="image-caption">Image Caption:</label>
                            <input type="text" id="image-caption" name="image_caption"
                                placeholder="Brief description...">
                        </div>

                        <div class="form-group btn-group flex-1">
                            <button type="submit" class="btn-create">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- SCROLLABLE ALBUM CONTAINER -->
        <div class="album-section-container">
            <h3>Existing Albums</h3>
            <div class="album-scroll-box">
                <div class="album-grid">

                    <!-- Album Card 1 -->
                    <div class="album-card">
                        <div class="image-wrapper">
                            <img src="#" alt="Athletic Meet 2026">
                        </div>
                        <div class="album-info">
                            <h4>EWC Athletic Meet 2026</h4>
                            <button type="button" class="btn-delete">Delete</button>
                        </div>
                    </div>

                    <!-- Album Card 2 -->
                    <div class="album-card">
                        <div class="image-wrapper">
                            <img src="path-to-your-image2.jpg" alt="Young Talent">
                        </div>
                        <div class="album-info">
                            <h4>Young Talent Felicitation Program 2025</h4>
                            <button type="button" class="btn-delete">Delete</button>
                        </div>
                    </div>

                    <!-- Album Card 3 -->
                    <div class="album-card">
                        <div class="image-wrapper">
                            <img src="path-to-your-image3.jpg" alt="Football">
                        </div>
                        <div class="album-info">
                            <h4>Football Tournament 2025</h4>
                            <button type="button" class="btn-delete">Delete</button>
                        </div>
                    </div>

                    <!-- Album Card 4 -->
                    <div class="album-card">
                        <div class="image-wrapper">
                            <img src="path-to-your-image4.jpg" alt="Badminton">
                        </div>
                        <div class="album-info">
                            <h4>Badminton Tournament 2025</h4>
                            <button type="button" class="btn-delete">Delete</button>
                        </div>
                    </div>

                    <!-- Add more album-card blocks here. The container will scroll dynamically! -->

                </div>
            </div>
        </div>

    </div>


    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>