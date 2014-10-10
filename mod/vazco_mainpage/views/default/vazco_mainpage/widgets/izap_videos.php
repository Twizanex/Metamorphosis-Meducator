<?php
/**
* iZAP izap_videos
*
* @package youtube, vimeo, veoh and onserver uploading
* @license GNU Public License version 3
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version 1.5-1.0
*/
if (is_plugin_enabled('izap_videos')){

	$videos = $vars['entities'];
	$total = 16;
	if(isset($vars['videosTOdisplay']))
	$total = $vars['videosTOdisplay'];
	echo '<div class="index_box">';
	echo elgg_view_title(elgg_echo('videos'));
	echo '<div class="contentWrapper">';
	?>
	<script>
	    function selectTab(selectedTab, displayDiv) {
	        $('#ltab').removeClass('selected');
	        $('#vtab').removeClass('selected');
	        $('#ctab').removeClass('selected');
	        
	        $('#latest').hide('slow');
	        $('#views').hide('slow');
	        $('#com').hide('slow');
	
	        $('#'+displayDiv).show('slow');
	        $('#'+displayDiv).load('<?php echo $vars['url']; ?>mod/izap_videos/customindexVideos.php?type='+displayDiv+'&videosTOdisplay='+<?php echo $total;?>);
	        $('#'+selectedTab).addClass('selected');
	    }
	</script>
	<div id="elgg_horizontal_tabbed_nav">
	    <ul>
	        <li id="ltab"><a href="javascript: selectTab('ltab', 'latest');"><?php echo elgg_echo('izap_videos:latestvideos'); ?></a></li>
	        <li id="vtab"><a href="javascript: selectTab('vtab', 'views');"><?php echo elgg_echo('izap_videos:topViewed'); ?></a></li>
	        <li id="ctab"><a href="javascript: selectTab('ctab', 'com');"><?php echo elgg_echo('izap_videos:topCommented'); ?></a></li>
	    </ul>
	</div>
	<?php
	echo '<div id="latest">';
	    echo '<p align="center"><img src="'.$CONFIG->wwwroot .'mod/embed/images/loading.gif"></p>';
	echo '</div>';
	echo '<div id="views">';
	    echo '<p align="center"><img src="'.$CONFIG->wwwroot .'mod/embed/images/loading.gif"></p>';
	echo '</div>';
	echo '<div id="com">';
	    echo '<p align="center"><img src="'.$CONFIG->wwwroot .'mod/embed/images/loading.gif"></p>';
	echo '</div>';
	echo '</div></div>';
	?>
	<script language="javascript">
	    $(document).ready(function(){
	       selectTab('ltab', 'latest');
	    });
	</script>
<?php }?>