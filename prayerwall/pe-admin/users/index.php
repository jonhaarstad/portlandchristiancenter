<?php /* Prayer Engine - Listing of All Administrative Users */
	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . USER_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
	
	$messages = array(); //Set up messages array
	if (isset($_GET['created'])) { // Provide feedback if new user created.
		if ($_GET['created'] == 'yes') {
			$messages[] = "User successfully added as an administrator!";
		}
	}
	
	/****** Paginate Records ******/
	$display = 10; // How many records to display
	$getall = "SELECT * FROM users"; //Get records from database
	$countusers = $dbh->query($getall);
	
	if (isset($_GET['p']) && is_numeric($_GET['p'])) { // # of pages already been determined.
		$pages = $_GET['p'];
	} else { // Need to determine # of pages.
		$count = $countusers->rowCount(); // Count records of PDO query above
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
	$findusers = "SELECT * FROM users ORDER BY last_name ASC LIMIT $start, $display";	
	$users = $dbh->query($findusers);//Get records from database	
	/****** End Paginate Records ******/
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - All Administrative Users</title>
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
	<script src="../../javascripts/prayerengine/delete_user.js" type="text/javascript"></script>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
</head>
<body id="user">
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>
	
		<div id="pe-backend-content">
			<a href="new.php" class="new-user">Add a New User</a>
			<h2>All Administrative Users</h2>
			<p class="instructions">All registered users are listed below. Click a user to edit their details and access privileges. If you would like to permanently remove a user, simply click "Delete." Inactive user accounts are listed with a striped background.</p>
		
			<?php require_once '../includes/messagebox.php'; //Messages
			if(!empty($messages)) {echo '<br />';} ?>
			<table border="0" cellspacing="0" cellpadding="0" class="pe-list-table">	
				<?php foreach ($users as $user) {?>
				<tr id="row<?php echo $user['id'] ?>" <?php if ($user['active'] != null) { ?>class="inactive"<?php } ?>>
					<td class="link-cell"><a href="edit.php?id=<?php echo $user['id'] ?><?php if (isset($_GET['c']) && isset($_GET['p'])){echo '&amp;c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>"><?php echo stripslashes($user['first_name']) . " " . stripslashes($user['last_name']) ?></a></td>
					<td class="date-cell"><a href="edit.php?id=<?php echo $user['id'] ?><?php if (isset($_GET['c']) && isset($_GET['p'])){echo '&amp;c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>">Created on <?php echo date('F j, Y', strtotime($user['registration_date'])) ?></a></td>
					<td class="delete-cell"><?php if ($user['id'] != 1) { ?><a href="#" class="deletelink" name="<?php echo $user['id'] ?>">Delete</a><?php }; ?></td>
				</tr>	
				<?php } ?>
			</table>
	
			<?php require_once '../includes/pagination.php'; //Paginate Users ?>
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
</body>
</html>