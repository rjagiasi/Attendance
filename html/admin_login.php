<script type="text/javascript">
$(document).ready(function() {

	$("#enggbranches ul").find("a").click(function(event) {
		var classid = $(event.target).parent().attr("id");
		var branchid = $(event.target).parent().parent().attr("id");

		var url="staff.php?branch="+branchid+"&class="+classid;
		$(location).attr("href", url);
	});

	$("#custom_menu ul").find("a").click(function(event) {
		var classid = $(event.target).parent().attr("class");
		var branchid = $(event.target).parent().attr("data-branch");

		var url="staff.php?branch="+branchid+"&class="+classid;
		$(location).attr("href", url);
	});

	$("#menu ul li").first().toggleClass('active', true);

});
</script>

<!-- <h4 class="col-sm-4">Admin Login</h4> -->
<?php if ( !isset( $_COOKIE["admin"] ) ) : ?>
	<form class = "form-horizontal" id="admin_login_form" method="POST" action="admin_login_check.php">

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

	<!--script type="text/javascript">
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

	});
	</script-->

<?php else : ?>

	<script type="text/javascript">

	$(document).ready(function() {

		function setdepts () {
			var dept = "<option value=\"\">Select Dept</option>";
			ajaxcall("getdepts.php","",function (data) {
				for (var i = 0; i < data.length; i++) {
					dept = dept + "<option value=\"" + data[i].DeptId + "\">"+ data[i].Name + "</option>";
				};
				$("[name=dept]").html(dept);
			});
		}

		function setteaachers () {
			ajaxcall("admin_getteachers.php", "", function (data) {
				var teacher_obj = $("select[name^=teacher]");
				var teacher = "<option value=\"\">Select Teacher</option>";
				for (var i = 0; i < data.length; i++) {
					teacher = teacher + "<option value=\"" + data[i].StaffId + "\">"+ data[i].Name + "</option>";
				};
				$(teacher_obj).html(teacher);
				for (var i=0 ; i < teacher_obj.length ; i++) {
					$(teacher_obj[i]).attr('name', 'teacher['+i+']');
				};
			});
		}

		function setdaystable () {
			var weekday = new Array(7);
			weekday[1] = "M";
			weekday[2] = "T";
			weekday[3] = "W";
			weekday[4] = "T";
			weekday[5] = "F";
			weekday[6] = "S";

			var days_ip = $("[name=days_table]");

			for (var i = 0; i < days_ip.length; i++) {
				var tablestr = "<tr>";
				for (var j = 1, n = weekday.length; j < n; j++) {
					tablestr += "<th>"+weekday[j]+" </th>";
					tablestr += "<td><input type = \"checkbox\" value=\"1\" name=\"day["+i+"]["+j+"]\"></td>";
				};
				tablestr += "</tr>";
				$(days_ip[i]).html(tablestr);
			};
		}

		//set classes dropdown
		$("[name=dept]").change(function () {
			var listValue = this.value;
			var dropdownobj = this;
			var classes = "<option value=\"\">Select Class</option>";
			var datastring = "dept="+listValue;

			ajaxcall("getclasses.php",datastring,function (data) {
				for (var i = 0; i < data.length; i++) {
					classes = classes + "<option value=\"" + data[i].ClassId + "\">"+ data[i].Name + "</option>";
				};
				$(dropdownobj).next("[name=classes]").html(classes);
			});
		});

		// set subjects dropdown
		$("[name=classes]").change(function () {

			var listValue = this.value;
			var dropdownobj = this;
			var subjects = "<option value=\"\">Select Subject</option>";
			var datastring = "class="+listValue;
			ajaxcall("getadminsubjects.php",datastring,function (data) {
				for (var i = 0; i < data.length; i++) {
					subjects = subjects + "<option value=\"" + data[i].SubjectId + "\">"+ data[i].Name + "</option>";
				};
				$(dropdownobj).next("[name=subject]").html(subjects);
			});
		});


		$("#modify_subjects").find('[name=subject]').change(function () {
			ajaxcall("admin_getsubdetails.php", $(this).serialize(), function(data) {
				$("#modify_subjects").find('[name^=teacher] option').each(function(){
			        // alert(data[0]["StaffId"]);
			        if($(this).val()==parseInt(data[0]["StaffId"])){ 
			        	$(this).attr("selected","selected");
			        	return;
			        }
		    	});

				var pad = "000000"
		    	var bin = parseInt(data[0]["Days"], 10).toString(2);
		    	bin = pad.substring(0, pad.length - bin.length) + bin;

		    	var i=0;
		    	$("#modify_subjects").find('[name=days_table] input').each(function() {
		    		if(bin[i] == "1")
		    			$(this).attr('checked', true);
		    		else
		    			$(this).attr('checked', false);
		    		i++;
		    	});
			});
		});

		$("#modify_subjects").submit(function(event) {
			event.preventDefault();
			ajaxcall("admin_modifysub.php", $(this).serialize(), function(data) {
				if(data == true)
					successmessage("Subject modified!");
				else
					failuremessage("Some Error Occurred");
			});
		});

		$("#create_dept").submit(function(event) {
			event.preventDefault();
			ajaxcall("admin_adddept.php", $(this).serialize(), function (data) {
				if(data == true)
					successmessage("Dept added");
				else
					failuremessage("Some Error Occurred");
				setdepts();
			});
		});

		$("#add_classes").submit(function(event) {
			event.preventDefault();
			ajaxcall("admin_addclass.php", $(this).serialize(), function (data) {
				if(data == true)
					successmessage("Class added");
				else
					failuremessage("Some Error Occurred");
				// setdepts();
			});
		});

		$("#create_batches").submit(function(event) {
			event.preventDefault();
			ajaxcall("admin_getstudlist.php", $(this).serialize(), function (data) {
				if(data != false)
				{
					var divop = "<form id=\"batch_list\" method = \"POST\" action = \"admin_createbatches.php\"><table class = \"table table-striped\"><thead><tr>";
					var no = $("#create_batches").find('[name=no_of_batches]').val();
					no = parseInt(no);
					for (var i = 0; i < no; i++) {
						divop += "<th>Batch "+String.fromCharCode(65+i)+"</th>"
					};

					divop += "<th>Roll No</th><th>Name</th></tr></thead><tbody>";

					for (var i = 0, n = data.length; i < n; i++) {
						var batchsize = Math.ceil(n/no);

						divop += "<tr>";
						for (var j = 1; j <= no; j++) {

							if(Math.ceil(parseInt(data[i]["RollNo"])/batchsize) == j)
								divop += "<td><input type=\"radio\" name=batchid["+data[i]["StudentId"]+"] value=\""+j+"\" checked></td>";
							else
								divop += "<td><input type=\"radio\" name=batchid["+data[i]["StudentId"]+"] value=\""+j+"\"></td>";
						};

						divop += "<td>"+data[i]["RollNo"]+"</td><td>"+data[i]["Name"]+"</td></tr>";
					};
					divop += "</tbody></table><input class=\"btn btn-primary\" type=\"submit\"/></form>";
					$("#create_batches_div").html(divop);
				}
				else
					failuremessage("Some Error Occurred");
				// setdepts();
			});
		});

		$("#create_batches_div").on('submit', 'form', function(event) {
			event.preventDefault();
			ajaxcall("admin_createbatches.php", $(this).serialize()+"&"+$("#create_batches > [name=classes]").serialize(), function (data) {
				if(data == true){
					successmessage("Batches Made");
					$("#create_batches_div").html("");
				}
				else
					failuremessage("Some Error Occurred");
			});
		});

		$("#add_subject_submit").click(function(event) {
			// if(confirm("Are you sure?"))
			// {
				var data;
				if($("#add_subjects").find("[name=haslab]").is(':checked'))
					data = $("#add_subjects, #lab_form").serialize();
				else
					data = $("#add_subjects").serialize();

				ajaxcall("admin_addsubjects.php", data, function(data) {
					if(data == true)
					{
						successmessage("Subject added sucessfully!");
						$("#lab_form").html("");
					}
					else if(data == false)
						failuremessage("Some Error Occurred!");
					else
						failuremessage(data);
				});
			// }
		});

		$("[name=haslab]").click(function(event) {

			if($(this).is(':checked') != true)
			{
				$("#lab_form").hide();
			}
			else
			{
				var labsubstring = "Name : "+$("#add_subjects > [name=subject]").val()+"_lab <br/>";
				ajaxcall("admin_getbatches.php", $("#add_subjects").serialize(), function(data) {
					if(data == false)
					{
						failuremessage("No batches found!");
						return;
					}

					for (var i = 0, n = data.length; i < n; i++) {
						labsubstring += "<br/>Batch : "+String.fromCharCode(64+parseInt(data[i]["BatchId"]));
						labsubstring += "<table class=\"table table-striped\" name=\"days_table\" style=\"margin-left:1cm;\"></table> <select class=\"form-control\" name=\"teacher\" required style=\"margin-left:1cm;\"><option value=\"\">Select Teacher</option></select>";
						// alert(labsubstring);
					};
					$("#lab_form").html(labsubstring);
					setdaystable();
					setteaachers();
				});

				$("#lab_form").show();
			}
			$('#lab_form input').attr('disabled',! this.checked);

		});

		$("#add_student").submit(function(event) {
			event.preventDefault()
			$("#loadinggif").show();
			var formData = new FormData($(this)[0]);

			$.ajax({
				url: "admin_addstudents.php",
				type: 'POST',
				dataType: 'json',
				data: formData,
				processData : false,
				contentType: false,
			})
			.done(function(data) {
				if(data == true)
				{
					successmessage("Students added sucessfully!");
					$("#preview").html("");
				}
				else if(data == false)
					failuremessage("Some Error Occurred!");
				else
					failuremessage(data);
				$("#loadinggif").hide();
			});

		});

		$("[name=bulkdata]").change(function(event) {

			if (window.File && window.FileReader && window.FileList && window.Blob) {
				// Great success! All the File APIs are supported.
				var input, file, fr;
				input = document.getElementById('bulkdata');
				// alert(input.value);
				if (!input) {
					failuremessage("Um, couldn't find the fileinput element.");
					$("#preview").html("");
				}
				else if (!input.files[0]) {
					failuremessage("File API not supported!");
					$("#preview").html("");
				}
				else if(input.value.split('.').pop() != "csv")
				{
					failuremessage("Invalid File Format!");
					$("#preview").html("");
				}
				else {
					$("#loadinggif").show();
					file = input.files[0];
					fr = new FileReader();
					fr.onload = receivedText;
					fr.readAsText(file);
					$("#loadinggif").hide();
				}
			}
			else {
				failuremessage('The File APIs are not fully supported in this browser.');
				$("#preview").html("");
			}
			function receivedText() {
				showResult(fr, "Text");

				// fr = new FileReader();
				// fr.onload = receivedBinary;
				// fr.readAsBinaryString(file);
			}

			function showResult(fr, label) {
				var markup, result, n, byteStr;

				markup = [];
				result = fr.result;
				for (n = 0; n < result.length; ++n) {
					byteStr = result;
					if (byteStr.length < 2) {
						byteStr += byteStr;
					}
					markup.push(byteStr);
					// alert(markup);
				}
				// alert(label + " (" + result.length + "):");
				create_preview(markup);
			}

			function create_preview(data) {
				var table = "Preview<br/><table class = \"table table-striped\"><thead><tr><th>RollNo</th><th>Name</th></tr></thead><tbody>";
				var temp;
				data = data[0].split("\n");
				for (var i = 0; i < data.length; i++) {
					temp = data[i].split(",");
					table += "<tr><td>" + temp[0] + "</td><td>" + temp[1] + "</td></tr>";
				};
				table += "</tbody></table>";
				$("#add_student #preview").html(table);
			}
		});

		setdepts();
		setteaachers();
		setdaystable();
		$("[name=haslab]").attr('checked', true).click();
	});


