<?php

$rbody = "Thank you for sharing your prayer request at " . BASE_URL . "\n\n";
$rbody .= "Your Name: " . $name . "\n";
$rbody .= "Received: " . date('F j, Y', strtotime($datereceived)) . "\n";
$rbody .= "Your Email: " . $email . "\n";
$rbody .= "Your Phone Number: " . $phone . "\n\n";
$rbody .= stripslashes($prayer) . "\n\n";
$rbody .= "Your Sharing Instructions: Please " . $shareoption . "\n\n";
$rbody .= "Your prayer request is currently being moderated, and will then be shared according to your instructions. You may remove this prayer request from our Prayer Wall at any time by clicking the following link (this cannot be undone):\n\n";
$rbody .= BASE_URL . "actions/remove_request.php?e=" . urlencode($email) . "&dc=" . $dc . " \n\n";
$rbody .= "This is an automated notification sent from " . BASE_URL . ". Please do not respond to this email, as this account is not monitored.";
mail($email, 'Your Prayer Request Has Been Received', $rbody, 'From: ' . ROBOTEMAIL);

?>