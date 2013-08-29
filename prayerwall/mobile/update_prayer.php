<?php /* Prayer Engine - Update the Prayer Count on the Mobile Site */
	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	require_once '../' . DATABASE;
	
	if ($_POST) {
		$id = $_POST['id'];
		$prayercount = $_POST['prayer_count'] + 1;
		
		//update the prayer count
		$sql = "UPDATE prayers SET prayer_count=? WHERE id=?";
		$updateprayer = $dbh->prepare($sql);
		$updateprayer->execute(array($prayercount, $id));
		
		$findprayer = "SELECT * FROM prayers WHERE id = ?";
		$queryprayers = $dbh->prepare($findprayer); 
		$queryprayers->execute(array($id)); 
		$prayer = $queryprayers->fetch(PDO::FETCH_ASSOC);
		
		//Send a notification email if they signed up for one.
		include '../includes/prayed_for_this_email.php';
	} else {
		//get specified prayer request
		$id = $_GET['id'];
		$findprayer = "SELECT * FROM prayers WHERE id = ?";
		$queryprayers = $dbh->prepare($findprayer); 
		$queryprayers->execute(array($id)); 
		$prayer = $queryprayers->fetch(PDO::FETCH_ASSOC);
	};
?>

	<p class="thanks-for-praying">Thanks for Praying!</p>
	<p class="prayed-for-after"><?php if ($prayer['prayer_count'] == 0) { ?>Be the first to pray for this!<?php } ?><?php if ($prayer['prayer_count'] == 1) { ?>This has been prayed for 1 time.<?php } ?><?php if ($prayer['prayer_count'] > 1) { ?>This has been prayed for <strong><?php echo $prayer['prayer_count']; ?></strong> times.<?php } ?></p>

<?php $dbc = null; ?>