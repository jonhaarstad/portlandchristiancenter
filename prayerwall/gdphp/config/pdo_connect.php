<?php /* Prayer Engine - Database Connection Script */

$username = "397629_pcctoday"; // Enter Your DB User Name Here
$password = "HEPVpiV7AvX4Kx4fP7zx"; // Enter Your DB Password Here
$host = "mysql50-63.wc1.dfw1.stabletransit.com"; // Enter your DB host name here - BE SURE TO LEAVE OFF THE HTTP://
$database_name = "397629_pcctoday_prayer"; // Enter Your DB Name Here

try {
    $dbh = new PDO("mysql:host=" . $host . ";dbname=" . $database_name, $username, $password);
    }
catch(PDOException $e) {
	echo '<h2>Hmmm... I can\'t connect to the database.</h2><p>It looks like your files are uploaded to the right place, but the login credentials for the database connection are incorrect.</p><p>Be sure that your username, password, host name and database name are all correct and spelled correctly in <em>\'php/config/pdo_connect.php\'</em>.</p>';
}

$pe_connected_okay = "yep";

?>