<?php /* Prayer Engine - Delete the Specified Prayer Request */
	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . PRAYER_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
	
	if ($_POST) {
		$id = $_POST['id'];
		$sql = "DELETE FROM prayers WHERE id=?";
		$deleteprayer = $dbh->prepare($sql);
		$deleteprayer->execute(array($id));
	} else {
		header("Location: index.php");
  		exit;
	};
?>