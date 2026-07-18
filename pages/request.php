<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'request.php') {
    header("Location: ../main.php?frmid=2");
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
            <h2>Booking Request</h2>
            <p>Request a facility or service for your events and requirements.</p>
        </div>

        <!-- SEARCH & NAVIGATION PANEL CARD -->
        <div class="filter-search-card" style="padding: 20px; border-radius: 8px;">
            <!-- NAV BUTTONS ROW FROM SCREENSHOT -->
            <div class="booking-nav-buttons-row" style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
                <a href="assets/docs/community_center_booking_charges.pdf" target="_blank" class="nav-green-btn" style="flex: 1; min-width: 250px; background-color: #15803d; color: white; padding: 12px 20px; border-radius: 4px; text-decoration: none; font-weight: 700; text-align: center; font-size: 14px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.2s ease;">
                    Click here to Download Booking Terms and Condition
                </a>
                <a href="main.php?frmid=1" class="nav-green-btn" style="flex: 1; min-width: 250px; background-color: #15803d; color: white; padding: 12px 20px; border-radius: 4px; text-decoration: none; font-weight: 700; text-align: center; font-size: 14px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.2s ease;">
                    Click here to View Booking Status
                </a>
            </div>

            <h4 style="font-size: 14px; font-weight: bold; margin-bottom: 12px; color: var(--maroon-primary);">Quick Search (By CPF Number)</h4>
            <div class="filter-form-inputs">
                <div class="input-field-group">
                    <label>CPF Number</label>
                    <input type="text" id="search-cpf" placeholder="Enter CPF Registration Number" style="height: 38px; border-radius: 4px;">
                </div>
                <div class="input-field-group">
                    <label>Date of Birth (DD/MM/YYYY)</label>
                    <input type="date" id="search-dob" style="height: 38px; border-radius: 4px;">
                </div>
                <button type="button" id="btn-search-cpf-submit" class="btn-search-submit" style="height: 38px; border-radius: 4px; padding: 0 25px; font-weight: bold;">Search</button>
            </div>
            
            <!-- AJAX Verification Status Alert Banner -->
            <div id="verification-status-msg" style="margin-top: 15px; padding: 10px 15px; border-radius: 4px; font-size: 13.5px; font-weight: 500; display: none;"></div>
        </div>

        <!-- POPULAR FACILITIES & SERVICES SECTION -->
        <div class="facilities-selection-section" style="margin-top: 25px;">
            <h4 style="font-size: 14px; font-weight: bold; margin-bottom: 12px; color: #334155;">Popular Facilities & Services</h4>
            <div class="facilities-grid-container" id="facilities-grid">
                
                <div class="facility-select-card facility-card-interactive" data-facility="Community Center Slot" style="border-radius: 8px; padding: 20px 10px; transition: all 0.2s ease;">
                    <div class="facility-icon-wrap"><i class="fa-regular fa-building text-maroon"></i></div>
                    <h5>Community Center Slot</h5>
                </div>
                
                <div class="facility-select-card facility-card-interactive" data-facility="Guest House Booking" style="border-radius: 8px; padding: 20px 10px; transition: all 0.2s ease;">
                    <div class="facility-icon-wrap"><i class="fa-solid fa-hotel text-maroon"></i></div>
                    <h5>Guest House Booking</h5>
                </div>
                
                <div class="facility-select-card facility-card-interactive" data-facility="Transport Facility" style="border-radius: 8px; padding: 20px 10px; transition: all 0.2s ease;">
                    <div class="facility-icon-wrap"><i class="fa-solid fa-bus text-maroon"></i></div>
                    <h5>Transport Facility</h5>
                </div>
                
                <div class="facility-select-card facility-card-interactive" data-facility="Equipment / Other Services" style="border-radius: 8px; padding: 20px 10px; transition: all 0.2s ease;">
                    <div class="facility-icon-wrap"><i class="fa-solid fa-screwdriver-wrench text-maroon"></i></div>
                    <h5>Equipment / Other Services</h5>
                </div>
                
                <div class="facility-select-card facility-card-interactive" data-facility="Need Help?" style="border-radius: 8px; padding: 20px 10px; transition: all 0.2s ease;">
                    <div class="facility-icon-wrap"><i class="fa-regular fa-circle-question text-maroon"></i></div>
                    <h5>Need Help?</h5>
                </div>
                
            </div>
        </div>

        <!-- DYNAMIC BOOKING REQUEST FORM (Initially Hidden) -->
        <div id="booking-request-form-container" class="filter-search-card" style="display: none; margin-top: 25px; padding: 25px; border: 1.5px solid var(--maroon-primary); border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
            <h3 style="color: var(--maroon-primary); font-size: 16px; margin-bottom: 18px; font-weight: bold; border-bottom: 2px solid #F1F5F9; padding-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-regular fa-calendar-check"></i> <span id="form-facility-title">Facility Booking Application</span>
            </h3>
            
            <form id="public-booking-request-form">
                <!-- Hidden fields for submission -->
                <input type="hidden" name="cpf_no" id="form-cpf">
                <input type="hidden" name="purpose" id="form-facility-name">

                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px 20px;">
                    <div class="input-field-group">
                        <label>Employee Name</label>
                        <input type="text" id="form-emp-name" name="customer_name" readonly style="background-color: #f1f5f9; cursor: not-allowed; border-color: #cbd5e1; height: 38px; color: #475569;">
                    </div>
                    
                    <div class="input-field-group">
                        <label>Designation</label>
                        <input type="text" id="form-emp-desig" name="designation" readonly style="background-color: #f1f5f9; cursor: not-allowed; border-color: #cbd5e1; height: 38px; color: #475569;">
                    </div>
                    
                    <div class="input-field-group">
                        <label>Contact Number *</label>
                        <input type="text" id="form-emp-phone" name="phone_no" required maxlength="10" placeholder="10-digit mobile number" style="height: 38px;">
                    </div>
                    
                    <div class="input-field-group">
                        <label>Relation with Employee *</label>
                        <select name="relation_with_employee" id="form-relation" required style="padding: 6px 10px; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12px; height: 38px; background: white; outline: none;">
                            <option value="" disabled selected>--Select Relation--</option>
                            <option value="Self">Self</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Son">Son</option>
                            <option value="Daughter">Daughter</option>
                            <option value="Father">Father</option>
                            <option value="Mother">Mother</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="input-field-group">
                        <label>Booking Date *</label>
                        <input type="date" name="booking_date" id="form-booking-date" required style="height: 38px;">
                    </div>
                    
                    <div class="input-field-group">
                        <label>Number of Days *</label>
                        <select name="no_of_days" id="form-days" required style="padding: 6px 10px; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12px; height: 38px; background: white; outline: none;">
                            <option value="" disabled selected>--Select Days--</option>
                            <?php for ($d = 1; $d <= 30; $d++): ?>
                                <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="input-field-group" style="grid-column: 1 / -1;">
                        <label>Purpose of Booking / Special Remarks</label>
                        <textarea name="remarks" id="form-remarks" rows="3" placeholder="Enter details about your event, venue details, or special requests" style="padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12px; font-family: inherit; resize: vertical; outline: none;"></textarea>
                    </div>
                </div>

                <!-- Submission Alerts banner inside form -->
                <div id="form-submission-alert" style="margin-top: 15px; padding: 10px 15px; border-radius: 4px; font-size: 13.5px; font-weight: 500; display: none;"></div>

                <div style="margin-top: 20px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-search-submit" style="background-color: #15803d; border-radius: 4px; height: 38px; padding: 0 25px; font-weight: bold; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-check"></i> Submit Booking Request
                    </button>
                    <button type="button" id="btn-cancel-request" class="btn-search-submit" style="background-color: #64748b; border-radius: 4px; height: 38px; padding: 0 20px; font-weight: bold; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-xmark"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php
include("include/footer.php")
    ?>

    <!-- JAVASCRIPT LOGIC FOR VALIDATION AND FORMS -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let isEmployeeVerified = false;
        let verifiedEmployeeData = null;

        const searchCpfInput = document.getElementById("search-cpf");
        const searchDobInput = document.getElementById("search-dob");
        const btnSearchSubmit = document.getElementById("btn-search-cpf-submit");
        const verificationStatusMsg = document.getElementById("verification-status-msg");

        const facilityCards = document.querySelectorAll(".facility-card-interactive");
        const bookingFormContainer = document.getElementById("booking-request-form-container");
        const formFacilityTitle = document.getElementById("form-facility-title");
        const formFacilityName = document.getElementById("form-facility-name");
        
        // Form Fields
        const formCpf = document.getElementById("form-cpf");
        const formEmpName = document.getElementById("form-emp-name");
        const formEmpDesig = document.getElementById("form-emp-desig");
        const formEmpPhone = document.getElementById("form-emp-phone");
        const formRelation = document.getElementById("form-relation");
        const formBookingDate = document.getElementById("form-booking-date");
        const formDays = document.getElementById("form-days");
        const formRemarks = document.getElementById("form-remarks");
        const btnCancelRequest = document.getElementById("btn-cancel-request");
        const formSubmissionAlert = document.getElementById("form-submission-alert");
        const requestForm = document.getElementById("public-booking-request-form");

        // Set CSS styles dynamically to support hovered/active states
        const styles = document.createElement('style');
        styles.innerHTML = `
            .nav-green-btn:hover {
                background-color: #166534 !important;
            }
            .facility-card-interactive {
                position: relative;
                opacity: 0.65;
                border: 1px solid #E2E8F0;
                background-color: #F8FAFC;
                cursor: pointer;
            }
            .facility-card-interactive.unlocked {
                opacity: 1;
                background-color: #FFFFFF;
                border-color: #E2E8F0;
            }
            .facility-card-interactive.unlocked:hover {
                border-color: var(--maroon-primary) !important;
                transform: translateY(-3px) !important;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            }
            .facility-card-interactive.active-selected {
                border-color: var(--maroon-primary) !important;
                background-color: #FFF5F5 !important;
                box-shadow: 0 0 0 2px var(--maroon-primary);
            }
        `;
        document.head.appendChild(styles);

        // Initially cards are visualised as locked
        facilityCards.forEach(card => {
            card.classList.remove("unlocked");
        });

        // 1. CPF Search Validation Event
        btnSearchSubmit.addEventListener("click", function() {
            const cpfVal = searchCpfInput.value.trim();
            const dobVal = searchDobInput.value.trim();

            if (!cpfVal) {
                showVerificationStatus("Please enter a CPF Number.", "error");
                searchCpfInput.focus();
                return;
            }

            // Reset States
            isEmployeeVerified = false;
            verifiedEmployeeData = null;
            bookingFormContainer.style.display = "none";
            facilityCards.forEach(c => {
                c.classList.remove("unlocked");
                c.classList.remove("active-selected");
            });
            showVerificationStatus("Verifying CPF Number...", "loading");

            // Perform AJAX Fetch Call
            fetch("pages/verify-employee.php?cpf_no=" + encodeURIComponent(cpfVal))
                .then(res => res.json())
                .then(data => {
                    if (data && data.success) {
                        isEmployeeVerified = true;
                        verifiedEmployeeData = data.employee;

                        // Pre-fill hidden/visible fields
                        formCpf.value = verifiedEmployeeData.cpf_no;
                        formEmpName.value = verifiedEmployeeData.name;
                        formEmpDesig.value = verifiedEmployeeData.designation;
                        formEmpPhone.value = verifiedEmployeeData.phone_no || "";

                        showVerificationStatus(`✓ Verification Successful! Welcome, <strong>${verifiedEmployeeData.name}</strong> (${verifiedEmployeeData.designation}). Please select a facility below.`, "success");
                        
                        // Unlock cards
                        facilityCards.forEach(card => {
                            card.classList.add("unlocked");
                        });
                    } else {
                        const errMsg = data ? data.message : "CPF verification failed.";
                        showVerificationStatus(`✗ Verification Failed: ${errMsg}`, "error");
                    }
                })
                .catch(err => {
                    console.error("AJAX Error:", err);
                    showVerificationStatus("✗ Error connecting to the server. Please try again.", "error");
                });
        });

        // Helper function for showing validation status
        function showVerificationStatus(msg, type) {
            verificationStatusMsg.style.display = "block";
            verificationStatusMsg.innerHTML = msg;

            if (type === "success") {
                verificationStatusMsg.style.backgroundColor = "#DEF7EC";
                verificationStatusMsg.style.color = "#03543F";
                verificationStatusMsg.style.border = "1px solid #BCF0DA";
            } else if (type === "error") {
                verificationStatusMsg.style.backgroundColor = "#FDE8E8";
                verificationStatusMsg.style.color = "#9B1C1C";
                verificationStatusMsg.style.border = "1px solid #FBD5D5";
            } else if (type === "loading") {
                verificationStatusMsg.style.backgroundColor = "#E1EFFE";
                verificationStatusMsg.style.color = "#1E429F";
                verificationStatusMsg.style.border = "1px solid #C3DDFD";
            }
        }

        // 2. Click Logic on Facility Cards
        facilityCards.forEach(card => {
            card.addEventListener("click", function() {
                if (!isEmployeeVerified) {
                    // Visual shake effect or status notification
                    showVerificationStatus("⚠️ Please search and verify your CPF Number before choosing a facility.", "error");
                    searchCpfInput.focus();
                    
                    // Highlight CPF input container to direct the user
                    searchCpfInput.style.borderColor = "#EF4444";
                    setTimeout(() => {
                        searchCpfInput.style.borderColor = "";
                    }, 2000);
                    return;
                }

                // If Help card is selected
                const facilityName = card.getAttribute("data-facility");
                if (facilityName === "Need Help?") {
                    alert("For any booking help, please contact the EWC Office or email ewc.dehradun@ongc.co.in.");
                    return;
                }

                // Highlight Selected Card
                facilityCards.forEach(c => c.classList.remove("active-selected"));
                card.classList.add("active-selected");

                // Open Form
                formFacilityTitle.innerText = `${facilityName} Booking Application`;
                formFacilityName.value = facilityName;

                // Reset form alert banner
                formSubmissionAlert.style.display = "none";

                // Show Form
                bookingFormContainer.style.display = "block";
                bookingFormContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        });

        // 3. Submit Booking Form Logic via AJAX
        requestForm.addEventListener("submit", function(e) {
            e.preventDefault();

            formSubmissionAlert.style.display = "block";
            formSubmissionAlert.innerHTML = "Submitting request...";
            formSubmissionAlert.style.backgroundColor = "#E1EFFE";
            formSubmissionAlert.style.color = "#1E429F";
            formSubmissionAlert.style.border = "1px solid #C3DDFD";

            const formData = new FormData(requestForm);

            fetch("pages/submit-booking-request.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    // Show success status
                    formSubmissionAlert.innerHTML = `✓ ${data.message}`;
                    formSubmissionAlert.style.backgroundColor = "#DEF7EC";
                    formSubmissionAlert.style.color = "#03543F";
                    formSubmissionAlert.style.border = "1px solid #BCF0DA";

                    // Disable inputs and buttons briefly or reset form
                    setTimeout(() => {
                        requestForm.reset();
                        bookingFormContainer.style.display = "none";
                        facilityCards.forEach(c => c.classList.remove("active-selected"));
                        
                        // Scroll back to top card
                        verificationStatusMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 4000);
                } else {
                    const errorMsg = data ? data.message : "Error submitting booking request.";
                    formSubmissionAlert.innerHTML = `✗ Submission Failed: ${errorMsg}`;
                    formSubmissionAlert.style.backgroundColor = "#FDE8E8";
                    formSubmissionAlert.style.color = "#9B1C1C";
                    formSubmissionAlert.style.border = "1px solid #FBD5D5";
                }
            })
            .catch(err => {
                console.error("Submission error:", err);
                formSubmissionAlert.innerHTML = "✗ Network error submitting request. Please try again.";
                formSubmissionAlert.style.backgroundColor = "#FDE8E8";
                formSubmissionAlert.style.color = "#9B1C1C";
                formSubmissionAlert.style.border = "1px solid #FBD5D5";
            });
        });

        // 4. Cancel Button logic
        btnCancelRequest.addEventListener("click", function() {
            requestForm.reset();
            bookingFormContainer.style.display = "none";
            facilityCards.forEach(c => c.classList.remove("active-selected"));
        });
    });
    </script>

</body>
</html>