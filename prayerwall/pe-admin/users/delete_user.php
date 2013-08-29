<?php /* Prayer Engine - Delete Administrative User */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . USER_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
	
	if ($_POST) {
		$id = strip_tags($_POST['id']);
		if ($id == 1) { // Don't delete the main admin
			header("Location: index.php");
  			exit;
		} else { // Delete the user if not the main admin
			$sql = "DELETE FROM users WHERE id=? LIMIT 1";
			$deleteuser = $dbh->prepare($sql);
			$deleteuser->execute(array($id));
		};
	} else {
		header("Location: index.php");
  		exit;
	};
?>