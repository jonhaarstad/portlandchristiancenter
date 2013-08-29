<?php /* Prayer Engine - Display Error Messages */

if(!empty($errors)) { ?>
	<div id="errors">
		<p>Your changes could not be saved due to the following errors...</p>
		<ul>
		<?php foreach ($errors as $error) {
			echo "<li>$error</li>";
		}; ?>	
		</ul>
	</div>
<?php }; ?>