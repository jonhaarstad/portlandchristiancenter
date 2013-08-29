/* Prayer Engine - This deletes a user in the Prayer Engine Backend */
$(document).ready(function(){
	$('.deletelink').click(function() {
		var id = $(this).attr("name");
		if (id == 1) {
			alert("Sorry, you can't delete the main administrator. Feel free to change the password, though.");
		} else {
			var answer = confirm("Are you SURE you want to delete this user? Click 'OK' to continue.");
			if (answer){
				$.post("delete_user.php", {id: id}, function() {
					$('#row'+id).fadeOut("slow");
				});
			};	
		};
	});
});