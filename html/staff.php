
	<script>

	$(document).ready(function () {

		//set classes dropdown
		
		$("[name=dept]").change(function () {
			
			var listValue = this.value;
			var classes = "";
			
			if(listValue == "it")
				classes = "<option value=\"\">Select Class</option><option value=\"d10\">D10</option><option value=\"d15\">D15</option>";
			
			else if(listValue == "comps")
				classes = "<option value=\"\">Select Class</option><option value=\"d7a\">D7A</option><option value=\"d7b\">D7B</option>";
			
			else
				return;
			
			$(this).next("[name=classes]").html(classes);
		});
		
		$("[name=classes]").change(function () {
			
			var listValue = this.value;
			var subjects = "";
			
			if(listValue == "d10")
				subjects = "<option value=\"\">Select Subject</option><option value=\"at\">AT</option><option value=\"wp\">WP</option><option value=\"cn\">CN</option>";
			else
				subjects = "<option value=\"\">Select Subject</option><option value=\"\">"+ listValue +" subjects</option>";
			
			$(this).next("[name=subject]").html(subjects);
		});
		
		
		// $("[name=classes]").trigger("change");
		
		$("#attendance_button").click(function() {
			$("#add_attendance").validate({
				rules:
				{
					roll_nos:
					{Confirm_roll : true},
				},
				submitHandler: function() {
					alert("Success!");
				},
				onfocusout: false,
				onkeyup: false,
				onclick: false,
				
			});
		});
		
		$.validator.addMethod("Confirm_roll", function(value, element){
			var bool;
			
			if($("#status").val() == "p" && value == "")
				bool = confirm("All Absent?");
			else if($("#status").val() == "a" && value == "")
				bool = confirm("All Present?");
			else
				bool = true;
			
			return bool;
			
		}, "Add Some Roll Nos");

		$.validator.addMethod("Confirm_username", function (value, element) {
			return /^[a-zA-Z0-9][a-zA-Z0-9_]{5,19}$/.test(value);
		},"Needs an underscore and length between 6 & 20");

		$.validator.addMethod("confirm_password", function (value, element) {
			return /(?=^.{6,255}$)((?=.*\d)(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[^A-Za-z0-9])(?=.*[a-z])|(?=.*[^A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9]))^.*/.test(value);
		}, "At least 1 upper case, lower case or special character, numerical character. Minlength 6");

		$("#register_form").validate({
			// Specify the validation rules
			rules: {
				name: {
					checkName: true
				},
				gender: "required",
				username: {
					Confirm_username: true
				},
				pass: {
					confirm_password: true,
				},
				email: {
					required:true,
					email: true
				},
				cnfpass: {
					equalTo: "#pass",
				},
				
			},
			
			// Specify the validation error messages
			messages: {
				gender: "Please select gender",
				email: {
					required: "Please enter your email address",
					email: "Enter a volid Email!"
				},
				cnfpass: {
					equalTo: "Password and Confirmation doesn't match"
				}
				
			},

			submitHandler: function(form) {

				$.ajax({
					url : "register.php",
					type : "POST",
					data : $("#register_form").serialize(),
					dataType: "json",
				})
				.done(function (data) {
					if(data == false){
						$(".alert").show();
						$(".alert").removeClass("alert-success");
						$(".alert").addClass("alert-danger");
						$(".alert").html("<strong>Username Taken</strong>");
					}
					else if(data == true)
					{
						$(".alert").show();
						$(".alert").removeClass("alert-danger");
						$(".alert").addClass("alert-success");
						$(".alert").html("<strong>Registration Successful</strong>");
					}
				});
			}
		});
		
		$.validator.addMethod("checkName",
			function(value, element) {
				if(value == "")
					return false;
				else
					return !/[\d]/.test(value);
			}
			, "Invalid Name");
		
		$("[name=dept]").trigger("change");
		
		$("#staff").collapse("show");
		$("#content > div").hide();
		if ( $('[type="date"]').prop('type') != 'date' ) {
			$('[type="date"]').datepicker();
		}
		
		setactiveform("<?=$_GET["formid"]?>");

		$("#staff").find("a").click(function(event) {
			var id = $(event.target).parent().attr("id");
			setactiveform(id);
		});

		function setactiveform(id){
			$(".active").toggleClass("active", false);
			$("#"+id).addClass("active");

			$("#content > div").hide();
			$("#"+id+"_form_div").show();
		}
		
		$("#enggbranches").find("a").click(function(event) {
			var classid = $(event.target).parent().attr("id");
			var branchid = $(event.target).parent().parent().attr("id");

			var url="student.php?branch="+branchid+"&class="+classid;
			$(location).attr("href", url);
		});

		// $("#register_form").submit(function(event){
		// 	event.preventDefault();
		// 	$reply = $.post("register.php", $("#register_form").serialize());
		// 	if($reply)
		// 		alert("Success");
		// });
		$("#clear").click(function(event) {
			$("#register_form").find("input").val("");
		});
		
	});

</script>
		
		<div id="content">
			<br/>
			<div id = "report_form_div">
				<label for="gen_report">For Generating Attendance Status for a particular subject</label>
				<form class="form-inline" id="gen_report" name="gen_report" action="nextpage.htm">
					<select class="form-control" name="dept" required>
						<option value="">Select Dept</option>
						<option value="it">IT</option>
						<option value="comps">COMPS</option>
					</select>
					<select class="form-control" name="classes" required>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject" required>
						<option value="">Select Subject</option>
					</select>
					<button class="btn btn-primary" type="submit">Generate Report</button>
				</form>
			</div>

			<div id="attendance_form_div">
				<label for="add_attendance">Add attendance</label>
				<form class="form-inline" id="add_attendance" name="add_attendance" action="nextpage.htm">
					<select class="form-control" name="dept" required>
						<option value="">Select Dept</option>
						<option value="it">IT</option>
						<option value="comps">COMPS</option>
					</select>
					<select class="form-control" name="classes" required>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject" required>
						<option value="">Select Subject</option>
					</select>
					<select class="form-control" id="status" name="status" required>
						<option value="p">Enter Presentees</option>
						<option value="a">Enter Absentees</option>
					</select><br/><br/>
					<input type="text" class="form-control" id="roll_nos" name="roll_nos" placeholder="Enter Roll Numbers"/>
					<input type="date" class="form-control" id="date" name="date" placeholder="mm/dd/yyyy" required/>
					<button class="btn btn-primary" id="attendance_button" type="submit">Add Attendance</button>
				</form>
			</div>

			<div id="register_form_div">
				<label>Register a New Staff Member</label>
				<form id="register_form" class="form-horizontal" action="register.php" method="POST">
					<input name="name" type="text" class="form-control" placeholder="Enter name" autofocus/><br/>
					<label>Select Gender</label></br>
					<input type="radio" name="gender" value="1"> Male </input><br/>
					<input type="radio" name="gender" value="0"> Female </input><br/><br/>
					<input name="email" type="email" class="form-control" placeholder="Enter Email"/><br/>
					<input name="username" type="text" class="form-control" placeholder="Enter Username"/><br/>
					<input name="pass" id="pass" type="password" class="form-control" placeholder="Enter Password"/><br/>
					<input name="cnfpass" type="password" class="form-control" placeholder="Confirm Password"/><br/>
					<button type="submit" class="btn btn-default">Submit</button>
					<button class="btn btn-default inline" id="clear">Clear</button>
				</form>				
			</div>
		</div>
		<br/>
	</div>
