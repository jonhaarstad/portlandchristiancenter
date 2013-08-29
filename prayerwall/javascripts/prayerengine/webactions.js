/* Prayer Engine - This controls various AJAX interactions on the main prayer wall */
$(document).ready(function() {
	
	if ($.browser.mozilla) { // Get rid of dotted links in Firefox
		$('head').append('<style type=\"text/css\">:-moz-any-link:focus { outline: none; }</style>');
	};
	
	$("#prayerform").validate({ // Initial JS validation of form.
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
	
	$("#twitter_ok").click(function () { //If enabled, toggle the Prayer Tweet field
		$("#pe-twitter-area").slideToggle();
	});
});