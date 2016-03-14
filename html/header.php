<?php session_start();?>

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
				</div>
				<?php endif ?>


			</div>
			<hr/>
			<p class="alert" style="text-align:center"/>
				<script type="text/javascript">
					$(".alert").hide();

					<?php if(isset($_GET["error"])): ?>
						$(".alert").show();
						$(".alert").addClass("alert-danger");
						$(".alert").html(<?php urldecode($_GET["error"])?>);
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
							<li id="d10"><a>D10</a></li>
							<li id="d15"><a>D15</a></li>
						</ul>
						<li data-toggle="collapse" data-target="#comps" class="menuheads" id="compsheader" aria-expanded="true">Comps</li>
						<ul id="comps" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
							<li id="d7a"><a>D7A</a></li>
							<li id="d7b"><a>D7B</a></li>
						</ul>
					</div>
					
					<?php if(isset($_SESSION["uid"])): ?>
					<li><a data-toggle="collapse" data-target="#staff" class="menuheads collapsed" aria-expanded="false">Staff</a></li>
					<div class="sectionbranches collapse" id="staff" aria-expanded="false" style="height:0px;">
						<ul id="staff" class="nav nav-pills nav-stacked collapse in" aria-expanded="true">
							<li id="register"><a>Register</a></li>
							<li id="report"><a>Report</a></li>
							<li id="attendance"><a>Add Attendance</a></li>
						</ul>
					</div>
					<?php endif ?>
					
				</ul>
			</div>