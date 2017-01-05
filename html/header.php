<!DOCTYPE html>
<html>
<head>

	<title><?=$title?> - VESIT Attendance</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/global.css">
	<link rel="icon" href="../imgs/logo.png" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<!--script src="../js/jquery.tablesorter.min.js"></script-->
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/tableExport.js"></script>
	<script src="../js/jquery.base64.js"></script>
	<script src="../js/jspdf.debug.js"></script>
	<script src="../js/js2pdf.js"></script>
	<!--script src="../js/html2canvas.js"></script-->
	<script src="../js/jspdf.plugin.autotable.js"></script>
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
	<div class="col-sm-2" style="padding:10px;">
		<div class="sidebar-nav">
			<div id="menu" class="navbar navbar-default" role="navigation">
				<div class="navbar-header" style="background-color: white">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<span class="visible-xs navbar-brand">Menu</span>
				</div>
				<div class="navbar-collapse collapse sidebar-navbar-collapse" style="padding:0;">
					<ul class="nav nav-pills nav-stacked">
						<li><a class="menuheads" href="index.php">Home</a></li>
						<?php if(!isset($_COOKIE["uid"])): ?>
							<li><a class="menuheads" href="register_page.php">Register</a></li>
						<?php else : ?>

							<ul class="nav nav-pills nav-stacked" id="custom_menu">
								<li data-toggle="collapse" data-target="#custom" id="customheader"><a class="menuheads">Teaching</a></li>
								<ul id="custom" class="nav nav-pills nav-stacked collapse">

									<?php
									$values = json_decode($_COOKIE["cust_menu"], true);

									$str = "";
									foreach ($values as $sub) {
										$str .= "<li class=".$sub["c"]." data-branch=".strtolower($sub["d"])."><a>".$sub["c"]."</a></li>";
									}
									echo($str);
									?>
								</ul>
							</ul>

							<ul class="nav nav-pills nav-stacked" id="enggbranches">
								<li data-toggle="collapse" data-target="#it" id="itheader"><a class="menuheads">IT</a></li>
								<ul id="it" class="nav nav-pills nav-stacked collapse">
									<li id="D10" class="D10"><a>D10</a></li>
									<li id="D15" class="D15"><a>D15</a></li>
								</ul>
								<li data-toggle="collapse" data-target="#cmpn" id="cmpnheader"><a class="menuheads">CMPN</a></li>
								<ul id="cmpn" class="nav nav-pills nav-stacked collapse">
									<li id="D7A" class="D7A"><a>D7A</a></li>
									<li id="D7B" class="D7B"><a>D7B</a></li>
								</ul>
								<li data-toggle="collapse" data-target="#extc" id="extcheader"><a class="menuheads">EXTC</a></li>
								<ul id="extc" class="nav nav-pills nav-stacked collapse">
									<li id="D9A" class="D9A"><a>D9A</a></li>
									<li id="D9B" class="D9B"><a>D9B</a></li>
								</ul>
							</ul>
						<?php endif ?>

						<?php if(isset($_COOKIE["admin"])): ?>
							<li><a class="menuheads" href="admin_login.php">Admin</a></li>
						<?php endif ?>

					</ul>


				</div>
			</div>
		</div>
	</div>
	<div id="loadinggif">
		<img src="../imgs/loading.gif" alt="Loading"/>
	</div>
	<script type="text/javascript">
		$("#loadinggif").hide();
	</script>
	
	<div id="content" class="col-sm-10">
		<?php if(isset($_COOKIE["uid"])): ?>
			<div id = "greeting_div">
				<p id="greet">Welcome <?= $_COOKIE["name"] ?></p>
				<a href="logout.php"><button id="logout" class="btn btn-primary" style="float:right">Logout</button></a>
				<br/>
			</div>
		<?php endif ?>