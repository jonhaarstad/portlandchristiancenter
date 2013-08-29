<?php /* Prayer Engine - Application Configuration (EDIT AT YOUR OWN RISK!) */

include 'pdo_connect.php'; // Pull settings from database
$findsettings = "SELECT * FROM settings WHERE id = 1 LIMIT 1";
$querysettings = $dbh->prepare($findsettings);
$querysettings->execute(); 
$settings = $querysettings->fetch(PDO::FETCH_ASSOC);

/* ---------- ENGINE SETTINGS (ADJUST IN WIZARD BEFORE FIRST USE!) ----------- */

// Site URL (base for all redirections):
define ('BASE_URL', $settings['base_url']);

// Adjust the time zone for PHP 5.1 and greater:
date_default_timezone_set ($settings['time_zone']);

// Installing your copy of The Prayer Engine in a sub-directory?
//
// If you plan to use The Prayer Engine in a sub-directory (www.yoursite.com/prayer/) rather than
// using it in the root of your public directory (www.yoursite.com or prayer.yoursite.com), you'll
// need to follow the instructions found in 'public/config/subdirectories.php'

/* ---------- GENERAL SETTINGS (EDIT AT YOUR OWN RISK!) ---------- */

// Location of the PDO connection script:
define ('DATABASE', 'gdphp/config/pdo_connect.php');

// Location of the Validation scripts:
define ('PRAYER_VALIDATION', 'gdphp/actions/validate_prayers.php');
define ('REPORT_VALIDATION', 'gdphp/actions/validate_reports.php');
define ('USER_VALIDATION', 'gdphp/actions/validate_users.php');
define ('SETTINGS_VALIDATION', 'gdphp/actions/validate_settings.php');
define ('ACCOUNT_VALIDATION', 'gdphp/actions/validate_account.php');

function curPageName() {
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

/* ---------- SETTINGS & PREFERENCES (EDIT VIA ADMIN PORTAL) ----------- */

// Set how many prayer requests you want displayed per page.
define('PRAYERSDISPLAYED', $settings['prayersdisplayed']);

// Your Church/Ministry Name
define('MINISTRYNAME', $settings['ministryname']);

/* Twitter Feed */

// Collect prayer requests for Twitter distribution?
define('TWITTER', $settings['twitter']); 

// The Title of your Twitter feed:
define('TWITTER_TITLE', $settings['twitter_title']);

// The Title of your Twitter feed:
define('TWITTER_DESCRIPTION', $settings['twitter_description']);

/* RSS Feed */

// The Title of your main RSS feed:
define('RSS_TITLE', $settings['rss_title']);

// The Title of your main RSS feed:
define('RSS_DESCRIPTION', $settings['rss_description']);

/* Email Settings */

// Admin contact address:
define('EMAIL', $settings['email']);

// Email address that "I Prayed for This" emails appear to come from:
define('ROBOTEMAIL', $settings['robot_email']);

// Require email activation for your administators?
define('ACTIVATION', $settings['activation']); 

// Send all Prayer Wall Admins an email notification when a new prayer request is received?
define('NOTIFICATION', $settings['notification']);

/* Mobile Site Settings */

// Enable redirect to mobile site?
define('MOBILESITE', $settings['mobile_site']);

/* Backend Login Timeout */

// How many seconds of inactivity before a user is automatically logged out for security reasons?
define('LOGINTIMEOUT', $settings['login_timeout']);

?>
