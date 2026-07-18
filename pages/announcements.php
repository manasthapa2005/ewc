<?php
include("../db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements | ONGC EWC - Employee Welfare Committee</title>
    <meta name="description" content="Stay updated with the latest announcements, events and news from ONGC Employee Welfare Committee, Dehradun.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo filemtime(dirname(__DIR__) . '/css/style.css'); ?>">
    <link rel="stylesheet" href="../css/announcements.css?v=<?php echo filemtime(dirname(__DIR__) . '/css/announcements.css'); ?>">
</head>
<body class="interior-body">

    <!-- Header -->
    <header class="main-header">
        <div class="container header-container">
            <div class="brand-identity">
                <img src="../assets/images/ongc_logo.png" alt="ONGC Logo" class="logo-ongc">
                <div class="brand-text">
                    <h1>ONGC EWC</h1>
                    <p>EMPLOYEE WELFARE COMMITTEE</p>
                </div>
            </div>
            <nav class="main-nav">
                <div class="nav-links">
                    <a href="../main.php?frmid=0" class="nav-item"><i class="fa-solid fa-house"></i> Home</a>
                    <a href="../main.php?frmid=1" class="nav-item"><i class="fa-regular fa-address-book"></i> Booking Status</a>
                    <a href="../main.php?frmid=2" class="nav-item"><i class="fa-regular fa-calendar-check"></i> Booking Request</a>
                    <a href="../main.php?frmid=3" class="nav-item">Welfare Schemes</a>
                    <a href="../main.php?frmid=4" class="nav-item"><i class="fa-regular fa-image"></i> Photo Gallery</a>
                    <a href="../main.php?frmid=5" class="nav-item"><i class="fa-solid fa-user-tie"></i> Executive Body</a>
                    <a href="../main.php?frmid=6" class="nav-item bg-pill">About Us</a>
                    <a href="../main.php?frmid=7" class="admin-login-btn"><i class="fa-regular fa-circle-user"></i> Admin Login <i class="fa fa-chevron-down"></i></a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Slider -->
    <section class="hero-section">
        <div class="hero-slides">
            <div class="slide active" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('../assets/images/hero3.jpg');"></div>
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('../assets/images/hero2.jpg');"></div>
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('../assets/images/gallery2.jpeg');"></div>
        </div>
        <div class="container hero-content">
            <p class="welcome-tag">Latest Updates</p>
            <h2>Announcements</h2>
            <h3>Employee Welfare Committee &bull; Dehradun &amp; All Locations</h3>
            <p class="hero-desc">Stay informed with the latest events, notices and updates from ONGC EWC.</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container status-main-layout">

        <!-- Page title + filter bar -->
        <div class="ann-page-header">
            <div class="ann-title-block">
                <h2><i class="fa-solid fa-bullhorn"></i> All Announcements</h2>
                <p><?php
                    $count_q = mysqli_query($connect, "SELECT COUNT(*) as total FROM events");
                    $count_r = mysqli_fetch_assoc($count_q);
                    echo $count_r['total'];
                ?> notices found</p>
            </div>
            <a href="../main.php?frmid=0" class="ann-back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>

        <!-- Announcements grid -->
        <?php
        $ann_query = "SELECT EVENTID, EVENTNAME, EVENTDETAIL, POSTEDDATE, EVENTFILE FROM events ORDER BY POSTEDDATE DESC, EVENTID DESC";
        $ann_result = mysqli_query($connect, $ann_query) or die(mysqli_error($connect));
        $total = mysqli_num_rows($ann_result);
        ?>

        <?php if ($total > 0): ?>
        <div class="ann-cards-scroll-area">
        <div class="ann-grid">
            <?php
            $colors = [
                ['bg' => '#FFF0F1', 'border' => '#FFD1D4', 'text' => '#7B1416', 'tag_bg' => '#7B1416'],
                ['bg' => '#EEF2FF', 'border' => '#C7D2FE', 'text' => '#3730A3', 'tag_bg' => '#4F46E5'],
                ['bg' => '#F0FDF4', 'border' => '#BBF7D0', 'text' => '#166534', 'tag_bg' => '#16A34A'],
                ['bg' => '#FFFBEB', 'border' => '#FDE68A', 'text' => '#92400E', 'tag_bg' => '#D97706'],
                ['bg' => '#F0F9FF', 'border' => '#BAE6FD', 'text' => '#075985', 'tag_bg' => '#0EA5E9'],
            ];
            $i = 0;
            while ($row = mysqli_fetch_assoc($ann_result)):
                $c = $colors[$i % count($colors)];
                $day   = date('d', strtotime($row['POSTEDDATE']));
                $month = date('M', strtotime($row['POSTEDDATE']));
                $year  = date('Y', strtotime($row['POSTEDDATE']));
                $full_date = date('d F Y', strtotime($row['POSTEDDATE']));
                $i++;
            ?>
            <div class="ann-card" style="--card-accent: <?php echo $c['tag_bg']; ?>;">
                <div class="ann-card-date-strip" style="background: <?php echo $c['bg']; ?>; border-right: 3px solid <?php echo $c['border']; ?>;">
                    <span class="ann-day" style="color: <?php echo $c['text']; ?>;"><?php echo $day; ?></span>
                    <span class="ann-month" style="color: <?php echo $c['text']; ?>;"><?php echo $month; ?></span>
                    <span class="ann-year" style="color: <?php echo $c['text']; ?>; opacity:0.7;"><?php echo $year; ?></span>
                </div>
                <div class="ann-card-body">
                    <div class="ann-card-top">
                        <span class="ann-tag" style="background: <?php echo $c['tag_bg']; ?>;">
                            <i class="fa-solid fa-circle-dot"></i> Announcement
                        </span>
                        <span class="ann-card-date-full"><?php echo $full_date; ?></span>
                    </div>
                    <h3 class="ann-card-title"><?php echo htmlspecialchars($row['EVENTNAME']); ?></h3>
                    <p class="ann-card-detail"><?php echo htmlspecialchars($row['EVENTDETAIL']); ?></p>
                    <?php if (!empty($row['EVENTFILE'])): ?>
                        <div class="ann-card-attachment" style="margin-top: 8px;">
                            <a href="../assets/docs/<?php echo htmlspecialchars($row['EVENTFILE']); ?>" target="_blank" class="ann-pdf-btn" style="display: inline-flex; align-items: center; gap: 6px; background-color: #7B1416; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: bold; width: fit-content; transition: background 0.2s;">
                                <i class="fa-solid fa-file-pdf"></i> View Attachment (PDF)
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ann-card-indicator" style="background: <?php echo $c['tag_bg']; ?>;"></div>
            </div>
            <?php endwhile; ?>
        </div>
        </div><!-- /.ann-cards-scroll-area -->
        <?php else: ?>
        <div class="ann-cards-scroll-area">
        <div class="ann-empty-state">
            <i class="fa-regular fa-folder-open"></i>
            <h3>No Announcements Yet</h3>
            <p>Check back soon for the latest updates from ONGC EWC.</p>
        </div>
        </div><!-- /.ann-cards-scroll-area -->
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container footer-strip-content">
            <p><i class="fa-solid fa-location-dot text-maroon"></i> EWS Office, ONGC Dehradun Uttarakhand - 248001</p>
            <div class="footer-center-links">
                <p>&copy; 2026 ONGC EWS - Employee Welfare Committee. All Rights Reserved.</p>
            </div>
            <div class="footer-right-brands">
                <a href="#">Terms ></a>
                <a href="#">Policies ></a>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const details = document.querySelectorAll(".ann-card-detail");
        details.forEach(detail => {
            detail.style.cursor = "pointer";
            detail.addEventListener("click", function() {
                detail.classList.toggle("expanded");
            });
        });
    });
    </script>

</body>
</html>
