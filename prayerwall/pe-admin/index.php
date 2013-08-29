<?php /* Prayer Engine - Log In Page for Administrators */

	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	require_once '../includes/markdown.php';
	ob_start();
	session_start();
	
	if (isset($_GET['pid'])) { // Set prayer id if they're trying to edit a request but need to log in
		$pid = $_GET['pid'];
	} else {
		$pid = 0;
	}
	
	if ($_POST) {
		require_once '../' . DATABASE;
	
		$errors = array(); //Set up errors array
	
		// Validate the email address:
		if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_POST['email'])) {
			$e = $_POST['email'];
		} else {
			$errors[] = "Please Enter a Valid Email Address";
		}
	
		// Validate the password:
		if (!empty($_POST['pass'])) {
			$p = strip_tags($_POST['pass']);
		} else {
			$errors[] = "Please Enter Your Password";
		}
	
		if (empty($errors)) { // If everything's OK.
			// Query the database:
			$password = SHA1($p);
			$a = NULL;
			$getuser = $dbh->prepare("SELECT id, first_name, last_name, vp, vs, vu, vr FROM users WHERE (email=? AND pw=?) AND active IS ?");	
			$getuser->execute(array($e, $password, $a));
			$usercount = $getuser->rowCount();	
		
			if ($usercount == 1) { // A match was made.
				// Register the values & redirect:
				$_SESSION = $getuser->fetch(PDO::FETCH_ASSOC);
				$_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
				$dbc = null;
				
				if ($_SESSION['vp'] == 1) { // Redirect based on user privileges.
					$url = 'prayers/index.php?pid=' . $pid;
					ob_end_clean(); 
					header("Location: $url");
					exit(); 
				} elseif ($_SESSION['vr'] == 1) {
					$url = 'reports/index.php';
					ob_end_clean(); 
					header("Location: $url");
					exit(); 
				} elseif ($_SESSION['vu'] == 1) {
					$url = 'users/index.php'; 
					ob_end_clean(); 
					header("Location: $url");
					exit(); 
				} elseif ($_SESSION['vs'] == 1) {
					$url = 'settings/index.php'; 
					ob_end_clean(); 
					header("Location: $url");
					exit(); 
				} else {
					$errors[] = "Insufficient User Privileges... Cannot Log In";
				}					
			} else { // No match was made.
				if (ACTIVATION == "yes") { 
					$errors[] = "Incorrect Email/Password (or Account Not Activated)";
				} else {
					$errors[] = "Incorrect Email Address or Password";
				}
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
	<title>Prayer Engine - Login</title>
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
	<script language="javascript" src="../javascripts/prayerengine/jquery.js" type="text/javascript"></script> 
	<script language="javascript" src="../javascripts/prayerengine/jquery.validate.js" type="text/javascript"></script> 
	<script language="javascript" src="../javascripts/prayerengine/validate_login.js" type="text/javascript"></script> 
	<?php
		if (isset($_GET['logged_out']) || isset($_GET['activated']) || isset($_GET['inactive'])) { 
			echo '<script language="javascript" src="../javascripts/prayerengine/slide_message.js" type="text/javascript"></script>';
		}
	?>
</head>

<body id="login">
	
	<div id="login-area">
		<?php // Success/Error Messages
			if (isset($_GET['inactive'])) { 
				echo '<div id="messages"><p class="message">You Must Log In Again to Continue Working</p></div>';
		 	}
			if (isset($_GET['logged_out'])) { 
				echo '<div id="messages"><p class="message">You Have Successfully Logged Out</p></div>';
		 	}
			if (isset($_GET['activated'])) {
				if ($_GET['activated'] == "yes") {
					echo '<div id="messages"><p class="message">Your Account Has Been Successfully Activated!</p></div>';
				} 
				if ($_GET['activated'] == "error") {
					echo '<p class="error">Account Activation Encountered an Error</p>';
				} 
				if ($_GET['activated'] == "no") {
					echo '<p class="error">Nice Try</p>';
				} 
			}
			if (!empty($errors)) { 
			 	foreach ($errors as $error) {
					echo '<p class="error">' . $error . '</p>';
				}	
			}
		?>

		<form action="index.php?pid=<?php echo $pid; ?>" method="post" id="loginform">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td class="label-cell"><label for="email">Email Address:</label></td>
					<td>
						<input type="text" name="email" id="email" size="20" maxlength="40" tabindex="1" />
					</td>
				</tr>
				<tr>
					<td class="label-cell"><label for="pass">Password:</label></td>
					<td>
						<input type="password" name="pass" id="pass" size="20" maxlength="20" tabindex="2" />
					</td>
				</tr>
			</table>
			<input type="submit" name="submit" class="main-login-button" value="Login" />
			<a href="contact.php" class="login-trouble">Having Trouble Logging In?</a>
			<div style="clear: both;"></div>
		</form>
	</div>
	<?php require_once 'includes/footer.php'; ?>
</body>
</html>


