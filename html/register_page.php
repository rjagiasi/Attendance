
			<div id="register_form_div">
				<label>Register a New Staff Member</label>
				<form id="register_form" class="form-horizontal" action="register.php" method="POST">
					<input name="name" type="text" class="form-control" placeholder="Enter name"/><br/>
					<label>Select Gender</label></br>
					<input type="radio" name="gender" value="1"> Male </input><br/>
					<input type="radio" name="gender" value="0"> Female </input><br/><br/>
					<input name="email" type="email" class="form-control" placeholder="Enter Email"/><br/>
					<input name="username" type="text" class="form-control" placeholder="Enter Username"/><br/>
					<input name="pass" id="pass" type="password" class="form-control" placeholder="Enter Password"/><br/>
					<input name="cnfpass" type="password" class="form-control" placeholder="Confirm Password"/><br/>
					<button type="submit" class="btn btn-default">Submit</button>
					<button class="btn btn-default inline" id="clear">Clear</button>
				</form>				
			</div>
