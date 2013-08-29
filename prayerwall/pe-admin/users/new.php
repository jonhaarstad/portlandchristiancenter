<?php /* Prayer Engine - Administrative User Registration Form */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../includes/markdown.php';
	require_once '../../' . USER_VALIDATION; // Validate that they're logged in

	if ($_POST) {
		require_once '../../' . DATABASE;
	
		$errors = array(); //Set up errors array
	
		// Check for a first name:
		if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $_POST['first_name'])) {
			$fn = strip_tags($_POST['first_name']);
		} else {
			$errors[] = "Please enter a first name.";
		}
	
		// Check for a last name:
		if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $_POST['last_name'])) {
			$ln = strip_tags($_POST['last_name']);
		} else {
			$errors[] = "Please enter a last name.";
		}
	
		// Check for an email address:
		if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_POST['email'])) {
			$e = $_POST['email'];
		} else {
			$errors[] = "Please enter a valid email address.";
		}

		// Check for a password and match against the confirmed password:
		if (preg_match ('/^\w{4,20}$/', $_POST['password1'])) {
			if ($_POST['password1'] == $_POST['password2']) {
				$p = $_POST['password1'];
			} else {
				$errors[] = "Please correctly confirm the password.";
			}
		} else {
			$errors[] = "Please enter a valid password.";
		}
	
		if (empty($errors)) { // If everything's OK...
			// Make sure the email address is available:
			$checkedemail = $dbh->prepare("SELECT id FROM users WHERE email=?");	
			$checkedemail->execute(array($e));	
			$emailcount = $checkedemail->rowCount();
		
			if ($emailcount == 0) { // Available.
				// Create the activation code:
				if (ACTIVATION == "yes") {
					$a = md5(uniqid(rand(), true));
				} else {
					$a = null;
				}

				// Registration Timestamp
				$time = date("Y-m-d H:i:s", time());
			
				// Set Privilages
				if (isset($_POST['viewprayers'])) { 
					$viewprayers = $_POST['viewprayers'];
				} else {
					$viewprayers = 0;
				}
			
				if (isset($_POST['viewsettings'])) {
					$viewsettings = $_POST['viewsettings'];
				} else {
					$viewsettings = 0;
				}
				
				if (isset($_POST['viewusers'])) {
					$viewusers = $_POST['viewusers'];
				} else {
					$viewusers = 0;
				}

				if (isset($_POST['viewreports'])) {
					$viewreports = $_POST['viewreports'];
				} else {
					$viewreports = 0;
				}

				if (isset($_POST['notifications'])) {
					$notifications = $_POST['notifications'];
				} else {
					$notifications = 0;
				}
			
				// Add the user to the database:
				$newusersql = "INSERT INTO users (email, pw, first_name, last_name, active, registration_date, vp, vs, vu, vr, notifications) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
				$createuser = $dbh->prepare($newusersql);
				$createuser->execute(array($e, SHA1($p), $fn, $ln, $a, $time, $viewprayers, $viewsettings, $viewusers, $viewreports, $notifications));
				$newusercount = $createuser->rowCount();

				if ($newusercount == 1) { // If it ran OK.
					if (ACTIVATION == "yes") {
						// Send the activation email:
						$body = "You have been registered as an administrator to manage our prayer requests. To activate your account, please click on this link:\n\n";
						$body .= BASE_URL . 'pe-admin/activate.php?x=' . urlencode($e) . '&y=' . $a . "\n\n";
						$body .= "After you have activated your account, please use the information below to log in:\n\n";
						$body .= BASE_URL . "pe-admin/\n";
						$body .= "Email Address: " . $e . "\n";
						$body .= "Password: " . $p . "\n\n";
						$body .= "You can change your email address and password at any time by clicking \"Update Your Account\" after you have logged in. If you have trouble logging in, or have any questions, please contact " . EMAIL . ".";
						mail($e, 'You are now a Prayer Engine Administrator', $body, 'From: ' . EMAIL);
					} else {
						// Send the activation email:
						$body = "You have been registered as an administrator to manage our prayer requests. Please use the information below to log in to your account:\n\n";
						$body .= BASE_URL . "pe-admin/\n";
						$body .= "Email Address: " . $e . "\n";
						$body .= "Password: " . $p . "\n\n";
						$body .= "You can change your email address and password at any time by clicking \"Update Your Account\" after you have logged in. If you have trouble logging in, or have any questions, please contact " . EMAIL . ".";
						mail($e, 'You are now a Prayer Engine Administrator', $body, 'From: ' . EMAIL);
					}
				
					// Finish the page:
					header("Location: index.php?created=yes");
  					exit;
				
				} else { // If it did not run OK.
					$errors[] = "There was an error with this user's registration. Sorry for the inconvenience.";
				}
			
			} else { // The email address is not available.
				$errors[] = "That email address is already in use. Please choose a different one.";
			}
		} 
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - Create a New User</title>
	<script src="../../javascripts/prayerengine/jquery.js" type="text/javascript"></script>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#first_name').focus();
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
<body id="user">
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<h2>Create a New User</h2>
			<p class="instructions">You may create a new administrator by using the form below. Once you have added a new user, they will receive an email with login instructions. Please remember that passwords must be alphanumeric and between 4 and 20 characters long.</p>
		
			<?php require_once '../includes/errorbox.php'; //Errors ?>
			<?php require_once '../includes/messagebox.php'; //Messages ?>
			<form action="new.php" method="post" class="edit-form-table">
				<table cellpadding="0" cellspacing="0" class="user-table">
					<tr>
						<td class="label-cell"><label for="first_name">First Name:</label></td>
						<td><input type="text" name="first_name" id="first_name" size="20" maxlength="20" tabindex="1" value="<?php if ($_POST && !empty($errors)) {echo $_POST['first_name'];} ?>" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="last_name">Last Name:</label></td>
						<td><input type="text" name="last_name" size="20" maxlength="40" tabindex="2" value="<?php if ($_POST && !empty($errors)) {echo $_POST['last_name'];} ?>" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="email">Email:</label></td>
						<td><input type="text" name="email" size="30" maxlength="80" tabindex="3" value="<?php if ($_POST && !empty($errors)) {echo $_POST['email'];} ?>" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="password1">Password:</label></td>
						<td><input type="password" name="password1" size="20" tabindex="4" maxlength="20" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="password2">Confirm Password:</label></td>
						<td><input type="password" name="password2" size="20" maxlength="20" tabindex="5" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label>Privileges:</label></td>
						<td>
							<ul>
								<li><input name="viewprayers" type="checkbox" value="1" tabindex="6" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewprayers'])) {echo 'checked="checked"';}} else {echo 'checked="checked"';} ?> class="check" /> <label for"viewprayers">Can View, Moderate and Update Prayer Requests</label></li>
								<li><input name="viewreports" type="checkbox" value="1" tabindex="7" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewreports'])) {echo 'checked="checked"';}} ?> class="check" /> <label for"viewreports">Can Run Printable Reports of Prayer Requests</label></li>
								<li><input name="viewusers" type="checkbox" value="1" tabindex="8" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewusers'])) {echo 'checked="checked"';}} ?> class="check" /> <label for"viewusers">Can Add and Modify Prayer Engine Administrators</label></li>							
								<li><input name="viewsettings" type="checkbox" value="1" tabindex="9" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewsettings'])) {echo 'checked="checked"';}} ?> class="check" /> <label for"viewsettings">Can Change Prayer Engine Settings</label></li>
							</ul>
						</td>
					</tr>
				</table>
		
				<div id="pe-submit-area">
					<?php if (NOTIFICATION == "yes") { ?><input name="notifications" type="checkbox" value="1" id="notifications" tabindex="10" <?php if ($_POST && !empty($errors)) {if (isset($_POST['notifications'])) {echo 'checked="checked"';}} ?> class="check" /> <label for="notifications">Receive Email Notification When a Prayer Request is Received</label><?php } ?>
					<input type="submit" value="Create User" class="submit" tabindex="11" />
				</div>
			</form>
			<a href="index.php" class="back-link"><strong>&laquo;</strong> Return to List</a>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
</body>
</html>