</script>

<!-- <div id = "greeting_div">
	<p id="greet">Welcome Admin</p>
	<a href="logout.php"><button id="logout" class="btn btn-primary" style="float:right">Logout</button></a>
	<br/>
</div> -->
<!-- <h4>Add Data</h4> -->
<label class="admin_label" for="create_dept">Create a Department</label>
<form id="create_dept" class = "form-inline">
	<input type="text" name="dept" placeholder="Dept Name" class="form-control" required/>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>
<hr/>

<label class="admin_label" for="add_classes">Add a class</label>
<form id="add_classes" class = "form-inline">
	<select class="form-control" name="dept" required >
		<option value="">Select Dept</option>

	</select>
	<input type="text" name="class" placeholder="Class Name" class="form-control" required/>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>
<hr/>

<label class="admin_label" for="create_batches">Create or Modify Batches</label>
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
<div id="create_batches_div"></div>

<hr/>

<label class="admin_label" for="add_subjects">Add a Subject</label>
<form id="add_subjects" class = "form-inline">
	<select class="form-control" name="dept" required >
		<option value="">Select Dept</option>
	</select>
	<select class="form-control" name="classes" required >
		<option value="">Select Class</option>
	</select>
	<input type="text" name="subject" placeholder="Subject Name" class="form-control" required/>
	<label for="haslab" style="margin-left:0.5cm"> Has Lab?</label>
	<input type="checkbox" class="form-control" name="haslab" value="1" style="margin-right:0.5cm"/>
	<select class="form-control" name="teacher" required >
		<option value="">Select Teacher</option>
	</select>
	<table class="table table-striped" name="days_table" style="margin-left:1cm">

	</table>

