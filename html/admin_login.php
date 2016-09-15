<script type="text/javascript">
	
	$(document).ready(function() {
		$("#admin_login_form").submit(function(event) {
			event.preventDefault();
			$("#loadinggif").show();

			$.ajax({
				url: 'admin_login_check.php',
				type: 'POST',
				dataType: 'json',
				data: $(this).serialize(),
			})
			.done(function(data) {
				if(data == false)
					failuremessage("Invalid!");
				else
					location.reload();
			});
		});


		function setdepts () {
			$("#loadinggif").show();
			var dept = "<option value=\"\">Select Dept</option>";
			$.ajax({
				url : "getdepts.php", 
				dataType : "json",
			})
			.done(function (data) {
				for (var i = 0; i < data.length; i++) {
					dept = dept + "<option value=\"" + data[i].DeptId + "\">"+ data[i].Name + "</option>";
				};
				$("[name=dept]").html(dept);
				$("#loadinggif").hide();
			});
		}
		
		//set classes dropdown
		$("[name=dept]").change(function () {
			var listValue = this.value;
			var dropdownobj = this;
			var classes = "<option value=\"\">Select Class</option>";
			var datastring = "dept="+listValue;
			$("#loadinggif").show();
			$.ajax({
					url : "getclasses.php",
					type : "POST",
					data : datastring,
					dataType: "json",
				})
				.done(function (data) {
					for (var i = 0; i < data.length; i++) {
						classes = classes + "<option value=\"" + data[i].ClassId + "\">"+ data[i].Name + "</option>";
					};
					$(dropdownobj).next("[name=classes]").html(classes);
					$("#loadinggif").hide();
			});
			$(dropdownobj).parent().siblings(".content2").hide();
			$(dropdownobj).parent().siblings(".pagination").hide();
			$(dropdownobj).parent().siblings("#download_buttons").hide();
		});
		
		// set subjects dropdown
		$("[name=classes]").change(function () {
			
			var listValue = this.value;
			var dropdownobj = this;
			var subjects = "<option value=\"\">Select Subject</option>";
			var datastring = "class="+listValue;
			$("#loadinggif").show();
			$.ajax({
					url : "getsubjects.php",
					type : "POST",
					data : datastring,
					dataType: "json",
				})
				.done(function (data) {
					for (var i = 0; i < data.length; i++) {
						subjects = subjects + "<option value=\"" + data[i].SubjectId + "\">"+ data[i].Name + "</option>";
					};
					$(dropdownobj).next("[name=subject]").html(subjects);
					// alert(classes);
					$("#loadinggif").hide();
				});
			$(dropdownobj).parent().siblings(".content2").hide();
			$(dropdownobj).parent().siblings(".pagination").hide();
			$(dropdownobj).parent().siblings("#download_buttons").hide();
		});
		
		// hide content on subject change
		$("[name=subject]").change(function () {
			var dropdownobj = this;
			$(dropdownobj).parent().siblings(".content2").hide();
			$(dropdownobj).parent().siblings(".pagination").hide();
			$(dropdownobj).parent().siblings("#download_buttons").hide();
		});
		setdepts();
	});

	
</script>
<!-- <h4 class="col-sm-4">Admin Login</h4> -->
<?php if(!isset($_COOKIE["admin"])) : ?>
<form class = "form-horizontal" id="admin_login_form">
	
	<label class="control-label col-sm-4" for="username">Admin Username : </label>
	<div class="col-sm-5">
		<input name="username" id="username" class="form-control" placeholder="Username" type = "text" required/>
	</div>

	<br/><br/><br/>

	<label class="control-label col-sm-4" for="password">Admin Password : </label>
	<div class="col-sm-5">
		<input name="password" id="password" class="form-control" placeholder="Password" type = "password" required/>
	</div>

	<button class = "btn btn-primary">Submit</button>
</form>
<?php else : ?>

	<div id = "greeting_div">
		<p id="greet">Welcome Admin</p>
		<a href="logout.php"><button id="logout" class="btn btn-primary" style="float:right">Logout</button></a>
		<br/><br/>
	</div>

	<label class="admin_label" for="create_dept">Create a Department</label>
	<form id="create_dept" class = "form-inline">
		<input type="text" name="dept" placeholder="Dept Name" class="form-control" required/>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	<br/><br/>

	<label class="admin_label" for="add_classes">Add a class</label>
	<form id="add_classes" class = "form-inline">
		<select class="form-control" name="dept" required >
			<option value="">Select Dept</option>

		</select>
		<input type="text" name="class" placeholder="Class Name" class="form-control" required/>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	<br/><br/>
	
	<label class="admin_label" for="create_batches">Create Batches</label>
	<form id="create_batches" class = "form-inline">
		<select class="form-control" name="dept" required >
			<option value="">Select Dept</option>
		</select>

		<select class="form-control" name="classes" required >
			<option value="">Select Class</option>
		</select>

		<input type="number" name="no_of_batches" placeholder="No of Batches" class="form-control" required/>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	<br/><br/>

	<label class="admin_label" for="add_subjects">Add a Subject</label>
	<form id="add_subjects" class = "form-inline">
		<select class="form-control" name="dept" required >
			<option value="">Select Dept</option>
		</select>
		<select class="form-control" name="classes" required >
			<option value="">Select Class</option>
		</select>
		<input type="text" name="subject" placeholder="Subject Name" class="form-control" required/>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	<br/><br/>

	<label class="admin_label" for="add_student">Add a Student</label>
	<form id="add_student" class = "form-inline">
		<select class="form-control" name="dept" required >
			<option value="">Select Dept</option>
		</select>
		<select class="form-control" name="classes" required >
			<option value="">Select Class</option>
		</select>
		<input type="number" name="rollno" placeholder="Roll No" class="form-control" required/>
		<input type="text" name="name" placeholder="Name" class="form-control" min="1" required/>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
<?php endif ?>