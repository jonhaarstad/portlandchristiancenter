<?php /* Prayer Engine - RSS Feed of Prayer Tweets Specifically for Twitter  */
	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	require_once '../' . DATABASE;
	
	$getapprovedprayers = "SELECT * FROM prayers WHERE post_this = 1 AND twitter_ok = 1 AND (share_option = 'Share Online' OR share_option = 'Share Online Anonymously') ORDER BY date_received DESC";
	$prayers = $dbh->query($getapprovedprayers);
	header('Content-type: text/xml; charset=utf-8');
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?php echo TWITTER_TITLE ?></title>
    <link><?php echo BASE_URL ?></link>
    <description><?php echo TWITTER_DESCRIPTION ?></description>
    <copyright><?php echo MINISTRYNAME ?></copyright>
    <language>en-us</language>
    <atom:link href="<?php echo BASE_URL; ?>feeds/twitter.php" rel="self" type="application/rss+xml" />
	<?php foreach ($prayers as $prayer) { ?>
	<item>
      <title><?php if (($prayer['share_option'] == "Share Online Anonymously") || (($prayer['share_option'] == "Share Online") && (strlen($prayer['name']) == 0))) { ?>Anonymous Prayer Request<?php } else { ?>Prayer Request From <?php echo htmlspecialchars(stripslashes($prayer['name'])); ?> <?php }; ?></title>
      <description><?php echo htmlspecialchars(stripslashes($prayer['prayer_tweet'])) ?></description>
      <pubDate><?php echo date('D, d M Y H:i:s T', strtotime($prayer['date_received'])) ?></pubDate>
      <link><?php echo BASE_URL . 'index.php'; ?></link>
	  <guid><?php echo BASE_URL . 'index.php#' . $prayer['id']; ?></guid>
    </item>
	<?php } ?>
  </channel>
</rss>
<?php $dbc = null; ?>