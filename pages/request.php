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

        <div class="filter-search-card">
            <h4>Quick Search (By CPF Number)</h4>
            <div class="filter-form-inputs">
                <div class="input-field-group">
                    <label>CPF Number</label>
                    <input type="text" placeholder="Enter CPF Registration Number">
                </div>
                <div class="input-field-group">
                    <label>Date of Birth (DD/MM/YYYY)</label>
                    <input type="date">
                </div>
                <button class="btn-search-submit">Search</button>
            </div>
        </div>

        <div class="facilities-selection-section">
            <h4>Popular Facilities & Services</h4>
            <div class="facilities-grid-container">
                <div class="facility-select-card">
                    <div class="facility-icon-wrap"><i class="fa-regular fa-building text-maroon"></i></div>
                    <h5>Community Center Slot</h5>
                </div>
                <div class="facility-select-card">
                    <div class="facility-icon-wrap"><i class="fa-solid fa-hotel text-maroon"></i></div>
                    <h5>Guest House Booking</h5>
                </div>
                <div class="facility-select-card">
                    <div class="facility-icon-wrap"><i class="fa-solid fa-bus text-maroon"></i></div>
                    <h5>Transport Facility</h5>
                </div>
                <div class="facility-select-card">
                    <div class="facility-icon-wrap"><i class="fa-solid fa-screwdriver-wrench text-maroon"></i></div>
                    <h5>Equipment / Other Services</h5>
                </div>
                <div class="facility-select-card">
                    <div class="facility-icon-wrap"><i class="fa-regular fa-circle-question text-maroon"></i></div>
                    <h5>Need Help?</h5>
                </div>
            </div>
        </div>
    </main>

    <?php
include("include/footer.php")
?>

</body>
</html>