<?php

	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

	//get the widget list from the database
	$mainpageWidgets = $_SERVER['mainpageWidgets'];
	$mainpageWidgets->load();
	
	$login_text = get_plugin_setting('loginbox_text','vazco_mainpage');
	$brief_content = get_plugin_setting('brief_content','vazco_mainpage');
	if ($brief_content == "yes"){
		set_context('search');	
	}
	if ($show3columns == "yes")
		$loginBox = elgg_view("vazco_mainpage/widgets/narrow/login");
	else
		$loginBox = elgg_view("vazco_mainpage/widgets/narrow/login");
	$show3columns = $mainpageWidgets->use3ColumnLayout();
?>
<div id="mainpage_content">
	<div id="custom_index">
	    <!-- left column content -->
	    <div id="index_left<?php if ($show3columns == "yes"){?>_narrow<?php }?>">
	        <!-- welcome message -->
	        <div id="index_welcome<?php if($show3columns == "yes"){?>_narrow<?php }else if(get_plugin_setting('loginbox_wide','vazco_mainpage') == 'yes'){?>_wide<?php }?>"> 
	        	<?php
	        		if (isloggedin()){
		        		echo "<h2>" . elgg_echo("welcome") . " ";
	        			echo $vars['user']->name;
	        			echo "</h2>";

	    			}
	        	?>
	            <?php
	            	//include a view that plugins can extend
	            	echo elgg_view("index/lefthandside");
	            ?>
		        <?php
		            //this displays some content when the user is logged out
				    if (!isloggedin() && (!function_exists("loginbox_enabled") || loginbox_enabled())){
		            	//display the login form

				    	echo $loginBox;
				    	echo $login_text;
				    	echo "<div class=\"clearfloat\"></div>";
			        }
		        ?>
	        </div>
	<?php
		echo $mainpageWidgets->displayColumnWidgets("left");
	?>
	    </div>
	    <?php if ($show3columns == "yes"){?>
		    <div id="index_center">
			<?php
				echo $mainpageWidgets->displayColumnWidgets("center",false);	
			?>
			</div>
	    <?php }?>
	    
	    <!-- right hand column -->
	    <div id="index_right<?php if ($show3columns == "yes"){?>_narrow<?php }?>">
	        <!-- more content -->
		    <?php
			    //include a view that plugins can extend
	            echo elgg_view("index/righthandside");
				echo $mainpageWidgets->displayColumnWidgets("right");	    
	            
	        ?>
	
		<?php 
			//display right column widgets
			foreach($rightColumnWidgets as $widget){
				echo $widget->getWidget();
			}	
		?>
	    </div>
	    <div class="clearfloat"></div>
	</div>
</div>
<?php set_context('main');?>