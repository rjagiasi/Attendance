
	<script>

	$(document).ready(function () {

		//set dept dropdown initially
		function setdepts (callback) {
			$("#loadinggif").show();
			var dept = "<option value=\"\">Select Dept</option>";
			$.ajax({
				url : "getdepts.php", 
				dataType : "json",
			})
			.done(function (data) {
				var no=0;
				
				for (var i = 0; i < data.length; i++) {
					dept = dept + "<option value=\"" + data[i].DeptId + "\">"+ data[i].Name + "</option>";
				}
				
				$("[name=dept]").html(dept);
				// $("[name=dept]").first().trigger('change');
				// changedept();
				callback();
				$("#loadinggif").hide();
			});
			return true;
		}
		
		function changedept(){
			var no = 0;
			$("#loadinggif").show();

			$("[name=dept] option").each(function() {
				// alert($(this).html());
				if($(this).html() == $("#enggbranches .active").parent().attr('id').toUpperCase())
				{
					no = $(this).val();
					return false;
				}
			});

			$("[name=dept]").val(no);
			$("[name=dept]").first().trigger('change');

			$("#loadinggif").hide();
		}
		// var deptdropdown = $("[name=dept]");

		//set classes dropdown
		$("[name=dept]").change(function () {

			$("#loadinggif").show();
			var listValue = this.value;
			var classes = "<option value=\"\">Select Class</option>";
			var datastring = "dept="+listValue;
			
			$.ajax({
					url : "getclasses.php",
					type : "POST",
					data : datastring,
					dataType: "json",
				})
				.done(function(data) {
					var no = 0;
					for (var i = 0; i < data.length; i++) 
					{
						classes = classes + "<option value=\"" + data[i].ClassId + "\">"+ data[i].Name + "</option>";
						if(data[i].Name == $("#enggbranches .active").attr('id').toUpperCase())
						{
							no = data[i].ClassId;
						}
					}
					$("[name=classes]").html(classes).val(no);
					$("[name=classes]").first().trigger('change');
					$("#loadinggif").hide();
			});
		});
		
		// set subjects dropdown
		$("[name=classes]").change(function () {
			
			var listValue = this.value;
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
					}
					$("[name=subject]").html(subjects);
					// alert(classes);
					$("#loadinggif").hide();
				});
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

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');
			
			$.ajax({
				url : "getstudentlist.php",
				type : "POST",
				data : data,
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
					table += "<tr><td><input class=\"btn btn-primary\" type=\"submit\"/></td><td/><td/><td/></tr></table></form>";
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
			$("#attendance_form_div .pagination .active").toggleClass("active", false);
			var pgno = $(event.target).parent().val();
			// alert(pgno);
			$("#attendance_form_div .pagination li[value="+pgno+"]").addClass("active");
			// var pgno = parent.attr("value");
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
				}
				$("#loadinggif").hide();
			});
		});

		//gen report ajax call
		$("#gen_report").submit(function (event) {
			event.preventDefault();
			$("#loadinggif").show();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');

			$.ajax({
				url : "getreport.php",
				type : "POST",
				data : data,
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
							if(per<75 && per>50)
								table += "<td class = \"defaulter\">"+value+"</td>";
							else if(per<=50)
								table += "<td class = \"critical\">"+value+"</td>";
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
			$("#report_form_div .pagination .active").toggleClass("active", false);
			
			var pgno = $(event.target).parent().val();
			$("#report_form_div .pagination li[value="+pgno+"]").addClass("active");

			$("#report_form_div .content2 tbody tr").hide();
			$("#report_form_div .content2 tbody tr").slice((pgno-1)*itemsperpg, pgno*itemsperpg).show();
		});

		//report download code
		$("#download_buttons button").click(function(event) {
			var downloadtype = $(event.target).attr("id");
			$("#loadinggif").show();
			$("#report_form_div .content2 tbody tr").show();

			if(downloadtype == "pdf")
				converttopdf();	//demoPDF()
			else
				$("#generated_report").tableExport({type: downloadtype,escape:'false'});

			$("#report_form_div .pagination").find(".active a").trigger("click");
			$("#loadinggif").hide();
		});


		//update attendance ajax
		$("#update_attendance").submit(function(event) {
			event.preventDefault();
			$("#loadinggif").show();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');

			$.ajax({
				url : "fetchdayatt.php",
				type : "POST",
				data : data,
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
					c += "<p style=\"margin-right:20px;\">"+data[0]["Name"]+"</p>";
					c += (data[0]["PA"] == "1")?"<input val=\"1\" class=\"form-control\" value=\"Present\" name=\"old\" disabled style=\"width:100px;margin-right:20px;\"/>":"<input val=\"0\" class=\"form-control\" value=\"Absent\" name=\"old\" style=\"width:100px; margin-right:20px;\" disabled/>";
					c += "<button id=\"change\" style=\"float:right;\" class=\"btn btn-primary\">Change</button>";
					$("#update_form_div .content2").html(c);
					$("#update_form_div .content2").show();
				}
				$("#loadinggif").hide();
			});
		});

		//toggle attendance
		$("#update_form_div .content2").on("click", "#change", function(event) {
			$("#loadinggif").show();

			var disabledfields = $("#update_attendance").find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $("#update_attendance").serialize();
			disabledfields.attr('disabled', 'disabled');

			$.ajax({
				url : "update_att.php",
				type : "POST",
				data : data + "&" + "currval=" + $("[name=old]").attr("val"),
				dataType : "json",
			})
			.done(function (data) {
				$("#update_attendance").trigger("submit");
				$("#loadinggif").hide();
			});
			
		});
		
		$.validator.addMethod("confirm_password", function (value, element) {
			return /(?=^.{6,255}$)((?=.*\d)(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[^A-Za-z0-9])(?=.*[a-z])|(?=.*[^A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9]))^.*/.test(value);
		}, "At least 1 upper case, lower case or special character, numerical character. Minlength 6");
		
		$("#chngpass_form").validate({
			// Specify the validation rules
			rules: {
				pass: {
					confirm_password: true,
				},
				cnfpass: {
					equalTo: "#pass2",
				},
			},
			// Specify the validation error messages
			messages: {
				cnfpass: {
					equalTo: "Password and Confirmation doesn't match"
				}
			},

			submitHandler: function(form) {
				$("#loadinggif").show();
				$.ajax({
					url : "chngpass.php",
					type : "POST",
					data : $("#chngpass_form").serialize(),
					dataType: "json",
				})
				.done(function (data) {
					if(data == false)
						failuremessage("Some Error Occured");
					else if(data == true)
						successmessage("Password Changed Successfully!");
					$("#loadinggif").hide();
				});
			}
		});

		

		//initialize dept dropdown
		// setdepts();

		setdepts(function () {
			setactiveclass('<?=$class?>','<?=$branch?>');
		});
		

		// $("#staff").collapse("show");
		$("#download_buttons").hide();

		$("#loadinggif").hide();
		//use jquery datepicker if browser doesn't support date type
		if ( $('[type="date"]').prop('type') != 'date' ) {
			$('[type="date"]').datepicker({ dateFormat: 'yy-mm-dd' });
		}
		
		$("#roll").change(function(event) {
			$(this).parent().siblings(".content2").hide();
		});

		//set initial form chosen
		// $("#content > div").not("#greeting_div").hide();

		// $("#staff").find("#attendance a").first().trigger("click");

		//changing forms in same page
		$("#staff").find("a").click(function(event) {
			var id = $(event.target).parent().attr("id");
			setactiveform(id);
		});

		// setactiveform("attendance");
		$("#attendance a").first().trigger("click");

		function setactiveform(id){
			$("#staff .active").toggleClass("active", false);
			$("#"+id).addClass("active");

			$("#content > div").not("#greeting_div").not('#staff').hide();
			$("#"+id+"_form_div").show();
		}
		
		
		$("#enggbranches ul").find("a").click(function(event) {
			var classid = $(event.target).parent().attr("id");
			var branchid = $(event.target).parent().parent().attr("id");
			setactiveclass(classid, branchid);
		});
		
		// changedept();
		function setactiveclass(classid, branchid) {
			
			$("#enggbranches .active").toggleClass("active", false);
			$("#enggbranches ul").not("#" + branchid).collapse("hide");
			// $("#" + branchid).collapse("show");
			$("#" + classid).addClass("active");
			changedept();
			$(".content2").hide();
			$(".pagination").hide();
			$("#download_buttons").hide();
			// $("#attendance a").first().trigger("click");
		}

		// $("#loadinggif").show();
	});
	$("#enggbranches ul").collapse("show");

