<?php

	/**
	 * Elgg custom profile 
	 * You can edit the layout of this page with your own layout and style. Whatever you put in the file
	 * will replace the frontpage of your Elgg site.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	 
?>

	<table cellpadding="0" cellspacing="0" border="0" width="986px">
		<link rel="stylesheet" href="<?php echo $vars['url']; ?>vendors/coda/stylesheets/coda-slider-2.0.css" type="text/css" media="screen" />	
		<script type="text/javascript" src="<?php echo $vars['url'];?>vendors/coda/javascripts/jquery.coda-slider-2.0.js"></script>
		 <script type="text/javascript">
$().ready(function() {
       $('#coda-slider-1').codaSlider({
           dynamicArrows: false,
		   dynamicTabs: false,
		              autoHeightEaseDuration: 10,
           autoHeightEaseFunction: "easeInBounce",
           slideEaseDuration: 10,
           slideEaseFunction: "easeInBounce"

       });
   });

		 </script>
        <tr>
            <td align="center" valign="top">
                <table cellpadding="0" cellspacing="0" border="0" width="986px">
                    <tr>
                        <td class="Ann_Left" nowrap="nowrap" />
                        <td class="Ann_Repeater" nowrap="nowrap">
                            <br /><b>ANNOUNCEMENTS</b>
                        </td>
         <?php //               <td class="Previous_Arrow" nowrap="nowrap" onclick="window.open('http://yahoo.com','_blank');"
                //            onmouseover="this.style.cursor='pointer';" />
                 //       <td class="Next_Arrow" nowrap="nowrap" onclick="window.open('http://yahoo.com','_blank');"
                  //           onmouseover="this.style.cursor='pointer';" />   ?>
                        <td id="Announcements" class="Ann_Repeater" nowrap="nowrap">
                          <br /> Welcome to MetaMorphosis+. Please Hard refresh (shift+refresh) your browsers to view the new layout 
                        </td>
                        <td class="Ann_Right" nowrap="nowrap" />
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table cellpadding="20px" cellspacing="20px" border="0" width="986px">
                    <tr>
                        <td rowspan="4" id="LeftPane"  valign="top" width="65%" style="padding:20px 20px 10px 0px;">
<?php
$profs=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
?>


<?php 
		$res_count=0;
		$total_count=0;
		$user_count=0;
/*		$query1= "SELECT guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE is_content_item = \"1\"";
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
*/
		$res_count=count(get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000));
//		foreach ($count as $cou)
	//	    $res_count++;
		$user_count=count( get_entities_from_metadata('issimpleuser', 'yes', 'user', '', '',10000));	
	//	foreach ($countuser as $countus)
	//		$user_count++;
			
		$members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
		foreach ($members as $member)
		{
		$friends = $member->getFriends("", $num=30, $offset = 0);
		$friendsof = $member->getFriendsof("", $num=30, $offset = 0);
			if ($friends!=FALSE)
			{	$repc++;
					//echo $member->name."<br />";
				
			}
			else if ($member->meducator25)
			{
			$repc++;
					//echo $member->name."<br />";
			}
		
		}
?>



<!-- latest members -->
        <div class="index_box">
            <h2><?php echo "30 Latest Resources"; ?></h2> 
			<div class="contentWrapper">
				<?php 
							//	$repc=$repc1+$repc2;
				echo "<b><font color=\"gray\">There are currently ".$res_count." Educational Resources (".$repc." repurposed)</font></b>" ;
				?>
 
            </div>
			     <div id="coda-nav-1" class="coda-nav">
       	<ul>
           	<li class="tab1"><a href="#1">1-10</a></li>
               <li class="tab2"><a href="#2">11-20</a></li>
               <li class="tab3"><a href="#3">21-30</a></li>
			   &nbsp &nbsp <a href="http://metamorphosis.med.duth.gr/mod/content_item/all_resources.php"> View all</a>

           </ul>
       </div>

            <?php 

			$lc=0;
			
			if(isset($profs)) {
                    //display member avatars ?>
							<div class="coda-slider-wrapper">
								<div class="coda-slider preload" id="coda-slider-1">
									<div class="panel">
										<div class="panel-wrapper">
  <?php                  foreach($profs as $members){

					if ( $lc>29) break;
		$query1= "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$members->guid."\"";
		$result1 = get_data($query1);
		$res= mysql_query($query1);
		while($row = mysql_fetch_array($res, MYSQL_ASSOC))
				$nikolas=$row['creator_guid'];
		$creator=get_entity($nikolas);
		$gid=$members->guid;
		if ($lc==10) {
	?>	
									</div>
										</div>
									<div class="panel">
										<div class="panel-wrapper">
<?php		
		}
				if ($lc==20) {
	?>	
									</div>
										</div>
									<div class="panel">
										<div class="panel-wrapper">
<?php		
		
		
		
		}

				if ($result1!=NULL)
				{
                                    if ($members->extparent!=1) {
					$time=$members->getTimeCreated();
					$metalang=get_metadata_byname($gid,'meducator5');
					$metakey=get_metadata_byname($gid,'meducator4');
					$metadesc=get_metadata_byname($gid,'meducator7');
					echo "<div class=\"filerepo_file\">";
					echo "		<div class=\"filerepo_icon\">\n"; 
					echo elgg_view("profile/icon",array('entity' => $members, 'size' => 'small'));
					echo "</div>";
					echo "		<div class=\"filerepo_title_owner_wrapper\">\n"; 
					echo "		<div class=\"filerepo_title\"><b><a href=\"".$vars['url']."pg/profile/".$members->username."\">".$members->name."</a></b>"; 
	
					echo " <br />Created by: ";	
					echo "<a href=\"".$vars['url']."pg/profile/".$creator->username."\">".$creator->name."</a>";
					echo " (".friendly_time($time).")"; 
					if ($metadesc->value !=NULL)
					echo "<br /><br />".$metadesc->value;
					echo "<br /><br />";
					if ($metalang->value !=NULL)
					echo "<b>Language:    </b>".$metalang->value."<br />";
					if ($metakey->value !=NULL)
					echo "<b>Keywords:    </b>".$metakey->value; 
					echo  "</div>";
					echo "</div>";
					echo "</div>";
					$lc++; }
				/*	else {
					$time=$members->getTimeCreated();
					$metalang=get_metadata_byname($gid,'meducator5');
					$metakey=get_metadata_byname($gid,'meducator4');
					$metadesc=get_metadata_byname($gid,'meducator7');
					echo "<div class=\"filerepo_file\">";
					echo "		<div class=\"filerepo_icon\">\n"; 
					echo elgg_view("profile/icon",array('entity' => $members, 'size' => 'small'));
					echo "</div>";
					echo "		<div class=\"filerepo_title_owner_wrapper\">\n"; 
					echo "		<div class=\"filerepo_title\"><b><a href=\"".$vars['url']."pg/profile/".$members->username."\">".$members->name."(external parent)</a></b>"; 
	
					echo " <br />Created by: ";	
					echo "<a href=\"".$vars['url']."pg/profile/".$creator->username."\">".$creator->name."</a>";
					echo " (".friendly_time($time).")"; 
					if ($metadesc->value !=NULL)
					echo "<br /><br />".$metadesc->value;
					echo "<br /><br />";
					if ($metalang->value !=NULL)
					echo "<b>Language:    </b>".$metalang->value."<br />";
					if ($metakey->value !=NULL)
					echo "<b>Keywords:    </b>".$metakey->value; 
					echo  "</div>";
					echo "</div>";
					echo "</div>";
					$lc++;
					
					} */
                    }
				}
			 }
            ?>
			</div> </div> </div> </div>
	        <div class="clearfloat"></div>
	      
        </div>
		</td>
                        <td id="RightPane"  valign="top" width="22%" style="padding:20px 20px 10px 20px;height:100%;">
                            <!-- RightPane -->
