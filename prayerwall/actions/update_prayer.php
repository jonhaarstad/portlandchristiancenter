<?php /* Prayer Engine - Update Prayer Count on Main Prayer Wall */
	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	require_once '../' . DATABASE;
	
	if ($_POST) {
		$id = $_POST['id'];
		$prayercount = $_POST['prayer_count'];
		
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

<blockquote>I Prayed for This</blockquote>
<?php if ($prayer['prayer_count'] == 1) { ?>
<h4>Prayed for <strong><?php echo number_format($prayer['prayer_count'], 0, '.', ','); ?></strong> time.</h4>
<?php } elseif ($prayer['prayer_count'] > 1) { ?>
<h4>Prayed for <strong><?php echo number_format($prayer['prayer_count'], 0, '.', ','); ?></strong> times.</h4>
<?php } else {} ?>

<?php $dbc = null; ?>