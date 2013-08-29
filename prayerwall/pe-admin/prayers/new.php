<?php /* Prayer Engine - Administrative User Registration Form */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../includes/markdown.php';
	require_once '../../' . DATABASE;
	require_once '../../' . PRAYER_VALIDATION; // Validate that they're logged in

	if ($_POST) { /* IF FORM SUBMITTED */
		$errors = array(); //Set up errors array
		
		//Get all info from the form and validate some of it
		$name = strip_tags($_POST['name']);
		
		if (empty($_POST['email'])) { //validate presence and format of email
			$errors[] = 'An email address is required to submit a prayer request.';
		} else {
			if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['email'])) { 
				$email = $_POST['email'];
			} else {
				$errors[] = 'A valid email address is required.';
			};
		};
		
		$phone = strip_tags($_POST['phone']);
		$desiredshareoption = strip_tags($_POST['share_option']);
		$shareoption = strip_tags($_POST['share_option']);
		
		if (empty($_POST['posted_prayer'])) { //validate presence and length of prayer
			$errors[] = 'Please enter a prayer request.';
		} else {
			if (preg_match('/(href=)/', $_POST['posted_prayer']) || preg_match('/(HREF=)/', $_POST['posted_prayer'])) { // Simple check for spam hyperlinks
				$errors[] = 'Sorry, no HTML is allowed in the prayer request.';
			} else {
				$prayer = strip_tags($_POST['posted_prayer']);
				$posted_prayer = strip_tags($_POST['posted_prayer']);
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
		
		if (isset($_POST['twitter_ok'])) { //validate prayer tweet
			if (!empty($_POST['prayer_tweet'])) { 
				if (preg_match('/(href=)/', $_POST['prayer_tweet']) || preg_match('/(HREF=)/', $_POST['prayer_tweet'])) { // Simple check for spam hyperlinks
					$errors[] = 'Sorry, no HTML is allowed in the prayer tweet.';
				} else {
					$prayertweet = strip_tags(substr($_POST['prayer_tweet'],0,140));
				}
			} else {
				$errors[] = 'Please enter a prayer tweet.';
			};
		} else {
			$prayertweet = $_POST['prayer_tweet'];
		}
		
		$datereceived = $_POST['date_received'];
		$prayercount = $_POST['prayer_count'];
		$brandnew = $_POST['brand_new'];
		$moderated_by = $_POST['moderated_by'];
		$postthis = $_POST['post_this'];
		$dc = md5(uniqid(rand(), true));
		
		if (empty($errors)) { /* IF THERE AREN'T ANY ERRORS, WRITE IT TO THE DB */
			//Submit Prayer Request
			$sql = "INSERT INTO prayers(name, email, phone, desired_share_option, share_option, prayer, posted_prayer, date_received, prayer_count, brand_new, post_this, notifyme, twitter_ok, prayer_tweet, moderated_by, delete_code) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$createprayer = $dbh->prepare($sql);
			$createprayer->execute(array($name, $email, $phone, $desiredshareoption, $shareoption, $prayer, $posted_prayer, $datereceived, $prayercount, $brandnew, $postthis, $notifyme, $twitterok, $prayertweet, $moderated_by, $dc));
			
			// Send 'Request Received' email with delete link
			include '../../includes/prayer_received_email.php';
			// Finish the page:
			header("Location: index.php?created=yes");
			exit;
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - Add a New Prayer Request</title>
	<script src="../../javascripts/prayerengine/jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#name').focus();
		});
	</script>
	<link rel="stylesheet" href="../../stylesheets/prayerengine/pe_backend.css" type="text/css" />
	<!-- Display fixes for Internet Explorer -->
		<!--[if lte IE 6]>
		<link href="../../stylesheets/prayerengine/backend_ie6_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 7]>
		<link href="../../stylesheets/prayerengine/backend_ie7_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 8]>
		<link href="../../stylesheets/prayerengine/backend_ie8_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
	<!-- end display fixes for Internet Explorer -->
</head>
<body id="prayer">
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<h2>Add a New Prayer Request</h2>
			<p class="instructions">You may add a new prayer request by using the form below. <strong><em>This prayer request will immediately be posted live to the prayer wall unless you choose "Do Not Share This."</em></strong></p>
		
			<?php require_once '../includes/errorbox.php'; //Errors ?>
			<?php require_once '../includes/messagebox.php'; //Messages ?>
			<form action="new.php" method="post" class="edit-form-table">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="label-cell"><label for="name">Name:</label></td>
						<td><input type="text" name="name" value="<?php if ($_POST && !empty($errors)) {echo $_POST['name'];} ?>" id="name" tabindex="1" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="email">Email:</label></td>
						<td><input type="text" name="email" value="<?php if ($_POST && !empty($errors)) {echo $_POST['email'];} ?>" id="email" tabindex="2" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="email">Phone:</label></td>
						<td><input type="text" name="phone" value="<?php if ($_POST && !empty($errors)) {echo $_POST['phone'];} ?>" id="phone" tabindex="3" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="share_option">Sharing:</label></td>
						<td>
							<select name="share_option" id="share_option" tabindex="4">
								<option value="Share Online" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "Share Online") { ?>selected="selected"<?php }} ?>>Post Online</option>
								<option value="Share Online Anonymously" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "Share Online Anonymously") { ?>selected="selected"<?php }} ?>>Post Online Anonymously</option>
								<option value="DO NOT Share Online" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "DO NOT Share Online") { ?>selected="selected"<?php }} ?>>DO NOT Post Online</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="label-cell"><label for="posted_prayer">Prayer Request:</label></td>
						<td>
							<textarea name="posted_prayer" id="posted_prayer" rows="8" cols="40" tabindex="5"><?php if ($_POST && !empty($errors)) {echo $_POST['posted_prayer'];} ?></textarea><br />
						</td>
					</tr>
				</table>
				
				<?php if (TWITTER == 'yes') { ?>
				<div id="twitter-area" <?php if ($_POST && !empty($errors)) {if (isset($_POST['twitter_ok'])) {echo 'style="display:block"';}} ?>>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td class="label-cell"><label for="prayer_tweet">Prayer Tweet:</label></td>
							<td>
								<textarea name="prayer_tweet" id="prayer_tweet" rows="8" cols="40"><?php if ($_POST && !empty($errors)) {echo $_POST['prayer_tweet'];} ?></textarea>
								<p class="twitter-counter"><span id="counter"></span>&nbsp;characters remaining.</p>
							</td>
						</tr>
					</table>
				</div>
				<?php } ?>
			
				<div id="pe-submit-area">
					<input name="notifyme" type="checkbox" value="1" id="notifyme" <?php if ($_POST && !empty($errors)) {if (isset($_POST['notifyme'])) {echo 'checked="checked"';}} ?> class="check" /> <label for="notifyme">Email When Someone Prays For Them</label>
					<?php if (TWITTER == 'yes') { ?><input name="twitter_ok" type="checkbox" value="1" id="twitter_ok" <?php if ($_POST && !empty($errors)) {if (isset($_POST['twitter_ok'])) {echo 'checked="checked"';}} ?> class="check" /> <label for="twitter_ok">Share This on Twitter</label><?php } ?>
					<input type="hidden" name="post_this" value="1" id="post_this" />
					<input type="hidden" name="brand_new" value="0" id="brand_new" />
					<input type="hidden" name="date_received" value="<?php echo date("Y-m-d H:i:s", time()) ?>" id="date_received" />
					<input type="hidden" name="prayer_count" value="0" id="prayer_count" />
					<input type="hidden" name="moderated_by" value="<?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?>" id="moderated_by" />
					<input type="submit" value="Add Request" class="submit" />
				</div>
			</form>			
			<a href="index.php<?php if (isset($_GET['c']) && isset($_GET['p'])){echo '?c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>" class="back-link"><strong>&laquo;</strong> Return to List</a>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
	<?php if (TWITTER == 'yes') { ?>
		<script type="text/javascript" src="../../javascripts/prayerengine/jquery.limit.js"></script>
		<script type="text/javascript" src="../../javascripts/prayerengine/prayertweet.js"></script>
	<?php } ?>
	<?php if ($_POST && !empty($errors) && isset($_POST['twitter_ok'])) { // Show prayer tweet if POST and checkbox selected  ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#twitter-area').show();
			});
		</script>
	<?php } else { // Hide prayer tweet and replace text if not selected ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#twitter-area').hide();
				$('#prayer_tweet').focus(function() { // Replace instructions with current prayer request on click.
					var prayer = $('#posted_prayer').attr('value');
					$(this).val(prayer);
					$(this).unbind();
				});
			});
		</script>
	<?php } ?>
</body>
</html>