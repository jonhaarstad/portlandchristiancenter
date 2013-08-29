<?php /* Prayer Engine - Validate Privilages to Update an Account */
	ob_start();
	session_start();
	
	if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))) { // Make sure the session user agents match up
		$url = '../logout.php'; 
		header("Location: $url");
		exit(); 
	} else { // Log them out if no settings session is provided
		if (!isset($_SESSION['vu']) || !isset($_SESSION['vp']) || !isset($_SESSION['vs']) || !isset($_SESSION['vr'])) {
			$url = '../logout.php'; 
			header("Location: $url");
			exit(); 
		} else { // Log them out if they don't have settings privilages
			if (($_SESSION['vu'] != 1) && ($_SESSION['vp'] != 1) && ($_SESSION['vs'] != 1) && ($_SESSION['vr'] != 1)) {
				$url = '../logout.php'; 
				header("Location: $url");
				exit(); 
			}
		}
	}
	
	if (LOGINTIMEOUT != "never") {
		if (!isset($_SESSION['created'])) {
		    $_SESSION['created'] = time();
		} else if (time() - $_SESSION['created'] > LOGINTIMEOUT) {
		    $url = '../logout.php?inactive'; 
			header("Location: $url");
			exit();
		} else {
			$_SESSION['created'] = time();
		}
	}
	
	$userfrom = 'users';
?>