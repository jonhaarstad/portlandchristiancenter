$(document).ready(function(){ /* Prayer Engine - Slide Up Login Success Messages */
	if($.browser.msie && $.browser.version <= 7) {}
	else {
		$('#messages').fadeTo(3000, 1, function () {
			$('#messages').slideUp();
		});
	};
});