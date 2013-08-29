/* Prayer Engine - This deletes a prayer request in the Prayer Engine admin */
$(document).ready(function(){
	$('.deletelink').click(function() {
		var answer = confirm("Are you SURE you want to delete this prayer request? Click 'OK' to continue deleting this prayer request.")
		if (answer){
			var id = $(this).attr("name");
			$.post("delete_prayer.php", {id: id}, function() {
				$('#row'+id).fadeOut("slow");
			});
		};
	});
});