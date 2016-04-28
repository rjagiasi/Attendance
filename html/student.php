

	<script type="text/javascript">
	$(document).ready(function() {
		$("#disp").html("<p>Select a class</p>");
		$("#enggbranches").collapse("show");

		<?php if(isset($class) || isset($branch)): ?>
		setactiveclass('<?=$class?>','<?=$branch?>');
		<?php else : ?>
		setactiveclass('D10','IT');
		<?php endif?>

		$("#enggbranches").find("a").click(function(event) {
			var classid = $(event.target).parent().attr("id");
			var branchid = $(event.target).parent().parent().attr("id");
			setactiveclass(classid, branchid);
		});

		$("#loadinggif").hide();

		function setactiveclass(classid, branchid) {
			$(".active").toggleClass("active", false);
			$("#enggbranches ul").not("#" + branchid).collapse("hide");
			$("#enggbranches").collapse("show");
			$("#" + classid).addClass("active");
			$("#disp").html("Class Selected : "+ classid.toUpperCase());
		}
		var table = "";
		$("#rollno").submit(function (event) {
			event.preventDefault();
			$("#loadinggif").show();
			$.ajax({
				url : "getstudrep.php",
				type : "POST",
				data : $("#rollno").serialize()+"&class="+$(".active").attr("id"),
				dataType : "json",
			})
			.done(function (data) {
				if(data == "false")
					failuremessage("Roll No. doesn't exist!");
				else
				{
					table = "<table class=\"table table-striped\"><tbody>";
					$.each(data, function (key, value) {
						table += "<tr><th>" + key.toUpperCase() + "</th><td>" + value + "</td>";
						if(value == null)
							failuremessage("Student data doesn't exist!");
					});
					table += "</tbody></table>";
					$("#studrep").html(table);
				}
				$("#loadinggif").hide();
			});
		});

		$("#staff").find("a").click(function(event) {
			// $(".active").toggleClass("active", false);
			var id = $(event.target).parent().attr("id");
			var url = "staff.php?formid="+id;
			$(location).attr("href", url);
		});
	});
	</script>

	<div id="content">
		<p id="disp"></p>
		<form id="rollno" class="form-inline">
			<input id="roll" name="roll" type="number" class="form-control" placeholder="Roll No" min="1" max="100" style="width:100px;" required/>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<br/>
		<div id="studrep"></div>
	</div>
	<div id="loadinggif" style="text-align:center;">
		<img src="../imgs/loading.gif" alt="Loading"/>
	</div>
	