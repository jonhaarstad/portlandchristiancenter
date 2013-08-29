<?php /*  Prayer Engine - Mobile Submit Prayer Request Form */

require_once '../config/subdirectories.php';
require_once '../gdphp/config/engineconfig.php';

if ($_POST) { /* IF FORM SUBMITTED */
	require_once '../' . DATABASE;
	$errors = array(); //Set up errors array
	
	//Get all info from the form and validate some of it
	$name = strip_tags($_POST['name']);
	
	if (empty($_POST['email'])) { //validate presence and format of email
		$errors[] = 'An email address is required to submit a prayer request.';
	} else {
		if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['email'])) { 
			$email = $_POST['email'];
		} else {
			$errors[] = 'You must provide a valid email address.';
		};
	};
	
	$phone = strip_tags($_POST['phone']);
	$shareoption = strip_tags($_POST['desired_share_option']);
	
	if (empty($_POST['prayer'])) { //validate presence and length of prayer
		$errors[] = 'Please enter a prayer request.';
	} else {
		if (preg_match('/(href=)/', $_POST['prayer']) || preg_match('/(HREF=)/', $_POST['prayer'])) { // Simple check for spam hyperlinks
			$errors[] = 'Sorry, no HTML is allowed in your prayer request.';
		} else {
			$prayer = strip_tags($_POST['prayer']);
		}
	};
	
	if (isset($_POST['notifyme'])) { //email notification
		$notifyme = $_POST['notifyme'];
	} else {
		$notifyme = 0;
	}
	
	if (isset($_POST['twitter_ok'])) { //add to twitter prayer feed
		$twitterok = $_POST['twitter_ok'];
	} else {
		$twitterok = 0;
	}
	
	$prayertweet = strip_tags(substr($_POST['prayer_tweet'],0,140));
	
	$datereceived = $_POST['date_received'];
	$prayercount = $_POST['prayer_count'];
	$brandnew = $_POST['brand_new'];
	$postthis = $_POST['post_this'];
	$dc = md5(uniqid(rand(), true));
	
	if (empty($errors)) { /* IF THERE AREN'T ANY ERRORS, WRITE IT TO THE DB */
		//Submit Prayer Request
		$sql = "INSERT INTO prayers(name, email, phone, desired_share_option, prayer, date_received, prayer_count, brand_new, post_this, notifyme, twitter_ok, prayer_tweet, delete_code) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$createprayer = $dbh->prepare($sql);
		$createprayer->execute(array($name, $email, $phone, $shareoption, $prayer, $datereceived, $prayercount, $brandnew, $postthis, $notifyme, $twitterok, $prayertweet, $dc));
		
		// Send 'Request Received' email with delete link
		include '../includes/prayer_received_email.php';
		//Send emails to specific admins if enabled
		include '../includes/email_notification.php';	
	} 
}

?>
	<?php if (TWITTER == 'yes') { ?><script type="text/javascript" src="../javascripts/prayerengine/jquery.limit.js" ></script><?php } ?> 
	<script type="text/javascript" src="../javascripts/prayerengine/mobile_prayer_form.js" ></script> 
<div class="prayer-header">
	<h2>Submit Your Prayer Request</h2>
</div>

<div id="prayer-form">

	<form action="<?php echo BASE_URL ?>mobile/submit_prayer_request.php" method="post" id="send-prayer-request">
		<div class="prayer-submit-table">
			<label for="name">Your Name:</label> 
			<input id="name" name="name" tabindex="1" type="text" tabindex="1">
	
			<label for="email">Your Email:</label>
			<input id="email" name="email" tabindex="2" type="text" tabindex="2">
	
			<label for="phone">Your Phone Number:</label>
			<input id="phone" name="phone" tabindex="3" type="text" tabindex="3">

	
			<label for="desired_share_option">Can We Share This?:</label>
			<select id="desired_share_option" name="desired_share_option" tabindex="4">
				<option value="Share Online">Please Share This</option>
				<option value="Share Online Anonymously">Share Anonymously</option>
				<option value="DO NOT Share Online">Do Not Share This</option>
			</select>
	
			<label for="prayer">Your Prayer Request:</label>
			<textarea cols="40" id="prayer" name="prayer" rows="10" tabindex="5"></textarea>
			
			<label for="notifyme">Email When Someone Prays:</label>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><input type="radio" name="notifyme" value="1" class="radio" tabindex="6" /></td>
					<td class="radioanswer">Yes</td>
					<td><input type="radio" name="notifyme" value="0" class="radio" tabindex="7" /></td>
					<td class="radioanswer">No</td>
				</tr>
			</table>
			
			<?php if (TWITTER == 'yes') { ?>
			<label for="twitter_ok">Share This on Twitter?:</label>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><input type="radio" name="twitter_ok" value="1" class="radio" id="twitter_yes" tabindex="8" /></td>
					<td class="radioanswer">Yes</td>
					<td><input type="radio" name="twitter_ok" value="0" class="radio" id="twitter_no" tabindex="9" /></td>
					<td class="radioanswer">No</td>
				</tr>
			</table>
			
			<div id="twitter-area">
				<p class="twitter-counter"><span id="counter"></span> characters remaining.</p>
				<textarea cols="40" id="prayer_tweet" name="prayer_tweet" rows="10" tabindex="10">Please list your prayer request as you would like it to appear on our Twitter Prayer Chain.</textarea>
			</div>
			<?php } ?>
			
			<input type="hidden" name="date_received" value="<?php echo date("Y-m-d H:i:s", time()) ?>" id="date_received" />
			<input type="hidden" name="prayer_count" value="0" id="prayer_count" />
			<input type="hidden" name="brand_new" value="1" id="brand_new" />
			<input type="hidden" name="post_this" value="0" id="post_this" />


			<a href="#" class="prayer-form-submit" tabindex="11">Submit Request</a>
		</div>
	</form>
</div>
<?php $dbc = null; ?>