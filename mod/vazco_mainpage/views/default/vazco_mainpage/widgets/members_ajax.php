<?php 
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

	//get the newest members who have an avatar
$profs=get_entities($type = "user",$subtype="",$owner_guid=0,$order_by="",$limit = 0,$offset = 0,$count = false,$site_guid=0,$container_guid = null,$timelower=0,$timeupper=0);
?>


<?php 
		$res_count=0;
		$total_count=0;
		$query1= "SELECT guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE is_content_item = \"1\"";
				$result1 = mysql_query($query1);
				while($row = mysql_fetch_array($result1, MYSQL_ASSOC))
				{
						$nikolas1=$row['guid'];
						if (get_entity($nikolas1))
						$res_count++;
				}
				

		$query3= "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='925' AND m1.string like '%.%')) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc limit 0,100000000";
				$result3 = mysql_query($query3);
							while($row = mysql_fetch_array($result3, MYSQL_ASSOC))
				{
						$repc1++;
				}
		$query4="SELECT elgg_content_item_discrimination.guid FROM elgg_content_item_discrimination INNER JOIN elggentity_relationships ON elgg_content_item_discrimination.guid=elggentity_relationships.guid_two";	
			$result4 = mysql_query($query4);
										while($row = mysql_fetch_array($result4, MYSQL_ASSOC))
				{
						$repc2++;
				}

?>



<!-- latest members -->
        <div class="index_box">
            <h2><?php echo "Latest Resources"; ?></h2>
			<div class="contentWrapper">
				<?php 
								$repc=$repc1+$repc2;
				echo "<b>There are currently ".$res_count." Educational Resources.<br />".$repc." are repurposed.</b>" ;
				?>
 
            </div>
            <?php 

			$lc=0;
			
			if(isset($profs)) {
                    //display member avatars
					
                    foreach($profs as $members){

		//			if ( $lc>9000) break;
		$query1= "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$members->guid."\"";
		$result1 = get_data($query1);
		$res= mysql_query($query1);
		while($row = mysql_fetch_array($res, MYSQL_ASSOC))
				$nikolas=$row['creator_guid'];
		$creator=get_entity($nikolas);
		$gid=$members->guid;


				if ($result1!=NULL)
				{
					$metalang=get_metadata_byname($gid,'meducator5');
					$metakey=get_metadata_byname($gid,'meducator4');
					$metadesc=get_metadata_byname($gid,'meducator7');
///////////////////////////////////////////////////////////////////////////////////////////////
	$cont_item_ID = $members->guid;

$avatarId = 3039;
	if ($avatarId){
		$userId = $members->guid;
		create_metadata($userId, 'avatar', $avatarId,'', $userId, ACCESS_PUBLIC);

		create_metadata($userId, 'icontime', time(),'', 0, ACCESS_PUBLIC); }
/////////////////////////////////////////////////////////////////////////////////////		
					echo "<div class=\"filerepo_file\">";
					echo "		<div class=\"filerepo_icon\">\n"; 
					echo elgg_view("profile/icon",array('entity' => $members, 'size' => 'small'));
					echo "</div>";
					echo "		<div class=\"filerepo_title_owner_wrapper\">\n"; 
					echo "		<div class=\"filerepo_title\">Name: ".$members->name; 
					echo " <br />Created by: ";	
					echo "<a href=\"".$vars['url']."pg/profile/".$creator->username."\">".$creator->name."</a>";
					if ($metadesc->value !=NULL)
					echo "<br /><br />".$metadesc->value."<br /><br />";

					if ($metalang->value !=NULL)
					echo "<b>Language:    </b>".$metalang->value."<br />";
					if ($metakey->value !=NULL)
					echo "<b>Keywords:    </b>".$metakey->value; 
					echo  "</div>";
					echo "</div>";
					echo "</div>";
					$lc++;
                    }
				}
			 }
            ?>
	        <div class="clearfloat"></div>
	      
        </div>