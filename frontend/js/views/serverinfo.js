$(document).ready(function() {
	$(".mws-datatable-fn").dataTable({sPaginationType: "full_numbers"});
	
	// Begin the Ajax Request for server status
	$.ajax({
		type: "POST",
		url: '?task=serverinfo',
		data: { action : 'status' },
		dataType: "json",
		timeout: 10000, // in milliseconds
		success: function(result) 
		{
			// Create our message!
			$.each( result.data, function(k, v){
				$('#status_' + k).html( v );
			});
		}
	});
});