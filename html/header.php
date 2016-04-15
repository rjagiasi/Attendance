<!DOCTYPE html>
<html>
	<head>
		
		<title><?=$title?> - VESIT Attendance</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/global.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css"/>

		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/jquery.validate.min.js"></script>
		<script src="../js/jquery-ui.min.js"></script>
		<script src="../js/jquery.tablesorter.min.js"></script>
		<script src="../js/tableExport.js"></script>
		<script src="../js/jquery.base64.js"></script>
		<script src="../js/jspdf.debug.js"></script>

		<script src="../js/functions.js"></script>
		<script src="../js/js2pdf.js"></script>
	</head>
	<body>
		
		<div class="wrapper">
			<div id="heading">
				<div id="logo" >
					<a href="index.php"><img src="../imgs/logo.png" alt = "Ves Logo"/></a>
				</div>
				
				<?php if(!isset($_SESSION["uid"])): ?>
				<div id="login_form">
					<h2>VESIT Attendance Portal</h2>
					<p>For authorized Users only</p>
					<form class = "form-inline" action = "../php/login.php" method = "POST">
						<input name="username" class="form-control" placeholder="Username" type = "text" required/>
						<input name="password" class="form-control" placeholder="Password" type = "password" required/>
						<button class = "btn btn-primary">Submit</button>
					</form>
				</div>
				
				<?php else: ?>
				<div>
					<h2>VESIT Attendance Portal</h2>
					<p id="greet">Welcome <?= $_SESSION["name"] ?></p>
					<a href="logout.php"><button id="logout"class="btn btn-primary" style="float:right">Logout</button></a>
					<br/>
				</div>
				<?php endif ?>


			</div>
			<hr/>
			<p class="alert"style="text-align:center"/>
				<script type="text/javascript">
					$(".alert").hide();

					<?php if(isset($_GET["error"])): ?>
						failuremessage("<?php echo(urldecode($_GET["error"]))?>");
					<?php endif ?>
				</script>

			<div id="menu">
				<ul class="nav nav-pills nav-stacked">
					<li><a class="menuheads" href="index.php">Home</a></li>
					<li><a class="menuheads">MCA</a></li>
					<li><a class="menuheads">Diploma</a></li>
					<li><a data-toggle="collapse" data-target="#enggbranches" class="menuheads collapsed" aria-expanded="false">Engineering</a></li>
					<div class="sectionbranches collapse" id="enggbranches" aria-expanded="false" style="height:0px;">
						<li data-toggle="collapse" data-target="#it" class="menuheads" id="itheader" aria-expanded="true">IT</li>
						<ul id="it" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
							<li id="D10"><a>D10</a></li>
							<li id="D15"><a>D15</a></li>
						</ul>
						<li data-toggle="collapse" data-target="#comps" class="menuheads" id="compsheader" aria-expanded="true">Comps</li>
						<ul id="comps" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
							<li id="D7A"><a>D7A</a></li>
							<li id="D7B"><a>D7B</a></li>
						</ul>
						<li data-toggle="collapse" data-target="#extc" class="menuheads" id="extcheader" aria-expanded="true">EXTC</li>
						<ul id="extc" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
							<li id="D9A"><a>D9A</a></li>
							<li id="D9B"><a>D9B</a></li>
						</ul>
					</div>
					
					<?php if(isset($_SESSION["uid"])): ?>
					<li><a data-toggle="collapse" data-target="#staff" class="menuheads collapsed" aria-expanded="false">Staff</a></li>
					<div class="sectionbranches collapse" id="staff" aria-expanded="false" style="height:0px;">
						<ul id="staff" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
							<li id="attendance"><a>Add Attendance</a></li>
							<li id="update"><a>Update</a></li>
							<li id="report"><a>Report</a></li>
							<li id="register"><a>Register</a></li>
						</ul>
					</div>
					<?php endif ?>
					
				</ul>
			</div>
			