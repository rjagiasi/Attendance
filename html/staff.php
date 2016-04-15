
	<script>

	$(document).ready(function () {

		//set dept dropdown initially
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
		

		var deptdropdown = $("[name=dept]");

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
		

		var table = "";
		var itemsperpg = 25;

		//get student list and set pagination
		$("#add_attendance").submit(function (event) {
			event.preventDefault();
			$("#loadinggif").show();
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
					table += "</table><input class=\"btn btn-primary\" type=\"submit\" style=\"float:center;\"/></form>";
					$("#attendance_form_div .content2").html(table);

					var noofpages = (data.length/itemsperpg)+1;
					// alert(noofpages);
					var pagination_html = "";

					for (var i = 1; i <= noofpages; i++) {
						pagination_html += "<li value=\"" + i + "\"><a href=\"#\">"+i+"</a></li>"
					};
					$("#attendance_form_div .pagination").html(pagination_html);
					$("#attendance_form_div .pagination a").first().trigger("click");
				}
				$("#loadinggif").hide();
			});
});

		//manage attendance list pagination

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

		//insert data after attendance list submitted
		$("#attendance_form_div").on("submit", "#attendance_list", function (event) {
			event.preventDefault();
			$("#loadinggif").show();
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
					$("#loadinggif").hide();
				}
			});
		});

		//gen report ajax call
		$("#gen_report").submit(function (event) {
			event.preventDefault();
			$("#loadinggif").show();
			$.ajax({
				url : "getreport.php",
				type : "POST",
				data : $("#gen_report").serialize(),
				dataType : "json",
			})
			.done(function (data) {
				if(data == null)
					failuremessage("Some Error Occured!");
				else
				{
					table = "<table class = \"table table-striped tablesorter\" id=\"generated_report\"><thead><tr>";
					$.each(data[0], function(key, value) {
						table += "<th>"+key+"<span class=\"glyphicon glyphicon-sort\"></th>";
					});


					table += "</tr></thead><tbody>";

					for (var i = 0, n = data.length; i < n; i++) {
						table += "<tr>"
						$.each(data[i], function(key, value) {
							var per = value.split(" : ")[1];
							if(per<75)
								table += "<td class = \"defaulter\">"+value+"</td>";
							else
								table += "<td>"+value+"</td>";
						});
						table += "</tr>"
					};
					table += "</tbody></table>";

					var noofpages = (data.length/itemsperpg)+1;
					// alert(noofpages);
					var pagination_html = "";

					for (var i = 1; i <= noofpages; i++) {
						pagination_html += "<li value=\"" + i + "\"><a href=\"#\">"+i+"</a></li>"
					};
					$("#report_form_div .content2").html(table);
					$("#report_form_div .pagination").html(pagination_html);
					// $("#report_form_div .content2").show();
					
					$("#report_form_div .pagination a").first().trigger("click");
					$(".tablesorter").tablesorter().bind("sortEnd", function () {
					$(this).parent().siblings(".pagination").find(".active a").trigger("click");
						// alert(id);
					});

				}
				$("#loadinggif").hide();
			});
		});
		

		//manage report list pagination
		$("#report_form_div .pagination").on("click", "a", function (event) {
			$("#report_form_div .content2").show();
			$("#report_form_div .pagination").show();
			$("#download_buttons").show();
			$(".pagination .active").toggleClass("active", false);
			var parent = $(event.target).parent();
			parent.addClass("active");
			var pgno = parent.attr("value");
			$("#report_form_div .content2 tbody tr").hide();
			$("#report_form_div .content2 tbody tr").slice((pgno-1)*itemsperpg, pgno*itemsperpg).show();
		});

		//report download code
		$("#download_buttons button").click(function(event) {
			var downloadtype = $(event.target).attr("id");
			$("#report_form_div .content2 tbody tr").show();

			if(downloadtype == "pdf")
				converttopdf();
			else
				$("#generated_report").tableExport({type: downloadtype,escape:'false'});

			$("#report_form_div .pagination").find(".active a").trigger("click");
		});


		//update attendance ajax
		$("#update_attendance").submit(function(event) {
			event.preventDefault();
			$("#loadinggif").show();
			$.ajax({
				url : "fetchdayatt.php",
				type : "POST",
				data : $("#update_attendance").serialize(),
				dataType : "json",
			})
			.done(function (data) {
				if(data == "false"){
					failuremessage("Attendance data does not exist!");
					$("#update_form_div .content2").hide();
				}
				else
				{
					var c = "";
					c += "<p>"+data[0]["Name"]+"</p>";
					c += (data[0]["PA"] == "1")?"<input val=\"1\" class=\"form-control\" value=\"Present\" name=\"old\" disabled style=\"width:100px; float:left;\"/>":"<input val=\"0\" class=\"form-control\" value=\"Absent\" name=\"old\" style=\"width:100px; float:left;\" disabled/>";
					c += "<button id=\"change\" style=\"float:right;\" class=\"btn btn-primary\">Change</button>";
					$("#update_form_div .content2").html(c);
					$("#update_form_div .content2").show();
				}
			});
			$("#loadinggif").hide();
		});

		//toggle attendance
		$("#update_form_div .content2").on("click", "#change", function(event) {
			$("#loadinggif").show();
			$.ajax({
				url : "update_att.php",
				type : "POST",
				data : $("#update_attendance").serialize() + "&" + "currval=" + $("[name=old]").attr("val"),
				dataType : "json",
			})
			.done(function (data) {
				$("#update_attendance").trigger("submit");
			});
			$("#loadinggif").hide();
		});

		//register form validation methods
		$.validator.addMethod("Confirm_username", function (value, element) {
			return /^[a-zA-Z0-9][a-zA-Z0-9_]{5,19}$/.test(value);
		},"Length between 6 & 20 (special character only _ allowed)");

		$.validator.addMethod("confirm_password", function (value, element) {
			return /(?=^.{6,255}$)((?=.*\d)(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[^A-Za-z0-9])(?=.*[a-z])|(?=.*[^A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9]))^.*/.test(value);
		}, "At least 1 upper case, lower case or special character, numerical character. Minlength 6");

		$.validator.addMethod("checkName", function(value, element) {
				if(value == "")
					return false;
				else
					return !/[\d]/.test(value);
			}
			, "Invalid Name");

		//register form validation and ajax call on submit
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
					email: "Enter a valid Email!"
				},
				cnfpass: {
					equalTo: "Password and Confirmation doesn't match"
				}
				
			},

			submitHandler: function(form) {
				$("#loadinggif").show();
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
					$("#loadinggif").hide();
				});
			}
		});
		

		//register form clear button
		$("#clear").click(function(event) {
			$("#register_form").find("input").val("");
		});

		//initialize dept dropdown
		setdepts();

		//show only staff menu
		$("#staff").collapse("show");
		$("#download_buttons").hide();

		$("#loadinggif").hide();
		//use jquery datepicker if browser doesn't support date type
		if ( $('[type="date"]').prop('type') != 'date' ) {
			$('[type="date"]').datepicker();
		}
		
		$("#roll").change(function(event) {
			$(this).parent().siblings(".content2").hide();
		});

		//set initial form chosen
		$("#content > div").hide();

		setactiveform("<?=$_GET["formid"]?>");

		//changing forms in same page
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
		
		//link to student on click
		$("#enggbranches").find("a").click(function(event) {
			var classid = $(event.target).parent().attr("id");
			var branchid = $(event.target).parent().parent().attr("id");

			var url="student.php?branch="+branchid+"&class="+classid;
			$(location).attr("href", url);
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
						
					</select>
					<select class="form-control" name="classes" required>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject">
						<option value="">Select Subject</option>
					</select><br/></br>
					<label for="startdate">Start Date</label>
					<input type="date" class="form-control" id="startdate" name="startdate" required/>
					<label for="enddate">End Date</label>
					<input type="date" class="form-control" id="enddate" name="enddate" required/>
					<button class="btn btn-primary" type="submit">Generate Report</button>
				</form>
				<ul class="pagination" style="float:center;" >

				</ul>
				<div class="content2">
					
				</div>
				<div id="download_buttons">
					Download Report as
					<button type="button" id = "csv" class="btn btn-primary">CSV</button>
					<button type="button" id = "excel" class="btn btn-success">Excel</button>
					<button type="button" id = "pdf" class="btn btn-danger">PDF</button>
				</div>
				<br/>
				<p style="float:down;">If subject not selected, all subjects are shown</p>
			</div>

			<div id="attendance_form_div">
				<label for="add_attendance">Add attendance</label>
				<form class="form-inline" id="add_attendance" name="add_attendance" action="">
					<select class="form-control" name="dept" required>
						<option value="">Select Dept</option>
						
					</select>
					<select class="form-control" name="classes" required>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject" required>
						<option value="">Select Subject</option>
					</select>
					<input type="date" class="form-control" id="date" name="date" required/>
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

			<div id="update_form_div">
				<label for="update_attendance">Update attendance</label>
				<form class="form-inline" id="update_attendance" name="update_attendance" action="">
					<select class="form-control" name="dept" required>
						<option value="">Select Dept</option>
						
					</select>
					<select class="form-control" name="classes" required>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject" required>
						<option value="">Select Subject</option>
					</select>
					<input type="date" class="form-control" id="date" name="date" required/>
					<input id="roll" name="roll" type="number" class="form-control" placeholder="Roll No" min="1" max="100" style="width:100px;" required/>
					<button class="btn btn-primary" type="submit">Fetch</button>
				</form>
				<br/>
				<div class="content2">
					
				</div>
			</div>

		</div>
		<div id="loadinggif" style="text-align:center;">
			<img src="../imgs/loading.gif"/>
		</div>
		<br/>
	</div>
