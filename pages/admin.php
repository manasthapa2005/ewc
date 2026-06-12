<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'admin.php') {
    header("Location: ../main.php?frmid=7");
    exit;
}
include("include/top-nav.php");
?>
<body>

    <!--<div class="top-bar">
        <div class="container top-bar-nav">
            <a href="index.html" class="active"><i class="fa fa-home"></i></a>
            <a href="#">Booking Status</a>
            <a href="#">Booking Request</a>
            <a href="pages/gallery.html">Photo Gallery</a>
            <a href="#">Executive Body</a>
            <a href="#">Feedback</a>
            <a href="#">Admin Login</a>
            <a href="#">About Us</a>
        </div>
    </div> -->

    <?php
    include("include/header.php");
    include("include/slider.php");
    ?>
<main>
<div id="admin-login-modal" class="admin-modal-backdrop">
    <div class="admin-modal-card">
        
        <div class="admin-modal-header">
            <span>EWC Portal Admin Login</span>
            <button class="admin-modal-close" id="admin-modal-close-btn">&times;</button>
        </div>
        
        <div class="admin-modal-body">
            <form id="admin-login-form" class="admin-form" method="POST" action="login-process.php">
                
                <div class="admin-form-group">
                    <label class="admin-form-label" for="admin-username">Username (Numeric ID Only)</label>
                    <input 
                        type="text" 
                        name="cpf_no" 
                        id="admin-username" 
                        class="admin-form-input" 
                        placeholder="e.g. 078441"
                        inputmode="numeric"
                        pattern="[0-9]+" 
                        title="Please enter numbers/digits only." 
                        required
                    >
                </div>
                
                <div class="admin-form-group">
                    <label class="admin-form-label" for="admin-password">Password</label>
                    <div style="position: relative; display: flex; width: 100%;">
                        <input 
                            type="password" 
                            name="password" 
                            id="admin-password" 
                            class="admin-form-input" 
                            style="padding-right: 35px;" 
                            placeholder="Enter system password" 
                            required
                        >
                        <button type="button" id="toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b;">
                            👁️
                        </button>
                    </div>
                </div>
                
                <button type="submit" name="submit_login" class="btn-admin-submit-classic">Execute Login</button>
            </form>
        </div>
        
    </div>
</div>
</main>
<?php
// Re-open PHP cleanly right here to handle your system's footer include statement:
include("include/footer.php");
?>