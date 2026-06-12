<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'welfare.php') {
    header("Location: ../main.php?frmid=3");
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
        <div class="page-title-block">
            <h2>Scheme Categories</h2>
        </div>

        <div class="schemes-layout-split">
            
        <?php
// Fetch category totals
$query = "SELECT category, COUNT(*) as total FROM schemes WHERE status = 'Active' GROUP BY category";
$result = mysqli_query($connect, $query);

$counts = [
    'Financial Assistance' => 0,
    'Health & Wellness'    => 0,
    'Educational Grants'   => 0,
    'Recreation & Clubs'   => 0,
    'Counseling Support'   => 0
];

$total_schemes = 0;
while($row = mysqli_fetch_assoc($result)) {
    $counts[$row['category']] = $row['total'];
    $total_schemes += $row['total']; // Calculates the "All Schemes" badge total dynamically
}
?>

<div class="category-list">
    <a href="#" class="active">All Schemes <span class="badge"><?php echo $total_schemes; ?></span></a>
    <a href="#">Financial Assistance <span class="badge"><?php echo $counts['Financial Assistance']; ?></span></a>
    <a href="#">Health & Wellness <span class="badge"><?php echo $counts['Health & Wellness']; ?></span></a>
    <a href="#">Educational Grants <span class="badge"><?php echo $counts['Educational Grants']; ?></span></a>
    <a href="#">Recreation & Clubs <span class="badge"><?php echo $counts['Recreation & Clubs']; ?></span></a>
    <a href="#">Counseling Support <span class="badge"><?php echo $counts['Counseling Support']; ?></span></a>
</div>

<div class="schemes-cards-scroll-container">
    <?php
    // 1. Fetch all active schemes from the database
    $sql_schemes = "SELECT title, description, category, status FROM schemes WHERE status = 'Active' ORDER BY scheme_id DESC";
    $schemes_result = mysqli_query($connect, $sql_schemes);

    // 2. Loop through each scheme row dynamically
    while ($scheme = mysqli_fetch_assoc($schemes_result)) {
        
        // 3. Dynamically assign the correct FontAwesome icon based on the scheme's category
        $icon_class = "fa-user-graduate"; // Default icon
        $color_class = "";

        if ($scheme['category'] == 'Health & Wellness') {
            $icon_class = "fa-heart-pulse";
            $color_class = "text-red";
        } elseif ($scheme['category'] == 'Financial Assistance') {
            $icon_class = "fa-hand-holding-dollar";
        } elseif ($scheme['category'] == 'Recreation & Clubs') {
            $icon_class = "fa-republican"; /* or any sports/club icon you prefer */
        } elseif ($scheme['category'] == 'Counseling Support') {
            $icon_class = "fa-comments";
        }
        ?>
        
        <div class="scheme-info-card">
            <div class="s-card-header">
                <i class="fa-solid <?php echo $icon_class; ?> icon-head <?php echo $color_class; ?>"></i>
                <div>
                    <h4><?php echo htmlspecialchars($scheme['title']); ?></h4>
                    <p><?php echo htmlspecialchars($scheme['description']); ?></p>
                </div>
            </div>
            <div class="s-card-footer">
                <span class="badge-active-tag"><?php echo htmlspecialchars($scheme['status']); ?></span>
                <a href="#" class="view-details-link">View Details <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

    <?php 
    } // End of while loop 
    ?>
</div>
        </div>
    </main>

    <?php
include("include/footer.php")
?>

</body>
</html>