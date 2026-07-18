<?php
    include("include/top-nav.php");
    ?>
<body class="home-body">

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
    include("include/slider-link.php");
    ?>

    <main class="container updates-container">
        <section class="update-col executive-committee-container">
            <div class="col-header">
                <h3><i class="fa-solid fa-user-tie text-maroon"></i> Executive Committee Members</h3>
            </div>
            
            <?php
            $sql_check="SELECT member_name, position, contact_number, term_year FROM executive_members ORDER BY id ASC;";
            $s_check = mysqli_query($connect, $sql_check) or die(mysqli_error($connect));
            ?>
            
            <div class="table-scroll-wrapper">
                <table class="executive-table">
                    <thead>
                        <tr>
                            <th>Member Name</th>
                            <th>Position</th>
                            <th>Contact Number</th>
                            <th>Term Year</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    while($row = mysqli_fetch_array($s_check)) { 
                    ?>
                        <tr>
                            <td class="member-name-cell"><?php echo $row['member_name'] ?></td>
                            <td class="position-cell"><?php echo $row['position'] ?></td>
                            <td class="contact-cell"><?php echo $row['contact_number'] ?></td>
                            <td class="term-cell"><?php echo $row['term_year'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>    
                    </tbody>
                </table>
            </div>
        </section>

        <section class="update-col">
            <div class="col-header">
                <h3><i class="fa-regular fa-image text-maroon"></i> Photo Gallery</h3>
                <a href="main.php?frmid=4" class="view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <?php
            $sql_widget = "SELECT * FROM albums WHERE status = 'ACTIVE' ORDER BY created_at DESC LIMIT 4";
            $res_widget = mysqli_query($connect, $sql_widget);
            $widget_albums = [];
            if ($res_widget && mysqli_num_rows($res_widget) > 0) {
                while ($row = mysqli_fetch_assoc($res_widget)) {
                    $img = !empty($row['cover_image']) ? $row['cover_image'] : '';
                    $path = !empty($img) ? "assets/images/album/" . $img : "data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22350%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000/svg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f1f5f9%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20fill%3D%22%2394a3b8%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3ENo%20Image%3C%2Ftext%3E%3C%2Fsvg%3E";
                    $widget_albums[] = [
                        'src' => $path,
                        'name' => $row['album_name']
                    ];
                }
            }
            
            // Fill with default ones if fewer than 4
            $defaults = [
                ['src' => 'assets/images/gallery1.jpeg', 'name' => 'Gallery Image 1'],
                ['src' => 'assets/images/gallery2.jpeg', 'name' => 'Gallery Image 2'],
                ['src' => 'assets/images/gallery3.png', 'name' => 'Gallery Image 3'],
                ['src' => 'assets/images/gallery4.jpg', 'name' => 'Gallery Image 4']
            ];
            for ($i = count($widget_albums); $i < 4; $i++) {
                $widget_albums[] = $defaults[$i - count($widget_albums)];
            }
            ?>
            <div class="gallery-widget">
                <div class="gallery-main-slide">
                    <img id="galleryMainImg" src="<?php echo htmlspecialchars($widget_albums[0]['src']); ?>" alt="<?php echo htmlspecialchars($widget_albums[0]['name']); ?>" onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22350%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000/svg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f1f5f9%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20fill%3D%22%2394a3b8%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3ENo%20Image%20Available%3C%2Ftext%3E%3C%2Fsvg%3E';">
                    <button class="gallery-nav-btn prev-btn" id="galleryPrevBtn"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="gallery-nav-btn next-btn" id="galleryNextBtn"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <div class="gallery-thumbs-row">
                    <?php foreach ($widget_albums as $index => $album): ?>
                        <div class="gallery-thumb-item<?php echo $index === 0 ? ' active' : ''; ?>" data-src="<?php echo htmlspecialchars($album['src']); ?>">
                            <img src="<?php echo htmlspecialchars($album['src']); ?>" alt="<?php echo htmlspecialchars($album['name']); ?>" onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22350%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000/svg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f1f5f9%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20fill%3D%22%2394a3b8%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3ENo%20Image%20Available%3C%2Ftext%3E%3C%2Fsvg%3E';">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const mainImg = document.getElementById("galleryMainImg");
                const prevBtn = document.getElementById("galleryPrevBtn");
                const nextBtn = document.getElementById("galleryNextBtn");
                const thumbs = document.querySelectorAll(".gallery-thumb-item");
                let currentIndex = 0; // Initially pointing to the first image (index 0)

                function updateGallery(index) {
                    currentIndex = index;
                    const targetThumb = thumbs[currentIndex];
                    if (!targetThumb) return;
                    const newSrc = targetThumb.getAttribute("data-src");
                    
                    mainImg.src = newSrc;
                    
                    thumbs.forEach(t => t.classList.remove("active"));
                    targetThumb.classList.add("active");
                }

                thumbs.forEach((thumb, idx) => {
                    thumb.addEventListener("click", () => {
                        updateGallery(idx);
                    });
                });

                prevBtn.addEventListener("click", () => {
                    let prevIndex = currentIndex - 1;
                    if (prevIndex < 0) {
                        prevIndex = thumbs.length - 1;
                    }
                    updateGallery(prevIndex);
                });

                nextBtn.addEventListener("click", () => {
                    let nextIndex = currentIndex + 1;
                    if (nextIndex >= thumbs.length) {
                        nextIndex = 0;
                    }
                    updateGallery(nextIndex);
                });
            });
            </script>
        </section>

        <section class="update-col">
            <div class="col-header">
                <h3><i class="fa-solid fa-bullhorn text-maroon"></i> Latest Announcements</h3>
                <a href="pages/announcements.php" class="view-all">View All ></a>
            </div>
            <?php
            $sql_check="SELECT EVENTID, EVENTNAME, EVENTDETAIL, POSTEDDATE FROM events ORDER BY POSTEDDATE DESC LIMIT 5;";
            $s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
            
            $Count=mysqli_num_rows($s_check);
            
            ?>
            <div class="marquee-container">
                <div class="list-wrapper marquee-track">
                <?php
                while($row = mysqli_fetch_array($s_check)){ 
                ?>    
                <div class="list-item">
                    <div class="date-badge">
                        <span class="day"><?php echo date('d',strtotime($row['POSTEDDATE'])) ?></span>
                        <span class="month"><?php echo date('M',strtotime($row['POSTEDDATE'])) ?></span>
                    </div>
                    <div class="item-details">
                        <a href="pages/announcements.php" class="item-title"><?php echo $row['EVENTNAME'] ?> <span class="tag-new">New</span></a>
                        <p class="item-sub"><?php echo $row['EVENTDETAIL'] ?></p>
                    </div>
                </div>
                <?php
                }
                ?>
                </div>
            </div>
        </section>
    </main>


<?php
include("include/footer.php")
?>
<script src="js/script.js"></script>

</body>
</html>
<?php
function catagory_count($catg)
{
	global $connect;
	
	$sql_check="select op from tmp_stk where grp='$grp' and icode='$icode' and yer='$yer'";
	$s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
	$row=mysqli_fetch_assoc($s_check);
	$Count=mysqli_num_rows($s_check);
	return $Count;
	
}
?>