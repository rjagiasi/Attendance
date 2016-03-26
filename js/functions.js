function failuremessage (output) {
			$(".alert").show();
			$(".alert").removeClass("alert-success");
			$(".alert").addClass("alert-danger");
			$(".alert").html("<strong>"+output+"</strong>");
			$('.alert').delay(5000).fadeOut('slow');
		}

		function successmessage (output) {
			$(".alert").show();
			$(".alert").removeClass("alert-danger");
			$(".alert").addClass("alert-success");
			$(".alert").html("<strong>" + output + "</strong>");
			$('.alert').delay(5000).fadeOut('slow');
		}