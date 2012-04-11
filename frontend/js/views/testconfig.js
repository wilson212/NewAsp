$(document).ready(function() {
	// Create our base loading modal
	Modal = $("#ajax-dialog").dialog({
		autoOpen: false, 
		title: "Processing System Tests", 
		modal: true, 
		width: "600",
		buttons: [{
			text: "Close Window", 
			click: function() {
				$( this ).dialog( "close" );
			}
		}]
	});
	
	// Hide our close window button from view unless needed
	Modal.parent().find(".ui-dialog-buttonset").hide();
	//Modal.parent().find(".ui-dialog-buttonset .ui-button-text:eq(0)").text("Close Window");

	// Bind the Test Button button to an action
    $("#test-config").click(function() {
		// Open the Modal Window
		Modal.dialog("option", {
			modal: true, 
			open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
			closeOnEscape: false, 
			draggable: false,
			resizable: false
		}).dialog("open");
		
		// Lock the button so we dont click again after errors
		$("#test-config").attr("disabled", true).attr('value', 'Please Refresh Window');
	
		// Begin the Ajax Request
		$.ajax({
            type: "POST",
            url: '?task=testconfig',
            data: { action : 'runtests' },
            dataType: "json",
            timeout: 30000, // in milliseconds
            success: function(result) 
            {
				var button = '<br /><br /><center><input id="refresh" type="button" class="mws-button blue" value="Refresh Window" onClick="window.location.reload();"/></center>';
				$('.mws-panel-content').html(result.html + button);
				Modal.dialog('close');
			},
			error: function(request, status, err) 
            {
                $('.mws-dialog-inner').html('<font color="red">There was an error testing the system. Please refresh the page and try again.</font>');
				Modal.parent().find(".ui-dialog-buttonset").show();
            }
		});
    });
});