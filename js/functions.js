function failuremessage (output) {
	$("#response").show();
	$("#response").removeClass("alert-success");
	$("#response").addClass("alert-danger");
	$("#response").html("<strong>"+output+"</strong>");
	$('#response').delay(5000).fadeOut('slow');
}

function successmessage (output) {
	$("#response").show();
	$("#response").removeClass("alert-danger");
	$("#response").addClass("alert-success");
	$("#response").html("<strong>" + output + "</strong>");
	$('#response').delay(5000).fadeOut('slow');
}

function ajaxcall (filename, datain, donefun) {
	$("#loadinggif").show();

	$.ajax({
		url: filename,
		type: 'POST',
		dataType: 'json',
		data: datain,
	})
	.done(function(data) {
		if(data == "refresh")
			location.reload();
		else
			donefun(data);

		$("#loadinggif").hide();
	});
	
}