<?php /* Prayer Engine - Listing of Reporting Features in Backend */

	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . REPORT_VALIDATION; // Validate that they're logged in
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - Print a List of Prayer Requests</title>
	<link rel="stylesheet" href="../../stylesheets/prayerengine/pe_backend.css" type="text/css" />
	<!-- Display fixes for Internet Explorer -->
		<!--[if lte IE 6]>
		<link href="../../stylesheets/prayerengine/backend_ie6_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 7]>
		<link href="../../stylesheets/prayerengine/backend_ie7_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 8]>
		<link href="../../stylesheets/prayerengine/backend_ie8_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
	<!-- end display fixes for Internet Explorer -->
	<script src="../../javascripts/prayerengine/jquery.js" type="text/javascript"></script>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
</head>
<body id="reports">
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<h2>Print a List of Prayer Requests</h2>
			<p class="instructions">Various printable reports are listed below. Select the report best suited for your needs, and click "Run This Report." Please note that all reports use the current date as the starting point.</p>
			<p>&nbsp;</p>
			
			<a href="day.php" target="_blank" class="run-report">Run This Report</a>
			<h3 class="report-title">Received in the Last Day</h3>
			<p class="report">This report will return a list of all prayer requests received in the last day. Only moderated prayer requests that are publicly shared will appear in this report.</p>
			
			<a href="week.php" target="_blank" class="run-report">Run This Report</a>
			<h3 class="report-title">Received in the Last Week</h3>
			<p class="report">This report will return a list of all prayer requests received in the last week. Only moderated prayer requests that are publicly shared will appear in this report.</p>
			
			<a href="month.php" target="_blank" class="run-report">Run This Report</a>
			<h3 class="report-title">Received in the Last Month</h3>
			<p class="report">This report will return a list of all prayer requests received in the last month. Only moderated prayer requests that are publicly shared will appear in this report.</p>
			
			<a href="quarter.php" target="_blank" class="run-report">Run This Report</a>
			<h3 class="report-title">Received in the Last Quarter</h3>
			<p class="report">This report will return a list of all prayer requests received in the last quarter. Only moderated prayer requests that are publicly shared will appear in this report.</p>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
</body>
</html>