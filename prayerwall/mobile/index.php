<?php /* Prayer Engine - Main Page of Mobile Site... Everything Else Pulled in Via AJAX */
	
	require_once '../config/subdirectories.php';
	require_once '../gdphp/config/engineconfig.php';
	require_once '../' . DATABASE;
	include '../includes/paginated_prayer_requests.php';
	$prayercount = 0;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta name="Description" content="Welcome to the Mobile version of our prayer wall" />
	<meta name="viewport" content="user-scalable=no, width=320" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="apple-touch-icon-precomposed" href="../images/prayerengine/mobile/structure/applogo.png"/><!-- web app icon -->
	<link rel="apple-touch-startup-image" href="../images/prayerengine/mobile/structure/apploading.png" /><!-- web app loading screen -->
	
	

	<title>Prayer Wall</title>

	<!-- stylesheets -->
	<link href="../stylesheets/prayerengine/mobile_colors.css" rel="stylesheet" type="text/css" />
	<link href="../stylesheets/prayerengine/mobile.css" rel="stylesheet" type="text/css" />
	<!-- end stylesheets -->
	<script type="text/javascript" src="../javascripts/prayerengine/jquery.js"></script>
	<script type="text/javascript" src="../javascripts/prayerengine/jquery.scrollTo.js"></script>
	<script type="text/javascript" src="../javascripts/prayerengine/jquery.validate.js" ></script> 
	<script type="text/javascript" src="../javascripts/prayerengine/mobileactions.js"></script>
	
	</head>

	<body>
	<div id="site-container">
		
	<!-- main screen -->
	<div id="main-page">
		<!-- logo and slide down nav -->
		<h1 id="full-link"><a href="<?php echo BASE_URL; ?>index.php?display=full">View the Full Prayer Wall</a></h1>
		
		<div id="header">
			<div class="logo">
				<a href="<?php echo BASE_URL; ?>mobile">Prayer Wall</a>
			</div>
		</div>
		<!-- end logo and slide down nav -->
		
		<!-- main screen content -->
		<div class="content">
			<h3 class="title-divider">&nbsp;&nbsp;Recent Prayer Requests</h3>

			<a href="<?php echo BASE_URL ?>mobile/submit_prayer_request.php" class="form-link submit-your-request">Add Your Prayer Request</a>
			<ul>
				<?php foreach ($prayers as $prayer) {
					$prayercount = $prayercount + 1;
				?>
				<li <?php if ($prayercount == 1) { ?>class="noborder"<?php } ?>>
					<a href="<?php echo BASE_URL ?>mobile/viewprayer.php?id=<?php echo $prayer['id']; ?>" class="ajax-link">
						<h3>From: <?php if ($prayer['share_option'] == "Share Online") {echo stripslashes($prayer['name']);} else {echo "Anonymous";}; if (($prayer['share_option'] == "Share Online") && (strlen($prayer['name']) == 0)) {echo "Anonymous";} ?></h3>
						<p><strong><?php echo date('M j', strtotime($prayer['date_received'])) ?></strong> - <?php echo stripslashes(substr($prayer['posted_prayer'],0,120)) ?>  <?php if (strlen($prayer['posted_prayer']) > 120) {echo '(<em>continued...</em>)';} ?></p>
					</a>
				</li>
				<?php } ?>
			</ul>
			<?php include '../includes/mobile_pagination.php'; ?>
		</div>
	</div>
	
	<!-- ajax area for selected content -->	
	<div id="selected-item">
		<div class="sub-nav">
			<a href="#" class="back-link"><strong>&laquo;</strong> Back</a>
		</div>
		
		<div class="content">
		</div>
	</div>
	<!-- end ajax area -->
	
</div>
<?php $dbc = null; ?>
	
