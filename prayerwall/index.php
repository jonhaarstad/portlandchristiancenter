<?php 
	require_once 'includes/prayerwall_guts.php'; // The guts of the Prayer Wall are in this file.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="favicon.ico" />
	
	<link rel="alternate" type="application/rss+xml" title="RSS Prayer Feed" href="<?php echo BASE_URL ?>feeds/prayers.php" /><!-- LINK TO PRAYER ENGINE RSS FEED -->
	<title>Prayer Requests</title>
<!-- FILES NEEDED FOR PRAYER ENGINE - EDIT AT YOUR OWN RISK! -->
	<script language="javascript" src="javascripts/prayerengine/jquery.js" type="text/javascript"></script> 
	<script language="javascript" src="javascripts/prayerengine/jquery.validate.js" type="text/javascript"></script> 
	<script language="javascript" src="javascripts/prayerengine/update_prayers.js" type="text/javascript"></script> 
	<script language="javascript" src="javascripts/prayerengine/webactions.js" type="text/javascript"></script>
	<?php include 'includes/form_state_options.php'; // Display certain parts of the form if posted ?>
	<link rel="stylesheet" href="stylesheets/prayerengine/pe_screen.css" type="text/css" /><!-- prayer engine styles -->
	<link rel="stylesheet" href="stylesheets/prayerengine/color_schemes/<?php echo $settings['stylesheet']; ?>" type="text/css" /><!-- prayer engine color scheme -->
	<!-- Display fixes for Internet Explorer -->
	<!--[if lte IE 6]>
	<link href="stylesheets/prayerengine/ie6_fix.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!--[if IE 7]>
	<link href="stylesheets/prayerengine/ie7_fix.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!--[if IE 8]>
	<link href="stylesheets/prayerengine/ie8_fix.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!-- end display fixes for Internet Explorer -->
<!-- END PRAYER ENGINE HEADER FILES -->
</head>
<body id="prayerengine"><!-- "prayerengine" body id must be present for correct styling -->
	
	
	
