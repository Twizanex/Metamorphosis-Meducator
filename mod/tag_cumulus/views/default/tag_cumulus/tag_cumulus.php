<?php
   if(get_context() != 'admin'){
		echo '<div id="tag_cum" class="index_box">' ."<h2>Institutions</h2>";
    	echo display_tag_cumulus(0,30,'Affiliation','user','','','') . '</div>';
   }
?>