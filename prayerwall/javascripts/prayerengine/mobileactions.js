/* Prayer Engine - This JavaScript controls all AJAX calls in the mobile site */
$(document).ready(function(){
	windowsize=window.innerWidth;
	setTimeout(scrollTo,0,0,1);
	$("#selected-item").hide();
	
	$("#header .logo a").live("click",function(){ // Return to mobile home
		var a=$(this).attr("href");
		$("#site-container").load(a);
		return false
	});
	
	$(".content .form-link").live("click",function(){ // Load prayer form
		var a=$(this).attr("href");
		$("#selected-item .content").load(a,function(){
			$("#selected-item").show();
			$.scrollTo(0,0);
			$("#main-page").animate({left:"-"+windowsize},400)});
			return false
		});
	});
		
	$(".content ul li a.ajax-link").live("click",function(){ // Load prayer request details
		var a=$(this).attr("href");
		$(this).ajaxSend(function(){
			$(this).addClass("loading")
		});
		$(this).ajaxStop(function(){
			$(this).removeClass("loading");
			$(this).unbind("ajaxSend")
		});
		$("#selected-item .content").load(a,function(){
			$("#selected-item").show();$.scrollTo(0,0);
			$("#main-page").animate({left:"-"+windowsize},400)
		});
		return false
	});
		
	$("#selected-item a.back-link").live("click",function(){ // Go back to prayer listing
		$("#main-page").animate({left:"+="+windowsize},400,function(){
			$("#selected-item").hide()
		});
		return false
	});
		
	$("#prayed-for-area .prayer-link").live("click",function(){ // Pray for this
		var c=$("#prayerform").attr("action");
		var b=$("#prayerform").serialize();
		function a(){
			$("#prayed-for-area").load(c)
		}
		$.post(c,b,a);
		return false
	});
		
	$("#prayer-form .prayer-form-submit").live("click",function(){ // Submit new prayer request
		var isvalid = $("#send-prayer-request").valid();
		if (isvalid == true) {
			$(this).addClass("working");
			var a=$("#send-prayer-request").serialize();
			function b(){
				$.scrollTo(0,0);
				$("#selected-item .content").load("fresh_prayer_request.php")
			}
			$.post("submit_prayer_request.php",a,b);
			return false
		} else {
			$.scrollTo(0,0);
		};
	});
		
	$("#main-page .content a.moreprayers").live("click",function(){ // Load more prayers to list
		var a=$(this).attr("href");
		var d=$(this).attr("title");
		$(this).ajaxSend(function(){
			$(this).parent(".more-prayers-link").addClass("loading")
		});
		$("#main-page .content #more-prayers"+d).load(a,function(){});
		return false
	});

				
	