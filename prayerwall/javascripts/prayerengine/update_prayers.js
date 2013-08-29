/* Prayer Engine - This updates the prayer counter on individual prayer requests via AJAX */
$(document).ready(function() {
	$(".submitlink").click(function() {
		var prayercountform = $(this).parents("form").serialize();
		var x = $(this).siblings("input.id").attr("value");
		var y = Math.floor(Math.random()*1001);
		function loadrefreshednumber() {
			$('#count'+x).load('actions/update_prayer.php?id='+x+'&time='+y);
		};
		$.post('actions/update_prayer.php', prayercountform, loadrefreshednumber);
		return false; 
	});	
});