<?php
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

?>
<div class="index_box">
	<?php
	  global $CONFIG;
	    
	  if (!class_exists('SimplePie'))
	  {
	    require_once $CONFIG->pluginspath . '/vazco_mainpage/models/simplepie.inc';
	  }
	  
	  $blog_tags = '<a><p><br><b><i><em><del><pre><strong><ul><ol><li>';
	  $feed_url =  get_plugin_setting('feed_url','vazco_mainpage');
	  if($feed_url){
	
	    $excerpt   = get_plugin_setting('excerpt','vazco_mainpage');
	    $num_items = get_plugin_setting('feed_num_items','vazco_mainpage');
	    $post_date = get_plugin_setting('post_date','vazco_mainpage');
	     
	    $cache_loc = $CONFIG->pluginspath . '/vazco_mainpage/cache';
	    
	    $feed = new SimplePie($feed_url, $cache_loc);
	    
	    // doubles timeout if going through a proxy
	    //$feed->set_timeout(20);
	    
	    $num_posts_in_feed = $feed->get_item_quantity();
	    
	    // only display errors to profile owner
	    if (get_loggedin_userid() == page_owner())
	    {        
	      if (!$num_posts_in_feed)
	        echo '<p>' . elgg_echo('vazco_mainpage:simplepie:notfind') . '</p>';
	    }
	?>
	<h2><a href="<?php echo $feed->get_permalink(); ?>"><?php echo $feed->get_title(); ?></a></h2>
	<div class="contentWrapper singleview">

	<?php
	  if ($num_items > $num_posts_in_feed)
	    $num_items = $num_posts_in_feed;
	    
	  foreach ($feed->get_items(0,$num_items) as $item):
	?>
			<div class="simplepie_item">
				<div class="simplepie_title">
				<h4><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h4>
	    	</div>
				<?php 
	        if ($excerpt)
	        {
	          echo '<div class="simplepie_excerpt">' . strip_tags($item->get_description(true),$blog_tags) . '</div>';
	        }
	
	        if ($post_date) 
	        {
	      ?>
	        <div class="simplepie_date"><?php echo elgg_echo('vazco_mainpage:simplepie:postedon').' '; echo $item->get_date('j F Y | g:i a'); ?></div>
	      <?php } ?>
			</div>
	<?php endforeach; ?>
	
	<?php 
	  } else {
	    if (get_loggedin_userid() == page_owner())        
	      echo '<p>' . elgg_echo('vazco_mainpage:simplepie:notset') . '</p>';      
	  }
	?>
	</div>
</div>
