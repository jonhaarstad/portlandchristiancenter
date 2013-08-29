<?php  /* Prayer Engine - Quarterly Report of All Public Prayer Requests */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . DATABASE;
	require_once '../../' . REPORT_VALIDATION; // Validate that they're logged in
	require_once '../../includes/markdown.php';
	
	$findprayers = "SELECT * FROM prayers WHERE post_this = 1 AND (share_option = 'Share Online' OR share_option = 'Share Online Anonymously') AND (DATE_SUB(CURDATE(),INTERVAL 89 DAY) <= date_received || date_received = CURDATE()) ORDER BY date_received DESC";	
	$prayers = $dbh->query($findprayers);
	$count = $prayers->rowCount();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Prayer Engine - Prayer Requests From the Last Quarter</title>
	<link rel="stylesheet" href="../../stylesheets/prayerengine/pe_backend.css" type="text/css" />
</head>
<body id="report">

<h1>Prayer Requests From the Last Quarter</h1>

<?php include '../includes/format_report_results.php'; ?>

</body>
</html>