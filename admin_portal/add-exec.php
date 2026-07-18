<?php
include("head.php");
?>
<script>
function showNotification(message, isSuccess) {
	const notification=document.getElementById('form-notification');
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

function get_employee()
{
	cpfno=document.getElementById('cpf-no').value;
	//alert(cpfno);
	//var formData = new FormData(document.getElementById('myform'));
	$.ajax({
		method: 'POST',
		url : 'data_set_employee.php?typ=employee&md=0&cpf_no='+cpfno,
		dataType : 'json',
		success: function (data) { 
			console.log(data);
			document.getElementById('member-name').value 	 = data.name;
			document.getElementById('designation-text').value 	 = data.designation;
			document.getElementById('mobile-number').value 	 = data.phone_no;
			document.getElementById('tel-number').value 	 = data.epbax_office;
            fill_existing(cpfno)

document.getElementById('addon').style='';
			//document.getElementById('elected-year').value 	 = date('Y');
			//setSelectedValue(document.getElementById("designation-select"), data.designation);
			//showNotification("Record Found",true);
//document.getElementById('stname').innerHTML 		= "( "+data.stname+" )";
		
		}
	});
}

function fill_existing(cpfno)
{
	$.ajax({
		method: 'get',
	    url : 'data_set_employee.php?typ=exes&md=0&cpf_no='+cpfno,
	    dataType : 'html',
		success: function (text) { $('#addon').html(text); }
	});
	
}

function member_add_edit_delete(md)
{
	cpfno=document.getElementById('cpf-no').value;
	yer=document.getElementById('term-year').value;
	position=document.getElementById('designation-select').value;
	$.ajax({
		method: 'get',
	    url : 'data_set_employee.php?typ=addedit&md='+md+'&cpf_no='+cpfno+'&yer='+yer+'&position='+position,
	    dataType : 'text',
		success: function (text) { $('#addon').html(text);
		alert("Done"); 
		}
	});
	
}



function setSelectedValueByText(selectObj, valueToSet) {
	for (var i = 0; i < selectObj.options.length; i++) {
		if (selectObj.options[i].text== valueToSet) {
			selectObj.options[i].selected = true;
			return;
		}
	}
}
function setSelectedValue(selectObj, valueToSet) {
	for (var i = 0; i < selectObj.options.length; i++) {
		if (selectObj.options[i].value== valueToSet) {
			selectObj.options[i].selected = true;
			return;
		}
	}
}
</script>

<body class="interior-body">
<?php
include("menu.php");
?>
<div class="member-form-wrapper">
	<div class="form-container">
		<h2>Add Elected Member</h2>
		<p class="form-subtitle">Enter the Detail of New Elected Member:</p>
		<div id="form-notification" style="display: none; padding: 12px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-weight: 500; font-family: 'Outfit', sans-serif;"></div>

		<form name="myform" id="myform" action="" method="POST" enctype="multipart/form-data">
			<!-- Search Row -->
			<div class="search-row">
				<div class="form-group">
					<label for="cpf-no">Enter CPF No. :</label>
					<div class="search-input-group">
						<input type="text" id="cpf-no" name="cpf_no" placeholder="e.g. 12345" required>
						<button type="button" class="btn-search" onclick="get_employee()">Search</button>
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
			</div>
			<div id="addon" style="display:none">
				
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
					
						<button type="button" class="btn-submit" onclick="member_add_edit_delete(0)" >Add Member</button>
						<button type="button" id="del_id" class="btn-submit" onclick="member_add_edit_delete(1)>Delete</button>
					
					
				</div>
			</div>
		</form>
	</div>
</div>

    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>
	<script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
</body>

</html>