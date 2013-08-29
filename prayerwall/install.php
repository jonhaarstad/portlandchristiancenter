<?php /* Prayer Engine - Installation Wizard */

require_once 'config/subdirectories.php';

if ($_POST) { 
	
	include_once 'gdphp/config/pdo_connect.php'; // Connect to the database
	
	$errors = array(); //Set up errors array
	
	if ((empty($_POST['url'])) || ($_POST['url'] == "http://www.yourprayerwalladdress.com/")) { //validate base_url
		$errors[] = 'Please enter the URL for your prayer wall!';
	} else {
		$base_url = $_POST['url'];
	};

	$time_zone = $_POST['time_zone'];
	
	$id = 1;
	
	if (empty($errors)) {
		//update the settings
		$sql = "UPDATE settings SET base_url=?, time_zone=? WHERE id=?";
		$updatesettings = $dbh->prepare($sql);
		$updatesettings->execute(array($base_url, $time_zone, $id));
	}
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
	<?php
	
	$installready = "no";

		if (!extension_loaded('pdo_mysql')) { // Check that 'pdo_mysql' is installed on the server
			echo "<h2>Your server is missing an extension.</h2><p>It looks like you need to have your hosting provider install the \"<a href=\"http://php.net/manual/en/ref.pdo-mysql.php\" target=\"_blank\">pdo_mysql</a>\" extension for you (most hosts will do this for free). The Prayer Engine needs this extension to connect to the database.</p>";
		} else {
			include_once 'gdphp/config/pdo_connect.php'; // Connect to the database

			if (isset($pe_connected_okay)) { // Check to make sure The Prayer Engine's files are in the right directory
				if (!isset($e)) { // Check to make sure the database is set up properly
					$dbcheck = "SELECT 1 FROM settings";	
					$checkrow = $dbh->query($dbcheck);

					if ($checkrow == NULL) { // Check to see if the database has been installed yet
						
						$installready = "yes";
						
						//Build Prayers Table
						$buildprayers = "CREATE TABLE prayers (
						  	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
						  	name varchar(100) DEFAULT NULL,
						  	email varchar(150) DEFAULT NULL,
						  	phone varchar(20) DEFAULT NULL,
						  	share_option varchar(30) DEFAULT NULL,
						  	prayer_count int(11) DEFAULT NULL,
						  	moderated_by varchar(100) DEFAULT NULL,
						  	post_this int(1) DEFAULT NULL,
						  	prayer text,
						  	posted_prayer text,
						  	date_received datetime DEFAULT NULL,
						  	brand_new int(1) DEFAULT NULL,
						  	notifyme tinyint(1) DEFAULT NULL,
						  	prayer_tweet varchar(160) DEFAULT NULL,
						  	twitter_ok tinyint(1) DEFAULT NULL,
						  	desired_share_option varchar(30) DEFAULT NULL,
						  	delete_code char(32) DEFAULT NULL) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
						$dbh->exec($buildprayers);
						$dbh->exec("INSERT INTO prayers (id, name, email, phone, share_option, prayer_count, moderated_by, post_this, prayer, posted_prayer, date_received, brand_new, notifyme, prayer_tweet, twitter_ok, desired_share_option, delete_code) VALUES ('1','Sample Prayer Request','sample@theprayerengine.com','','Share Online','5','Setup Robot','1','This is a sample prayer request. If you\'re seeing this, it means your installation of The Prayer Engine is set up successfully! \r\n\r\nPlease log in to the Prayer Engine Administrative Portal to delete this fake prayer request and complete The Prayer Engine setup process. We hope The Prayer Engine is a valuable asset to your ministry!','This is a sample prayer request. If you\'re seeing this, it means your installation of The Prayer Engine is set up successfully! \r\n\r\nPlease log in to the Prayer Engine Administrative Portal to delete this fake prayer request and complete The Prayer Engine setup process. We hope The Prayer Engine is a valuable asset to your ministry!','2010-01-16 17:30:18','0','0','This is a sample Prayer Tweet. If you\'re seeing this, it means your installation of The Prayer Engine is set up successfully! Please log in.','1','Share Online','b8d30156ef00fe65f8aaec817f0564fa')");

						//Build Settings Table
						$buildsettings = "CREATE TABLE settings (
							id int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
							prayersdisplayed int(3) DEFAULT NULL,
							ministryname varchar(30) DEFAULT NULL,
							twitter varchar(3) DEFAULT NULL,
							twitter_title varchar(100) DEFAULT NULL,
							twitter_description text,
							rss_title varchar(100) DEFAULT NULL,
							rss_description text,
							email varchar(120) DEFAULT NULL,
							robot_email varchar(120) DEFAULT NULL,
							activation varchar(3) DEFAULT NULL,
							notification varchar(3) DEFAULT NULL,
							mobile_site varchar(3) DEFAULT NULL,
							stylesheet varchar(80) DEFAULT NULL,
							login_timeout varchar(10) DEFAULT NULL,
							base_url varchar(255) DEFAULT NULL,
							time_zone varchar(50) DEFAULT NULL) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
						$dbh->exec($buildsettings);
						$dbh->exec("INSERT INTO settings (id, prayersdisplayed, ministryname, twitter, twitter_title, twitter_description, rss_title, rss_description, email, robot_email, activation, notification, mobile_site, stylesheet, login_timeout) VALUES ('1','10','Your Ministry Name Here','no','Our Church Twitter Feed','This feed delivers the latest prayer tweets as soon as they\'re received.','Our Church Prayer Chain','This feed delivers the latest prayer requests as soon as they\'re received.','name@youremailaddress.com','robot@youremailaddress.com','yes','yes','yes','pe_color_scheme.css','900')");

						//Build Users Table
						$buildusers = "CREATE TABLE users (
							 id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
							 first_name varchar(20) NOT NULL,
							 last_name varchar(40) NOT NULL,
							 email varchar(80) NOT NULL UNIQUE KEY,
							 pw char(40) NOT NULL,
							 active char(32) DEFAULT NULL,
							 registration_date datetime NOT NULL,
							 vp tinyint(1) unsigned NOT NULL,
							 vs tinyint(1) unsigned NOT NULL,
							 notifications tinyint(1) DEFAULT NULL,
							 vu tinyint(1) unsigned NOT NULL,
							 vr tinyint(1) unsigned NOT NULL,
							 KEY `login` (`email`,`pw`)
							) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
						$dbh->exec($buildusers);
						$dbh->exec("INSERT INTO users (id, first_name, last_name, email, pw, active, registration_date, vp, vs, notifications, vu, vr) VALUES ('1','Temporary','Admin','tempemail@youraddress.com','17320abe4ebca737a2baa5dfaef6427a21c38a02',NULL,'2010-01-16 09:26:39','1','1','1','1','1')");						
					
						$findsettings = "SELECT base_url FROM settings WHERE id = 1 LIMIT 1";
						$querysettings = $dbh->prepare($findsettings);
						$querysettings->execute(); 
						$settings = $querysettings->fetch(PDO::FETCH_ASSOC);
					} else {
						$installready = "yes";
						
						$findsettings = "SELECT base_url FROM settings WHERE id = 1 LIMIT 1";
						$querysettings = $dbh->prepare($findsettings);
						$querysettings->execute(); 
						$settings = $querysettings->fetch(PDO::FETCH_ASSOC);

						if ($_POST && empty($errors)) { 
							echo '<h2>Good Job!</h2> <p>You have completed the installation process for your copy of The Prayer Engine. Visit the <a href="index.php">prayer wall</a> now or log in to the <a href="pe-admin/index.php">administrative portal</a> (using the credentials in your User Guide) to customize your installation.</p><p>Enjoy your copy of The Prayer Engine! We hope it\'s a tremendous blessing to your ministry.</p>';
						} elseif ($settings['base_url'] != null) { 
							echo '<h2>No need to load this again...</h2> <p><a href="index.php">Your copy</a> of The Prayer Engine has already been successfully installed!</p>';
						}
					}
				}
			} else { // If The Prayer Engine's files aren't in the right directories
				echo '<h2>Hmmm... I can\'t find the \'php\' folder.</h2> <p>Make sure you uploaded The Prayer Engine\'s "php" folder just outside of your Prayer Engine site\'s public web directory.</p><p><em>If you\'ve installed The Prayer Engine at a sub-directory within your website, make sure you\'ve changed the settings in \'config/subdirectories.php\'.</em></p>';
			}
		}

	?>
	
