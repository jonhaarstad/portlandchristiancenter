<?php /* Prayer Engine - Notify By Email When "I Prayed For This" is Clicked */

if ($prayer['notifyme'] == 1) {
	$body = "This is a quick email to let you know that someone has just prayed for your prayer request!\n\n";
	$body .= "This is an automated notification sent from " . BASE_URL . ". Please do not respond to this email, as this account is not monitored.";
	mail($prayer['email'], 'Someone just prayed for you!', $body, 'From: ' . ROBOTEMAIL);
} 

?>