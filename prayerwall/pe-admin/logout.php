<?php /* Prayer Engine - Log Users Out */
	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	ob_start();
	session_start();
	
	if (isset($_GET['pid'])) { // Set prayer id if they're trying to edit a request but need to log in
		$pid = $_GET['pid'];
	} else {
		$pid = 0;
	}
	
	// Verify that they've been logged in somehow:
	if (!isset($_SESSION['first_name'])) {
		$url = 'index.php?pid=' . $pid; 
		header("Location: $url");
		exit(); 
	} else { // Log them out if they're logged in.
		if (isset($_GET['inactive'])) {
			$_SESSION = array(); 
			session_destroy(); 
			setcookie (session_name(), '', time()-300); 
			$url = 'index.php?inactive&pid=' . $pid; 
			ob_end_clean(); 
			header("Location: $url");
			exit();
		} else {
			$_SESSION = array(); 
			session_destroy(); 
			setcookie (session_name(), '', time()-300); 
			$url = 'index.php?logged_out'; 
			ob_end_clean(); 
			header("Location: $url");
			exit();
		}
	}
?>