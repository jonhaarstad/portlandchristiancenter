<?php

require 'src/facebook.php';
require_once('php/simplepie.inc');

// Create our Application instance.
$facebook = new Facebook(array(
  'appId'  => '115718891810176',
  'secret' => 'a371229f906ab0ff0e5920f8624b0882',
  'cookie' => true,
));

// We may or may not have this data based on a $_GET or $_COOKIE based session.
//
// If we get a session here, it means we found a correctly signed session using
// the Application Secret only Facebook and the Application know. We dont know
// if it is still valid until we make an API call using the session. A session
// can become invalid if it has already expired (should not be getting the
// session back in this case) or if the user logged out of Facebook.
$session = $facebook->getSession();

$me = null;
// Session based API call.
if ($session) {
  try {
    $uid = $facebook->getUser();
    $me = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}

// login or logout url will be needed depending on current user state.
if ($me) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');

// This is the RSS Feed Elements related to SimplePie
$feed = new SimplePie();
$feed->set_feed_url('http://feeds.feedburner.com/PCCFeed/');
$feed->init();
$feed->handle_content_type();

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>PCC News : Facebook App</title>
<style type="text/css">


body {
	margin:0;
	padding:0;
	margin-top: 0;
	font-family: 'Lucida Grande', 'Myriad Pro', Helvetica, Arial, sans-serif;
}
p {
	font-size: 11px;
}

#container {
	margin: 0;
	padding: 0;
	width:520px;
	height: 100%;
	background: url(http://www.pcctoday.com/_fb/i/containter_back.gif) top left repeat-y #ffffff;
	text-align: left;
	clear: both;
}
#header {
	display: block;
	height: 141px;
	width: 511px;
	background: url(http://pcctoday.com/_fb/header_bck.jpg) top left repeat-x;
}
#twitter {
	display: block;
	position: relative;
	float: right;
	right:0px;
	top:0;
	height:111px;
	width:189px;
	background: url(http://pcctoday.com/_fb/i/twitterback.gif) top left repeat-x;
	margin-right: -9px;
	padding: 0;
	font-size: 10px;
	z-index: 10;
	clear: both;
}
#twitter > p {
	padding: 5px 15px 15px 15px;
	margin: 0;
}
#leftCol {
	display: block;
	width: 332px;
	float: left;
}
#rightCol {
	display: block;
	width: 180px;
	float: left;
}
#times {
	display: block;
	background: #39579a;
	margin-top: 0;
	padding: 6px 15px;
	margin-right: 1px;
}
#times > p {
	color: #ffffff;
}
#green_header {
	background: url(http://pcctoday.com/_fb/i/header_back.gif) repeat-x;
	height: 59px;
	width: 520px;
}
#articles {
	width: 520px;
	height: 300px;
	background: url(http://pcctoday.com/_fb/i/greenhdr_corner.png) top right no-repeat;
}
#footer {
	margin: 0;
	padding: 0;
	display: block;
	width:711px;
	background: url(http://pcctoday.com/_fb/background_bottom.png) bottom left no-repeat;
	text-align: left;
	clear: both;
	height: 62px;
}

img {
	margin: 0;
	padding: 0;
}


.PotW {
	margin-bottom: -1px;
}
.welcomeBlock {
	padding:10px 24px;
}
.welcomeBlock p {
	line-height: 150%;
}
.welcomeBlock > img {
	padding-top: 15px;
}
.imgPadMed {
	margin-bottom: 13px;
}
.imgPadSmall {
	margin-bottom: -1px;
}
.logo {
	padding-top: 30px;
	padding-left: 24px;
}
.fbsubscribelink {
	display: none;
}
#creditfooter {
	display: none;
}

#twitter_foll {
	background: url(http://pcctoday.com/_fb/i/twitter_shadow.png) top right no-repeat;
	display: block;
	height: 20px;
	position: relative;
	top: 4px;
	right: -8px;
}
.twitter_follow {
	float: right;
	padding-top: 0;
	padding-right: 20px;
	z-index: 0;
	clear: both;
}
.twitter_icon {
	padding-top: 13px;
	padding-left: 15px;
}
.sup {
	padding: 10px 0 0 24px;
}

/* FEED CUSTOMIZATION */

.feedTitle {
	display: none;
}
.date {
	display: none;
}
#rightCol ul {
	list-style: none;
	margin-left: 0;
	padding-left: 0;
	padding-right: 0;
}
#rightCol ul li {
	background: url(http://pcctoday.com/_fb/bottom_line.gif) bottom 10px no-repeat;
	padding-bottom: 10px;
	margin-bottom: 20px;
}
#rightCol ul li div {
	padding-left: 25px;
	padding-right: 25px;
	margin-top: 10px;
	margin-bottom: 10px;
	font-family: 'Lucida Grande', 'Myriad Pro', Helvetica, Arial, sans-serif;
	font-size: 11px;
	line-height: 150%;
}
#rightCol img {
	margin: 0;
	padding: 0;
}
.headline a {
	text-decoration: none;
	color: #6f1200;
	font-size: 16px;
	font-weight: bold;
	margin-left: 25px;
}
.headline a:hover {
	text-decoration: underline;
}
#rightCol p {
	line-height: 150%;
}
.clr {
	clear: both;
}

