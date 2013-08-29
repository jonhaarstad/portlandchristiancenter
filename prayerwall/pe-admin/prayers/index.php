<?php /* Prayer Engine - Listing of All Prayer Requests */
	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . PRAYER_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
	require_once '../../includes/markdown.php';
	
	if (isset($_GET['pid'])) { // Redirect to edit requests directly from email
		if ($_GET['pid'] != 0) {
			$url = 'edit.php?id=' . $_GET['pid'] . '&new=yes'; // Define the URL:
			header("Location: $url");
			exit(); // Quit the script.
		}
	}
	
	$messages = array(); //Set up messages array
	if (isset($_GET['created'])) { // Provide feedback if new user created.
		if ($_GET['created'] == 'yes') {
			$messages[] = "Prayer request successfully added to the system!";
		}
	}
	
	/****** Paginate Records ******/
	$display = 10; // How many records to display
	$getall = "SELECT * FROM prayers"; //Get records from database
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
	$findprayers = "SELECT * FROM prayers ORDER BY id DESC LIMIT $start, $display";	
	$prayers = $dbh->query($findprayers);//Get records from database
	/****** End Paginate Records ******/
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - All Prayer Requests</title>
	<link rel="stylesheet" href="../../stylesheets/prayerengine/pe_backend.css" type="text/css" />
	<!-- Display fixes for Internet Explorer -->
		<!--[if lte IE 6]>
		<link href="../../stylesheets/prayerengine/backend_ie6_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 7]>
		<link href="../../stylesheets/prayerengine/backend_ie7_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 8]>
		<link href="../../stylesheets/prayerengine/backend_ie8_fix.css" rel="stylesheet" type="text/css" />
		<![endif]-->
	<!-- end display fixes for Internet Explorer -->
	<script src="../../javascripts/prayerengine/jquery.js" type="text/javascript"></script>
	<script src="../../javascripts/prayerengine/delete_prayer.js" type="text/javascript"></script>
</head>

<body id="prayer">
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<a href="new.php<?php if (isset($_GET['c']) && isset($_GET['p'])){echo '?c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>" class="new-request">Add a New Request</a>
			<h2>All Prayer Requests </h2>
			<p class="instructions">Click a prayer request to moderate content or edit other details. If you would like to permanently remove a prayer request, simply click "Delete." New prayer requests are labelled with a blue background. Prayer requests waiting for moderation are labelled with a striped background.</p>
			
			<?php require_once '../includes/messagebox.php'; //Messages
			if(!empty($messages)) {echo '<br />';} ?>
			<table border="0" cellspacing="0" cellpadding="0" class="pe-list-table">	
			<?php foreach ($prayers as $prayer) { ?>
				<tr id="row<?php echo $prayer['id'] ?>" <?php if ($prayer['brand_new'] == 1) { ?>class="new"<?php } elseif ($prayer['post_this'] == 0) {  ?>class="inactive"<?php } ?>>
					<td class="link-cell"><a href="edit.php?id=<?php echo $prayer['id'] ?><?php if (isset($_GET['c']) && isset($_GET['p'])){echo '&amp;c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?><?php if ($prayer['brand_new'] == 1) { ?>&amp;new=yes<?php } ?>"> <?php if ($prayer['name'] == null) {echo "Anonymous";} else {echo stripslashes($prayer['name']);} ?></a></td>
					<td class="date-cell"><a href="edit.php?id=<?php echo $prayer['id'] ?><?php if (isset($_GET['c']) && isset($_GET['p'])){echo '&amp;c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?><?php if ($prayer['brand_new'] == 1) { ?>&amp;new=yes<?php } ?>">Received on <?php echo date('F j, Y', strtotime($prayer['date_received'])) ?></a></td>
					<td class="delete-cell"><a href="#" class="deletelink" name="<?php echo $prayer['id'] ?>">Delete</a></td>
				</tr>	
			<?php } ?>
			</table>
	
			<?php require_once '../includes/pagination.php'; //Paginate Prayer Requests ?>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
</body>
</html>