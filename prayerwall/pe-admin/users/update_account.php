<?php /* Prayer Engine - Allow a User to Update Their Email and Password */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . ACCOUNT_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
	
	$errors = array(); //Set up errors array
	$messages = array(); //Set up messages array
	
	if ($_POST) {
		if ($_POST['email'] == $_POST['origemail']) { // If they're not trying to update the email address
			if (($_POST['password1'] != null) || ($_POST['password2'] != null) ) { // If a new password supplied
				$id = $_SESSION['id'];

				// Check for a password and match against the confirmed password:
				if (preg_match ('/^\w{4,20}$/', $_POST['password1'])) {
					if ($_POST['password1'] == $_POST['password2']) {
						$password = $_POST['password1'];
					} else {
						$errors[] = "You must correctly confirm your new password to save changes.";
					}
				} else {
					$errors[] = "Please enter a valid password!";
				}
				
				if (isset($_POST['notifications'])) {
					$notifications = $_POST['notifications'];
				} else {
					$notifications = 0;
				}
				
				//update the user if no errors
				if (empty($errors)) { 
					$sql = "UPDATE users SET pw=?, notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array(SHA1($password), $notifications, $id));
					$messages[] = "You have successfully updated your account!";
				}
				
				// Fill in form
				$id = $_SESSION['id'];
				$userquery = "SELECT email, notifications FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id)); 
				$user = $finduser->fetch(PDO::FETCH_ASSOC);
			} else { // Just update notifications
				$id = $_SESSION['id'];
				
				if (isset($_POST['notifications'])) {
					$notifications = $_POST['notifications'];
				} else {
					$notifications = 0;
				}
				
				//update the user if no errors
				if (empty($errors)) { 
					$sql = "UPDATE users SET notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array($notifications, $id));
					$messages[] = "You have successfully updated your account!";
				}
				
				// Fill in form
				$id = $_SESSION['id'];
				$userquery = "SELECT email, notifications FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id)); 
				$user = $finduser->fetch(PDO::FETCH_ASSOC);
			}
		} else { // Trying to Update Email Address
			if (($_POST['password1'] != null) || ($_POST['password2'] != null) ) { // If a new password supplied
				$id = $_SESSION['id'];
				
				if (empty($_POST['email'])) { //validate presence and format of email
					$errors[] = 'An email address is required for a login ID';
				} else {
					if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['email'])) { 
						$email = $_POST['email'];
					} else {
						$errors[] = 'Please provide a valid email address';
					};
				};

				// Check for a password and match against the confirmed password:
				if (preg_match ('/^\w{4,20}$/', $_POST['password1'])) {
					if ($_POST['password1'] == $_POST['password2']) {
						$password = $_POST['password1'];
					} else {
						$errors[] = "You must correctly confirm your new password to save changes.";
					}
				} else {
					$errors[] = "Please enter a valid password!";
				}
				
				if (isset($_POST['notifications'])) {
					$notifications = $_POST['notifications'];
				} else {
					$notifications = 0;
				}
				
				// Make sure the email address is available:
				if (isset($email)) {
					$checkedemail = $dbh->prepare("SELECT id FROM users WHERE email=?");	
					$checkedemail->execute(array($email));	
					$emailcount = $checkedemail->rowCount();

					if ($emailcount != 0) { //Email Not Available
						$errors[] = 'Sorry, but that email address is already in use. Please choose another one.';
					}
				}
				
				//update the user if no errors
				if (empty($errors)) { 
					$sql = "UPDATE users SET email=?, pw=?, notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array($email, SHA1($password), $notifications, $id));
					$messages[] = "You have successfully updated your account!";
				}
				
				// Fill in form
				$id = $_SESSION['id'];
				$userquery = "SELECT email, notifications FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id)); 
				$user = $finduser->fetch(PDO::FETCH_ASSOC);
			} else { // Just update notifications and email
				$id = $_SESSION['id'];
				
				if (empty($_POST['email'])) { //validate presence and format of email
					$errors[] = 'An email address is required for a login ID';
				} else {
					if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['email'])) { 
						$email = $_POST['email'];
					} else {
						$errors[] = 'Please provide a valid email address';
					};
				};
				
				if (isset($_POST['notifications'])) {
					$notifications = $_POST['notifications'];
				} else {
					$notifications = 0;
				}
				
				// Make sure the email address is available:
				if (isset($email)) {
					$checkedemail = $dbh->prepare("SELECT id FROM users WHERE email=?");	
					$checkedemail->execute(array($email));	
					$emailcount = $checkedemail->rowCount();

					if ($emailcount != 0) { //Email Not Available
						$errors[] = 'Sorry, but that email address is already in use. Please choose another one.';
					}
				}
				
				//update the user if no errors
				if (empty($errors)) { 
					$sql = "UPDATE users SET email=?, notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array($email, $notifications, $id));
					$messages[] = "You have successfully updated your account!";
				}
				
				// Fill in form
				$id = $_SESSION['id'];
				$userquery = "SELECT email, notifications FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id)); 
				$user = $finduser->fetch(PDO::FETCH_ASSOC);
			}	
		}
	} else {
		$id = $_SESSION['id'];
		$userquery = "SELECT email, notifications FROM users WHERE id = ?";
		$finduser = $dbh->prepare($userquery);
		$finduser->execute(array($id)); 
		$user = $finduser->fetch(PDO::FETCH_ASSOC);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - Update Your Account</title>
	<script src="../../javascripts/prayerengine/jquery.js" type="text/javascript"></script>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
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
<body>
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<h2>Update Your Account</h2>
			<p class="instructions">You may edit your account information and settings using the form below. Please remember that passwords must be alphanumeric and between 4 and 20 characters long.</p>
			
			<?php require_once '../includes/errorbox.php'; //Errors ?>
			<?php require_once '../includes/messagebox.php'; //Messages ?>
			<form action="update_account.php<?php if (isset($_GET['from'])) {echo "?from=" . $_GET['from'];} ?>" method="post" class="edit-form-table">
				<table cellpadding="0" cellspacing="0" class="user-table">
					<tr>
						<td class="label-cell"><label for="email">Email:</label></td>
						<td><input type="text" name="email" value="<?php if ($_POST && !empty($errors)) {echo $_POST['email'];} else {echo $user['email'];} ?>" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="password1">Password:</label></td>
						<td><input type="password" name="password1" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="password2">Confirm Password:</label></td>
						<td><input type="password" name="password2" /></td>
					</tr>
				</table>
		
				<div id="pe-submit-area">
					<?php if (NOTIFICATION == "yes") { ?><input name="notifications" type="checkbox" value="1" id="notifications" <?php if ($_POST && !empty($errors)) {if (isset($_POST['notifications'])) {echo 'checked="checked"';}} else {if ($user['notifications'] == 1) {echo 'checked="checked"';}} ?> class="check" /> <label for="notifications">Receive Email Notification When a Prayer Request is Received</label><?php } ?>
					<input type="hidden" name="id" value="<?php echo $id ?>" id="id" />
					<input type="hidden" name="origemail" value="<?php echo $user['email'] ?>" id="email" />
					
					<input type="submit" value="Update Account" class="submit" />
				</div>
			</form>
			<?php 
				if (isset($_GET['from'])) {
					if ($_GET['from'] == "prayers") {
						echo '<a href="../prayers/index.php" class="return-link"><strong>&laquo;</strong> Return to Requests</a>';
					}
					if ($_GET['from'] == "reports") {
						echo '<a href="../reports/index.php" class="return-link"><strong>&laquo;</strong> Return to Reports</a>';
					}
					if ($_GET['from'] == "users") {
						echo '<a href="../users/index.php" class="return-link"><strong>&laquo;</strong> Return to Users</a>';
					}
					if ($_GET['from'] == "settings") {
						echo '<a href="../settings/index.php" class="return-link"><strong>&laquo;</strong> Return to Settings</a>';
					}
				}
			 ?>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
</body>
</html>