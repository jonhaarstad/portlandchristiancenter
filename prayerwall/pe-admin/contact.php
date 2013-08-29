<?php /* Prayer Engine - Contact Form for Trouble Logging In */ 

	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	ob_start();
	
	if ($_POST) {
		require_once '../' . DATABASE;
	
		$errors = array(); //Set up errors array
		$messages = array(); //Set up errors array
	
		$issue = strip_tags($_POST['issue']);
	
		// Validate the email address:
		if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_POST['email'])) {
			$email = $_POST['email'];
		} else {
			$errors[] = "Please Enter a Valid Email Address";
		}
	
		// Validate the password:
		if (!empty($_POST['name'])) {
			$name = strip_tags($_POST['name']);
		} else {
			$errors[] = "Please Enter Your Name";
		}
	
		if (empty($errors)) { // If everything's OK.
			if ($issue == "password") {
				$body = $name . " has forgotten the password for their Prayer Engine account. Please contact them at " . $email . " with a new temporary password.\n\n";
				$body .= '(This is an automated email sent by the Prayer Engine robot.)';
				mail(EMAIL, 'Prayer Engine Account Help Needed', $body, 'From: ' . $email);
				$messages[] = "The Prayer Engine Administrator Has Been Notified";
			}
			
			if ($issue == "activation") {
				$body = $name . " (" . $email . ") has lost their activation email. Please login to the Prayer Engine and send them another one through the Manage Users section.\n\n";
				$body .= '(This is an automated email sent by the Prayer Engine robot.)';
				mail(EMAIL, 'Prayer Engine Account Help Needed', $body, 'From: ' . $email);
				$messages[] = "The Prayer Engine Administrator Has Been Notified";
			}
			
			if ($issue == "account") {
				$body = $name . " has requested a Prayer Engine account. You can set their email address as " . $email . ", and send them a temporary password.\n\n";
				$body .= '(This is an automated email sent by the Prayer Engine robot.)';
				mail(EMAIL, 'Prayer Engine Account Requested', $body, 'From: ' . $email);
				$messages[] = "The Prayer Engine Administrator Has Been Notified";
			}
		} 
	} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../favicon.ico" />
	<title>Please Log In</title>
	<link rel="stylesheet" href="../stylesheets/prayerengine/pe_backend.css" type="text/css" />
	<!-- Display fixes for Internet Explorer -->
		<!--[if lte IE 6]>
		<link href="../stylesheets/prayerengine/backend_ie6_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 7]>
		<link href="../stylesheets/prayerengine/backend_ie7_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 8]>
		<link href="../stylesheets/prayerengine/backend_ie8_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
	<!-- end display fixes for Internet Explorer -->
</head>

<body id="login">
	
	<div id="login-area">
		<?php // Success/Error Messages
			if (!empty($messages)) { 
			 	foreach ($messages as $message) {
					echo '<div id="messages"><p class="message">' . $message . '</p></div>';
				}	
			}
			if (!empty($errors)) { 
			 	foreach ($errors as $error) {
					echo '<p class="error">' . $error . '</p>';
				}	
			}
		?>

		<form action="contact.php" method="post" id="loginform">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td class="label-cell"><label for="name">Your Issue:</label></td>
					<td>
						<select name="issue" id="issue" tabindex="1">
							<option value="password" <?php if ($_POST && !empty($errors)) {if ($_POST['issue'] == "password") {echo 'selected="selected"';}} ?>>Forgot My Password</option>
							<?php if (ACTIVATION == "yes") { ?>
							<option value="activation"<?php if ($_POST && !empty($errors)) {if ($_POST['issue'] == "activation") {echo 'selected="selected"';}} ?>>Lost My Activation Email</option>
							<?php } ?>
							<option value="account"<?php if ($_POST && !empty($errors)) {if ($_POST['issue'] == "account") {echo 'selected="selected"';}} ?>>I Need an Account</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="label-cell"><label for="name">Your Name:</label></td>
					<td>
						<input type="text" name="name" id="name" value="<?php if ($_POST && !empty($errors)) {echo $_POST['name'];} ?>" tabindex="2" />
					</td>
				</tr>
				<tr>
					<td class="label-cell"><label for="email">Email Address:</label></td>
					<td>
						<input type="text" name="email" id="email" value="<?php if ($_POST && !empty($errors)) {echo $_POST['email'];} ?>" tabindex="3" />
					</td>
				</tr>
			</table>
			<input type="submit" name="submit" class="main-login-button" value="Get Help" />
			<a href="index.php" class="login-trouble">&laquo; Return to Login Form</a>
			<div style="clear: both;"></div>
		</form>
	</div>
	<?php require_once 'includes/footer.php'; ?>
</body>
</html>