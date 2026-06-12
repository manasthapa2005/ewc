<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'about.php') {
    header("Location: ../main.php?frmid=6");
    exit;
}
include("include/top-nav.php");
?>
<body>

    <!--<div class="top-bar">
        <div class="container top-bar-nav">
            <a href="index.html" class="active"><i class="fa fa-home"></i></a>
            <a href="#">Booking Status</a>
            <a href="#">Booking Request</a>
            <a href="pages/gallery.html">Photo Gallery</a>
            <a href="#">Executive Body</a>
            <a href="#">Feedback</a>
            <a href="#">Admin Login</a>
            <a href="#">About Us</a>
        </div>
    </div> -->

    <?php
    include("include/header.php");
    include("include/slider.php");
    ?>

    <main>
    <div class="abp-page-wrapper">
    <div class="abp-main-content">
        
        <div class="abp-section-intro">
            <h2 class="abp-section-heading">Our Commitment</h2>
            <p class="abp-section-subtext">
                We strive to create a supportive and inclusive environment by providing welfare services, facilities and programs that enhance the quality of life for ONGC employees and their families.
            </p>
        </div>

        <div class="abp-pillars-grid">
            
            <div class="abp-pillar-card">
                <div class="abp-icon-circle">
                    <i class="fa-solid fa-users-between-lines"></i>
                </div>
                <h4>Employee Well-being</h4>
                <p>Promoting physical, mental and emotional well-being through various welfare initiatives.</p>
            </div>

            <div class="abp-pillar-card">
                <div class="abp-icon-circle">
                    <i class="fa-solid fa-handshake-angle"></i>
                </div>
                <h4>Support & Assistance</h4>
                <p>Providing financial, medical and educational support to employees and their families.</p>
            </div>

            <div class="abp-pillar-card">
                <div class="abp-icon-circle">
                    <i class="fa-solid fa-people-roof"></i>
                </div>
                <h4>Community Building</h4>
                <p>Encouraging a strong sense of community through events, activities and engagement.</p>
            </div>

            <div class="abp-pillar-card">
                <div class="abp-icon-circle">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4>Transparency & Trust</h4>
                <p>Ensuring transparent processes and ethical practices in all our services and operations.</p>
            </div>

            <div class="abp-pillar-card">
                <div class="abp-icon-circle">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h4>Continuous Improvement</h4>
                <p>Continuously improving our services to meet the evolving needs of our employees.</p>
            </div>

        </div>

        <div class="abp-vision-mission-row">
            
            <div class="abp-vm-block">
                <div class="abp-vm-icon">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div class="abp-vm-text">
                    <h4>Our Vision</h4>
                    <p>To be a trusted welfare organization that enhances the quality of life and fosters a culture of care, respect and empowerment.</p>
                </div>
            </div>

            <div class="abp-vm-block">
                <div class="abp-vm-icon">
                    <i class="fa-solid fa-rocket"></i>
                </div>
                <div class="abp-vm-text">
                    <h4>Our Mission</h4>
                    <p>To deliver valuable welfare services and programs with integrity, efficiency and empathy for the holistic development of ONGC employees and their families.</p>
                </div>
            </div>

        </div>

    </div>

    <aside class="abp-sidebar-stack">
        
        <div class="abp-widget-card">
            <h4 class="abp-widget-title">Quick Links</h4>
            <nav class="abp-widget-nav">
                <a href="main.php?frmid=1"><i class="fa-solid fa-clipboard-check"></i> Booking Status</a>
                <a href="main.php?frmid=2"><i class="fa-solid fa-calendar-plus"></i> Booking Request</a>
                <a href="main.php?frmid=3"><i class="fa-solid fa-hand-holding-heart"></i> Welfare Schemes</a>
                <a href="main.php?frmid=4"><i class="fa-solid fa-images"></i> Photo Gallery</a>
                <a href="main.php?frmid=5"><i class="fa-solid fa-scale-balanced"></i> EWC Members</a>
            </nav>
        </div>

        <div class="abp-widget-card">
            <h4 class="abp-widget-title">Contact Us</h4>
            <div class="abp-contact-list">
                <div class="abp-contact-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>EWC Office, ONGC Dehradun<br>Uttarakhand - 248001</p>
                </div>
                <div class="abp-contact-item">
                    <i class="fa-solid fa-phone"></i>
                    <p>0135-XXXXXXX<br><span>(Mon - Fri: 10:00 AM - 5:00 PM)</span></p>
                </div>
                <div class="abp-contact-item">
                    <i class="fa-solid fa-envelope"></i>
                    <p><a href="mailto:ews.dehradun@ongc.co.in">ews.dehradun@ongc.co.in</a></p>
                </div>
            </div>
        </div>

    </aside>
</div>
    </main>

<?php
include("include/footer.php")
?>

</body>