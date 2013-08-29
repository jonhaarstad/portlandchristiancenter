<?php /* Prayer Engine - Paginates All Public Prayer Requests for the Prayer Wall */
	$display = PRAYERSDISPLAYED; // How many records to display
	$getall = "SELECT * FROM prayers WHERE post_this = 1 AND (share_option = 'Share Online' OR share_option = 'Share Online Anonymously') ORDER BY id DESC"; //Get records from database
	$countprayers = $dbh->query($getall);
	
	if (isset($_GET['p']) && is_numeric($_GET['p'])) { // # of pages already been determined.
		$pages = $_GET['p'];
	} else { // Need to determine # of pages.
		$count = $countprayers->rowCount(); // Count records of PDO query above
		if ($count > $display) { // More than 1 page.
			$pages = ceil($count/$display);
		} else {
			$pages = 1;
		}
	}
	
	// Determine where in the database to start returning results...
	if (isset($_GET['c']) && is_numeric($_GET['c'])) {
		$start = $_GET['c'];
	} else {
		$start = 0;
	}
	
	// Get records from database according to the page and display count
	$findprayers = "SELECT * FROM prayers WHERE post_this = 1 AND (share_option = 'Share Online' OR share_option = 'Share Online Anonymously') ORDER BY id DESC LIMIT $start, $display";	
	$prayers = $dbh->query($findprayers);//Get records from database
?>