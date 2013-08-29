<?php /* Prayer Engine - Activate an Administrative User's Account */

	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	ob_start();
	session_start();

// Validate email and encrypted validation code:
$x = $y = FALSE;
if (isset($_GET['x']) && preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_GET['x']) ) { 
	$x = $_GET['x'];
}
if (isset($_GET['y']) && (strlen($_GET['y']) == 32 ) ) { 
	$y = $_GET['y'];
}


if ($x && $y) {

	require_once '../' . DATABASE;
	$a = NULL;
	$updatequery = "UPDATE users SET active=? WHERE (email=? AND active=?) LIMIT 1";
	$updateuser = $dbh->prepare($updatequery);
	$updateuser->execute(array($a, $x, $y));
	$updatecount = $updateuser->rowCount();
	
	if ($updatecount == 1) {
		$url = 'index.php?activated=yes'; 
		ob_end_clean();
		header("Location: $url");
		exit();
	} else {
		$url = 'index.php?activated=error'; 
		ob_end_clean();
		header("Location: $url");
		exit();
	}
	$dbc = null;
} else { 
	$url = 'index.php?activated=no'; 
	ob_end_clean();
	header("Location: $url");
	exit();
} 

?>