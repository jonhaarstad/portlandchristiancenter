<?php /* Prayer Engine - Load a Single Prayer Through AJAX on the Mobile Site */

	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	require_once '../' . DATABASE;
	require_once '../includes/markdown.php';
	
	$id = $_GET['id'];
	$findprayer = "SELECT * FROM prayers WHERE id = ?";
	$queryprayers = $dbh->prepare($findprayer);
	$queryprayers->execute(array($id));
	$prayer = $queryprayers->fetch(PDO::FETCH_ASSOC);

?>

<div class="prayer-header">
	<h2><?php if ($prayer['share_option'] == "Share Online") {echo stripslashes($prayer['name']);} else {echo "Anonymous";}; if (($prayer['share_option'] == "Share Online") && (strlen($prayer['name']) == 0)) {echo "Anonymous";} ?></h2>
	<h3><?php echo date('M j, Y', strtotime($prayer['date_received'])) ?></h3>
</div>

<div id="prayed-for-area">
	<form action="<?php echo BASE_URL ?>mobile/update_prayer.php?id=<?php echo $prayer['id']; ?>" id="prayerform" method="post">
		<input id="prayer_count" name="prayer_count" value="<?php echo $prayer['prayer_count']; ?>" type="hidden" />
		<input id="id" name="id" value="<?php echo $prayer['id']; ?>" type="hidden" />
		<a href="#" class="prayer-link">I prayed for this</a>
	</form>

	<?php if ($prayer['prayer_count'] == 0) {echo '<p class="prayed-for">Be the first to pray for this!</p>';} elseif ($prayer['prayer_count'] == 1) {echo '<p class="prayed-for">This has been prayed for <strong>1</strong> time.</p>';} else {echo '<p class="prayed-for">This has been prayed for <strong>' . $prayer['prayer_count'] . '</strong> times.</p>';} ?>
	
</div>

<div class="prayertext">
<?php echo Markdown(stripslashes($prayer['posted_prayer'])) ?>
</div>

<?php $dbc = null; ?>
