<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../../../main.php?frmid=7");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welfare Schemes | ONGC EWC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="interior-body">

    <header class="main-header">
        <div class="container header-container">
            <div class="brand-identity">
                <img src="../../assets/images/ongc_logo.png" alt="ONGC Logo" class="logo-ongc">
                <div class="brand-text">
                    <h1>ONGC EWC-DEHRADUN</h1>
                    <p>EMPLOYEE WELFARE COMMITTEE</p>
                </div>
            </div>

            <nav class="main-nav">
                <div class="nav-links">
                    <a href="admin.php" class="nav-item current"><i class="fa-solid fa-house"></i> Home</a>
                    <a href="add-exec.php" class="nav-item"><i class="fa-regular fa-address-book"></i>
                        Executive Member</a>
                    <a href="album.php" class="nav-item"><i class="fa-regular fa-calendar-check"></i>
                        New Album</a>
                    <a href="event.php" class="nav-item">New Event</a>
                    <a href="registration.php" class="nav-item"><i class="fa-regular fa-image"></i>
                        Registration</a>
                    <a href="booking.php" class="nav-item"><i class="fa-solid fa-user-tie"></i> Bookings</a>
                    <a href="book-req.php" class="nav-item bg-pill">View Booking Request</a>

                    <a href="settings.php" class="admin-login-btn"><i class="fa-regular fa-circle-user"></i>Account Settings <i class="fa fa-chevron-down"></i></a>
                    <a href="logout.php" class="admin-login-btn" style="background-color: #dc2626; color: white;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
            </nav>
        </div>
    </header>
    <div class="member-form-wrapper">
        <div class="form-container">
            <h2>Add Elected Member</h2>
            <p class="form-subtitle">Enter the Detail of New Elected Member:</p>
            <div id="form-notification" style="display: none; padding: 12px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;"></div>

            <form action="/add-member" method="POST">
                <!-- Search Row -->
                <div class="search-row">
                    <div class="form-group">
                        <label for="cpf-no">Enter CPF No. :</label>
                        <div class="search-input-group">
                            <input type="text" id="cpf-no" name="cpf_no" placeholder="e.g. 12345" required>
                            <button type="button" class="btn-search">Search</button>
                        </div>
                    </div>
                </div>

                <!-- Main Form Grid Layout -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="member-name">Member Name:</label>
                        <input type="text" id="member-name" name="member_name" readonly>
                    </div>

                    <div class="form-group">
                        <label for="designation-text">Designation:</label>
                        <input type="text" id="designation-text" name="designation_text" readonly>
                    </div>

                    <div class="form-group">
                        <label for="mobile-number">Mobile Number:</label>
                        <input type="tel" id="mobile-number" name="mobile_number" readonly>
                    </div>

                    <div class="form-group">
                        <label for="tel-number">Telephone Number:</label>
                        <input type="tel" id="tel-number" name="telephone_number" readonly>
                    </div>

                    <div class="form-group">
                        <label for="elected-year">Term Year:</label>
                        <input type="text" id="elected-year" name="term_year" placeholder="e.g. 2025-27" required>
                    </div>

                    <div class="form-group">
                        <label for="designation-select">Designation Type:</label>
                        <select id="designation-select" name="designation_select" required>
                            <option value="" disabled selected>Select option</option>
                            <option value="PRESIDENT">PRESIDENT</option>
                            <option value="SECRETARY">SECRETARY</option>
                            <option value="JOINT SECRETARY">JOINT SECRETARY</option>
                            <option value="TREASURER">TREASURER</option>
                            <option value="EXECUTIVE MEMBER">EXECUTIVE MEMBER</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button Row -->
                <div class="submit-row">
                    <button type="submit" class="btn-submit">Add Member</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cpfInput = document.getElementById('cpf-no');
        const searchBtn = document.querySelector('.btn-search');
        const memberNameInput = document.getElementById('member-name');
        const designationInput = document.getElementById('designation-text');
        const mobileInput = document.getElementById('mobile-number');
        const telInput = document.getElementById('tel-number');
        const termYearInput = document.getElementById('elected-year');
        const designationSelect = document.getElementById('designation-select');
        const form = document.querySelector('form');
        const notification = document.getElementById('form-notification');

        function showNotification(message, isSuccess) {
            notification.textContent = message;
            notification.style.display = 'block';
            if (isSuccess) {
                notification.style.backgroundColor = '#d1fae5';
                notification.style.color = '#065f46';
                notification.style.border = '1px solid #a7f3d0';
            } else {
                notification.style.backgroundColor = '#fee2e2';
                notification.style.color = '#991b1b';
                notification.style.border = '1px solid #fecaca';
            }
        }

        searchBtn.addEventListener('click', function() {
            const cpf = cpfInput.value.trim();
            if (!cpf) {
                showNotification('Please enter a CPF Number to search.', false);
                return;
            }

            fetch(`fetch-employee.php?cpf_no=${encodeURIComponent(cpf)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        memberNameInput.value = data.data.name;
                        designationInput.value = data.data.designation;
                        mobileInput.value = data.data.phone_no;
                        telInput.value = data.data.epbax_office;
                        showNotification('Employee details loaded successfully.', true);
                    } else {
                        memberNameInput.value = '';
                        designationInput.value = '';
                        mobileInput.value = '';
                        telInput.value = '';
                        showNotification(data.message, false);
                    }
                })
                .catch(error => {
                    showNotification('Error fetching employee details.', false);
                });
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const cpf = cpfInput.value.trim();
            const termYear = termYearInput.value.trim();
            const designation = designationSelect.value;

            if (!cpf || !termYear || !designation) {
                showNotification('Please search a valid employee and fill all mandatory fields.', false);
                return;
            }

            const formData = new FormData();
            formData.append('cpf_no', cpf);
            formData.append('term_year', termYear);
            formData.append('designation_select', designation);

            fetch('save-elected.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Elected Member updated successfully.', true);
                    form.reset();
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                showNotification('Error saving elected member.', false);
            });
        });
    });
    </script>
</body>

</html>