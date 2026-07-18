<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("head.php");
include("../db.php");
?>
<body class="interior-body">
<?php
include("menu.php");
?>

    <!-- Enclosed wrapper to isolate this specific table view perfectly -->
    <div class="booking-status-scoped">

        <!-- Title Header Section -->
        <div class="status-header">
            <h2>Booking Request Status</h2>
        </div>

        <!-- Main Solid Green Data Table Display Grid -->
        <div class="status-table-container">
            <table class="status-data-table">
                <thead>
                    <tr>
                        <th class="col-id">ID No</th>
                        <th class="col-name">Name</th>
                        <th class="col-desig">Designation</th>
                        <th class="col-date">Booking Date</th>
                        <th class="col-status">Status</th>
                        <th class="col-mobile">Mobile</th>
                        <th class="col-for">Booking For</th>
                        <th class="col-action" style="width: 8%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$connect) {
                        echo "<tr><td colspan='8' style='text-align:center; color:#dc2626;'>Database connection error.</td></tr>";
                    } else {
                        $sql_req = "SELECT id, id_no, customer_name, designation, booking_date, booking_status, mobile_number, booking_for FROM facility_bookings ORDER BY booking_date DESC, id DESC";
                        $res_req = mysqli_query($connect, $sql_req);
                        if ($res_req && mysqli_num_rows($res_req) > 0) {
                            while ($row = mysqli_fetch_assoc($res_req)) {
                                $status_style = "";
                                if ($row['booking_status'] === 'Booked') {
                                    $status_style = "color: #16a34a; font-weight: bold;";
                                } elseif ($row['booking_status'] === 'Pending') {
                                    $status_style = "color: #d97706; font-weight: bold;";
                                } else {
                                    $status_style = "color: #dc2626; font-weight: bold;";
                                }
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['designation']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                                echo "<td style='" . $status_style . "'>" . htmlspecialchars($row['booking_status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['mobile_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['booking_for']) . "</td>";
                                echo "<td><a href='download-booking-pdf.php?id=" . htmlspecialchars($row['id']) . "' target='_blank' style='background-color: #15803d; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: bold; display: inline-flex; align-items: center; gap: 4px;'><i class='fa-solid fa-file-pdf'></i> PDF</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center; padding: 20px; color: #6b7280;'>No booking requests found.</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>


    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>