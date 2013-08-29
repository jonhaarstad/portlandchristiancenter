<?php /* Prayer Engine - Pagination for Various Pages */
		if ($pages > 1) {
			$thispage = curPageName();
			echo '<div class="pe-pagination">';
			$current_page = ($start/$display) + 1;
			if ($current_page != 1) { // make previous button if not first page
				echo '<a href="' . $thispage . '?c=' . ($start - $display) . '&amp;p=' . $pages . '">&laquo; Previous</a> ';
			}
			
			if ($pages > 11) { // Make no more than 10 links to other pages at a time.
				if ($current_page < 6) {
					for ($i = 1; $i <= 11; $i++) { 
						if ($i != $current_page) {
							echo '<a href="' . $thispage . '?c=' . (($display * ($i - 1))) . '&amp;p=' . $pages . '">' . $i . '</a> ';
						} else {
							echo '<span class="pe-current-page">' . $i . '</span> ';
						}
					} 
					echo '<a href="' . $thispage . '?c=' . (($pages - 1) * $display) . '&amp;p=' . $pages . '">&hellip;' . $pages . '</a>';
				} else {

					if ($pages - $current_page <= 5) {
						echo '<a href="' . $thispage . '?c=0&amp;p=' . $pages . '">1&hellip;</a>';
						$startpoint = $pages - 10;
						for ($i = $startpoint; $i <= $pages; $i++) { 
							if ($i != $current_page) {
								echo '<a href="' . $thispage . '?c=' . (($display * ($i - 1))) . '&amp;p=' . $pages . '">' . $i . '</a> ';
							} else {
								echo '<span class="pe-current-page">' . $i . '</span> ';
							}
						} 
						
					} else {
						if ($current_page != 6) {
							echo '<a href="' . $thispage . '?c=0&amp;p=' . $pages . '">1&hellip;</a>';
						}					
						$startpoint = $current_page - 5;
						$endpoint = $current_page + 5;
						for ($i = $startpoint; $i <= $endpoint; $i++) { 
							if ($i != $current_page) {
								echo '<a href="' . $thispage . '?c=' . (($display * ($i - 1))) . '&amp;p=' . $pages . '">' . $i . '</a> ';
							} else {
								echo '<span class="pe-current-page">' . $i . '</span> ';
							}
						} 
						echo '<a href="' . $thispage . '?c=' . (($pages - 1) * $display) . '&amp;p=' . $pages . '">&hellip;' . $pages . '</a>';	
					}
				}
			} else {
				for ($i = 1; $i <= $pages; $i++) { 
					if ($i != $current_page) {
						echo '<a href="' . $thispage . '?c=' . (($display * ($i - 1))) . '&amp;p=' . $pages . '">' . $i . '</a> ';
					} else {
						echo '<span class="pe-current-page">' . $i . '</span> ';
					}
				} 
			}
			
			if ($current_page != $pages) { // make next button if not the last page
				echo '<a href="' . $thispage . '?c=' . ($start + $display) . '&amp;p=' . $pages . '">Next &raquo;</a>';
			}
	
			echo "<div style=\"clear: both;\"></div></div>\n"; 	
		}
?>