<?php
$profs=get_entities_from_metadata('issimpleuser', 'yes', 'user', '', '',5);

	?>


        <div class="index_box2">
		            <h2><?php echo "Latest Users"; ?></h2>

			<div class="contentWrapper">
				<?php 
				echo "<b><font color=\"gray\">There are currently ".$user_count." registered users</font></b>"; ?>
 
            </div>
            <?php 
			$lc=0;
			if(isset($profs)) {
                    //display member avatars
					
                    foreach($profs as $members){

					if ( $lc>3) break;
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
					echo "		<div class=\"filerepo_title\"><b><a href=\"".$vars['url']."pg/profile/".$members->username."\">".$members->name."</a></b>"; 
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
                        </td>
                    </tr>
                    <tr>
                        <td id="RightPane2"  valign="top" style="padding:20px 20px 10px 0px;height:100%;">
                            <!-- RightPane2 -->
                           <?php echo  elgg_view("tag_cumulus/tag_cumulus"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td id="RightPane3"  valign="top" style="padding:20px 20px 10px 0px; height:100%;">
                            <!-- RightPane3 -->
					<div id="nik" class="index_box">
	
<?php
$ents=get_entities('object','publication',0,'',4,0,false,0,null,0,0);
	 echo          "<h2>Latest Publications</h2>";
	foreach ($ents as $ent) 
	{
					$link=$ent->getURL();
     				echo "<div class=\"filerepo_file\">";
					echo "		<div class=\"filerepo_icon\">\n"; 
					echo "<img src=\"".$vars['url']."_graphics/pubtiny.png\">";
					echo "</div>";
					echo "		<div class=\"filerepo_title_owner_wrapper\">\n"; 
					echo "		<div class=\"filerepo_title\">";

					echo "<b><a href=\"$link\">".$ent->title."</a></b><br /><br />";
					echo "<b>Authors: </b>".$ent->authors;
					echo "</div>";
					echo "</div>";
					echo "</div>";




	}		 ?>
		        <div class="clearfloat"></div>
	</div>
	 </td>
                    </tr>
					<tr><td id="RightPane4"  valign="top" style="padding:20px 20px 10px 0px;height 100%;";>
					
					
					

									<div id="nik2" class="index_box">
	
<?php /*
$ents=get_entities('object','blog',0,'',3,0,false,0,null,0,0);
	 echo          "<h2>Latest Blog</h2>";
	foreach ($ents as $ent) 
	{
					$own=get_entity($ent->owner_guid);
     				$time=$ent->getTimeCreated();
					$link=$ent->getURL();
					echo "<div class=\"filerepo_file\">";
					echo "		<div class=\"filerepo_icon\">\n"; 
					echo "<img src=\"".$vars['url']."_graphics/blogtiny.png\">";
					echo "</div>";
					echo "		<div class=\"filerepo_title_owner_wrapper\">\n"; 
					echo "		<div class=\"filerepo_title\">";

					echo "<b><a href=\"$link\">".$ent->title."</a></b><br /><br />";
					echo "<b>Created By: </b>".$own->name;
					echo "<br /><br />";
					echo " (".friendly_time($time).")"; 
					echo "</div>";
					echo "</div>";
					echo "</div>";




	}	*/	 ?>
	</div>
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					</td></tr>
                </table>
            </td>
        </tr>
    </table>