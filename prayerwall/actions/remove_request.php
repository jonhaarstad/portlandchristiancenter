<?php /* Prayer Engine - Validate User's Remove Request Link to Manually Remove the Prayer Request From the Prayer Wall */

	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	ob_start();
	session_start();
	
	// Validate email and encrypted validation code:
	$e = $dc = FALSE;
	if (isset($_GET['e']) && preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_GET['e']) ) { 
		$e = $_GET['e'];
	}
	
	if (isset($_GET['dc']) && (strlen($_GET['dc']) == 32 ) ) { 
		$dc = $_GET['dc'];
	}


	if ($e && $dc) {

		require_once '../' . DATABASE;
	
		$checkcombo = $dbh->prepare("SELECT id FROM prayers WHERE (email=? AND delete_code=?) LIMIT 1");	
		$checkcombo->execute(array($e, $dc));
		$prayer = $checkcombo->fetch(PDO::FETCH_ASSOC);	
		$returnedprayer = $checkcombo->rowCount();

		if ($returnedprayer == 0) { // Available.
			$url = BASE_URL; 
			ob_end_clean();
			header("Location: $url");
			exit();
		} else {
			$sql = "DELETE FROM prayers WHERE id=?";
			$deleteprayer = $dbh->prepare($sql);
			$deleteprayer->execute(array($prayer['id']));
			ob_end_clean();
		}
	} else {
		$url = BASE_URL; 
		ob_end_clean();
		header("Location: $url");
		exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Prayer Request Removed</title>
	
</head>

<body>

	<h3>Your prayer request has been deleted from our database.</h3>
	<h4><a href="<?php echo BASE_URL; ?>">View the Prayer Wall</a></h4>

</body>
</html>