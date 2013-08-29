<?php /*  Prayer Engine - Load Fresh Prayer Request When Mobile Form Submitted */

require_once '../config/subdirectories.php';
require_once '../gdphp/config/engineconfig.php';

?>
	<?php if (TWITTER == 'yes') { ?><script type="text/javascript" src="../javascripts/prayerengine/jquery.limit.js" ></script><?php } ?>
	<script type="text/javascript" src="../javascripts/prayerengine/mobile_prayer_form.js" ></script> 
<div class="prayer-header">
	<h2>Submit Your Prayer Request</h2>
</div>

<div id="prayer-form">
	<p class="form-thanks">Thank you for submitting your prayer request. It is currently being moderated, and will then be shared according to your instructions. Feel free to submit another request by filling out the form again.</p>
	<form action="<?php echo BASE_URL ?>mobile/submit_prayer_request.php" method="post" id="send-prayer-request">
		<div class="prayer-submit-table">
			<label for="name">Your Name:</label> 
			<input id="name" name="name" tabindex="1" type="text">
	
			<label for="email">Your Email:</label>
			<input id="email" name="email" tabindex="2" type="text">
	
			<label for="phone">Your Phone Number:</label>
			<input id="phone" name="phone" tabindex="3" type="text">

	
			<label for="desired_share_option">Can We Share This?:</label>
			<select id="desired_share_option" name="desired_share_option" tabindex="4">
				<option value="Share Online">Please Share This</option>
				<option value="Share Online Anonymously">Share Anonymously</option>
				<option value="DO NOT Share Online">Do Not Share This</option>
			</select>
	
			<label for="prayer">Your Prayer Request:</label>

			<textarea cols="40" id="prayer" name="prayer" rows="10" tabindex="5"></textarea>
			
			<label for="notifyme">Email When Someone Prays:</label>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><input type="radio" name="notifyme" value="1" class="radio" tabindex="6" /></td>
					<td class="radioanswer">Yes</td>
					<td><input type="radio" name="notifyme" value="0" class="radio" tabindex="7" /></td>
					<td class="radioanswer">No</td>
				</tr>
			</table>
			
			<?php if (TWITTER == 'yes') { ?>
			<label for="twitter_ok">Share This on Twitter?:</label>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><input type="radio" name="twitter_ok" value="1" class="radio" id="twitter_yes" tabindex="8" /></td>
					<td class="radioanswer">Yes</td>
					<td><input type="radio" name="twitter_ok" value="0" class="radio" id="twitter_no" tabindex="9" /></td>
					<td class="radioanswer">No</td>
				</tr>
			</table>
			
			<div id="twitter-area">
				<p class="twitter-counter"><span id="counter"></span> characters remaining.</p>
				<textarea cols="40" id="prayer_tweet" name="prayer_tweet" rows="10" tabindex="8" tabindex="10">Please list your prayer request as you would like it to appear on our Twitter Prayer Chain.</textarea>
			</div>
			<?php } ?>
			
			<input type="hidden" name="date_received" value="<?php echo date("Y-m-d H:i:s", time()) ?>" id="date_received" />
			<input type="hidden" name="prayer_count" value="0" id="prayer_count" />
			<input type="hidden" name="brand_new" value="1" id="brand_new" />
			<input type="hidden" name="post_this" value="0" id="post_this" />


			<a href="#" class="prayer-form-submit">Submit Request</a>
		</div>
	</form>
</div>