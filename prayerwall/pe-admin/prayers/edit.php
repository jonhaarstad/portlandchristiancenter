<?php /* Prayer Engine - Edit the Selected Prayer Request */
	require_once '../../config/subdirectories.php';
	require_once '../../gdphp/config/engineconfig.php';
	require_once '../../' . PRAYER_VALIDATION; // Validate that they're logged in
	require_once '../../' . DATABASE;
	require_once '../../includes/markdown.php';
	
	if ($_POST) {
		$errors = array(); //Set up errors array
		$messages = array(); //Set up messages array
		
		$id = $_POST['id'];
		$newname = $_POST['newname'];
		
		if (empty($_POST['posted_prayer'])) { //validate presence and length of prayer
			$errors[] = 'Please enter a prayer request.';
		} else {
			if (preg_match('/(href=)/', $_POST['posted_prayer']) || preg_match('/(HREF=)/', $_POST['posted_prayer'])) { // Simple check for spam hyperlinks
				$errors[] = 'Sorry, no HTML is allowed in your prayer request.';
			} else {
				$prayer = strip_tags($_POST['posted_prayer']);
			}
		};
		
		$shareoption = $_REQUEST['share_option'];
		$postthis = $_POST['post_this'];
		
		if (isset($_POST['notifyme'])) { //email notification
			$notifyme = $_POST['notifyme'];
		} else {
			$notifyme = 0;
		}
		
		if (isset($_POST['twitter_ok'])) { //tweet prayer
			$twitterok = $_POST['twitter_ok'];
		} else {
			$twitterok = 0;
		}
		
		if (isset($_POST['twitter_ok'])) { //validate prayer tweet
			if (!empty($_POST['prayer_tweet'])) { 
				if (preg_match('/(href=)/', $_POST['prayer_tweet']) || preg_match('/(HREF=)/', $_POST['prayer_tweet'])) { // Simple check for spam hyperlinks
					$errors[] = 'Sorry, no HTML is allowed in the prayer tweet.';
				} else {
					$prayertweet = strip_tags(substr($_POST['prayer_tweet'],0,140));
				}
			} else {
				$errors[] = 'Please enter a prayer tweet.';
			};
		} else {
			$prayertweet = NULL;
		}
		
		$moderated_by = $_POST['moderated_by'];
		
		if (empty($errors)) {
			//update the post
			$sql = "UPDATE prayers SET name=?, posted_prayer=?, share_option=?, post_this=?, notifyme=?, moderated_by=?, twitter_ok=?, prayer_tweet=? WHERE id=?";
			$updateprayer = $dbh->prepare($sql);
			$updateprayer->execute(array($newname, $prayer, $shareoption, $postthis, $notifyme, $moderated_by, $twitterok, $prayertweet, $id));
			$messages[] = "Prayer Request successfully updated!";

			//fill in form
			$findprayer = "SELECT * FROM prayers WHERE id = ?";
			$queryprayers = $dbh->prepare($findprayer);
			$queryprayers->execute(array($id)); 
			$prayer = $queryprayers->fetch(PDO::FETCH_ASSOC);
		} else {
			//fill in form
			$findprayer = "SELECT * FROM prayers WHERE id = ?";
			$queryprayers = $dbh->prepare($findprayer);
			$queryprayers->execute(array($id));
			$prayer = $queryprayers->fetch(PDO::FETCH_ASSOC);
		}
		
	} else {
		if (isset($_GET['new'])) { //Mark new prayer request as read
			if ($_GET['new'] == 'yes') {
				$id = $_GET['id'];
				$brandnew = 0;
				$sql = "UPDATE prayers SET brand_new=? WHERE id=?";
				$updateprayer = $dbh->prepare($sql);
				$updateprayer->execute(array($brandnew, $id));
				$url = 'edit.php?id=' . $id;
				header("Location: $url");
				exit();
			}	
		}
		
		//get specified prayer request
		$id = $_GET['id'];
		$findprayer = "SELECT * FROM prayers WHERE id = ?";
		$queryprayers = $dbh->prepare($findprayer);
		$queryprayers->execute(array($id)); 
		$prayer = $queryprayers->fetch(PDO::FETCH_ASSOC);
		
		if ($prayer['email'] == NULL) { // redirect to index if no prayer request is found.
			$url = 'index.php';
			header("Location: $url");
			exit();
		}
	};
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
	<title>Prayer Engine - Edit Prayer Request</title>
	<script src="../../javascripts/prayerengine/jquery.js" type="text/javascript"></script>
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
</head>
<body id="prayer">
	<?php include '../includes/header.php'; ?>
	<div id="pe-backend-container">
		<?php include '../includes/nav.php'; ?>

		<div id="pe-backend-content">
			<?php if ($prayer['post_this'] == 1 && isset($prayer['moderated_by'])) { ?><h3 class="moderation">Last moderated by <?php echo $prayer['moderated_by'] ?></h3><?php } else { ?><h3 class="moderation">Awaiting Moderation</h3><?php }; ?>
			<h2>Edit Prayer Request</h2>

			<div id="pe-backend-prayer-details">
				<h4><strong><?php echo stripslashes($prayer['name']) ?> <?php if (strlen($prayer['name']) == 0) {echo "Anonymous";} ?></strong> on <?php echo date('F j, Y', strtotime($prayer['date_received'])) ?> at <?php echo date('g:i A', strtotime($prayer['date_received'])) ?></h4>
					<h5><strong>Email:</strong> <a href="mailto:<?php echo stripslashes($prayer['email']) ?>"><?php echo stripslashes($prayer['email']) ?></a> &nbsp;&nbsp;<strong>Phone:</strong> <?php echo stripslashes($prayer['phone']) ?><?php if (strlen($prayer['phone']) == 0) {echo "Not Provided";} ?></h5>
	
				<?php echo Markdown(stripslashes('&quot;' . $prayer['prayer'] . '&quot;')) ?>
	
				<?php if ($prayer['desired_share_option'] == "Share Online") { ?><h4 class="sharetext">Please share this prayer request online.</h4><?php } ?>
				<?php if ($prayer['desired_share_option'] == "Share Online Anonymously") { ?><h4 class="sharetext">Please share this prayer request online <em>anonymously</em>.</h4><?php } ?>
				<?php if ($prayer['desired_share_option'] == "DO NOT Share Online") { ?><h4 class="sharetext">DO NOT share this prayer request online.</h4><?php } ?>
			</div>
	
			<?php require_once '../includes/errorbox.php'; //Errors ?>
			<?php require_once '../includes/messagebox.php'; //Messages ?>
			<form action="edit.php?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['c']) && isset($_GET['p'])){echo '&amp;c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>#formarea" method="post" class="edit-form-table" id="formarea">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="label-cell"><label for="newname">Name:</label></td>
						<td><input type="text" name="newname" value="<?php if ($_POST && !empty($errors)) {echo $_POST['newname'];} else {echo $prayer['name'];} ?>" id="newname" tabindex="1" /></td>
					</tr>
					<tr>
						<td class="label-cell"><label for="share_option">Sharing:</label></td>
						<td>
							<?php if ($prayer['post_this'] == 0) { ?>
							<select name="share_option" id="share_option" tabindex="2">
								<option value="Share Online" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "Share Online") { echo 'selected="selected"';}} else {if ($prayer['desired_share_option'] == "Share Online") {echo 'selected="selected"';}} ?>>Share Online</option>
								<option value="Share Online Anonymously" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "Share Online Anonymously") { echo 'selected="selected"';}} else {if ($prayer['desired_share_option'] == "Share Online Anonymously") {echo 'selected="selected"';}} ?>>Share Online Anonymously</option>
								<option value="DO NOT Share Online" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "DO NOT Share Online") { echo 'selected="selected"';}} else {if ($prayer['desired_share_option'] == "DO NOT Share Online") {echo 'selected="selected"';}} ?>>DO NOT Share Online</option>
							</select>
							<?php } else { ?>
							<select name="share_option" id="share_option" tabindex="2">
								<option value="Share Online" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "Share Online") { echo 'selected="selected"';}} else {if ($prayer['share_option'] == "Share Online") {echo 'selected="selected"';}} ?>>Share Online</option>
								<option value="Share Online Anonymously" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "Share Online Anonymously") { echo 'selected="selected"';}} else {if ($prayer['share_option'] == "Share Online Anonymously") {echo 'selected="selected"';}} ?>>Share Online Anonymously</option>
								<option value="DO NOT Share Online" <?php if ($_POST && !empty($errors)) {if ($_POST['share_option'] == "DO NOT Share Online") { echo 'selected="selected"';}} else {if ($prayer['share_option'] == "DO NOT Share Online") {echo 'selected="selected"';}} ?>>DO NOT Share Online</option>
							</select>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="label-cell"><label for="posted_prayer">Live Request:</label></td>
						<td>
							<?php if ($prayer['post_this'] == 0) { ?>
							<textarea name="posted_prayer" rows="8" cols="40" tabindex="3"><?php if ($_POST && !empty($errors)) {echo $_POST['posted_prayer'];} else {echo stripslashes($prayer['prayer']);} ?></textarea><br />
							<?php } else { ?>
							<textarea name="posted_prayer" rows="8" cols="40" tabindex="3"><?php if ($_POST && !empty($errors)) {echo $_POST['posted_prayer'];} else {echo stripslashes($prayer['posted_prayer']);} ?></textarea><br />
							<?php } ?>
						</td>
					</tr>
				</table>
				
				<?php if (TWITTER == 'yes') { ?>
				<div id="twitter-area" <?php if ($_POST && !empty($errors)) {if (isset($_POST['twitter_ok'])) {echo 'style="display:block"';}} else {if ($prayer['twitter_ok'] == 1) {echo 'style="display:block"';}} ?>>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td class="label-cell"><label for="prayer_tweet">Prayer Tweet:</label></td>
							<td>
								<textarea name="prayer_tweet" id="prayer_tweet" rows="8" cols="40" tabindex="4"><?php if ($_POST && !empty($errors)) {echo $_POST['prayer_tweet'];} else {echo stripslashes($prayer['prayer_tweet']);} ?></textarea>
								<p class="twitter-counter"><span id="counter">0</span>&nbsp;characters remaining.</p>
							</td>
						</tr>
					</table>
				</div>
				<?php } ?>
			
				<div id="pe-submit-area">
					<input name="notifyme" type="checkbox" value="1" id="notifyme" <?php if ($_POST && !empty($errors)) {if (isset($_POST['notifyme'])) {echo 'checked="checked"';}} else {if ($prayer['notifyme'] == 1) {echo 'checked="checked"';}} ?> class="check" tabindex="5" /> <label for="notifyme">Email When Someone Prays for Them</label>
					<?php if (TWITTER == 'yes') { ?><input name="twitter_ok" type="checkbox" value="1" id="twitter_ok" tabindex="6" <?php if ($_POST && !empty($errors)) {if (isset($_POST['twitter_ok'])) {echo 'checked="checked"';}} else {if ($prayer['twitter_ok'] == 1) {echo 'checked="checked"';}} ?> class="check" /> <label for="twitter_ok">Share This on Twitter</label><?php } ?>
					<input type="hidden" name="post_this" value="1" id="post_this" />
					<input type="hidden" name="id" value="<?php echo $prayer['id'] ?>" id="id" />
					<input type="hidden" name="moderated_by" value="<?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?>" id="moderated_by" />
					<input type="submit" value="Update Prayer" class="submit" tabindex="7" />
				</div>
			</form>
			<a href="index.php<?php if (isset($_GET['c']) && isset($_GET['p'])){echo '?c=' . $_GET['c'] . '&amp;p=' . $_GET['p'];} ?>" class="back-link"><strong>&laquo;</strong> Return to List</a>	
		</div>
	</div>
	<?php require_once '../includes/footer.php'; ?>
	<script src="../../javascripts/prayerengine/backend.js" type="text/javascript"></script>
	<?php if (TWITTER == 'yes') { ?>
		<script type="text/javascript" src="../../javascripts/prayerengine/jquery.limit.js"></script>
		<script type="text/javascript" src="../../javascripts/prayerengine/prayertweet.js"></script>
	<?php } ?>
</body>
</html>