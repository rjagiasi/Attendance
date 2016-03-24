
	<script>

	$(document).ready(function () {

		//set classes dropdown
		
		$("[name=dept]").change(function () {
			
			var listValue = this.value;
			var dropdownobj = this;
			var classes = "<option value=\"\">Select Class</option>";
			var datastring = "dept="+listValue;
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
			});
			$(dropdownobj).parent().siblings(".content2").hide();
			$(dropdownobj).parent().siblings(".pagination").hide();
		});
		
		$("[name=classes]").change(function () {
			
			var listValue = this.value;
			var dropdownobj = this;
			var classes = "<option value=\"\">Select Subject</option>";
			var datastring = "class="+listValue;
			$.ajax({
					url : "getsubjects.php",
					type : "POST",
					data : datastring,
					dataType: "json",
				})
				.done(function (data) {
					for (var i = 0; i < data.length; i++) {
						classes = classes + "<option value=\"" + data[i].SubjectId + "\">"+ data[i].Name + "</option>";
					};
					$(dropdownobj).next("[name=subject]").html(classes);
					// alert(classes);
				});
			$(dropdownobj).parent().siblings(".content2").hide();
			$(dropdownobj).parent().siblings(".pagination").hide();
		});
		
		$("[name=subject]").change(function () {
			var dropdownobj = this;
			$(dropdownobj).parent().siblings(".content2").hide();
			$(dropdownobj).parent().siblings(".pagination").hide();
		});
		// $("[name=classes]").trigger("change");
		
		// $("#attendance_button").click(function() {
		// 	$("#add_attendance").validate({
		// 		rules:
		// 		{
		// 			roll_nos:
		// 			{Confirm_roll : true},
		// 		},
		// 		submitHandler: function() {
		// 			alert("Success!");
		// 		},
		// 		onfocusout: false,
		// 		onkeyup: false,
		// 		onclick: false,
				
		// 	});
		// });
		var table = "";
		var itemsperpg = 10;
		$("#add_attendance").submit(function (event) {
			
			event.preventDefault();
			$.ajax({
				url : "getstudentlist.php",
				type : "POST",
				data : $("#add_attendance").serialize(),
				dataType: "json",
			})
			.done(function (data) {
				if(data == false){
					failuremessage("Attendance for the day already exists!");
				}
					else
					{
						table = 
						"<form id=\"attendance_list\" method = \"POST\" action = \"add_attendance.php\"><table class = \"table table-striped\"><thead><tr><th>Present</th><th>Absent</th><th>Roll No</th><th>Name</th></tr></thead><tbody>";
						for (var i = 0; i < data.length; i++) {
							table += 
							"<tr><td><input type = \"radio\" name=\"pa_" + data[i].StudentId + "\" value = \"1\" checked/></td><td><input type = \"radio\" name=\"pa_" + data[i].StudentId + "\" value = \"0\"/></td><td>" + data[i].RollNo + "</td><td>" + data[i].Name + "</td></tr>";
						};
						table += "</table><input class=\"btn btn-primary\" type=\"submit\"/></form>";
						$("#attendance_form_div .content2").html(table);

						var noofpages = (data.length/itemsperpg)+1;
					// alert(noofpages);
					var pagination_html = "";

					for (var i = 1; i <= noofpages; i++) {
						pagination_html += "<li value=\"" + i + "\"><a href=\"#\">"+i+"</a></li>"
					};
					$("#attendance_form_div .pagination").html(pagination_html);
					$("#attendance_form_div .pagination a").first().trigger("click");}
				});
});

		$("#attendance_form_div .pagination").on("click", "a", function (event) {
			$("#attendance_form_div .content2").show();
			$("#attendance_form_div .pagination").show();
			$(".pagination .active").toggleClass("active", false);
			var parent = $(event.target).parent();
			parent.addClass("active");
			var pgno = parent.attr("value");
			$("#attendance_form_div .content2 tbody tr").hide();
			$("#attendance_form_div .content2 tbody tr").slice((pgno-1)*itemsperpg, pgno*itemsperpg).show();
		});

		function failuremessage (output) {
			$(".alert").show();
			$(".alert").removeClass("alert-success");
			$(".alert").addClass("alert-danger");
			$(".alert").html("<strong>"+output+"</strong>");
			$('.alert').delay(5000).fadeOut('slow');
		}

		function successmessage (output) {
			$(".alert").show();
			$(".alert").removeClass("alert-danger");
			$(".alert").addClass("alert-success");
			$(".alert").html("<strong>" + output + "</strong>");
			$('.alert').delay(5000).fadeOut('slow');
		}

		$("#attendance_form_div").on("submit", "#attendance_list", function (event) {
			event.preventDefault();
			var formobj = this;
			// alert($(this).parent().siblings("form").html());
			$.ajax({
				url : "add_attendance.php",
				type : "POST",
				data : $(formobj).serialize() + "&" + $(formobj).parent().siblings("form").serialize(),
				dataType : "json",
			})
			.done(function (data) {
				if(data == false)
					failuremessage("Some Error Occured");
				else if(data == true){
					successmessage("Attendance added Successfully");
					$("#attendance_form_div .content2").hide();
					$("#attendance_form_div .pagination").hide();
				}
			});
		});

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
					if(data == false)
						failuremessage("Username Taken");
					else if(data == true)
						successmessage("Registration Successful");
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
			$("#menu .active").toggleClass("active", false);
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
				<form class="form-inline" id="gen_report" name="gen_report" action="">
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
				<ul class="pagination" style="float:left;">

				</ul>
				<div class="content2">
					
				</div>
				
			</div>

			<div id="attendance_form_div">
				<label for="add_attendance">Add attendance</label>
				<form class="form-inline" id="add_attendance" name="add_attendance" action="">
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
					<input type="date" class="form-control" id="date" name="date" placeholder="mm/dd/yyyy" required/>
					<button class="btn btn-primary" id="attendance_button" type="submit">Get List</button>
				</form>
				<br/>
				<ul class="pagination" style="float:center;">
					
				</ul>
				<div class="content2">
					
				</div>
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