/*  UPDATED FEED CUSTOMIZATION */

.item {
	background-image: url(http://pcctoday.com/_fb/bottom_line.gif);
	background-position: bottom 189px;
	background-repeat: no-repeat;
	padding-bottom: 30px;
	margin-bottom: 5px;
}
.item h2 {
	margin-bottom: 0;
	margin-top: 0;
	margin-left: 25px;
	margin-right: 20px;
	padding-top: 20px;
	padding-bottom: 8px;
	line-height: 90%;
}
.item h2 a {
	text-decoration: none;
	color: #6f1200;
	font-size: 18px;
	font-weight: bold;
	line-height: 100%;
}
.item p {
	padding-left: 25px;
	padding-right: 25px;
	margin-top: 0;
	margin-bottom: 10px;
	font-family: 'Lucida Grande', 'Myriad Pro', Helvetica, Arial, sans-serif;
	font-size: 11px;
	line-height: 150%;
}
.item li {
	padding-left: 25px;
}
.readmore {
	float: right;
	display: block;
	height: 28px;
	width: 74px;
	margin-top: -13px;
}



</style>

  </head>
    <!--
      We use the JS SDK to provide a richer user experience. For more info,
      look here: http://github.com/facebook/connect-js
    -->
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId   : '<?php echo $facebook->getAppId(); ?>',
          session : <?php echo json_encode($session); ?>, // don't refetch the session when PHP already has it
          status  : true, // check login status
          cookie  : true, // enable cookies to allow the server to access the session
          xfbml   : true // parse XFBML
        });

        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.login', function() {
          window.location.reload();
        });
      };

      (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>

<div align="center">
<div id="container">
	<div id="header">
		<img src="http://pcctoday.com/_fb/logo.png" class="logo" />
		<div id="twitter">
		<img src="http://pcctoday.com/_fb/i/twitter_icon.gif" border="0" class="twitter_icon" />
		<p><?php include('twitter-rss-to-html.php'); ?></p></div>
		<div id="twitter_foll">
		<a href="http://www.twitter.com/pcctoday"><img src="http://pcctoday.com/_fb/Twitter_follow.png" border="0" class="twitter_follow" /></a></div>
	</div>
	<div id="leftCol">
		<div class="welcomeBlock">
			<img src="http://pcctoday.com/_fb/i/welcome.gif" border="0" />
			<p>Thank you for visiting us here on Facebook.  We are a church in Portland Oregon that is committed to "Bridging People to Christ". For more information, join us over at <a href="http://www.pcctoday.com">PCCToday.com</a>.</p>
		</div>
		<!-- <a href="#"><img src="http://pcctoday.com/_fb/Fan.gif" border="0" class="imgPadMed" /></a> -->
	</div>
	<div id="rightCol">
		<div id="times">
			<p><strong>SUNDAYS</strong><br />
			9:00am - Learning Hour<br />
			10:15am - Main Service</p>
			<p><strong>WEDNESDAYS</strong><br />
			7:00pm - Classes</p>
		</div>
	</div>
	
<div class="clr"></div>

	<div id="leftCol">
		<img src="http://pcctoday.com/_fb/i/img_2010_9_24.jpg" border="0" />	
	</div>
	<div id="rightCol"><a href="http://www.pcctoday.com/worship/"><img src="http://pcctoday.com/_fb/i/ad_1.jpg" border="0" align="right" /></a><a href="http://www.raynoah.com"><img src="http://pcctoday.com/_fb/i/ad_2.jpg" border="0" align="right" /></a></div>
	
<div class="clr"></div>

	<div id="full_width">
		<div id="green_header"><img src="http://pcctoday.com/_fb/i/whatshappening.png" border="0" class="sup" /></div>
		<div id="articles">
		<?php
		$max = $feed->get_item_quantity(5);
		for ($x = 0; $x < $max; $x++):
			$item = $feed->get_item($x);
			?>
			
			<div class="item">
				<h2><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h2>
				<?php 
					echo substr($item->get_description(), 0, 220);
					$str = $item->get_description();
					if (strlen($str) > 220) echo '...'; ?>
				<p><a href="<?php echo $item->get_permalink(); ?>"><img src="http://pcctoday.com/_fb/i/ReadMore_btn.png" border="0" class="readmore" /></a></p>
			</div>
		<?php endfor; ?>
		</div>
		<div class="clr"></div>
		<p>&nbsp;</p>
		<a href="http://www.pcctoday.com"><img src="http://pcctoday.com/_fb/i/footer.png" border="0" /></a>
	</div>
		
<!-- <script language="JavaScript" src="http://feeds.feedburner.com/PCCFeed?format=sigpro&excerptLength=30" type="text/javascript"></script> -->

</div>
</div>
</html>

