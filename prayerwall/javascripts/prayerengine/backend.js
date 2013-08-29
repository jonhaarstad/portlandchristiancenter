$(document).ready(function(){ /* Prayer Engine - JavaScript used in the backend */
	$('.twitter-counter').show();
	
	if ($.browser.mozilla) { // Get rid of dotted links in Firefox
		$('head').append('<style type=\"text/css\">:-moz-any-link:focus { outline: none; }</style>');
	};
	
	$("#twitter_ok").click(function () { //If enabled, toggle the Prayer Tweet field
		$("#twitter-area").slideToggle();
	});
	
	$('#messages').fadeTo(5000, 1, function () {
		$('#messages').slideUp();
	});
});