<?php
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
    include("include/slider-link.php");
    ?>

    <main class="container updates-container">
        <section class="update-col">
            <div class="col-header">
                <h3><i class="fa-solid fa-bullhorn text-maroon"></i> Latest Announcements</h3>
                <a href="pages/announcements.html" class="view-all">View All ></a>
            </div>
            <?php
            $sql_check="SELECT EVENTID, EVENTNAME, EVENTDETAIL, POSTEDDATE FROM events ORDER BY EVENTID DESC LIMIT 5;";
            $s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
            
            $Count=mysqli_num_rows($s_check);
            
            ?>
            <div class="list-wrapper">
            <?php
        // 2. CRITICAL: Ensure this variable name matches $s_check perfectly (No extra 'S' or 's')
        while($row = mysqli_fetch_array($s_check)){ 
        ?>    
            
            <div class="list-item">
                    <div class="date-badge">
                        <span class="day"><?php echo date('d',strtotime($row['POSTEDDATE'])) ?></span>
                        <span class="month"><?php echo date('M',strtotime($row['POSTEDDATE'])) ?></span>
                    </div>
                    <div class="item-details">
                        <a href="#" class="item-title"><?php echo $row['EVENTNAME'] ?> – Applications Open <span class="tag-new">New</span></a>
                        <p class="item-sub"><?php echo $row['EVENTDETAIL'] ?></p>
                    </div>
                </div>
                <?php
            }
                ?>
                
            </div>
        </section>

        <section class="update-col">
            <div class="col-header">
                <h3><i class="fa-regular fa-calendar-days text-maroon"></i>Welfare Schemes</h3>
                <a href="pages/events.html" class="view-all">View All ></a>
            </div>
            <?php
// 1. Querying your exact columns: title, description, and posted_date from `schemes`
// Filtering by your 'Health & Wellness' category (or 'Financial Assistance' depending on what you want to feature under welfare)
$sql_welfare = "SELECT title, description, posted_date FROM schemes WHERE category = 'Health & Wellness' AND status = 'Active' ORDER BY posted_date DESC LIMIT 3;";
$welfare_result = mysqli_query($connect, $sql_welfare) or die(mysqli_error($connect));
?>

<div class="list-wrapper">
    <?php
    // 2. Loop through your dataset rows
    while($row = mysqli_fetch_assoc($welfare_result)){
    ?>
    <div class="list-item">
        <div class="date-badge">
            <span class="day"><?php echo date('d', strtotime($row['posted_date'])) ?></span>
            <span class="month"><?php echo date('M', strtotime($row['posted_date'])) ?></span>
        </div>
        <div class="item-details">
            <h4 class="item-title"><?php echo htmlspecialchars($row['title']); ?></h4>
            <p class="item-sub"><?php echo htmlspecialchars($row['description']); ?></p>
        </div>
    </div>
    <?php
    }
    // Fallback if no schemes match the dynamic filter criteria
    if(mysqli_num_rows($welfare_result) == 0) {
        echo "<p style='padding: 15px; color: #718096; font-size: 0.9rem;'>No recent active schemes found in this category.</p>";
    }
    ?>
</div>
</div>
        </section>

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
    </main>

    <section class="bottom-dashboard-block">
        <div class="container bottom-flex-layout">
            
            <div class="stats-sub-banner">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-content"><h3>5000+</h3><p>Benefited</p></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-regular fa-folder-open"></i></div>
                    <div class="stat-content"><h3>25+</h3><p>Active Schemes</p></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-regular fa-calendar-check"></i></div>
                    <div class="stat-content"><h3>100+</h3><p>Events / Year</p></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="stat-content"><h3>10+</h3><p>Services</p></div>
                </div>
            </div>

            <div class="gallery-sub-block">
                <div class="col-header compact-gallery-header">
                    
                    <a href="pages/gallery.html" class="view-all">View Gallery ></a>
                </div>
                <div class="gallery-strip">
                    <div class="gallery-thumb"><img src="assets/images/gallery1.jpeg" alt="1" onerror="this.src='https://picsum.photos/150/80?random=1'"></div>
                    <div class="gallery-thumb"><img src="assets/images/gallery2.jpeg" alt="2" onerror="this.src='https://picsum.photos/150/80?random=2'"></div>
                    <div class="gallery-thumb"><img src="assets/images/gallery3.png" alt="3" onerror="this.src='https://picsum.photos/150/80?random=3'"></div>
                    <div class="gallery-thumb"><img src="assets/images/galler4.jpg" alt="4" onerror="this.src='https://picsum.photos/150/80?random=4'"></div>
                </div>
            </div>

        </div>
    </section>
<?php
include("include/footer.php")
?>
     
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