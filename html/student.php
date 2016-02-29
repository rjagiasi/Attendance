<!DOCTYPE html>
<html>
	<head>
		<title>VESIT : Student</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/global.css">
		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>

		<script type="text/javascript">
		$(document).ready(function() {
			
			$("#content").html("<p>Select a class</p>");
			$("#enggbranches").collapse("show");
			$("#enggbranches ul").not("#" + "<?=$_GET["branch"]?>").collapse("hide");

			$("#menu").find("a").click(function(event) {
				var classid = $(event.target).parent().attr("id");
				var branchid = $(event.target).parent().parent().attr("id");
				setactiveclass(classid, branchid);
			});

			function setactiveclass(classid, branchid) {
				$(".active").toggleClass("active", false);
				$("#enggbranches ul").not("#" + branchid).collapse("hide");
				$("#enggbranches").collapse("show");
				$("#" + classid).addClass("active");
				$("#content").html("<p>Class Selected : "+ classid.toUpperCase() +"</p>");
			}
		});
		</script>
		
		<div>
			<img id="logo" src="../imgs/logo.png" alt = "Ves Logo" >
			<h3 id="heading">VESIT Attendance Portal</h3>
			<hr>
			<div id = "login">
				<p>For authorized Users only</p>
				<form class = "form-inline" action = "staff.html">
					<input name="usrname" class="form-control" placeholder="Username" type = "text"/>
					<input name="password" class="form-control" placeholder="Password" type = "password"/>
					<button class = "btn btn-default">Submit</button>
				</form>
			</div>
		</div>
		
		
	</head>
	<body>
		
		<br/><br/><br/>
		
		<div id="menu">
			<ul class="nav nav-pills nav-stacked">
				<li class="menuheads">MCA</li>
				<li class="menuheads">Diploma</li>
				<li data-toggle="collapse" data-target="#enggbranches" class="menuheads collapsed" aria-expanded="false">Engineering</li>
				<div class="sectionbranches collapse" id="enggbranches" aria-expanded="false" style="height:0px;">
					<li data-toggle="collapse" data-target="#it" class="menuheads" id="itheader" aria-expanded="true">IT</li>
					<ul id="it" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
						<li id="d10"><a>D10</a></li>
						<li id="d15"><a>D15</a></li>
					</ul>
					<li data-toggle="collapse" data-target="#comps" class="menuheads" id="compsheader" aria-expanded="true">Comps</li>
					<ul id="comps" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
						<li id="d7a"><a>D7A</a></li>
						<li id="d7b"><a>D7B</a></li>
					</ul>
				</div>
			</ul>
		</div>
		
		<div id="content">

		</div>
		
		<div id="footer">
			Copyright &copy; vesit.edu
		</div>
	</body>
</html>