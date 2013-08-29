<?php /* Prayer Engine - Resend Activation Email to Users */
	
	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . USER_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
	
	if (isset($_GET['id'])) {
		$id = strip_tags($_GET['id']);
		
		$userquery = "SELECT email, active FROM users WHERE id = ? LIMIT 1";
		$finduser = $dbh->prepare($userquery);
		$finduser->execute(array($id)); 
		$user = $finduser->fetch(PDO::FETCH_ASSOC);
		
		if ($user['active'] != null) {
			// Send the activation email:
			$body = "You have been registered as an administrator to manage our prayer requests. To activate your account, please click on this link:\n\n";
			$body .= BASE_URL . 'pe-admin/activate.php?x=' . urlencode($user['email']) . '&y=' . $user['active'] . "\n";
			$body .= "You can change your email address and password at any time by clicking \"Update Your Account\" after you have logged in. If you have trouble logging in, or have any questions, please contact " . EMAIL . ".";
			mail($user['email'], 'You are now a Prayer Engine Administrator', $body, 'From: ' . EMAIL);
						
			// Finish the page:
			if (isset($_GET['c']) && isset($_GET['p'])) {
				header("Location: edit.php?id=" . $id . "&resent=yes&c=" . $_GET['c'] . "&p=" . $_GET['p']);
				exit;
			} else {
				header("Location: edit.php?id=" . $id . "&resent=yes");
				exit;
			}
		} else {
			if (isset($_GET['c']) && isset($_GET['p'])) {
				header("Location: edit.php?id=" . $id . "&resent=yes&c=" . $_GET['c'] . "&p=" . $_GET['p']);
				exit;
			} else {
				header("Location: edit.php?id=" . $id . "&resent=yes");
				exit;
			}
		}
	} else {
		header("Location: index.php");
		exit;
	}
 ?>