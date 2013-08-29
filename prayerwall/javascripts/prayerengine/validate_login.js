/* Prayer Engine - This validates login credentials on the backend via JavaScript */
$(document).ready(function() {
	$('#email').focus();
	$("#loginform").validate({
	  rules: {
	    email: {
	      required: true,
	      email: true
	    },
		pass: {
	      required: true
	    }
	  }
	});
});