</script>
		<!-- <div id="content"> -->
			<!-- <ul class="nav nav-tabs">
				<li class="active"><a href="#">Home</a></li>
				<li><a href="#">Menu 1</a></li>
				<li><a href="#">Menu 2</a></li>
				<li><a href="#">Menu 3</a></li>
			</ul> -->
			<div class="sectionbranches" id="staff" aria-expanded="true">
				<ul class="nav nav-tabs nav-justified" aria-expanded="true">
					<li id="attendance"><a>Add Attendance</a></li>
					<li id="update"><a>Update</a></li>
					<li id="report"><a>Report</a></li>
					<li id="chngpass"><a>Change Password</a></li>
				</ul>
			</div>
			<br/>
			<div id = "report_form_div">
				<label for="gen_report">For Generating Attendance Status for a particular subject</label>
				<form class="form-inline" id="gen_report" name="gen_report" action="">
					<select class="form-control" name="dept" required disabled>
						<option value="">Select Dept</option>
						
					</select>
					<select class="form-control" name="classes" required disabled>
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
				<ul class="pagination" style="float:center;" >

				</ul>
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
					<select class="form-control" name="dept" required disabled>
						<option value="">Select Dept</option>
						
					</select>
					<select class="form-control" name="classes" required disabled>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject" required>
						<option value="">Select Subject</option>
					</select>
					<input type="date" class="form-control" name="date" required/>
					<button class="btn btn-primary" id="attendance_button" type="submit">Get List</button>
				</form>
				<br/>
				<ul class="pagination" style="float:center;">
					
				</ul>
				<div class="content2">
					
				</div>
				<ul class="pagination" style="float:center;">
					
				</ul>
			</div>

			<div id="update_form_div">
				<label for="update_attendance">Update attendance</label>
				<form class="form-inline" id="update_attendance" name="update_attendance" action="">
					<select class="form-control" name="dept" required disabled>
						<option value="">Select Dept</option>
						
					</select>
					<select class="form-control" name="classes" required disabled>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject" required>
						<option value="">Select Subject</option>
					</select>
					<input type="date" class="form-control" name="date" required/>
					<input id="roll" name="roll" type="number" class="form-control" placeholder="Roll No" min="1" max="100" style="width:100px;" required/>
					<button class="btn btn-primary" type="submit">Fetch</button>
				</form>
				<br/>
				<div class="content2" style="display:inline-flex;">
					
				</div>
				
			</div>

			<div id="chngpass_form_div">
				<form action="chngpass.php" method="post" id="chngpass_form">
					<input class="form-control" name="oldpass" placeholder="Current Password" type="password"/><br/>
					<input class="form-control" name="pass" id="pass2" placeholder="New Password" type="password"/><br/>
					<input class="form-control" name="cnfpass" placeholder="Confirm New Password" type="password"/><br/>
					<button type="submit" class="btn btn-primary">Change Password</button>
				</form>
			</div>

		<!-- </div> -->
		
	
