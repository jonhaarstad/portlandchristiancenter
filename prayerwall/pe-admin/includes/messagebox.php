<?php /* Prayer Engine - Display Sucess Messages */

if(!empty($messages)) { ?>
	<div id="messages">
	<?php foreach ($messages as $message) {
		echo "<p>$message</p>";
	}; ?>	
	</div>
<?php }; ?>