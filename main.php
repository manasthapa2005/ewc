<?php
if (!isset($_GET['frmid'])){$fid=0;}else{$fid=$_GET['frmid'];}

if ($fid==0){
    include("index.php");
}elseif ($fid==1){
    include("pages/status.php");
}elseif ($fid==2){
    include("pages/request.php");
}elseif ($fid==3){
    include("pages/welfare.php");
}elseif ($fid==4){
    include("pages/gallery.php");
}elseif ($fid==5){
    include("pages/exec.php");
}elseif ($fid==6){
    include("pages/about.php");
}elseif ($fid==7){
    include("pages/admin.php");
}else{ 
    include("pages/status.php");
}


    ?>
    <script src="js/script.js"></script>