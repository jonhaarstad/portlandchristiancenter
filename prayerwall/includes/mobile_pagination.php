<?php /* Prayer Engine - Powers "Load More Prayer Requests" on the Mobile Site */ ?>
<div id="more-prayers<?php echo $start + $display;?>">
	<h3 class="more-prayers-link">
<?php
		if ($pages > 1) {
			$thispage = curPageName();
			$current_page = ($start/$display) + 1;
			
			if ($current_page != $pages) { // make next button if not the last page
				echo '<a href="' . BASE_URL . 'mobile/more_prayers.php?c=' . ($start + $display) . '&amp;p=' . $pages . '" class="moreprayers" title="' . ($start + $display) . '">Load More Prayer Requests...</a>';
			}
		}
?>
	</h3>
</div>