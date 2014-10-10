	<script type="text/javascript" src="jquery.asmselect.js"></script>

	<script type="text/javascript">

		$(document).ready(function() {
			$("select[multiple]").asmSelect({
				addItemTarget: 'bottom',
				animate: true,
				highlight: true,
				sortable: true
			});
			
		}); 

	</script>

	<link rel="stylesheet" type="text/css" href="jquery.asmselect.css" />
<?php $luser=$_SESSION['guid'];
$orguid=$_GET['compost'];
$original=get_entity($orguid);

 ?>
<div class="contentWrapper">
<form id="myForm" action="<?php echo $vars['url']; ?>action/companion/edit" method="post">
 
<p><?php echo "Collection Title";?><br />
<?php echo elgg_view('input/text',array('internalname' => 'title','value' =>$original->title)); ?></p>
 
<p><?php echo "Collection Description"; ?><br />
<?php echo elgg_view('input/longtext',array('internalname' => 'body','value' =>$original->description)); ?></p>
 
<p><?php echo "Collection Keywords"; ?><br />
<?php echo elgg_view('input/tags',array('internalname' => 'tags','value' =>$original->tags)); ?></p>

<p><?php echo "Please select the resources to add to this collection"; ?><br />
        <select name="resources[]" multiple="multiple">
<?php 

			$query4= "SELECT guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE creator_guid = \"".$luser."\" and is_content_item = \"1\"";
			$result4 = mysql_query($query4);						
			while($row = mysql_fetch_array($result4, MYSQL_ASSOC))
				{	$nikolas4=$row['guid'];
							if (get_entity($nikolas4)){
							
							$nikob=get_user($nikolas4);
							$objur=$nikob->getURL();
							echo "<option value=\"$nikob->guid\">";
							echo $nikob->name;
							echo "</option>"; }
				}
?>		
     
        </select> </p>

 <?php
$ori=implode(",",$original->identifiers); 
 echo elgg_view('input/hidden',array('internalname' => 'oriden','value' =>$ori)); ?></p>
 <?php echo elgg_view('input/hidden',array('internalname' => 'orgu','value' =>$original->guid)); ?></p>

<?php echo elgg_view('input/securitytoken'); ?>
 
<p><?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?></p>

 
</form>
</div>