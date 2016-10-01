<!DOCTYPE html>
<html>
	<head>
		
		<title><?=$title?> - VESIT Attendance</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/global.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css"/>
		<link rel="icon" href="../imgs/logo.png" type="image/x-icon" />

		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<!--script src="../js/jquery.tablesorter.min.js"></script-->
		<script src="../js/jquery.validate.min.js"></script>
		<script src="../js/jquery-ui.min.js"></script>
		<script src="../js/tableExport.js"></script>
		<script src="../js/jquery.base64.js"></script>
		<script src="../js/jspdf.debug.js"></script>
		<script src="../js/js2pdf.js"></script>
		<!--script src="../js/html2canvas.js"></script-->

		<script src="../js/functions.js"></script>
		
	</head>
	<body>
		
		<div class="wrapper">
			<div id="heading">
				<div id="logo" >
					<a href="index.php"><img id="logo_img" src="../imgs/logo.png" alt = "Ves Logo"/></a>
				</div>
				
				<h2>VESIT Attendance Portal</h2>

			</div>
			<!--br/><br/-->
			<br/><br/>
			<p class="alert" id="response"></p>
				<script type="text/javascript">
					$("#response").hide();
					
					<?php if(isset($_GET["error"])): ?>
						failuremessage("<?php echo(urldecode($_GET["error"]))?>");
					<?php endif ?>
				</script>
			<div id="menu">
				<ul class="nav nav-pills nav-stacked">
					<li><a class="menuheads" href="index.php">Home</a></li>
					<?php if(!isset($_COOKIE["uid"])): ?>
						<li><a class="menuheads" href="register_page.php">Register</a></li>
					<?php else : ?>
					<!-- <li><a class="menuheads">MCA</a></li>
					<li><a class="menuheads">Diploma</a></li> -->
					<!-- <li><a data-toggle="collapse" data-target="#enggbranches" class="menuheads collapsed">Engineering</a></li> -->
					<ul class="nav nav-pills nav-stacked" id="enggbranches">
						<li data-toggle="collapse" data-target="#it" id="itheader"><a class="menuheads">IT</a></li>
						<ul id="it" class="nav nav-pills nav-stacked collapse">
							<li id="D10"><a>D10</a></li>
							<li id="D15"><a>D15</a></li>
						</ul>
						<li data-toggle="collapse" data-target="#comps" id="compsheader"><a class="menuheads">Comps</a></li>
						<ul id="comps" class="nav nav-pills nav-stacked collapse">
							<li id="D7A"><a>D7A</a></li>
							<li id="D7B"><a>D7B</a></li>
						</ul>
						<li data-toggle="collapse" data-target="#extc" id="extcheader"><a class="menuheads">EXTC</a></li>
						<ul id="extc" class="nav nav-pills nav-stacked collapse">
							<li id="D9A"><a>D9A</a></li>
							<li id="D9B"><a>D9B</a></li>
						</ul>
					</ul>
					<?php endif ?>
				</ul>

				<div id="loadinggif">
					<img src="../imgs/loading.gif" alt="Loading"/>
				</div>
				<script type="text/javascript">
					$("#loadinggif").hide();
				</script>
			</div>

			<div id="content">
				<?php if(isset($_COOKIE["uid"])): ?>
				<div id = "greeting_div">
					<p id="greet">Welcome <?= $_COOKIE["name"] ?></p>
					<a href="logout.php"><button id="logout" class="btn btn-primary" style="float:right">Logout</button></a>
					<br/>
				</div>
				<?php endif ?>