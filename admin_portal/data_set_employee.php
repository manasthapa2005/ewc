<?php
include("../db.php");
$type=$_GET['typ'];
$md=$_GET['md'];
if($type=="employee"){
	if ($md==0){
		$sql_check="SELECT * FROM admin_credentials WHERE cpf_no = '$_GET[cpf_no]'";
		$s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
		$row=mysqli_fetch_assoc($s_check);
		$Count=mysqli_num_rows($s_check);
		echo json_encode($row);
	}else if($md==1){
		$sql_check="updatequery";
		$s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
		
		echo json_encode("Successfully Added");
		
	}
}elseif($type=="exes"){
	if ($md==0){
		$cpfno=$_GET['cpf_no'];
		$sql_check="SELECT * FROM executive_members WHERE cpf_no = '$_GET[cpf_no]'";
		$s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
		$row=mysqli_fetch_assoc($s_check);
		$count=mysqli_num_rows($s_check);
		if ($count==0){
			?>
		<div class="form-grid">
					<h3>Add Executive Details</h3>  
				</div>                 
				<div class="form-grid">
					<div class="form-group">
						<label for="term-year">Term Year:</label>
						<input type="text" id="term-year" name="term-year" placeholder="e.g. 2025-27" required>
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
			   

					<!-- Submit Button Row -->
					
						<button type="button" class="btn-submit"  onclick="member_add_edit_delete(1)">Add Member</button>
						
					
					
				</div>
			<?php
		}else{
			?>
		<div class="form-grid">
					<h3>Update Executive Details</h3>  
				</div>                 
				<div class="form-grid">
					<div class="form-group">
						<label for="term-year">Term Year:</label>
						<input type="text" id="term-year" name="term-year" value="<?php echo $row['term_year'] ?>" placeholder="e.g. 2025-27" required>
					</div>

					<div class="form-group">
						<label for="designation-select">Designation Type:</label>
						<select id="designation-select" name="designation_select" required>
							<option value="<?php echo $row['position'] ?>"><?php echo $row['position'] ?></option>
							<option value="PRESIDENT">PRESIDENT</option>
							<option value="SECRETARY">SECRETARY</option>
							<option value="JOINT SECRETARY">JOINT SECRETARY</option>
							<option value="TREASURER">TREASURER</option>
							<option value="EXECUTIVE MEMBER">EXECUTIVE MEMBER</option>
						</select>
					</div>
			   

					<!-- Submit Button Row -->
					
						<button type="button" class="btn-submit"  onclick="member_add_edit_delete(2)" >Add Member</button>
						<button type="button" id="del_id" class="btn-submit" onclick="member_add_edit_delete(3)">Delete</button>
					
					
				</div>
<?php
		}
			?>
			
			
			
			
	<?php
			
		
	}else if($md==1){
		
		echo json_encode("Successfully Added");
		
	}
}elseif($type=="addedit"){
	$cpfno=$_GET['cpf_no'];
	$sql_check="SELECT * FROM admin_credentials WHERE cpf_no = '$_GET[cpf_no]'";
	$s_check=mysqli_query($connect,$sql_check) or die(mysql_error());
	$row=mysqli_fetch_assoc($s_check);
	$yer=$_GET['yer'];
	$position=$_GET['position'];
	if ($md==1){
		$member_name = mysqli_real_escape_string($connect, $row['name'] ?? '');
		$contact_number = mysqli_real_escape_string($connect, $row['phone_no'] ?? '');
		$position_esc = mysqli_real_escape_string($connect, $position);
		$yer_esc = mysqli_real_escape_string($connect, $yer);
		$cpfno_esc = mysqli_real_escape_string($connect, $cpfno);
		
		$qry_update = "INSERT INTO executive_members (member_name, position, contact_number, term_year, cpf_no) VALUES ('$member_name', '$position_esc', '$contact_number', '$yer_esc', '$cpfno_esc')";
		mysqli_query($connect,$qry_update); 
		
	}elseif($md==2){
		$qry_add	="update executive_members set term_year='$yer',position='$position'  where cpf_no='$cpfno'";
		mysqli_query($connect,$qry_add); 
		
	}elseif($md==3){
		$qry_del	="delete from executive_members where cpf_no='$cpfno'";
		mysqli_query($connect,$qry_del); 
		
	}
	?>
	<div class="form-grid">
					<h3>Add Executive Details</h3>  
				</div>                 
				<div class="form-grid">
					<div class="form-group">
						<label for="term-year">Term Year:</label>
						<input type="text" id="term-year" name="term_year" placeholder="e.g. 2025-27" required>
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
			   

					<!-- Submit Button Row -->
					
						<button type="button" class="btn-submit"  onclick="member_add_edit_delete(2)" >Add Member</button>
						<button type="button" id="del_id" class="btn-submit" onclick="member_add_edit_delete(3)">Delete</button>
					
					
				</div>
<?php
}

?>
