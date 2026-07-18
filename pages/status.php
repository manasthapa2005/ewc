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

    <main class="container status-main-layout">
        <div class="page-title-block">
            <h2>Booking Status</h2>
            <p>View real-time availability and booking details for facilities.</p>
        </div>
        <div class="split-dashboard-row">

    <div class="split-col-left">
        <div class="integrated-calendar-container" style="margin: 0;">
            <?php
            $selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
            $selectedMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

            $currentYearReal = date('Y');
            $startYear = $currentYearReal - 3;
            $endYear = $currentYearReal + 3;

            $monthStartStr = sprintf('%04d-%02d-01', $selectedYear, $selectedMonth);
            $monthEndStr = date("Y-m-t", strtotime($monthStartStr));

            $calQuery = "SELECT booking_date, booking_status, id_no, customer_name, designation, mobile_number, booking_for 
                         FROM facility_bookings 
                         WHERE booking_date BETWEEN '$monthStartStr' AND '$monthEndStr'";
            $calResult = mysqli_query($connect, $calQuery) or die(mysqli_error($connect));

            $dateStatusMap = [];
            $bookingDetailsMap = [];
            while ($row = mysqli_fetch_array($calResult)) {
                $dateStatusMap[$row['booking_date']] = $row['booking_status'];
                $bookingDetailsMap[$row['booking_date']] = [
                    'booking_date' => $row['booking_date'],
                    'booking_status' => $row['booking_status'],
                    'id_no' => $row['id_no'],
                    'customer_name' => $row['customer_name'],
                    'designation' => $row['designation'],
                    'mobile_number' => $row['mobile_number'],
                    'booking_for' => $row['booking_for']
                ];
            }

            // Month navigation calculations
            $prevMonth = $selectedMonth - 1;
            $prevYear = $selectedYear;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear--;
            }

            $nextMonth = $selectedMonth + 1;
            $nextYear = $selectedYear;
            if ($nextMonth > 12) {
                $nextMonth = 1;
                $nextYear++;
            }
            ?>

            <div class="calendar-header-ribbon" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 18px;">
                <a href="main.php?frmid=1&month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="month-nav-arrow" style="color: #ffffff; text-decoration: none; font-weight: 600;">&lt; <?php echo date('M', mktime(0, 0, 0, $prevMonth, 1)); ?></a>
                
                <h3 style="margin: 0; font-size: 16px; font-weight: 600;"><?php echo date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)); ?></h3>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="year-selector" style="font-size: 13px; font-weight: 600; color: #ffffff; margin: 0;">Year:</label>
                    <select id="year-selector" onchange="window.location.href='main.php?frmid=1&month=<?php echo $selectedMonth; ?>&year=' + this.value" style="padding: 2px 6px; font-size: 12.5px; font-weight: 600; color: #1e3a8a; background-color: #ffffff; border: none; border-radius: 4px; cursor: pointer; outline: none;">
                        <?php
                        for ($y = $startYear; $y <= $endYear; $y++) {
                            $selected = ($y == $selectedYear) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </div>

                <a href="main.php?frmid=1&month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="month-nav-arrow" style="color: #ffffff; text-decoration: none; font-weight: 600;"><?php echo date('M', mktime(0, 0, 0, $nextMonth, 1)); ?> &gt;</a>
            </div>

            <div class="calendar-days-grid" style="margin-top: 15px;">
                <div class="weekday-header header-sun">Sun</div>
                <div class="weekday-header">Mon</div>
                <div class="weekday-header">Tue</div>
                <div class="weekday-header">Wed</div>
                <div class="weekday-header">Thu</div>
                <div class="weekday-header">Fri</div>
                <div class="weekday-header">Sat</div>

                <?php
                $numDays = date('t', strtotime($monthStartStr)); 
                $firstDayOfWeek = date('w', strtotime($monthStartStr)); 

                for ($i = 0; $i < $firstDayOfWeek; $i++) {
                    echo '<div class="calendar-date-cell cell-empty"></div>';
                }

                for ($day = 1; $day <= $numDays; $day++) {
                    $formattedDate = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                    $statusClass = 'day-normal'; 
                    
                    if (isset($dateStatusMap[$formattedDate])) {
                        if ($dateStatusMap[$formattedDate] === 'Booked') {
                            $statusClass = 'day-booked';
                        } elseif ($dateStatusMap[$formattedDate] === 'Pending') {
                            $statusClass = 'day-pending';
                        }
                    }
                    echo '<div class="calendar-date-cell ' . $statusClass . '" data-date="' . $formattedDate . '">' . $day . '</div>';
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
            
            <div class="table-scroll-wrapper" style="max-height: 400px; padding: 0;">
                <div id="booking-details-placeholder" style="text-align: center; padding: 60px 10px; color: #64748b;">
                    <i class="fa-regular fa-calendar-days" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                    <p style="font-size: 0.95rem; font-weight: 500;">Select a date from the calendar to view its booking details.</p>
                </div>
                
                <div id="booking-details-table-wrapper" style="display: none;">
                    <table class="status-data-table schema-headings-table">
                        <thead>
                            <tr>
                                <th>Field Property</th>
                                <th>Data Classification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="bold-cell">Booking Date</td>
                                <td id="detail-date">-</td>
                            </tr>
                            <tr>
                                <td class="bold-cell">Current Status</td>
                                <td id="detail-status">-</td>
                            </tr>
                            <tr>
                                <td class="bold-cell">ID Number</td>
                                <td id="detail-id">-</td>
                            </tr>
                            <tr>
                                <td class="bold-cell">Customer Name</td>
                                <td id="detail-name">-</td>
                            </tr>
                            <tr>
                                <td class="bold-cell">Designation</td>
                                <td id="detail-designation">-</td>
                            </tr>
                            <tr>
                                <td class="bold-cell">Mobile Number</td>
                                <td id="detail-mobile">-</td>
                            </tr>
                            <tr>
                                <td class="bold-cell">Booking Target</td>
                                <td id="detail-target">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const bookingsData = <?php echo json_encode($bookingDetailsMap); ?>;

        const placeholder = document.getElementById('booking-details-placeholder');
        const tableWrapper = document.getElementById('booking-details-table-wrapper');
        
        const detailDate = document.getElementById('detail-date');
        const detailStatus = document.getElementById('detail-status');
        const detailId = document.getElementById('detail-id');
        const detailName = document.getElementById('detail-name');
        const detailDesignation = document.getElementById('detail-designation');
        const detailMobile = document.getElementById('detail-mobile');
        const detailTarget = document.getElementById('detail-target');

        const cells = document.querySelectorAll('.calendar-date-cell:not(.cell-empty)');
        
        cells.forEach(cell => {
            cell.addEventListener('click', () => {
                cells.forEach(c => c.classList.remove('active-selected-date'));
                cell.classList.add('active-selected-date');

                const dateStr = cell.getAttribute('data-date');
                const dateParts = dateStr.split('-');
                const formattedDisplayDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;

                placeholder.style.display = 'none';
                tableWrapper.style.display = 'block';

                if (bookingsData[dateStr]) {
                    const booking = bookingsData[dateStr];
                    detailDate.textContent = formattedDisplayDate;
                    
                    let statusBadge = '';
                    if (booking.booking_status === 'Booked') {
                        statusBadge = '<span class="badge-status status-confirmed" style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;">Booked</span>';
                    } else if (booking.booking_status === 'Pending') {
                        statusBadge = '<span class="badge-status status-pending" style="background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a;">Pending</span>';
                    } else {
                        statusBadge = `<span class="badge-status">${booking.booking_status}</span>`;
                    }
                    detailStatus.innerHTML = statusBadge;
                    detailId.textContent = booking.id_no || 'N/A';
                    detailName.textContent = booking.customer_name || 'N/A';
                    detailDesignation.textContent = booking.designation || 'N/A';
                    detailMobile.textContent = booking.mobile_number || 'N/A';
                    detailTarget.textContent = booking.booking_for || 'N/A';
                } else {
                    detailDate.textContent = formattedDisplayDate;
                    detailStatus.innerHTML = '<span class="badge-status status-completed" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">Available</span>';
                    detailId.textContent = 'N/A';
                    detailName.textContent = 'N/A';
                    detailDesignation.textContent = 'N/A';
                    detailMobile.textContent = 'N/A';
                    detailTarget.textContent = 'N/A';
                }
            });
        });
    });
    </script>

    <?php
include("include/footer.php")
?>
</body>
