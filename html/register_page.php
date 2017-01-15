<script type="text/javascript">
	$(document).ready(function() {

		//register form validation methods
		$.validator.addMethod("Confirm_username", function (value, element) {
			return /^[a-zA-Z0-9][a-zA-Z0-9_]{5,19}$/.test(value);
		},"Length between 6 & 20 (special character only _ allowed)");

		$.validator.addMethod("checkName", function(value, element) {
				if(value == "")
					return false;
				else
					return !/[\d]/.test(value);
			}
			, "Invalid Name");

		$.validator.addMethod("confirm_password", function (value, element) {
			return /(?=^.{6,255}$)((?=.*\d)(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[^A-Za-z0-9])(?=.*[a-z])|(?=.*[^A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9]))^.*/.test(value);
		}, "At least 1 upper case, lower case or special character, numerical character. Minlength 6");


		//register form validation and ajax call on submit
		$("#register_form").validate({
			// Specify the validation rules
			rules: {
				name: {
					checkName: true
				},
				gender: "required",
				username: {
					Confirm_username: true
				},
				pass: {
					confirm_password: true,
				},
				email: {
					required:true,
					email: true
				},
				cnfpass: {
					equalTo: "#pass",
				},
				
			},
			// Specify the validation error messages
			messages: {
				gender: "Please select gender",
				email: {
					required: "Please enter your email address",
					email: "Enter a valid Email!"
				},
				cnfpass: {
					equalTo: "Password and Confirmation doesn't match"
				}
				
			},

			submitHandler: function(form) {
				$("#loadinggif").show();
				$.ajax({
					url : "register.php",
					type : "POST",
					data : $("#register_form").serialize(),
					dataType: "json",
				})
				.done(function (data) {
					if(data == false)
						failuremessage("Username Taken or email already registered!");
					else if(data == true){
						successmessage("Registration Successful");
						
						setTimeout(function(){
							$(location).attr("href", "./index.php");
						}, 1000);
					}
					$("#loadinggif").hide();
				});
			}
		});

		//register form clear button
		$("#clear").click(function(event) {
			$("#register_form").find("input").val("");
		});
	});
</script>

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
		<button type="submit" class="btn btn-success">Submit</button>
		<button class="btn btn-danger inline" id="clear">Clear</button>
	</form>				
</div>
