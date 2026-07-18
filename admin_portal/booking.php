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
    <div class="registration-module-isolated">

        <div class="registration-header">
            <h2>Booking</h2>
        </div>

        <?php if (isset($_SESSION['booking_success'])): ?>
            <div class="booking-success-alert" style="color: #065f46; background-color: #d1fae5; border: 1px solid #a7f3d0; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['booking_success']); unset($_SESSION['booking_success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['booking_error'])): ?>
            <div class="booking-error-alert" style="color: #991b1b; background-color: #fee2e2; border: 1px solid #fecaca; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['booking_error']); unset($_SESSION['booking_error']); ?>
            </div>
        <?php endif; ?>

        <div class="registration-actions-box tab-bar">
            <button type="button" class="action-btn active-tab" onclick="switchBookingTab(event, 'view-panel')">View Bookings</button>
            <button type="button" class="action-btn" onclick="switchBookingTab(event, 'make-panel')">Make Booking</button>
            <button type="button" class="action-btn" onclick="switchBookingTab(event, 'cancel-panel')">Cancel Booking</button>
        </div>

        <!-- PANEL 1: VIEW BOOKINGS (TABLE) -->
        <div id="view-panel" class="registration-form-container booking-panel active-panel">
            <div class="booking-status-scoped" style="padding: 0;">
                <div class="status-table-container">
                    <table class="status-data-table">
                        <thead>
                            <tr>
                                <th class="col-id">Booking ID</th>
                                <th class="col-date">Booking Date</th>
                                <th>CPF No</th>
                                <th>Customer Name</th>
                                <th>Designation</th>
                                <th>Mobile</th>
                                <th>Facility</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_bookings = "SELECT id, booking_date, booking_status, id_no, customer_name, designation, mobile_number, booking_for FROM facility_bookings ORDER BY booking_date DESC, id DESC";
                            $res_bookings = mysqli_query($connect, $sql_bookings);
                            if ($res_bookings && mysqli_num_rows($res_bookings) > 0) {
                                while ($row = mysqli_fetch_assoc($res_bookings)) {
                                    $status_class = "";
                                    if ($row['booking_status'] === 'Booked') {
                                        $status_class = "color: #16a34a; font-weight: bold;";
                                    } elseif ($row['booking_status'] === 'Pending') {
                                        $status_class = "color: #d97706; font-weight: bold;";
                                    } else {
                                        $status_class = "color: #dc2626; font-weight: bold;";
                                    }
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['id_no']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['designation']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mobile_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['booking_for']) . "</td>";
                                    echo "<td style='" . $status_class . "'>" . htmlspecialchars($row['booking_status']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align: center;'>No bookings found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PANEL 2: MAKE BOOKING (FORM) -->
        <div id="make-panel" class="registration-form-container booking-panel" style="display: none;">
            <h3>Make a New Facility Booking:</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Fill in the CPF number and click Search to auto-fill member details.</p>

            <form action="make-booking-process.php" method="POST" id="make-booking-form">

                <!-- ROW 1: CPF Search -->
                <div style="display: flex; align-items: flex-end; gap: 10px; margin-bottom: 14px; max-width: 780px;">
                    <div style="display: flex; flex-direction: column; gap: 5px; flex: 1;">
                        <label for="mb-cpf" style="font-weight: 600; font-size: 14px;">Enter CPF No.: <span style="color: #dc2626;">*</span></label>
                        <input type="text" id="mb-cpf" name="id_no" placeholder="Enter CPF number" required
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>
                    <button type="button" id="btn-cpf-search" onclick="searchCPF()"
                        style="padding: 9px 22px; background-color: #15803d; color: white; border: none; border-radius: 5px; font-size: 14px; font-weight: 600; font-family: 'Outfit', sans-serif; cursor: pointer; white-space: nowrap;">
                        Search
                    </button>
                </div>

                <!-- GRID: Member Details -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px 20px; max-width: 780px;">

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-name" style="font-weight: 600; font-size: 14px;">Member Name:</label>
                        <input type="text" id="mb-name" name="customer_name" placeholder="Auto-filled on search"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-desig" style="font-weight: 600; font-size: 14px;">Designation:</label>
                        <input type="text" id="mb-desig" name="designation" placeholder="Auto-filled on search"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-mobile" style="font-weight: 600; font-size: 14px;">Mobile Number:</label>
                        <input type="tel" id="mb-mobile" name="mobile_number" placeholder="10-digit mobile" maxlength="10"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-epbax" style="font-weight: 600; font-size: 14px;">EPBAX-Office:</label>
                        <input type="text" id="mb-epbax" name="epbax_office" placeholder="Office extension"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-email" style="font-weight: 600; font-size: 14px;">Email:</label>
                        <input type="email" id="mb-email" name="email" placeholder="employee@ongc.co.in"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-residence" style="font-weight: 600; font-size: 14px;">Residence No:</label>
                        <input type="text" id="mb-residence" name="residence_no" placeholder="Residence phone/quarter no."
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-purpose" style="font-weight: 600; font-size: 14px;">Purpose: <span style="color: #dc2626;">*</span></label>
                        <select id="mb-purpose" name="booking_for" required
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none; background: white;">
                            <option value="" disabled selected>--Select--</option>
                            <option value="Guest House">Guest House</option>
                            <option value="Holiday Home">Holiday Home</option>
                            <option value="Club">Club</option>
                            <option value="Stadium">Stadium</option>
                            <option value="Community Hall">Community Hall</option>
                            <option value="Conference Room">Conference Room</option>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-relation" style="font-weight: 600; font-size: 14px;">Relation with Employee:</label>
                        <select id="mb-relation" name="relation_with_employee"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none; background: white;">
                            <option value="" disabled selected>--Select--</option>
                            <option value="Self">Self</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Son">Son</option>
                            <option value="Daughter">Daughter</option>
                            <option value="Father">Father</option>
                            <option value="Mother">Mother</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-booking-date" style="font-weight: 600; font-size: 14px;">Booking Date: <span style="color: #dc2626;">*</span></label>
                        <input type="date" id="mb-booking-date" name="booking_date" required
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-days" style="font-weight: 600; font-size: 14px;">No of Days:</label>
                        <select id="mb-days" name="no_of_days"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none; background: white;">
                            <option value="" disabled selected>--Select--</option>
                            <?php for ($d = 1; $d <= 30; $d++): ?>
                                <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-amount" style="font-weight: 600; font-size: 14px;">Amount:</label>
                        <input type="number" id="mb-amount" name="amount" placeholder="0.00" min="0" step="0.01"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-payment-by" style="font-weight: 600; font-size: 14px;">Payment By:</label>
                        <select id="mb-payment-by" name="payment_by"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none; background: white;">
                            <option value="" disabled selected>Select</option>
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="DD">Demand Draft (DD)</option>
                            <option value="UPI">UPI / Online Transfer</option>
                            <option value="NEFT">NEFT / RTGS</option>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-dated" style="font-weight: 600; font-size: 14px;">Dated:</label>
                        <input type="date" id="mb-dated" name="payment_date"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="mb-bank" style="font-weight: 600; font-size: 14px;">Bank Name:</label>
                        <input type="text" id="mb-bank" name="bank_name" placeholder="Bank name"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px; grid-column: 1 / -1;">
                        <label for="mb-cheque" style="font-weight: 600; font-size: 14px;">Draft/Cheque No./Transaction No:</label>
                        <input type="text" id="mb-cheque" name="cheque_transaction_no" placeholder="Cheque / DD / Transaction number"
                            style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; font-family: 'Outfit', sans-serif; outline: none; max-width: 380px;">
                    </div>

                </div>

                <!-- Action Buttons -->
                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" name="submit_booking"
                        style="padding: 10px 32px; background-color: #15803d; color: white; border: none; border-radius: 5px; font-size: 15px; font-weight: 700; font-family: 'Outfit', sans-serif; cursor: pointer; letter-spacing: 0.5px;">
                        Book
                    </button>
                    <button type="reset"
                        style="padding: 10px 28px; background-color: #15803d; color: white; border: none; border-radius: 5px; font-size: 15px; font-weight: 700; font-family: 'Outfit', sans-serif; cursor: pointer; letter-spacing: 0.5px;">
                        Cancel
                    </button>
                    <button type="button" onclick="printBookingForm()"
                        style="padding: 10px 28px; background-color: #15803d; color: white; border: none; border-radius: 5px; font-size: 15px; font-weight: 700; font-family: 'Outfit', sans-serif; cursor: pointer; letter-spacing: 0.5px;">
                        Print
                    </button>
                </div>

            </form>
        </div>

        <!-- PANEL 3: CANCEL BOOKING (FORM) -->
        <div id="cancel-panel" class="registration-form-container booking-panel" style="display: none;">
            <h3>Cancel Facility Booking:</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Select an active facility booking from the dropdown below to cancel it.</p>
            
            <form action="cancel-booking-process.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                <div class="row-group" style="max-width: 600px; display: flex; flex-direction: column; align-items: flex-start; gap: 8px;">
                    <label for="cancel-booking-id" style="font-weight: 600;">Select Booking to Cancel:</label>
                    <select id="cancel-booking-id" name="booking_id" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                        <option value="" disabled selected>-- Select Booking --</option>
                        <?php
                        $sql_active = "SELECT id, booking_date, id_no, customer_name, booking_for FROM facility_bookings WHERE booking_status IN ('Booked', 'Pending') ORDER BY booking_date DESC, id DESC";
                        $res_active = mysqli_query($connect, $sql_active);
                        if ($res_active && mysqli_num_rows($res_active) > 0) {
                            while ($row = mysqli_fetch_assoc($res_active)) {
                                echo '<option value="' . htmlspecialchars($row['id']) . '">' 
                                     . 'ID: ' . htmlspecialchars($row['id']) 
                                     . ' - ' . htmlspecialchars($row['customer_name']) 
                                     . ' (' . htmlspecialchars($row['booking_for']) . ' on ' . htmlspecialchars($row['booking_date']) . ')' 
                                     . '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>No active bookings found</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" name="submit_cancel" class="btn-booking-action btn-cancel" style="margin-top: 15px; width: max-content; display: inline-block;">Cancel Selected Booking</button>
                </div>
            </form>
        </div>
    </div>

    <!-- INTERACTIVE TAB SWITCHING SCRIPT -->
    <script>
        function switchBookingTab(evt, panelId) {
            // Hide all booking panels
            const panels = document.querySelectorAll('.registration-module-isolated .booking-panel');
            panels.forEach(panel => {
                panel.style.display = 'none';
                panel.classList.remove('active-panel');
            });

            // Remove active class from all tab buttons
            const tabs = document.querySelectorAll('.registration-module-isolated .tab-bar .action-btn');
            tabs.forEach(tab => {
                tab.classList.remove('active-tab');
            });

            // Show selected panel
            const targetPanel = document.getElementById(panelId);
            targetPanel.style.display = 'block';
            targetPanel.classList.add('active-panel');

            // Mark current tab as active
            evt.currentTarget.classList.add('active-tab');
        }

        // CPF Search: auto-fill member details via AJAX
        function searchCPF() {
            const cpf = document.getElementById('mb-cpf').value.trim();
            if (!cpf) {
                alert('Please enter a CPF number to search.');
                return;
            }
            const btn = document.getElementById('btn-cpf-search');
            btn.textContent = 'Searching...';
            btn.disabled = true;

            fetch('fetch-employee.php?typ=employee&md=0&cpf_no=' + encodeURIComponent(cpf))
                .then(res => res.json())
                .then(data => {
                    if (data && data.name) {
                        document.getElementById('mb-name').value      = data.name         || '';
                        document.getElementById('mb-desig').value     = data.designation  || '';
                        document.getElementById('mb-mobile').value    = data.phone_no     || '';
                        document.getElementById('mb-epbax').value     = data.epbax_office || '';
                    } else {
                        alert('No employee found with this CPF number.');
                    }
                })
                .catch(() => alert('Error connecting to server. Please try again.'))
                .finally(() => {
                    btn.textContent = 'Search';
                    btn.disabled = false;
                });
        }

        // Print the booking form data
        function printBookingForm() {
            const f = {
                cpf:       document.getElementById('mb-cpf').value,
                name:      document.getElementById('mb-name').value,
                desig:     document.getElementById('mb-desig').value,
                mobile:    document.getElementById('mb-mobile').value,
                epbax:     document.getElementById('mb-epbax').value,
                email:     document.getElementById('mb-email').value,
                residence: document.getElementById('mb-residence').value,
                purpose:   document.getElementById('mb-purpose').value,
                relation:  document.getElementById('mb-relation').value,
                date:      document.getElementById('mb-booking-date').value,
                days:      document.getElementById('mb-days').value,
                amount:    document.getElementById('mb-amount').value,
                payment:   document.getElementById('mb-payment-by').value,
                dated:     document.getElementById('mb-dated').value,
                bank:      document.getElementById('mb-bank').value,
                cheque:    document.getElementById('mb-cheque').value,
            };
            const w = window.open('', '_blank', 'width=800,height=700');
            w.document.write(`
                <html><head><title>Booking Form - ONGC EWC</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 30px; font-size: 14px; }
                    h2 { text-align: center; color: #166534; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    td { padding: 8px 12px; border: 1px solid #ccc; }
                    td:first-child { font-weight: bold; width: 35%; background: #f9fafb; }
                    .btn-row { margin-top: 20px; text-align: center; }
                    @media print { .btn-row { display: none; } }
                </style></head><body>
                <h2>ONGC EWC — Facility Booking Form</h2>
                <table>
                    <tr><td>CPF No.</td><td>${f.cpf}</td></tr>
                    <tr><td>Member Name</td><td>${f.name}</td></tr>
                    <tr><td>Designation</td><td>${f.desig}</td></tr>
                    <tr><td>Mobile Number</td><td>${f.mobile}</td></tr>
                    <tr><td>EPBAX-Office</td><td>${f.epbax}</td></tr>
                    <tr><td>Email</td><td>${f.email}</td></tr>
                    <tr><td>Residence No.</td><td>${f.residence}</td></tr>
                    <tr><td>Purpose</td><td>${f.purpose}</td></tr>
                    <tr><td>Relation with Employee</td><td>${f.relation}</td></tr>
                    <tr><td>Booking Date</td><td>${f.date}</td></tr>
                    <tr><td>No. of Days</td><td>${f.days}</td></tr>
                    <tr><td>Amount</td><td>${f.amount}</td></tr>
                    <tr><td>Payment By</td><td>${f.payment}</td></tr>
                    <tr><td>Dated</td><td>${f.dated}</td></tr>
                    <tr><td>Bank Name</td><td>${f.bank}</td></tr>
                    <tr><td>Draft/Cheque/Transaction No.</td><td>${f.cheque}</td></tr>
                </table>
                <div class="btn-row"><button onclick="window.print()">🖨 Print</button></div>
                </body></html>`);
            w.document.close();
        }
    </script>
    </div>

    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>