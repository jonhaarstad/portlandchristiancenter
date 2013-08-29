<ul id="pe-backend-nav">
	<?php if ($_SESSION['vp'] == 1) { ?><li class="prayer"><a href="../prayers">Prayer Requests</a></li><?php } ?>
	<?php if ($_SESSION['vr'] == 1) { ?><li class="report"><a href="../reports">Report Library</a></li><?php } ?>
	<?php if ($_SESSION['vu'] == 1) { ?><li class="user"><a href="../users">Manage Users</a></li><?php } ?>
	<?php if ($_SESSION['vs'] == 1) { ?><li class="settings"><a href="../settings">Engine Settings</a></li><?php } ?>
</ul>