<?php
// Set BASE_URL and timezone
if (($settings['base_url'] == null) && ($installready == "yes")) { ?>
	<h2>Installation is almost complete!</h2>
	<p>Please enter the full URL where you are installing your Prayer Engine prayer wall. <strong>Don't forget the forward slash at the end!</strong></p> 
	<?php 
		if(!empty($errors)) { 
			foreach ($errors as $error) {
				echo "<p id=\"wizard-error\">$error</p>";
			};  
		}; 
	?>
	<form action="install.php" method="post">
	<table id="wizardtable">
		<tr>
			<td class="label-cell"><label for="url">URL:</label></td>
			<td class="input-cell"><input type="text" name="url" value="http://www.yourprayerwalladdress.com/" id="url" /></td>
		</tr>
		<tr>
			<td class="label-cell"><label for="time_zone">Time Zone:</label></td>
			<td class="input-cell"><input type="text" name="time_zone" value="<?php if ($_POST) {echo $_POST['time_zone'];} else { ?>US/Central<?php }; ?>" id="time_zone" /> <cite>Find your time zone <a href="http://www.php.net/manual/en/timezones.php" target="_blank">here</a></cite></td>
		</tr>
	</table>
	<input type="submit" name="submit" value="Complete Installation" id="wizardsubmit" />
	</form>
	<p><em>If you accidentally enter the wrong URL, you will likely encounter glitches with your Prayer Engine installation. You can correct this URL at any time in the "Engine Settings" tab in the administrative portal.</em></p>
<?php } ?>
	</div>
	<!-- Version 1.3.060311.gd -->
</body>
</html>