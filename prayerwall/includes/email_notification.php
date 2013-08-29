<?php /* Prayer Engine - Send Email to Administrators When New Prayer Request Received */

if (NOTIFICATION == "yes") {
	$findusers = "SELECT * FROM users WHERE notifications = 1";	
	$users = $dbh->query($findusers);
	
	$findid = "SELECT id FROM prayers ORDER BY id DESC LIMIT 1";
	$ids = $dbh->query($findid);	
	$newid = $ids->fetch(PDO::FETCH_ASSOC);
	
	foreach ($users as $user) {	
		$body = "A new prayer request has been received on the prayer wall:\n\n";
		$body .= "From: " . $name . "\n";
		$body .= "Received: " . date('F j, Y', strtotime($datereceived)) . "\n";
		$body .= "Their Email: " . $email . "\n";
		$body .= "Their Phone Number: " . $phone . "\n\n";
		$body .= stripslashes($prayer) . "\n\n";
		$body .= "Please " . $shareoption . "\n\n";
		$body .= "To moderate this prayer request, please log in using the link below:\n";
		$body .= BASE_URL . "pe-admin/prayers/index.php?pid=" . $newid['id'];
		mail($user['email'], 'New Prayer Request Received', $body, 'From: ' . EMAIL);
	} 
}

?>