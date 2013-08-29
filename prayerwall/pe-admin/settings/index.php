<?php /* Prayer Engine - Engine Settings in the Backend */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . SETTINGS_VALIDATION; // Validate that they're logged in
	
	if ($_POST) {
		$errors = array(); //Set up errors array
		$messages = array(); //Set up messages array
		
		$id = 1;
		
		$stylesheet = $_POST['stylesheet'];
		
		// Validate Prayers Displayed
		if (is_numeric($_POST['prayersdisplayed']) && (($_POST['prayersdisplayed'] > 0) && ($_POST['prayersdisplayed'] <= 100))) {
			$prayersdisplayed = round($_POST['prayersdisplayed']);
		} else {
			$errors[] = 'The number to display must be between 1 and 100 prayer requests';
		}
		
		//Validate presence and length of Ministry Name
		if ((strlen($_POST['ministryname']) < 30) && (strlen($_POST['ministryname']) > 1)) {
			$ministryname = $_POST['ministryname'];
		} else {
			$errors[] = 'Please enter a ministry name that is between 2 and 30 characters long';
		}
		
		$twitter = $_POST['twitter'];
		
		if ($_POST['twitter'] == 'yes') { // Twitter Title
			if (empty($_POST['twitter_title'])) {
				$errors[] = 'Please enter a title for your Twitter Feed';
			} else {
				$twitter_title = $_POST['twitter_title'];
			}
		} else {
			$twitter_title = $_POST['twitter_title'];
		}
		
		if ($_POST['twitter'] == 'yes') { // Twitter Description
			if (empty($_POST['twitter_description'])) {
				$errors[] = 'Please enter a description for your Twitter Feed';
			} else {
				$twitter_description = $_POST['twitter_description'];
			}
		} else {
			$twitter_description = $_POST['twitter_description'];
		}
		
		$rss_title = $_POST['rss_title'];
		$rss_description = $_POST['rss_description'];
		
		if (empty($_POST['email'])) { //validate presence and format of email
			$errors[] = 'An administrator\'s email address is required';
		} else {
			if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['email'])) { 
				$email = $_POST['email'];
			} else {
				$errors[] = 'Please provide a valid administrator email address';
			};
		};
		
		if (empty($_POST['robot_email'])) { //validate presence and format of email
			$errors[] = 'An email address is required for the notification robot';
		} else {
			if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['robot_email'])) { 
				$robot_email = $_POST['robot_email'];
			} else {
				$errors[] = 'Please provide a valid email address for the notification robot to use';
			};
		};
		
		$activation = $_POST['activation'];
		$notification = $_POST['notification'];
		$mobile_site = $_POST['mobile_site'];
		$login_timeout = $_POST['login_timeout'];
		
		if (empty($_POST['url'])) { //validate presence and format of email
			$errors[] = 'Please enter the URL for your prayer wall!';
		} else {
			$base_url = $_POST['url'];
		};

		$time_zone = $_POST['time_zone'];
		
		if (empty($errors)) {
			//update the settings
			$sql = "UPDATE settings SET prayersdisplayed=?, ministryname=?, twitter=?, twitter_title=?, twitter_description=?, rss_title=?, rss_description=?, email=?, robot_email=?, activation=?, notification=?, mobile_site=?, stylesheet=?, login_timeout=?, base_url=?, time_zone=? WHERE id=?";
			$updatesettings = $dbh->prepare($sql);
			$updatesettings->execute(array($prayersdisplayed, $ministryname, $twitter, $twitter_title, $twitter_description, $rss_title, $rss_description, $email, $robot_email, $activation, $notification, $mobile_site, $stylesheet, $login_timeout, $base_url, $time_zone, $id));
			$messages[] = "Prayer engine settings successfully updated!";

			//fill in form
			$findformsettings = "SELECT * FROM settings WHERE id = 1 LIMIT 1";
			$queryformsettings = $dbh->prepare($findformsettings);
			$queryformsettings->execute(); 
			$formsettings = $queryformsettings->fetch(PDO::FETCH_ASSOC);
		}
	} else {
		$findformsettings = "SELECT * FROM settings WHERE id = 1 LIMIT 1";
		$queryformsettings = $dbh->prepare($findformsettings);
		$queryformsettings->execute(); 
		$formsettings = $queryformsettings->fetch(PDO::FETCH_ASSOC);	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - Settings</title>
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
	<script src="../../javascripts/prayerengine/jquery.js" type="text/javascript"></script>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
</head>
<body id="settings">
	<?php include '../includes/settings_header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<h2>Prayer Engine Settings</h2>
			<p class="instructions">Edit the settings below to adjust the behaviors and functionality of the Prayer Engine. Settings can be updated at any time.</p>
			
			<?php require_once '../includes/errorbox.php'; //Errors ?>
			<?php require_once '../includes/messagebox.php'; //Messages ?>
			<form action="index.php" method="post" class="edit-form-table" id="settings-table">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="label-cell"><label for="prayersdisplayed">Prayer Wall Template:</label></td>
						<td>
							<table cellpadding="0" cellspacing="0" id="swatches">
								<tr>
									<td><input type="radio" name="stylesheet" value="color_scheme5.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme5.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme5.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cdcdcd; color: #cdcdcd">color</span><span style="background-color: #c9322b; color: #c9322b">color</span><span style="background-color: #e8e8e8; color: #e8e8e8">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="color_scheme3.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme3.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme3.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cdcdcd; color: #cdcdcd">color</span><span style="background-color: #71a113; color: #71a113">color</span><span style="background-color: #e8e8e8; color: #e8e8e8">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="color_scheme6.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme6.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme6.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cdcdcd; color: #cdcdcd">color</span><span style="background-color: #345ca7; color: #345ca7">color</span><span style="background-color: #e8e8e8; color: #e8e8e8">color</span></span></td>
								</tr>
								<tr>
									<td><input type="radio" name="stylesheet" value="color_scheme11.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme11.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme11.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cbc8c3; color: #cbc8c3">color</span><span style="background-color: #c25752; color: #c25752">color</span><span style="background-color: #f3efea; color: #f3efea">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="color_scheme9.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme9.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme9.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cbc8c3; color: #cbc8c3">color</span><span style="background-color: #89915f; color: #89915f">color</span><span style="background-color: #f3efea; color: #f3efea">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="color_scheme8.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme8.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme8.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cbc8c3; color: #cbc8c3">color</span><span style="background-color: #7c8aa0; color: #7c8aa0">color</span><span style="background-color: #f3efea; color: #f3efea">color</span></span></td>
								</tr>
								<tr>
									<td><input type="radio" name="stylesheet" value="color_scheme4.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme4.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme4.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cdcdcd; color: #cdcdcd">color</span><span style="background-color: #e17a00; color: #e17a00">color</span><span style="background-color: #e8e8e8; color: #e8e8e8">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="color_scheme2.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme2.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme2.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cdcdcd; color: #cdcdcd">color</span><span style="background-color: #10a4bd; color: #10a4bd">color</span><span style="background-color: #e8e8e8; color: #e8e8e8">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="pe_color_scheme.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'pe_color_scheme.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'pe_color_scheme.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #7e192b; color: #7e192b">color</span><span style="background-color: #94bfc6; color: #94bfc6">color</span><span style="background-color: #e8e8e8; color: #e8e8e8">color</span></span></td>
								</tr>
								<tr>
									<td><input type="radio" name="stylesheet" value="color_scheme10.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme10.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme10.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cbc8c3; color: #cbc8c3">color</span><span style="background-color: #d08d3f; color: #d08d3f">color</span><span style="background-color: #f3efea; color: #f3efea">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="color_scheme7.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'color_scheme7.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'color_scheme7.css') {echo 'checked="checked"';}} ?> /> <span class="color_swatch"><span style="background-color: #cbc8c3; color: #cbc8c3">color</span><span style="background-color: #71a0a8; color: #71a0a8">color</span><span style="background-color: #f3efea; color: #f3efea">color</span></span></td>
									<td><input type="radio" name="stylesheet" value="custom_color_scheme.css" class="radio" <?php if ($_POST && !empty($errors)) {if ($_POST['stylesheet'] == 'custom_color_scheme.css') {echo 'checked="checked"';}} else {if ($formsettings['stylesheet'] == 'custom_color_scheme.css') {echo 'checked="checked"';}} ?> /> <span class="color-label" title="Modify 'stylesheets/prayerengine/custom_color_scheme.css'">Custom</span></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="label-cell"><label for="prayersdisplayed">Display:</label></td>
						<td><input type="text" name="prayersdisplayed" value="<?php if ($_POST && !empty($errors)) {echo $_POST['prayersdisplayed'];} else {echo $formsettings['prayersdisplayed'];} ?>" id="prayersdisplayed" /> <span class="description">How Many Prayer Requests to Display at a Time</span></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="ministryname">Ministry Name:</label></td>
						<td><input type="text" name="ministryname" value="<?php if ($_POST && !empty($errors)) {echo $_POST['ministryname'];} else {echo $formsettings['ministryname'];} ?>" id="ministryname" /> </td>
					</tr>
					<tr>
						<td class="label-cell"><label for="twitter">Enable Twitter?</label></td>
						<td>
							<select name="twitter" id="twitter">
								<option value="yes" <?php if ($_POST && !empty($errors)) {if ($_POST['twitter'] == 'yes') {echo 'selected="selected"';}} else {if ($formsettings['twitter'] == 'yes') {echo 'selected="selected"';}} ?>>Yes</option>
								<option value="no" <?php if ($_POST && !empty($errors)) {if ($_POST['twitter'] == 'no') {echo 'selected="selected"';}} else {if ($formsettings['twitter'] == 'no') {echo 'selected="selected"';}} ?>>No</option>
							</select> <span class="description">Do you want to collect prayer tweets?</span>
						</td>
					</tr>
					<tr>
						<td class="label-cell"><label for="twitter_title">Twitter Title:</label></td>
						<td><input type="text" name="twitter_title" value="<?php if ($_POST && !empty($errors)) {echo $_POST['twitter_title'];} else {echo $formsettings['twitter_title'];} ?>" id="twitter_title" /> <span class="description">Title of <span class="info" title="<?php echo BASE_URL; ?>feeds/twitter.php">Feed</span> for Twitter</span></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="twitter_description">Twitter Feed Description:</label></td>
						<td>
							<textarea name="twitter_description" id="twitter_description" rows="8" cols="40"><?php if ($_POST && !empty($errors)) {echo $_POST['twitter_description'];} else {echo stripslashes($formsettings['twitter_description']);} ?></textarea>
						</td>
					</tr>
					<tr>
						<td class="label-cell"><label for="rss_title">RSS Title:</label></td>
						<td><input type="text" name="rss_title" value="<?php if ($_POST && !empty($errors)) {echo $_POST['rss_title'];} else {echo $formsettings['rss_title'];} ?>" id="rss_title" /> <span class="description">Title of <span class="info" title="<?php echo BASE_URL; ?>feeds/prayers.php">RSS Feed</span></span></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="rss_description">RSS Description:</label></td>
						<td>
							<textarea name="rss_description" id="rss_description" rows="8" cols="40"><?php if ($_POST && !empty($errors)) {echo $_POST['rss_description'];} else {echo stripslashes($formsettings['rss_description']);} ?></textarea>
						</td>				
					</tr>
					<tr>
						<td class="label-cell"><label for="email">Admin Email:</label></td>
						<td><input type="text" name="email" value="<?php if ($_POST && !empty($errors)) {echo $_POST['email'];} else {echo $formsettings['email'];} ?>" id="email" /> <span class="description">For User Questions</span></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="robot_email">Robot Email:</label></td>
						<td><input type="text" name="robot_email" value="<?php if ($_POST && !empty($errors)) {echo $_POST['robot_email'];} else {echo $formsettings['robot_email'];} ?>" id="robot_email" /> <span class="description">For Notifications</span></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="activation">Activation?</label></td>
						<td>
							<select name="activation">
								<option value="yes" <?php if ($_POST && !empty($errors)) {if ($_POST['activation'] == 'yes') {echo 'selected="selected"';}} else {if ($formsettings['activation'] == 'yes') {echo 'selected="selected"';}} ?>>Yes</option>
								<option value="no" <?php if ($_POST && !empty($errors)) {if ($_POST['activation'] == 'no') {echo 'selected="selected"';}} else {if ($formsettings['activation'] == 'no') {echo 'selected="selected"';}} ?>>No</option>
							</select> <span class="description">Require Email Activation for Admin Users?</span>
						</td>					
					</tr>
					<tr>
						<td class="label-cell"><label for="notification">Notifications?</label></td>
						<td>
							<select name="notification">
								<option value="yes" <?php if ($_POST && !empty($errors)) {if ($_POST['notification'] == 'yes') {echo 'selected="selected"';}} else {if ($formsettings['notification'] == 'yes') {echo 'selected="selected"';}} ?>>Yes</option>
								<option value="no" <?php if ($_POST && !empty($errors)) {if ($_POST['notification'] == 'no') {echo 'selected="selected"';}} else {if ($formsettings['notification'] == 'no') {echo 'selected="selected"';}} ?>>No</option>
							</select> <span class="description">Email Notification to Admins When Requests Received?</span>
						</td>					
					</tr>
					<tr>
						<td class="label-cell"><label for="mobile_site">Mobile Site?</label></td>
						<td>
							<select name="mobile_site">
								<option value="yes" <?php if ($_POST && !empty($errors)) {if ($_POST['mobile_site'] == 'yes') {echo 'selected="selected"';}} else {if ($formsettings['mobile_site'] == 'yes') {echo 'selected="selected"';}} ?>>Yes</option>
								<option value="no" <?php if ($_POST && !empty($errors)) {if ($_POST['mobile_site'] == 'no') {echo 'selected="selected"';}} else {if ($formsettings['mobile_site'] == 'no') {echo 'selected="selected"';}} ?>>No</option>
							</select> <span class="description">Enable Automatic Redirect to Mobile Site?</span>
						</td>					
					</tr>
					<tr>
						<td class="label-cell"><label for="login_timeout">Auto Logout:</label></td>
						<td>
							<select name="login_timeout">
								<option value="900" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '900') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '900') {echo 'selected="selected"';}} ?>>15 min</option>
								<option value="1800" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '1800') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '1800') {echo 'selected="selected"';}} ?>>30 min</option>
								<option value="2700" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '2700') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '2700') {echo 'selected="selected"';}} ?>>45 min</option>
								<option value="3600" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '3600') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '3600') {echo 'selected="selected"';}} ?>>1 hr</option>
								<option value="7200" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '7200') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '7200') {echo 'selected="selected"';}} ?>>2 hrs</option>
								<option value="10800" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '10800') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '10800') {echo 'selected="selected"';}} ?>>3 hrs</option>
								<option value="14400" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '14400') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '14400') {echo 'selected="selected"';}} ?>>4 hrs</option>
								<option value="18000" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '18000') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '18000') {echo 'selected="selected"';}} ?>>5 hrs</option>
								<option value="21600" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == '21600') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == '21600') {echo 'selected="selected"';}} ?>>6 hrs</option>
								<option value="never" <?php if ($_POST && !empty($errors)) {if ($_POST['login_timeout'] == 'never') {echo 'selected="selected"';}} else {if ($formsettings['login_timeout'] == 'never') {echo 'selected="selected"';}} ?>>Never</option>							
							</select> <span class="description">What Period of Inactivity Before Automatic Log Out?</span>
						</td>					
					</tr>
					<tr>
						<td class="label-cell"><label for="time_zone">Time Zone:</label></td>
						<td><input type="text" name="time_zone" value="<?php if ($_POST && !empty($errors)) {echo $_POST['time_zone'];} else {echo $formsettings['time_zone'];} ?>" id="time_zone" /> <span class="description">Find Your Time Zone <a href="http://www.php.net/manual/en/timezones.php" target="_blank">Here</a></span></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="url">Your URL:</label></td>
						<td><input type="text" name="url" value="<?php if ($_POST && !empty($errors)) {echo $_POST['url'];} else {echo $formsettings['base_url'];} ?>" id="url" /> <span class="description">Full Web Address</span> </td>
					</tr>
				</table>
				
				<div id="pe-submit-area">
					<input type="submit" value="Update Settings" class="submit" />
				</div>
			</form>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
</body>
</html>