

	<script type="text/javascript">
	$(document).ready(function() {
		$("#content").html("<p>Select a class</p>");
		$("#enggbranches").collapse("show");

		<?php if(isset($class) || isset($branch)): ?>
		setactiveclass('<?=$class?>','<?=$branch?>');
		<?php else : ?>
		setactiveclass('d10','it');
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
			$("#content").html("<p>Class Selected : "+ classid.toUpperCase() +"</p>");
		}

		$("#staff").find("a").click(function(event) {
			// $(".active").toggleClass("active", false);
			var id = $(event.target).parent().attr("id");
			var url = "staff.php?formid="+id;
			$(location).attr("href", url);
		});
	});
	</script>

		<div id="content">

		</div>
		<div id="loadinggif" style="text-align:center;">
			<img src="../imgs/loading.gif"/>
		</div>
	