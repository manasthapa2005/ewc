<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'gallery.php') {
    header("Location: ../main.php?frmid=4");
    exit;
}
include("include/top-nav.php");
?>
<body class="interior-body">

<?php
    include("include/header.php");
    include("include/slider.php");
    ?>

    <main class="container interior-main-layout">
        <div class="page-title-block image-gallery-header-row">
            <div>
                <h2>Photo Gallery</h2>
                <p>Explore moments from our events, activities and celebrations.</p>
            </div>
            <div class="gallery-filter-toolbar">
                <select><option>All Albums</option></select>
                <select><option>All Events</option></select>
                <select><option>All Years</option></select>
                <button class="btn-gallery-filter">Apply Filter</button>
            </div>
        </div>

        <div class="gallery-grid-scroll-box">
            <div class="album-media-card">
                <div class="album-preview-frame"><img src="../assets/images/gallery-1.jpg" alt="" onerror="this.src='https://picsum.photos/350/200?random=11'"></div>
                <div class="album-footer-meta">
                    <h5>EWC Athletic Meet 2026</h5>
                    <p>May 2026</p>
                </div>
            </div>
            <div class="album-media-card">
                <div class="album-preview-frame"><img src="../assets/images/gallery-2.jpg" alt="" onerror="this.src='https://picsum.photos/350/200?random=12'"></div>
                <div class="album-footer-meta">
                    <h5>Yoga Session</h5>
                    <p>May 2026</p>
                </div>
            </div>
            <div class="album-media-card">
                <div class="album-preview-frame"><img src="../assets/images/gallery-3.jpg" alt="" onerror="this.src='https://picsum.photos/350/200?random=13'"></div>
                <div class="album-footer-meta">
                    <h5>Blood Donation Camp</h5>
                    <p>April 2026</p>
                </div>
            </div>
            <div class="album-media-card">
                <div class="album-preview-frame"><img src="../assets/images/gallery-4.jpg" alt="" onerror="this.src='https://picsum.photos/350/200?random=14'"></div>
                <div class="album-footer-meta">
                    <h5>Holi Celebration</h5>
                    <p>March 2026</p>
                </div>
            </div>
            <div class="album-media-card">
                <div class="album-preview-frame"><img src="../assets/images/gallery-1.jpg" alt="" onerror="this.src='https://picsum.photos/350/200?random=15'"></div>
                <div class="album-footer-meta">
                    <h5>Women's Day Celebration</h5>
                    <p>March 2026</p>
                </div>
            </div>
            <div class="album-media-card">
                <div class="album-preview-frame"><img src="../assets/images/gallery-2.jpg" alt="" onerror="this.src='https://picsum.photos/350/200?random=16'"></div>
                <div class="album-footer-meta">
                    <h5>Cricket Tournament</h5>
                    <p>February 2026</p>
                </div>
            </div>
        </div>

        <div class="gallery-action-footer-row">
            <button class="btn-load-more-photos">View More Photos</button>
        </div>
    </main>

    <?php
include("include/footer.php")
?>

</body>
</html>