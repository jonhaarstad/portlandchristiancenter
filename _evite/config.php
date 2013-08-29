<?php
/*********************************************************
		This Free Script was downloaded at			
		Free-php-Scripts.net (HelpPHP.net)			
	This script is produced under the LGPL license		
		Which is included with your download.			
	Not like you are going to read it, but it mostly	
	States that you are free to do whatever you want	
			With this script!						
		NOTE: Linkback is not required, 
	but its more of a show of appreciation to us.					
*********************************************************/

//Site Options and configuration

//Require user to enter their email -> 1=yes,2=no
$REQUIRE_EMAIL = 1;

//Require user to enter a message -> 1=yes,2=no
$REQUIRE_MESS = 1;

//Allow Multiple emails be sent -> 1=yes,2=no
//Multiple emails separated comma
$MULTIPLE_EMAILS = 1;

//Send current page in email -> 1=yes,2=no
$CUR_PAGE = 1;

//Site title
$EMAIL_OPTIONS['TITLE'] = 'Tell A Friend Demo';
//Site URL
$EMAIL_OPTIONS['URL'] = 'http://demo.free-php-scripts.com/Tell_a_Friend/';
//Main email
$EMAIL_OPTIONS['FROM'] = 'support@test.com';
//Charset 
$EMAIL_OPTIONS['CHARSET'] = 'utf-8';
//Type -> HTML=text/html | Text = text/plain
$EMAIL_OPTIONS['TYPE'] = 'text/html';
//Email subjects
$EMAIL_OPTIONS['EMAIL_SUBJECT'] = 'Tell A Friend For You';

/*
For Templates you can use the following variables:
	$+name+$ -> User Name Sending Contact
	$+page_send+$ -> Page user is comming from
	$+message_text+$ -> Contact Message
*/

//EMAIL Template

$EMAIL_TEMPLATE = '<b>Hello,</b></span><br /><br />
	<span align="justify"><b>Your friend $+name+$ decided to sent you a tell-a-friend notice.</b></span><br /><br />';
if($CUR_PAGE == 1){	
	$EMAIL_TEMPLATE .= '<span align="justify"><b>You can visit the page your friend sent his email: <font color="blue">$+page_send+$</font></b></span><br /><br />';
}
if($REQUIRE_MESS == 1){	
	$EMAIL_TEMPLATE .= '<span align="justify"><b>Your friend\'s message was: <font color="blue">$+message_text+$</font></b></span><br /><br />';
}
$EMAIL_TEMPLATE .= '<span align="justify"><b>You can visit our main url @ '.$EMAIL_OPTIONS['URL'].', </b></span><br /><br />
	<span align="justify"><b>'.$EMAIL_OPTIONS['TITLE'].'</b></span><br />';

/* +++++++++++++++++++++++++++++++++++++
		END FILE CONFIGURATION
---------------------------------------*/

/*
Partner Sites:
====================

Free File Upload: 
			http://www.HotFile.us
			
Free Image Hosting: 
			http://www.MyImage.us
			
Free Games (all type of games): 
			http://www.FunTimes.us
			
Free Templates: 
			http://www.allfreetemplates.us
			
PHP Skills and Tricks: 
			http://www.phptricks.com
*/
?>