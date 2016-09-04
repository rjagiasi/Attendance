
		
		<script type="text/javascript">
		$(document).ready(function() {
		
			$("#enggbranches ul").find("a").click(function(event) {
				var classid = $(event.target).parent().attr("id");
				var branchid = $(event.target).parent().parent().attr("id");
				
				var url="staff.php?branch="+branchid+"&class="+classid;
				$(location).attr("href", url);
			});

			$("#menu ul li").first().toggleClass('active', true);
		});
		</script>

		
				<?php if(!isset($_COOKIE["uid"])): ?>
				<div id="login_form">
					<form class = "form-horizontal" action = "../php/login.php" method = "POST">
						<label class="control-label col-sm-3" for="username">Username : </label>
						<div class="col-sm-5">
							<input name="username" id="username" class="form-control" placeholder="Username" type = "text" required/>
						</div>

						<br/><br/><br/>

						<label class="control-label col-sm-3" for="password">Password : </label>
						<div class="col-sm-5">
							<input name="password" id="password" class="form-control" placeholder="Password" type = "password" required/>
						</div>

						<button class = "btn btn-primary">Submit</button>
					</form>

					<a data-toggle="modal" id="modal_anchor" data-target="#forgot_pass_modal">Forgot Passsword?</a>
					<br/><br/><br/>
					<a id="register_anchor" href="register_page.php">New User? Register Here</a>

					<div class="modal fade" id="forgot_pass_modal" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Forgot Password</h4>
								</div>
								<div class="modal-body">
									<form class="form-inline" id="fp">
										<input id="fp_username" name="fp_username" class="form-control" autofocus placeholder="Username" type = "text" required>
										<button type="submit" class="btn btn-primary">Submit</button>
										<img id="fploading" src="../imgs/fploading.gif" alt="Loading" style="height:20px; width:20px;"/>
									</form>
								</div>
								<div class="modal-footer" style="text-align:center;">
									<p class="alert-danger" id="err"></p>
									<p>Email with new password will be sent to your registered mail id</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<script type="text/javascript">
				
				$(document).ready(function() {
					$("#fploading").hide();

					$("#fp").submit(function(event) {
						event.preventDefault();

						$("#fploading").show();
						$("#fp .btn").hide();
						$.ajax({
							url : "resetpass.php",
							type : "POST",
							data : $("#fp").serialize(),
							dataType : "json",
						})
						.done(function (data) {
							if(data == "false")
								$("#err").html("No such Username");
							else
							{
								$("#forgot_pass_modal").modal("toggle");
								if(data == "1")
									successmessage("Password has been reset. Check your mail!");
								else if(data == "0")
									failuremessage("Some Error Occured!");
							}
							$("#fploading").hide();
							$("#fp .btn").show();
						});
						
					});
				});
				
				</script>
			<?php else:?>

			<div class="alert alert-danger" id="notification">
				<strong>Danger!</strong> Indicates a dangerous or potentially negative action. ydasgykudgyuagsdfykugsykuagkuadgeykwaeyu
			</div>

		<?php endif ?>
				
		
		