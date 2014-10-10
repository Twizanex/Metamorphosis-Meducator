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
		$query2= "SELECT * FROM {$CONFIG->dbprefix}users_entity";
				$result2 = mysql_query($query2);
				while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
				{
						$total_count++;
				}
							
			$user_count=$total_count-$res_count	;


?>

        <div class="index_box">
		            <h2><?php echo "Latest Users"; ?></h2>

			<div class="contentWrapper">
				<?php 
				echo "<b>There are currently ".$user_count." registered users</b>"; ?>
 
            </div>
            <?php 
			$lc=0;
			if(isset($profs)) {
                    //display member avatars
					
                    foreach($profs as $members){

					if ( $lc>9) break;
		$query1= "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$members->guid."\"";
		$result1 = get_data($query1);
		
				$gid=$members->guid;

				if ($result1==NULL)
				{
					$metaaff=get_metadata_byname($gid,'Affiliation');
					$metacou=get_metadata_byname($gid,'Location');
					echo "<div class=\"filerepo_file\">";
					echo "		<div class=\"filerepo_icon\">\n"; 
					echo elgg_view("profile/icon",array('entity' => $members, 'size' => 'small'));
					echo "</div>";
					echo "		<div class=\"filerepo_title_owner_wrapper\">\n"; 
					echo "		<div class=\"filerepo_title\">".$members->name; 
					if ($metaaff->value !=NULL)
					echo "<br />Affiliation: ".$metaaff->value;
					if ($metacou->value !=NULL)
					echo "<br />Country: ".$metacou->value;
					echo "</div>";
					echo "</div>";
					echo  "</div>";
					$lc++;
                    }
				}
			 }

			 ?>

			
	        <div class="clearfloat"></div>
	      
        </div>