<!-- PRAYER ENGINE PRAYER WALL - EDIT AT YOUR OWN RISK! -->	
	<div id="pe-container">
		<!-- begin prayer request submit form -->
		<div id="pe-submit-container">
			<div id="pe-form-header">
				<a href="#" id="pe-share-button"><?php if ($_POST) { ?>Close Prayer Request Form<?php } else { ?>Share Your Prayer Request<?php } ?></a>
				<h2><?php if ($_POST) { ?>Prayer Request Form<?php } else { ?>Prayer Requests<?php } ?></h2>
			</div>
			
			<div id="pe-form-container" <?php if ($_POST) {echo 'style="display: block;"';} ?>>
				<?php if (!empty($errors)) { // PHP Errors ?>
				<div id="errors">
					<p>Your prayer request cannot be submitted due to the following errors...</p>
					<ul>
					<?php foreach ($errors as $error) {
						echo "<li>$error</li>";
					}; ?>	
					</ul>
				</div>
				<?php }; ?>
			
				<?php if ($_POST && empty($errors)) { // Success Message ?>
				<div id="success">
					<p>Thank you for submitting your prayer request. It is currently being moderated, and will then be shared according to your instructions. Feel free to submit another request by filling out the form again.</p>
				</div>
				<?php }; ?>
			
				<p class="instructions">You may add your prayer request to our prayer wall using the form below. Once your prayer request is received, we will share it according to your instructions. Feel free to submit as many prayer requests as you like!</p>
			
				<form action="index.php" method="post" id="prayerform">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="label"><label for="name">Your Name:</label></td>
							<td class="inputcell"><input type="text" name="name" value="<?php if ($_POST && !empty($errors)) {echo $_POST['name'];}; ?>" id="name" tabindex="1" /></td>
						</tr>
						<tr>
							<td class="label"><label for="email">Your Email:</label></td>
							<td class="inputcell"><input type="text" name="email" value="<?php if ($_POST && !empty($errors)) {echo $_POST['email'];}; ?>" id="email" tabindex="2" /></td>
						</tr>
						<tr>
							<td class="label"><label for="phone">Your Phone:</label></td>
							<td class="inputcell"><input type="text" name="phone" value="<?php if ($_POST && !empty($errors)) {echo $_POST['phone'];}; ?>" id="phone" tabindex="3" /></td>
						</tr>
						<tr>
							<td class="label"><label for="desired_share_option">Please...</label></td>
							<td>
								<select name="desired_share_option" id="desired_share_option" tabindex="4">
									<option value="Share Online" <?php if ($_POST && !empty($errors) && ($_POST['desired_share_option'] == "Share Online")) {?>selected="selected"<?php }; ?>>Share This</option>
									<option value="Share Online Anonymously" <?php if ($_POST && !empty($errors) && ($_POST['desired_share_option'] == "Share Online Anonymously")) {?>selected="selected"<?php }; ?>>Share This Anonymously</option>
									<option value="DO NOT Share Online" <?php if ($_POST && !empty($errors) && ($_POST['desired_share_option'] == "DO NOT Share Online")) {?>selected="selected"<?php }; ?>>DO NOT Share This</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label"><label for="prayer">Your Prayer Request:</label></td>
							<td class="prayercell">
								<textarea name="prayer" rows="10" cols="50" id="prayer" tabindex="5"><?php if ($_POST && !empty($errors)) {echo $_POST['prayer'];}; ?></textarea>
							</td>
						</tr>
					</table>
				
					<?php if (TWITTER == 'yes') { // Take Prayer Tweets if Enabled ?>
					<div id="pe-twitter-area">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td class="label"><label for="prayer_tweet">Prayer Tweet:</label></td>
								<td>
									<textarea name="prayer_tweet" rows="10" cols="50" id="prayer_tweet" tabindex="8"><?php if ($_POST && !empty($errors)) {echo $_POST['prayer_tweet'];} else { ?>Please list your prayer request as you would like it to appear on our Twitter Prayer Chain.<?php } ?></textarea>
									<p class="twitter-counter"><span id="counter">0</span>&nbsp;characters remaining.</p>
								</td>
							</tr>
						</table>
					</div>
					<?php } ?>

					<div id="pe-submit-area">
						<input name="notifyme" id="notifyme" type="checkbox" value="1" class="check" tabindex="6" <?php if ($_POST && !empty($errors) && isset($_POST['notifyme'])) {echo 'checked="checked"';} ?> /> <label for="notifyme" class="checkbox">Email When Someone Prays for Me</label>
						<?php if (TWITTER == 'yes') { // If Prayer Tweets enabled ?>
						<input name="twitter_ok" id="twitter_ok" type="checkbox" value="1" class="check" tabindex="7" <?php if ($_POST && !empty($errors) && isset($_POST['twitter_ok'])) {echo 'checked="checked"';} ?> /> <label for="twitter_ok" class="checkbox">Share This on Twitter</label>
						<?php } ?>
						<input type="hidden" name="date_received" value="<?php echo date("Y-m-d H:i:s", time()) ?>" id="date_received" />
						<input type="hidden" name="prayer_count" value="0" id="prayer_count" />
						<input type="hidden" name="brand_new" value="1" id="brand_new" />
						<input type="hidden" name="post_this" value="0" id="post_this" />
					
						<input type="submit" value="Submit Request" class="submit" tabindex="9" />
					</div>
				</form>
			</div>
		</div>
		<div class="ie6-spacer"></div>
		<!-- end prayer request submit form -->
	
	
		<!-- list of prayer requests -->
		<?php include 'includes/pagination.php'; //Paginate Prayer Requests ?>
		<div id="pe-prayer-list">
		<?php $rowcycle = 'odd'; // Alternate odd and even rows from database
			foreach ($prayers as $prayer) { 
				if ($rowcycle == 'odd') {
					$rowcycle = 'even';
				} else {
					$rowcycle = 'odd';	
				}
		?>
			<div class="pe-prayer-<?php echo $rowcycle; ?>" id="prayer<?php echo $prayer['id'] ?>">
				<div class="pe-count-area" id="count<?php echo $prayer['id'] ?>">
					<form action="actions/update_prayer.php" id="form<?php echo $prayer['id'] ?>" method="post">
						<a href="#" class="submitlink">I prayed for this</a>
						<?php if ($prayer['prayer_count'] == 1) { ?>
						<h4>Prayed for <?php echo number_format($prayer['prayer_count'], 0, '.', ','); ?> time.</h4>
						<?php } elseif ($prayer['prayer_count'] > 1) { ?>
						<h4>Prayed for <?php echo number_format($prayer['prayer_count'], 0, '.', ','); ?> times.</h4>
						<?php } else {} ?>
						<?php $newcount = $prayer['prayer_count'] + 1 ?>
				
						<input type="hidden" name="prayer_count" value="<?php echo $newcount ?>"  />
						<input type="hidden" name="id" value="<?php echo $prayer['id'] ?>" class="id" />
					</form>
				</div>
				
				<h3><?php if ($prayer['share_option'] == "Share Online") {echo stripslashes($prayer['name']);} else {echo "Anonymous";}; if (($prayer['share_option'] == "Share Online") && (strlen($prayer['name']) == 0)) {echo "Anonymous";} ?></h3>
				
				<?php echo Markdown(stripslashes($prayer['posted_prayer'])) ?>
				
				<h4>Received: <?php echo date('F j, Y', strtotime($prayer['date_received'])) ?></h4>
			</div>
		<?php } ?>
		</div>
	
		<?php include 'includes/pagination.php'; //Paginate Prayer Requests ?>
		
		<?php $dbc = null; // Close database connection ?>
		
		<!-- You may remove this logo if desired -->
		<h3 id="powered_by"><a href="http://www.theprayerengine.com" target="_blank">Powered by The Prayer Engine</a></h3>
		<!-- Please DO NOT remove this copyright, per the user license -->
		<p id="copyright">Prayer Wall &copy; <a href="http://www.theprayerengine.com" target="_blank">The Prayer Engine</a>. All rights reserved.</p>
		<!-- end list of prayer requests -->
	</div>
<!-- END PRAYER ENGINE PRAYER WALL  -->



</body>
</html>
