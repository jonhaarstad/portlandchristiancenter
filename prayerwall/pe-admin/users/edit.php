<?php /* Prayer Engine - Administrative User Edit Form */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . USER_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
		
	if ($_POST) { 
		$errors = array(); //Set up errors array
		if ($_POST['email'] == $_POST['origemail']) { // If they're not trying to update the email address
			if (($_POST['password1'] != null) || ($_POST['password2'] != null) ) { // If a new password supplied
				$id = $_POST['id'];
				
				if (empty($_POST['first_name'])) { //validate presence of first name
					$errors[] = 'Please provide a first name';
				} else {
					$firstname = strip_tags($_POST['first_name']);
				}
				
				if (empty($_POST['last_name'])) { //validate presence of last name
					$errors[] = 'Please provide a last name';
				} else {
					$lastname = strip_tags($_POST['last_name']);
				}
				
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
						$errors[] = "Password must be correctly confirmed to update the user.";
					}
				} else {
					$errors[] = "Please enter a valid password!";
				}

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

				//update the user if no errors
				if (empty($errors)) { 
					$sql = "UPDATE users SET first_name=?, last_name=?, email=?, pw=?, vp=?, vs=?, vu=?, vr=?, notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array($firstname, $lastname, $email, SHA1($password), $viewprayers, $viewsettings, $viewusers, $viewreports, $notifications, $id));
					$messages[] = "User information and password successfully updated!";
				}
				
				//fill in form
				$userquery = "SELECT * FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id));
				$user = $finduser->fetch(PDO::FETCH_ASSOC); 
			} else { // If new password was not supplied
				$id = $_POST['id'];
				
				if (empty($_POST['first_name'])) { //validate presence of first name
					$errors[] = 'Please provide a first name';
				} else {
					$firstname = strip_tags($_POST['first_name']);
				}
				
				if (empty($_POST['last_name'])) { //validate presence of last name
					$errors[] = 'Please provide a last name';
				} else {
					$lastname = strip_tags($_POST['last_name']);
				}
				
				if (empty($_POST['email'])) { //validate presence and format of email
					$errors[] = 'An email address is required for a login ID';
				} else {
					if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['email'])) { 
						$email = $_POST['email'];
					} else {
						$errors[] = 'Please provide a valid email address';
					};
				};

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

				//update the user if no errors
				if (empty($errors)) { 
					$sql = "UPDATE users SET first_name=?, last_name=?, email=?, vp=?, vs=?, vu=?, vr=?, notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array($firstname, $lastname, $email, $viewprayers, $viewsettings, $viewusers, $viewreports, $notifications, $id));
					$messages[] = "User information successfully updated!";
				}

				//fill in form
				$userquery = "SELECT * FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id));
				$user = $finduser->fetch(PDO::FETCH_ASSOC); 
			}
		} else { // If they are trying to update the email address
			if (($_POST['password1'] != null) || ($_POST['password2'] != null) ) { // If a new password supplied
				$id = $_POST['id'];
				
				if (empty($_POST['first_name'])) { //validate presence of first name
					$errors[] = 'Please provide a first name';
				} else {
					$firstname = strip_tags($_POST['first_name']);
				}
				
				if (empty($_POST['last_name'])) { //validate presence of last name
					$errors[] = 'Please provide a last name';
				} else {
					$lastname = strip_tags($_POST['last_name']);
				}
				
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
						$errors[] = "Password must be correctly confirmed to update the user.";
					}
				} else {
					$errors[] = "Please enter a valid password!";
				}

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
					$sql = "UPDATE users SET first_name=?, last_name=?, email=?, pw=?, vp=?, vs=?, vu=?, vr=?, notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array($firstname, $lastname, $email, SHA1($password), $viewprayers, $viewsettings, $viewusers, $viewreports, $notifications, $id));
					$messages[] = "User information and password successfully updated!";
				}
				//fill in form
				$userquery = "SELECT * FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id)); 
				$user = $finduser->fetch(PDO::FETCH_ASSOC);
			} else { // If new password was not supplied
				$id = $_POST['id'];
				
				if (empty($_POST['first_name'])) { //validate presence of first name
					$errors[] = 'Please provide a first name';
				} else {
					$firstname = strip_tags($_POST['first_name']);
				}
				
				if (empty($_POST['last_name'])) { //validate presence of last name
					$errors[] = 'Please provide a last name';
				} else {
					$lastname = strip_tags($_POST['last_name']);
				}
				
				if (empty($_POST['email'])) { //validate presence and format of email
					$errors[] = 'An email address is required for a login ID';
				} else {
					if (preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$^', $_POST['email'])) { 
						$email = $_POST['email'];
					} else {
						$errors[] = 'Please provide a valid email address';
					};
				};

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
					$sql = "UPDATE users SET first_name=?, last_name=?, email=?, vp=?, vs=?, vu=?, vr=?, notifications=? WHERE id=?";
					$updateuser = $dbh->prepare($sql);
					$updateuser->execute(array($firstname, $lastname, $email, $viewprayers, $viewsettings, $viewusers, $viewreports, $notifications, $id));
					$messages[] = "User information successfully updated!";
				}
				
				//fill in form
				$userquery = "SELECT * FROM users WHERE id = ?";
				$finduser = $dbh->prepare($userquery);
				$finduser->execute(array($id)); 
				$user = $finduser->fetch(PDO::FETCH_ASSOC);
			}
			
		}
	} else { //get specified user
		if (isset($_GET['resent'])) { // If activation email has been resent
			if ($_GET['resent'] == 'yes') {
				$messages[] = "Activation Email Was Sent Successfully";
			} else {
				$errors[] = "This User Has Already Been Activated";
			}
		}
		
		$id = $_GET['id'];
		$userquery = "SELECT * FROM users WHERE id = ?";
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
	<title>Prayer Engine - Update User Information</title>
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
<body id="user">
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<h2>Edit <?php echo $user['first_name'] . ' ' . $user['last_name'] ?></h2>
			<p class="instructions">You may edit <?php echo $user['first_name'] . ' ' . $user['last_name'] . "'s" ?> user information and privileges using the form below. Please remember that passwords must be alphanumeric and between 4 and 20 characters long.</p>
		
			<?php if ($user['active'] != null) { ?>
			<div id="activation">
				<p>This user has not yet activated their account. <a href="resend_activation.php?id=<?php echo $user['id']; ?><?php if (isset($_GET['c']) && isset($_GET['p'])){echo '&amp;c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>">Resend Email?</a></p>
			</div>
			<?php } ?>
			
			<?php require_once '../includes/errorbox.php'; //Errors ?>
			<?php require_once '../includes/messagebox.php'; //Messages ?>
			<form action="edit.php?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['c']) && isset($_GET['p'])){echo '&amp;c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>#formarea" method="post" class="edit-form-table" id="formarea">
				<table cellpadding="0" cellspacing="0" class="user-table">
					<tr>
						<td class="label-cell"><label for="first_name">First Name:</label></td>
						<td><input type="text" name="first_name" size="20" maxlength="20" tabindex="1" value="<?php if ($_POST && !empty($errors)) {echo $_POST['first_name'];} else {echo $user['first_name'];} ?>" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="last_name">Last Name:</label></td>
						<td><input type="text" name="last_name" size="20" maxlength="40" tabindex="2" value="<?php if ($_POST && !empty($errors)) {echo $_POST['last_name'];} else {echo $user['last_name'];} ?>" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="email">Email:</label></td>
						<td><input type="text" name="email" size="30" maxlength="80" tabindex="3" value="<?php if ($_POST && !empty($errors)) {echo $_POST['email'];} else {echo $user['email'];} ?>" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="password1">Password:</label></td>
						<td><input type="password" name="password1" size="20" maxlength="20" tabindex="4" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="password2">Confirm Password:</label></td>
						<td><input type="password" name="password2" size="20" maxlength="20" tabindex="5" /></td>
					</tr>
					<?php if ($user['id'] != 1 ) { ?>
					<tr>
						<td class="label-cell"><label>Privileges:</label></td>
						<td>
							<ul>
								<li><input name="viewprayers" type="checkbox" value="1" tabindex="6" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewprayers'])) {echo 'checked="checked"';}} else {if ($user['vp'] == 1) {echo 'checked="checked"';}} ?> class="check" /> <label for="viewprayers">Can View, Moderate and Update Prayer Requests</label></li>
								<li><input name="viewreports" type="checkbox" value="1" tabindex="7" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewreports'])) {echo 'checked="checked"';}} else {if ($user['vr'] == 1) {echo 'checked="checked"';}} ?> class="check" /> <label for="viewreports">Can Run Printable Reports of Prayer Requests</label></li>
								<li><input name="viewusers" type="checkbox" value="1" tabindex="8" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewusers'])) {echo 'checked="checked"';}} else {if ($user['vu'] == 1) {echo 'checked="checked"';}} ?> class="check" /> <label for="viewusers">Can Add and Modify Prayer Engine Administrators</label></li>							
								<li><input name="viewsettings" type="checkbox" value="1" tabindex="9" <?php if ($_POST && !empty($errors)) {if (isset($_POST['viewsettings'])) {echo 'checked="checked"';}} else {if ($user['vs'] == 1) {echo 'checked="checked"';}} ?> class="check" /> <label for="viewsettings">Can Change Prayer Engine Settings</label></li>
							</ul>
						</td>
					</tr>
					<?php } ?>
				</table>
		
				<div id="pe-submit-area">
					<?php if (NOTIFICATION == "yes") { ?><input name="notifications" type="checkbox" value="1" id="notifications" tabindex="10" <?php if ($_POST && !empty($errors)) {if (isset($_POST['notifications'])) {echo 'checked="checked"';}} else {if ($user['notifications'] == 1) {echo 'checked="checked"';}} ?> class="check" /> <label for="notifications">Receive Email Notification When a Prayer Request is Received</label><?php } ?>
					<input type="hidden" name="id" value="<?php echo $user['id'] ?>" id="id" />
					<input type="hidden" name="origemail" value="<?php echo $user['email'] ?>" id="email" />
					<?php if ($user['id'] == 1 ) { ?>
					<input type="hidden" name="viewprayers" value="1" id="viewprayers" />
					<input type="hidden" name="viewreports" value="1" id="viewreports" />
					<input type="hidden" name="viewusers" value="1" id="viewusers" />
					<input type="hidden" name="viewsettings" value="1" id="viewsettings" />	
					<?php } ?>
					
					<input type="submit" value="Update User" class="submit" tabindex="11" />
				</div>
			</form>
			<a href="index.php<?php if (isset($_GET['c']) && isset($_GET['p'])){echo '?c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>" class="back-link"><strong>&laquo;</strong> Return to List</a>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
</body>
</html>
