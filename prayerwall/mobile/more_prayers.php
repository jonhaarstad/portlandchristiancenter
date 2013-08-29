<?php /*  Prayer Engine - Load More Prayer Requests When "Load More Prayer Requests" is tapped on the Mobile Site */

require_once '../config/subdirectories.php';
require_once '../gdphp/config/engineconfig.php';
require_once '../' . DATABASE;

include '../includes/paginated_prayer_requests.php';

?>
<ul>
	<?php foreach ($prayers as $prayer) { ?>
	<li>
		<a href="<?php echo BASE_URL ?>mobile/viewprayer.php?id=<?php echo $prayer['id']; ?>" class="ajax-link">
			<h3>From: <?php if ($prayer['share_option'] == "Share Online") {echo stripslashes($prayer['name']);} else {echo "Anonymous";}; if (($prayer['share_option'] == "Share Online") && (strlen($prayer['name']) == 0)) {echo "Anonymous";} ?></h3>
			<p><strong><?php echo date('M j', strtotime($prayer['date_received'])) ?></strong> - <?php echo stripslashes(substr($prayer['posted_prayer'],0,120)) ?>  <?php if (strlen($prayer['posted_prayer']) > 120) {echo '(<em>continued...</em>)';} ?></p>
		</a>
	</li>
	<?php } ?>
</ul>
<?php include '../includes/mobile_pagination.php'; ?>
<?php $dbc = null; ?>