<?php

	/**
	 * Elgg Poll plugin
	 * @package Elggpoll
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @Original author John Mellberg
	 * website http://www.syslogicinc.com
	 * @Modified By Team Webgalli to work with ElggV1.5
	 * www.webgalli.com or www.m4medicine.com
	 */
	 

	if (isset($vars['entity'])) {

		$showUser = true;;
		if (isset($vars['showUser']))
			$showUser = $vars['showUser'];

		if (get_context() == "search") {
				
			//display the correct layout depending on gallery or list view
			if (get_input('search_viewtype') == "gallery") {

				//display the gallery view
            	echo elgg_view("poll/gallery",$vars);

			} else {
				
				echo elgg_view("poll/listing",$vars);

			}

		} else {
			
	?>
		<!-- patches by webgalli -->
	<?php if ($showUser){?>
		<div class="poll_post">
			<!-- display the user icon -->
			<div class="poll_post_icon">
			    <?php
			        echo elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
				?>
		    </div>
	
				<p class="strapline">
					<?php echo sprintf(elgg_echo("poll:strapline"), date("F j, Y",$vars['entity']->time_created)); ?>
					<?php echo elgg_echo('by'); ?> <a href="<?php echo $vars['url']; ?>pg/ad/<?php echo $vars['entity']->getOwnerEntity()->username; ?>"><?php echo $vars['entity']->getOwnerEntity()->name; ?></a> 
					<!-- display the comments link -->
					<?php
				    //get the number of responses
					$num_responses = $vars['entity']->countAnnotations('vote');
					//get the number of comments
					$num_comments = elgg_count_comments($vars['entity']);
				    ?>
				    <?php echo "(" . $num_responses . " " . sprintf(elgg_echo("poll:responses:num")) . ")"; ?> 
				<a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo sprintf(elgg_echo("comments")) . " (" . $num_comments . ")"; ?></a><BR>		
				</p>
				<!-- display tags -->
					<?php
		
						$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
						if (!empty($tags)) {
							echo '<p class="tags">' . $tags . '</p>';
						}
					?>
				</div>
			<?php }?>
			<!-- patches by webgalli -->

	<div class="poll_post">
		<div class="poll_post_body">

			<!-- display the actual poll post -->
	<div class="contentWrapper">
			<?php
				
				$isPgOwner = ($vars['entity']->getOwnerEntity()->guid == $vars['user']->guid);
				$priorVote = checkForPreviousVote($vars['entity'], $vars['user']->guid);

		
		$alreadyVoted = 0;
        if ( $priorVote !== false ) {
          $alreadyVoted = 1;
        }
				
				//if user has voted, show the results
				if ( $alreadyVoted ) {
          // show the user's vote
					echo "<p><h2>" . elgg_echo('poll:voted') . "</h2></p>";
				} else {
					
					//else show the voting form
					echo elgg_view('poll/forms/vote', array('entity' => $vars['entity']));
					
				}
			?>
		
		</div>
		</div>
		<div class="clearfloat"></div>
			
		
    <!-- show results -->
	    <?php if ($alreadyVoted) { ?>
			<!-- show results -->
			<div class="contentWrapper">
			<p align="center"><a onclick="toggleResults();"><?php echo elgg_echo('poll:results'); ?></a></p>
			</div>
			<div id="resultsDiv" class="poll_post_body" style="display:none;">
				<?php echo elgg_view('vazco_mainpage/poll/results',array('entity' => $vars['entity'])); ?>
		</div>
			
		<?php
			}
	?>
		
		<div class="clearfloat"></div>
						

	</div>

	<script type="text/javascript">
		function toggleResults()
		{
			var resultsDiv = document.getElementById('resultsDiv');
			
			if (resultsDiv.style.display == 'none')
			{
				resultsDiv.style.display = 'block';
			}
			else 
			{	
				resultsDiv.style.display = 'none';
			}
		}		
	</script>
	
<?php

			// If we've been asked to display the full view
			if (isset($vars['full']) && $vars['full'] == true) {
				echo elgg_view_comments($vars['entity']);
			}
				
		}

	}
?>