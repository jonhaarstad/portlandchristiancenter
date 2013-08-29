/* Prayer Engine - This validates and controls the display of the submit prayer request form on the mobile site */
$(document).ready(function() {
	$("#send-prayer-request").validate({ //Make sure email and prayer request are there.
	  rules: {
	    email: {
	      required: true,
	      email: true
	    },
		prayer: {
	      required: true,
	      minlength: 10
	    }
	  }
	});
	
	$("#twitter_yes").click(function () { //If enabled, toggle the Prayer Tweet field
		$("#twitter-area").slideDown();
	});
	
	$("#twitter_no").click(function () { 
		$("#twitter-area").slideUp();
	});
	
	$('#prayer_tweet').limit('140','#counter'); //Counter for Prayer Tweet
	$('#prayer_tweet').focus(function() {
		var prayer = $('#prayer').attr('value');
		$(this).val(prayer);
		$(this).unbind();
	});
});