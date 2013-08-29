<?php  /* Prayer Engine - Update to Make The Prayer Engine Work Properly in Sub-Directories */

// YOU CAN IGNORE THIS STEP UNLESS YOU'RE INSTALLING THE PRAYER ENGINE IN A SUB-DIRECTORY ON YOUR SITE :-)
//
// If you plan to use The Prayer Engine in a sub-directory (www.yoursite.com/prayer/) rather than
// using it in the root of your public directory (www.yoursite.com or prayer.yoursite.com), you'll
// need to adjust the variable below.
//
// For example, for www.yoursite.com/prayer/ you would list one sub-dirctory:
// $number_of_subdirectories = 1;
// For www.yoursite.com/somedirectory/prayer/, you would list 2 sub-directories:
// $number_of_subdirectories = 2;
// and so on. The Prayer Engine can be no more than 5 sub-directories deep into your site.

$number_of_subdirectories = 0;

/* ---------- LEAVE THIS PART ALONE! ---------- */

if ($number_of_subdirectories == 0) {
	define ('DOCLEVEL', '');
} elseif ($number_of_subdirectories == 1) {
	define ('DOCLEVEL', '../');
} elseif ($number_of_subdirectories == 2) {
	define ('DOCLEVEL', '../../');
} elseif ($number_of_subdirectories == 3) {
	define ('DOCLEVEL', '../../../');	
} elseif ($number_of_subdirectories == 4) {
	define ('DOCLEVEL', '../../../../');	
} elseif ($number_of_subdirectories == 5) {
	define ('DOCLEVEL', '../../../../../');
} else {
	define ('DOCLEVEL', '');
}

?>