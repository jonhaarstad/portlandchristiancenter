<?php /* Prayer Engine - Special Header for the Settings Page */ ?>

<div id="pe-backend-header">
	<div id="pe-user-options">
		<ul class="links">
			<li class="logout"><a href="../logout.php">Log Out</a></li>
			<li class="live"><a href="<?php echo BASE_URL; ?>" target="_blank">Live Prayer Wall</a></li>
			<li class="account"><a href="../users/update_account.php?from=<?php echo $userfrom; ?>">Update Your Account</a></li>
		</ul>
		<p class="welcome">Hello, <?php echo $_SESSION['first_name'] . ' ' .  $_SESSION['last_name']; ?></p>
	</div>
	<div id="pe-inner-header">
		<h2><?php if ($_POST && !empty($errors)) {echo $_POST['ministryname'];} else {echo $formsettings['ministryname'];} ?></h2>
		<h1><a href="../prayers/">The Prayer Engine</a></h1>
	</div>
</div>