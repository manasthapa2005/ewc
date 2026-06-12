<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'status.php') {
    header("Location: ../main.php?frmid=1");
    exit;
}
include("include/top-nav.php");
?>
<body class="interior-body">

    <?php
include("include/header.php");
include("include/slider.php");
    ?>

    <main>
    <div class="split-dashboard-row">

    <div class="split-col-left">
        <div class="integrated-calendar-container" style="margin: 0;">
            <?php
            // Keep your exact working calendar logic completely untouched
            $currentYear = 2026;
            $currentMonth = 6; 
            
            $monthStartStr = "$currentYear-$currentMonth-01";
            $monthEndStr = date("Y-m-t", strtotime($monthStartStr));
            
            $calQuery = "SELECT booking_date, booking_status FROM facility_bookings 
                         WHERE booking_date BETWEEN '$monthStartStr' AND '$monthEndStr'";
            $calResult = mysqli_query($connect, $calQuery) or die(mysqli_error($connect));
            
            $dateStatusMap = [];
            while ($row = mysqli_fetch_array($calResult)) {
                $dateStatusMap[$row['booking_date']] = $row['booking_status'];
            }

            $numDays = date('t', strtotime($monthStartStr)); 
            $firstDayOfWeek = date('w', strtotime($monthStartStr)); 
            $monthLabel = date('F Y', strtotime($monthStartStr)); 
            ?>

            <div class="calendar-header-ribbon">
                <span class="month-nav-arrow">&lt; May</span>
                <h3><?php echo $monthLabel; ?></h3>
                <span class="month-nav-arrow">Jul &gt;</span>
            </div>

            <div class="calendar-days-grid">
                <div class="weekday-header header-sun">Sun</div>
                <div class="weekday-header">Mon</div>
                <div class="weekday-header">Tue</div>
                <div class="weekday-header">Wed</div>
                <div class="weekday-header">Thu</div>
                <div class="weekday-header">Fri</div>
                <div class="weekday-header header-sat">Sat</div>

                <?php
                for ($i = 0; $i < $firstDayOfWeek; $i++) {
                    echo '<div class="calendar-date-cell cell-empty"></div>';
                }

                for ($day = 1; $day <= $numDays; $day++) {
                    $formattedDate = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                    $statusClass = 'day-normal'; 
                    
                    if (isset($dateStatusMap[$formattedDate])) {
                        if ($dateStatusMap[$formattedDate] === 'Booked') {
                            $statusClass = 'day-booked';
                        } elseif ($dateStatusMap[$formattedDate] === 'Pending') {
                            $statusClass = 'day-pending';
                        }
                    }
                    echo '<div class="calendar-date-cell ' . $statusClass . '">' . $day . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="split-col-right">
        <div class="integrated-details-card">
            <div class="details-header-ribbon">
                <h3><i class="fa-solid fa-list-check"></i> Booking Metadata Fields</h3>
            </div>
            
            <div class="table-scroll-wrapper" style="max-height: 290px;">
            <?php
            $sql_check="SELECT booking_date, booking_status, id_no, customer_name, designation, mobile_number, booking_for 
            FROM facility_bookings 
            WHERE booking_date BETWEEN '$monthStartStr' AND '$monthEndStr'";
            $s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
            
            $Count=mysqli_num_rows($s_check);
            
            ?>
            <div class="list-wrapper">  
                <table class="status-data-table schema-headings-table">
                    <thead>
                        <tr>
                            <th>Field Property</th>
                            <th>Data Classification</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
            while($row = mysqli_fetch_array($s_check)) { 
                ?>  
                        <tr>
                            <td class="bold-cell">Booking Date</td>
                            <td><?php echo $row['booking_date'] ?></td>
                        </tr>
                        <tr>
                            <td class="bold-cell">Current Status</td>
                            <td><?php echo $row['booking_status'] ?></td>
                        </tr>
                        <tr>
                            <td class="bold-cell">ID Number</td>
                            <td><?php echo $row['id_no']; ?></td>
                        </tr>

                        <tr>
                            <td class="bold-cell">Customer Name</td>
                            <td><?php echo $row['customer_name']; ?></td>
                        </tr>
                            <td class="bold-cell">Designation</td>
                            <td><?php echo $row['designation'] ?></td>
                        </tr>
                        <tr>
                            <td class="bold-cell">Mobile Number</td>
                            <td><?php echo $row['mobile_number'] ?></td>
                        </tr>
                        <tr>
                            <td class="bold-cell">Booking Target</td>
                            <td><?php echo $row['booking_for'] ?></td>
                        </tr>
                        <?php 
                          } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
    </main>

    <?php
include("include/footer.php")
?>
