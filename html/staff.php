
	<script>

	$(document).ready(function () {

		//set dept dropdown initially
		function setdepts (callback) {

			var dept = "<option value=\"\">Select Dept</option>";
			ajaxcall("getdepts.php", "", function (data) {
				var no=0;
				
				for (var i = 0; i < data.length; i++) {
					dept = dept + "<option value=\"" + data[i].DeptId + "\">"+ data[i].Name + "</option>";
				}
				
				$("[name=dept]").html(dept);
	
				callback();
			
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

			var listValue = this.value;
			var classes = "<option value=\"\">Select Class</option>";
			var datastring = "dept="+listValue;
			
			ajaxcall("getclasses.php", datastring, function(data) {
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
			});
		});
		
		// set subjects dropdown
		$("[name=classes]").change(function () {
			var listValue = this.value;
			var subjects = "<option value=\"\">Select Subject</option>";
			var datastring = "class="+listValue;
			ajaxcall("getsubjects.php", datastring, function (data) {
					for (var i = 0; i < data.length; i++) {
						subjects = subjects + "<option value=\"" + data[i].SubjectId + "\">"+ data[i].Name + "</option>";
					}
					$("[name=subject]").html(subjects);
				});
		});
		
		// hide content on subject change
		$("[name=subject]").change(function () {
			var dropdownobj = this;
			$(dropdownobj).parent().siblings(".content2").hide();
			$(dropdownobj).parent().siblings(".pagination").hide();
			$(dropdownobj).parent().siblings("#download_buttons").hide();
			$("#report_count").html("");
			$("#download_buttons_list").hide();
		});
		

		var table = "";
		var itemsperpg = 25;

		//get student list and set pagination
		$("#add_attendance").submit(function (event) {
			event.preventDefault();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');
			
			ajaxcall("getstudentlist.php",data,function (data) {
				
				if(data == false){
					failuremessage("Attendance for the day already exists!");
				}
				else
				{
					table = 
					"<form id=\"attendance_list\" method = \"POST\" action = \"add_attendance.php\"><table class = \"table table-striped\"><thead><tr><th name=\"present_master\"><a>Present</a></th><th name=\"absent_master\"><a>Absent</a></th><th>Roll No</th><th>Name</th></tr></thead><tbody>";
					for (var i = 0; i < data.length; i++) {
						table += 
						"<tr><td><input type = \"radio\" name=\"pa_" + data[i].StudentId + "\" value = \"1\"/></td><td><input type = \"radio\" name=\"pa_" + data[i].StudentId + "\" value = \"0\"/></td><td>" + data[i].RollNo + "</td><td>" + data[i].Name + "</td></tr>";
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
					$("#attendance_form_div .content2").find("[name=present_master]").trigger("click");
				}
			});
		});
		
		//master present button
		$("#attendance_form_div .content2").on("click", "[name=present_master]", function (event) {
			var fields = $("[name^=pa_]");
        	fields.filter('[value=1]').prop('checked', true);
		});

		//master absent button
		$("#attendance_form_div .content2").on("click", "[name=absent_master]", function (event) {
			var fields = $("[name^=pa_]");
        	fields.filter('[value=0]').prop('checked', true);
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
			$('html, body').animate({
				scrollTop: $("#attendance_form_div").offset().top
			}, 500);
		});

		//insert data after attendance list submitted
		$("#attendance_form_div").on("submit", "#attendance_list", function (event) {
			event.preventDefault();
			
			var formobj = this;
			// alert($(this).parent().siblings("form").html());

			ajaxcall("add_attendance.php",
				$(formobj).serialize() + "&" + $(formobj).parent().siblings("form").serialize(),
				function (data) {
				if(data == false)
					failuremessage("Some Error Occured");
				else if(data == true){
					successmessage("Attendance added Successfully");
					$("#attendance_form_div .content2").hide();
					$("#attendance_form_div .pagination").hide();
				}
			});
			
		});

		//gen report ajax call
		$("#gen_report").submit(function (event) {
			event.preventDefault();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			// alert($(this).find("select,textarea, input").serialize());
			disabledfields.attr('disabled', 'disabled');

			$("#report_form_div .content2").hide();
			$("#report_form_div .pagination").hide();
			$("#download_buttons").hide();
			$("#report_count").html("");

			ajaxcall("getreport.php", data, function (data) {
				if(data == false)
					failuremessage("No Data Found!");
				else
				{
					table = "<div class = \"table-responsive\"><table class = \"table table-striped\" id=\"generated_report\"><thead><tr>";
					$.each(data[0], function(key, value) {
						table += "<th class = \"datacell\">"+key+"</th>";	//<span class=\"glyphicon glyphicon-sort\">
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
					table += "</tbody></table></div>";

					var noofpages = (data.length/itemsperpg)+1;
					// alert(noofpages);
					var pagination_html = "";

					for (var i = 1; i <= noofpages; i++) {
						pagination_html += "<li value=\"" + i + "\"><a href=\"#\">"+i+"</a></li>"
					};
					$("#report_form_div .content2").html(table);
					$("#report_form_div .pagination").html(pagination_html);
					// $("#report_form_div .content2").show();
					$("#report_count").html("Count : " + data.length);
					$("#report_form_div .pagination a").first().trigger("click");
					// $(".tablesorter").tablesorter().bind("sortEnd", function () {
					// 	$(this).parent().siblings(".pagination").find(".active a").trigger("click");
					// 	// alert(id);
					// });

				}
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
			$('html, body').animate({
				scrollTop: $("#report_form_div").offset().top
			}, 500);
		});

		//report download code
		$("#download_buttons button").click(function(event) {
			var downloadtype = $(event.target).attr("id");
			$("#loadinggif").show();
			$("#report_form_div .content2 tbody tr").show();
			var noofcol = $("#report_form_div .content2 thead th").length;

			if(downloadtype == "pdf")
				converttopdf(noofcol);	//demoPDF()
			else
				$("#generated_report").tableExport({type: downloadtype,escape:'false'});

			$("#report_form_div .pagination").find(".active a").trigger("click");
			$("#loadinggif").hide();
		});


		//modify attendance ajax
		$("#modify_attendance").submit(function(event) {
			event.preventDefault();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');

			ajaxcall("fetchdayatt.php", data, function (data) {
				if(data == "false"){
					failuremessage("Attendance data not found!");
					$("#modify_form_div .content2").hide();
				}
				else
				{
					var c = "";
					c += "<p style=\"margin-right:20px;\">"+data[0]["Name"]+"</p>";
					c += (data[0]["PA"] == "1")?"<input val=\"1\" class=\"form-control\" value=\"Present\" name=\"old\" disabled style=\"width:100px;margin-right:20px;\"/>":"<input val=\"0\" class=\"form-control\" value=\"Absent\" name=\"old\" style=\"width:100px; margin-right:20px;\" disabled/>";
					c += "<button id=\"change\" style=\"float:right;\" class=\"btn btn-primary\">Change</button>";
					$("#modify_form_div .content2").html(c);
					$("#modify_form_div .content2").show();
				}
			});

		});

		//toggle attendance
		$("#modify_form_div .content2").on("click", "#change", function(event) {

			var disabledfields = $("#modify_attendance").find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $("#modify_attendance").serialize();
			disabledfields.attr('disabled', 'disabled');
			data = data + "&" + "currval=" + $("[name=old]").attr("val");

			ajaxcall("update_att.php", data, function (data) {
				$("#modify_attendance").trigger("submit");
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

				ajaxcall("chngpass.php", $("#chngpass_form").serialize(), function (data) {
					if(data == false)
						failuremessage("Some Error Occured");
					else if(data == true)
						successmessage("Password Changed Successfully!");
				});

			}
		});

		
		//cancel a lecture
		$("#cancel_form").submit(function(event) {
			event.preventDefault();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');

			ajaxcall('cancel.php', data, function(data) {
				if(data == false)
					failuremessage("Some Error Occured");
				else if(data == true)
					successmessage("Lecture Cancelled");
			});

		});

		//initialize dept dropdown
		// setdepts();

		//get searched student
		$("#search_form").submit(function (event) {
			event.preventDefault();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');

			if(!($(this).find('#name').val() == "" ^ $(this).find('#roll').val() == ""))
			{
				failuremessage("Enter either name or roll no!");
				return;
			}

			ajaxcall("getstudrep.php", data, function (data) {
				if(data == "false")
					failuremessage("Roll No. doesn't exist!");
				else
				{
					$("#studrep").html(data);
				}
				$("#loadinggif").hide();
			});
				
		});

		//generate monthly list
		$("#list_form").submit(function(event) {
			event.preventDefault();

			var disabledfields = $(this).find(':input:disabled');
			disabledfields.removeAttr('disabled');
			var data = $(this).serialize();
			disabledfields.attr('disabled', 'disabled');

			ajaxcall("getmonthlylist.php", data, function(data) {
				
				var table = "<div class = \"table-responsive\"><table class = \"table table-striped\" id=\"generated_list\"><thead><tr><th>Roll No</th><th>Name</th>";

				headers = data.pop();

				if(headers[0] === undefined)
				{
					failuremessage("No data found!");
					return false;
				}

				for (var i = 0, n = headers.length; i < n; i++) {
					table += "<th>"+headers[i]+"</th>";
				};
				
				table += "</tr></thead><tbody>";

				for (var i = 0, n = data.length; i < n; i++) {
					//for each student
					var j=0;
					var att = "", roll = "", name = "";

					$.each(data[i], function(index, el) {

						if(index == headers[j])
						{
							att += "<td>"+el+"</td>";
							j++;
						}
						else if(index == "RollNo")
							roll = "<td>"+el+"</td>";
						else if(index == "Name")
							name = "<td>"+el+"</td>";
						else
						{
							while(index != headers[j])
							{
								att += "<td>-</td>";
								j++;
							}
							att += "<td>"+el+"</td>";
							j++;
						}


					});

					while(j != headers.length)
					{
						att += "<td>-</td>";
						j++;
					}

					table += "<tr>" + roll + name + att + "</tr>";
				};

				table += "</tbody></table></div>";

				var noofpages = (data.length/itemsperpg)+1;
				// alert(noofpages);
				var pagination_html = "";

				for (var i = 1; i <= noofpages; i++) {
					pagination_html += "<li value=\"" + i + "\"><a href=\"#\">"+i+"</a></li>"
				};


				$('#list_form_div .content2').html(table);
				
				$("#list_form_div .pagination").html(pagination_html);
				$("#list_form_div .pagination a").first().trigger("click");
				
				// $('#list_form_div .content2').show();
				// $("#list_form_div .pagination").show();
			});
		});
		
		//list pagination
		$("#list_form_div .pagination").on("click", "a", function (event) {
			$("#list_form_div .content2").show();
			$("#list_form_div .pagination").show();
			$("#download_buttons_list").show();
			$("#list_form_div .pagination .active").toggleClass("active", false);
			var pgno = $(event.target).parent().val();
			// alert(pgno);
			$("#list_form_div .pagination li[value="+pgno+"]").addClass("active");
			// var pgno = parent.attr("value");
			$("#list_form_div .content2 tbody tr").hide();
			$("#list_form_div .content2 tbody tr").slice((pgno-1)*itemsperpg, pgno*itemsperpg).show();
			$('html, body').animate({
				scrollTop: $("#list_form_div").offset().top
			}, 500);
		});


		//list download code
		$("#download_buttons_list button").click(function(event) {
			var downloadtype = $(event.target).attr("name");
			$("#loadinggif").show();
			$("#list_form_div .content2 tbody tr").show();
			var noofcol = $("#list_form_div .content2 thead th").length;

			if(downloadtype == "pdf")
				convertlisttopdf(noofcol);	//demoPDF()
			else
				$("#generated_list").tableExport({type: downloadtype,escape:'false'});

			$("#list_form_div .pagination").find(".active a").trigger("click");
			$("#loadinggif").hide();
		});


		setdepts(function () {
			setactiveclass('<?=$class?>','<?=$branch?>');
		});
		

		// $("#staff").collapse("show");
		$("#download_buttons").hide();
		$("#download_buttons_list").hide();

		$("#loadinggif").hide();
		//use jquery datepicker if browser doesn't support date type
		if ( $('[type="date"]').prop('type') != 'date' ) {
			$("#loadinggif").show();
			$('<link/>', {
				rel: 'stylesheet',
				type: 'text/css',
				href: '../css/jquery-ui.min.css'
			}).appendTo('head');

			$.getScript("../js/jquery-ui.min.js").done(function() {
				$('[type="date"]').datepicker({ dateFormat: 'yy-mm-dd', maxDate:0});
				$("#loadinggif").hide();
			});
			
		}

		var date = new Date();
		$('[type="date"]').attr('max', date.toISOString().substring(0,10));

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
		
		$("#custom_menu ul li").find("a").click(function(event) {
			if(!$(this).parent().hasClass('active')){
				var classid = $(event.target).parent().attr("class");
				var branchid = $(event.target).parent().attr("data-branch");

				setactiveclass(classid, branchid);
			}
		});

		$("#enggbranches ul").find("a").not("#enggbranches .active a").click(function(event) {
			if(!$(this).parent().hasClass('active')){
				var classid = $(event.target).parent().attr("class");
				var branchid = $(event.target).parent().parent().attr("id");
				setactiveclass(classid, branchid);
			}
		});
		
		// changedept();
		function setactiveclass(classid, branchid) {
			// alert(branchid);
			$("#enggbranches .active").toggleClass("active", false);
			$("#custom_menu .active").toggleClass("active", false);
			$("#enggbranches ul").not("#" + branchid).collapse("hide");
			$("#" + branchid).collapse("show");
			$("." + classid).addClass("active");
			changedept();
			$(".content2").hide();
			$(".pagination").hide();
			$("#download_buttons").hide();
			$("#download_buttons_list").hide();
			$("#report_count").html("");
			// $("#attendance a").first().trigger("click");
		}

		// $("#loadinggif").show();
	});
	$("#enggbranches ul").collapse("show");
	$("#custom_menu ul").collapse("show");

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
					<li id="attendance"><a>Add</a></li>
					<li id="modify"><a>Modify</a></li>
					<li id="lectcancel"><a>Cancel</a></li>
					<li id="list"><a>List</a></li>
					<li id="report"><a>Report</a></li>
					<li id="search"><a>Search</a></li>
					<li id="chngpass"><a>Password</a></li>
				</ul>
			</div>
			<br/>
			<div id="list_form_div">
				<label for="list_form">Get attendance sheet for a specific month</label>
				<form class="form-inline" id="list_form" name="list_form">
					<select class="form-control" name="dept" required disabled>
						<option value="">Select Dept</option>
					</select>
					<select class="form-control" name="classes" required disabled>
						<option value="">Select Class</option>
					</select>
					<select class="form-control" name="subject" required>
						<option value="">Select Subject</option>
					</select>
					<select class="form-control" name="month" required>
						<option value="">Select Month</option>
						<option value="1">January</option>
						<option value="2">February</option>
						<option value="3">March</option>
						<option value="4">April</option>
						<option value="5">May</option>
						<option value="6">June</option>
						<option value="7">July</option>
						<option value="8">August</option>
						<option value="9">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
					<button class="btn btn-primary" type="submit">Get List</button>
				</form>

				<ul class="pagination" style="float:center;">
					
				</ul>
				<div class="content2">

				</div>
				<ul class="pagination" style="float:center;">
					
				</ul>
				<div id="download_buttons_list">
					Download Attendance list as
					<button type="button" name = "csv" class="btn btn-primary">CSV</button>
					<button type="button" name = "excel" class="btn btn-success">Excel</button>
					<button type="button" name = "pdf" class="btn btn-danger">PDF</button>
				</div>
			</div>
			<div id="search_form_div">
				<label for="search_form">Search for a particular student</label>
				<form class="form-inline" id="search_form" name="search_form">
					<select class="form-control" name="dept" required disabled>
						<option value="">Select Dept</option>
					</select>
					<select class="form-control" name="classes" required disabled>
						<option value="">Select Class</option>
					</select>
					<input id="roll" name="roll" type="number" class="form-control" placeholder="Roll No" min="1" max="100"/>
					<label>OR</label> <input id="name" name="name" type="text" class="form-control" placeholder="Name"/>
					<button class="btn btn-primary" type="submit">Search</button>
				</form>
				<br/><br/>
				<div id = "studrep"></div>
			</div>
			<div id = "lectcancel_form_div">
				<label for="cancel_form">Cancel a lecture</label>
				<form class="form-inline" id="cancel_form" name="cancel_form">
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
					<br/><br/>
					<input type="text" class="form-control" maxlength="255" name="reason" placeholder="Reason" required></input>
					<button class="btn btn-primary" id="cancel_button" type="submit">Cancel</button>
				</form>
			</div>
			<div id = "report_form_div">
				<label for="gen_report">Generate Attendance Report</label>
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
					<br/><br/>
					<label for="from_percentage">From % </label>
					<input id="from_percentage" name="from_percentage" type="number" class="form-control" min="0" max="100" value="0" required/>
					<label for="to_percentage">To % </label>
					<input id="to_percentage" name="to_percentage" type="number" class="form-control"  min="0" max="100" value="100" required/>
					<button class="btn btn-primary" type="submit">Generate Report</button>
				</form>
				<p id="report_count"></p>
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
				<label for="add_attendance">Add attendance for a lecture</label>
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

			<div id="modify_form_div">
				<label for="modify_attendance">Modify attendance of a student</label>
				<form class="form-inline" id="modify_attendance" name="modify_attendance" action="">
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
					<input id="roll" name="roll" type="number" class="form-control" placeholder="Roll No" min="1" max="100" required/>
					<button class="btn btn-primary" type="submit">Fetch</button>
				</form>
				<br/>
				<div class="content2" style="display:inline-flex;">
					
				</div>
				
			</div>

			<div id="chngpass_form_div">
				<label for="chngpass_form">Change Password</label>
				<form action="chngpass.php" method="post" id="chngpass_form">
					<input class="form-control" name="oldpass" placeholder="Current Password" type="password"/><br/>
					<input class="form-control" name="pass" id="pass2" placeholder="New Password" type="password"/><br/>
					<input class="form-control" name="cnfpass" placeholder="Confirm New Password" type="password"/><br/>
					<button type="submit" class="btn btn-primary">Change Password</button>
				</form>
			</div>

		<!-- </div> -->
		
	
