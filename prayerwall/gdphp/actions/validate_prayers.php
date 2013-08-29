<?php /* Prayer Engine - Validate Privilages to View and Moderate Prayer Requests */
	ob_start();
	session_start();
	
	if (isset($_GET['pid'])) { // Assign a prayer id for rerouting if available
		$pid = $_GET['pid'];
	} else {
		$pid = 0;
	}
		
	if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))) { // Make sure the session user agents match up.
		$url = '../logout.php?pid=' . $pid; 
		header("Location: $url");
		exit(); 
	} else {
		if (!isset($_SESSION['vp'])) { // Log out if no prayer session variable
			$url = '../logout.php?pid=' . $pid; 
			header("Location: $url");
			exit(); 
		} else { // Log out if they don't have prayer rights
			if ($_SESSION['vp'] != 1) {
				$url = '../logout.php?pid=' . $pid; 
				header("Location: $url");
				exit(); 
			}
		}
	}
	
	if (LOGINTIMEOUT != "never") {
		if (!isset($_SESSION['created'])) {
		    $_SESSION['created'] = time();
		} else if (time() - $_SESSION['created'] > LOGINTIMEOUT) {
		    $url = '../logout.php?inactive&pid=' . $pid; 
			header("Location: $url");
			exit();
		} else {
			$_SESSION['created'] = time();
		}
	}
	
	$userfrom = 'prayers';

?>