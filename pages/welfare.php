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
    <a href="#" class="active" data-target="top">All Schemes <span class="scheme-badge"><?php echo $total_schemes; ?></span></a>
    <a href="#" data-target="cat-financial-assistance">Financial Assistance <span class="scheme-badge"><?php echo $counts['Financial Assistance']; ?></span></a>
    <a href="#" data-target="cat-health-and-wellness">Health & Wellness <span class="scheme-badge"><?php echo $counts['Health & Wellness']; ?></span></a>
    <a href="#" data-target="cat-educational-grants">Educational Grants <span class="scheme-badge"><?php echo $counts['Educational Grants']; ?></span></a>
    <a href="#" data-target="cat-recreation-and-clubs">Recreation & Clubs <span class="scheme-badge"><?php echo $counts['Recreation & Clubs']; ?></span></a>
    <a href="#" data-target="cat-counseling-support">Counseling Support <span class="scheme-badge"><?php echo $counts['Counseling Support']; ?></span></a>
</div>

<?php
// Group active schemes by category
$categories_list = [
    'Financial Assistance' => [],
    'Health & Wellness'    => [],
    'Educational Grants'   => [],
    'Recreation & Clubs'   => [],
    'Counseling Support'   => []
];

$sql_schemes = "SELECT title, description, category, status FROM schemes WHERE status = 'Active' ORDER BY scheme_id DESC";
$schemes_result = mysqli_query($connect, $sql_schemes);

while ($scheme = mysqli_fetch_assoc($schemes_result)) {
    $cat = $scheme['category'];
    if (isset($categories_list[$cat])) {
        $categories_list[$cat][] = $scheme;
    }
}
?>

<div class="schemes-cards-scroll-container">
    <?php
    foreach ($categories_list as $category_name => $schemes) {
        if (count($schemes) === 0) {
            continue;
        }
        $category_id = 'cat-' . strtolower(str_replace([' ', '&'], ['-', 'and'], $category_name));
        ?>
        <div class="category-section-anchor" id="<?php echo $category_id; ?>">
            <h3 class="category-section-header"><?php echo htmlspecialchars($category_name); ?></h3>
            <div class="category-schemes-grid">
                <?php
                foreach ($schemes as $scheme) {
                    $icon_class = "fa-user-graduate"; // Default icon
                    $color_class = "";

                    if ($scheme['category'] == 'Health & Wellness') {
                        $icon_class = "fa-heart-pulse";
                        $color_class = "text-red";
                    } elseif ($scheme['category'] == 'Financial Assistance') {
                        $icon_class = "fa-hand-holding-dollar";
                    } elseif ($scheme['category'] == 'Recreation & Clubs') {
                        $icon_class = "fa-republican";
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
                }
                ?>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const categoryLinks = document.querySelectorAll(".category-list a");
    const scrollContainer = document.querySelector(".schemes-cards-scroll-container");

    categoryLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            
            // Remove active class from all links and add to clicked
            categoryLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");

            const targetId = this.getAttribute("data-target");
            if (targetId === "top") {
                scrollContainer.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            } else {
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    const containerTop = scrollContainer.getBoundingClientRect().top;
                    const elementTop = targetElement.getBoundingClientRect().top;
                    const scrollOffset = elementTop - containerTop + scrollContainer.scrollTop - 5; // offset slightly for headers

                    scrollContainer.scrollTo({
                        top: scrollOffset,
                        behavior: "smooth"
                    });
                }
            }
        });
    });
});
</script>
        </div>
    </main>

    <?php
include("include/footer.php")
?>

</body>
</html>