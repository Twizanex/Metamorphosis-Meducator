<?php
// if something messe up do this : delete_entity(8818);
 $ts = time();
$token = generate_action_token($ts);
 echo elgg_view_title("Collection: ".$vars['entity']->title); ?>
 
<div class="contentWrapper">
 
<p><?php echo "<b>Description: </b>".$vars['entity']->description; ?> </p>
 
 <p>
 <?php 

 $pinakas=$vars['entity']->identifiers;
// print_r ($pinakas);
foreach ($pinakas as $pin) {
	$ent=get_entity($pin);
	if($ent)
	{
		$link=$ent->getURL();
	echo "<a href=\"$link\">$ent->name</a><br />";
	}
 }
?> 

  </p>
<?php echo "<b>Tags: </b>"; echo elgg_view('output/tags', array('tags' => $vars['entity']->tags)); ?>
<br /><br />
<p><?php
$own=$vars['entity']->owner_guid;
$owner=get_entity($own);


 echo"Created by: ".$owner->name; ?> </p>


<?php if ($vars['entity']->owner_guid==$_SESSION['guid'] || issuperadminloggedin()) { ?>

<div class="profile_info_edit_buttons">
<?php					echo elgg_view("output/confirmlink", array(
																	'href' => $vars['url'] . "action/companion/delete?compost=" . $vars['entity']->getGUID()."&__elgg_ts=".$ts."&__elgg_token=".$token,
																	'text' => "Delete ",
																	'confirm' => elgg_echo('deleteconfirm'),
																));
																
?>
<a href="<?php echo $vars['url']; ?>mod/companion/edit.php?compost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo"  Edit"; ?></a>
</div>
<br />	
</div>	
<?php }
else { ?>		
<div class="profile_info_edit_buttons">
<a href="<?php echo $vars['url']; ?>mod/companion/addedit.php?compost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo"  Edit"; ?></a>
</div>
<br />	
</div>
<?php } ?>													