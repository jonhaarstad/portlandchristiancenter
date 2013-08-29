<?php /* Prayer Engine - Installation Wizard */

require_once 'config/subdirectories.php';
$phpversion = phpversion();

if ($phpversion >= 5) { // Check to make sure their server is running PHP 5 or greater
	$url = 'install.php'; 
	header("Location: $url");
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Prayer Engine - Installation Wizard</title>
	<link rel="stylesheet" href="stylesheets/prayerengine/pe_backend.css" type="text/css" />
</head>

<body id="wizard">
	<div id="wizard-container">
		<h2>PHP 5 isn't installed.</h2>
		<p>The Prayer Engine requires PHP 5 to be installed on your server. Contact your hosting provider and ask them to install it for you.</p>
		<p><em>You might want to check your hosting provider's control panel before contacting them. Some hosts make it easy to upgrade to PHP 5 with a simple click!</em></p>
	</div>
	<!-- Version 1.3.053110 -->
</body>
</html>