<?php
        /**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Roger Curry, Grid Research Centre [curry@cpsc.ucalgary.ca]
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */
$ts = time();
$token = generate_action_token($ts);

if (isset($vars['entity'])) {
	if ($vars['entity']->comments_on == 'Off') {
		$comments_on = false;
	} else {
		$comments_on = true;
	}
			
	if (get_context() == "references" && $vars['entity'] instanceof ElggObject) {
		echo '<p>' . elgg_view("publication/references",$vars) . '</p>';
	} else {
		if (get_context() == "search" && $vars['entity'] instanceof ElggObject) {
			echo '<div class="search_listing"><div class="search_listing_icon">';
			echo '<img border="0" src="' . $CONFIG->wwwroot . 'mod/publications/_graphics/paper.png"/></div><div class="search_listing_info" style="min-height:52px">';
			echo 'Publication: ' . elgg_view('publication/references',$vars) . '</div></div>';
		} else {
			if ($vars['entity'] instanceof ElggObject) {
				$url = $vars['entity']->getURL();
				$owner = $vars['entity']->getOwnerEntity();
				$canedit = $vars['entity']->canEdit();
			} else {
				$url = 'javascript:history.go(-1);';
				$owner = $vars['user'];
				$canedit = false;
			}
				
?>

	<div class="contentWrapper singleview">
	
	<div class="publication_post">
	<h3><a href="<?php echo $url; ?>"><?php echo $vars['entity']->title; ?></a></h3>
			<div class="clearfloat"></div>
				
				<?php
					echo '<b>' . elgg_echo('publication:authors') . ':</b>';
					$authors = $vars['entity']->authors;
					$authors = explode(',',$authors);
					if(!(is_array($authors))) $authors = array($authors);
					foreach($authors as $author){
						if(!ctype_digit($author)) echo elgg_view('publication/authorinvite',array('exauthor'=>$author,'publication_guid'=>$vars['entity']->getGUID(),'canedit'=>$canedit));
						else{
							$user = get_entity($author);
							echo elgg_view_entity($user);
						}
					}
					echo '<br/>';
					$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
					if (!empty($tags)) {
						echo '<b>' .elgg_echo('publication:keywords') . ":</b><br/>" . $tags . "<br/><br/>";
					}
				?>
				<?php
                                        if (!empty($vars['entity']->source)) {
                                                echo '<b>' .elgg_echo('publication:source:ref') . ":</b> " . $vars['entity']->source . "<br/><br/>";
                                        }
                                ?>
				<?php 
					if (!empty($vars['entity']->year)) {	
						echo '<b>' .elgg_echo('publication:year') . ":</b> " . $vars['entity']->year . "<br/><br/>";
					}
				?>
				<?php
                                        if (!empty($vars['entity']->volume)) {
                                                echo '<b>' .elgg_echo('publication:volume') . ":</b> " . $vars['entity']->volume . "<br/><br/>";
                                        }
                                ?>
				<?php
                                        if (!empty($vars['entity']->issue)) {
                                                echo '<b>' .elgg_echo('publication:issue') . ":</b> " . $vars['entity']->issue . "<br/><br/>";
                                        }
                                ?>
				<?php
                                        if (!empty($vars['entity']->pages)) {
                                                echo '<b>' .elgg_echo('publication:pages') . ":</b> " . $vars['entity']->pages . "<br/><br/>";
                                        }
                                ?>
			<?php 
				if (!empty($vars['entity']->uri)) {
					$uri = $vars['entity']->uri;	
					echo '<b>' .elgg_echo('publication:uri') . ":</b> <a target=_blank href=\"" . $vars['entity']->uri . "\">$uri</a><br/><br/>";
				}
				
				if(!empty($vars['entity']->attachment)){
					$attachment = get_entity($vars['entity']->attachment);
					if($attachment){
					$attachment_url = $attachment->getUrl();
					echo '<b>' .elgg_echo('publication:attachment:title'). ":</b> <a href='$attachment_url'> $attachment->title</a><br/><br/>";
					}
				}
			?>

			<b>Abstract:</b>
			<div class="publication_post_body">

			<!-- display the actual publication post -->
				<?php
			
							echo elgg_view('output/longtext',array('value' => $vars['entity']->description));
				
				?>
			</div><div class="clearfloat"></div>			
			<!-- display edit options if it is the publication post owner -->
			<p class="options">
			<?php
	
				if (issuperadminloggedin()||($vars['user']->guid==$vars['entity']->owner_guid)) {
			?>
					<a href="<?php echo $vars['url']; ?>mod/publications/edit.php?publicationpost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>  &nbsp; 
					<?php
					
						echo elgg_view("output/confirmlink", array(
																	'href' => $vars['url'] . "action/publication/delete?publicationpost=" . $vars['entity']->getGUID()."&__elgg_ts=".$ts."&__elgg_token=".$token,
																	'text' => elgg_echo('delete'),
																	'confirm' => elgg_echo('deleteconfirm'),
																));
	
					// Allow the menu to be extended
					echo elgg_view("editmenu",array('entity' => $vars['entity']));
			?>
		<?php
			}
		?>
		</p>
	</div>
	</div>

<?php
		}
	}
}

?>
