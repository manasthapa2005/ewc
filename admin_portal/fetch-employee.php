<?php
include("../db.php");
$type=$_GET['typ'];
$md=$_GET['md'];
if($type=="employee"){
	if ($md==0){
		$sql_check="SELECT name, designation, phone_no, epbax_office FROM admin_credentials WHERE cpf_no = '$_GET[cpf_no]'";
		$s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
		$row=mysqli_fetch_assoc($s_check);
		$Count=mysqli_num_rows($s_check);
		echo json_encode($row);
	}else if($md==1){
		
		echo json_encode("Successfully Added");
		
	}
}

?>
