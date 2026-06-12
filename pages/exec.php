<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'exec.php') {
    header("Location: ../main.php?frmid=5");
    exit;
}
include("include/top-nav.php");
?>
<body class="interior-body">

<?php
    include("include/header.php");
    include("include/slider.php");
    ?>
<div class="executive-committee-container">
    <div class="col-header">
        <h3><i class="fa-solid fa-users text-maroon"></i> Executive Committee Members</h3>
    </div>
    <?php
            $sql_check="SELECT member_name, position, contact_number, term_year FROM executive_members WHERE YEAR(created_at) = YEAR(CURDATE()) ORDER BY id ASC;";
            $s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
            
            $Count=mysqli_num_rows($s_check);
            
            ?>

    <div class="table-scroll-wrapper">
        <table class="status-data-table executive-table">
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
                // Running your verified active 5-row query stream
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
</div>
    <?php
include("include/footer.php")
?>

</body>
</html>