</form><br/>
<form id = "lab_form" class = "form-inline">


</form>

<button type="submit" class="btn btn-primary" id="add_subject_submit">Submit</button>

<hr/>

<label class="admin_label" for="add_student">Add a Student</label>
<form id="add_student" class = "form-inline">
	<select class="form-control" name="dept" required >
		<option value="">Select Dept</option>
	</select>
	<select class="form-control" name="classes" required >
		<option value="">Select Class</option>
	</select>
	<input type="number" name="rollno" placeholder="Roll No" class="form-control" />
	<input type="text" name="name" placeholder="Name" class="form-control" min="1" />
	<select class="form-control" name="batch" >
		<option value="">Select Batch</option>
		<option value="1">A</option>
		<option value="2">B</option>
		<option value="3">C</option>
		<option value="4">D</option>
	</select> (optional)
	<br/><br/> OR bulk insert (csv file with rollno and name)
	<input type="file" name="bulkdata" id="bulkdata" class="form-control" accept=".csv"/><br/>
	<div id="preview"></div>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>
<hr/>

<!-- <h4>Modify Data</h4> -->

<label class="admin_label" for="modify_subjects">Modify Subject</label>
<form id="modify_subjects" class = "form-inline">
	<select class="form-control" name="dept" required >
		<option value="">Select Dept</option>
	</select>
	<select class="form-control" name="classes" required >
		<option value="">Select Class</option>
	</select>
	<select class="form-control" name="subject" required >
		<option value="">Select Subject</option>
	</select>
	<select class="form-control" name="teacher" required >
		<option value="">Select Teacher</option>
	</select>
	<table class="table table-striped" name="days_table" style="margin-left:1cm">

	</table>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php endif ?>
