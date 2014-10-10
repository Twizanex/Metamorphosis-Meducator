<?php

	/**
	 * Elgg user display
	 * 
	 * @package Elgg
	 * @subpackage Core


	 * @link http://elgg.org/
	 */

	 
	 
		if ($vars['full']) {
						$ts = time();
					$token = generate_action_token($ts);
												   	$ent=$vars['entity'];
	$res_count=0;
    //the page owner
	$owner = page_owner_entity();
	$creat=get_entity($vars['entity']->creatorg);

    //the number of files to display
	$num = (int) $vars['entity']->num_display;
	if (!$num)
		$num = 8;
		
	//get the correct size
	$size = (int) $vars['entity']->icon_size;
	if (!$size || $size == 1){
		$size_value = "small";
	}else{
    	$size_value = "tiny";
	}
		
    // Get the users friends
	$friends = $owner->getFriends("", $num, $offset = 0);
	$friendso= $owner->getFriendsOf("", $num, $offset = 0); 
	
	if ($owner->issimpleuser=='no') {
        $address = $CONFIG->API_URL . "eidsearch?id=http://metamorphosis.med.duth.gr/uid%23" . $owner->guid;
        $rdf_info = connectToSesame($address);

        require_once(dirname(dirname(dirname(dirname(__FILE__))))."/mod/mmsearch/custom/MeducatorParser.php");
        require_once(dirname(dirname(dirname(dirname(__FILE__))))."/mod/mmsearch/custom/MeducatorMetadata.php");

        $medParser = new MeducatorParser($rdf_info, true);
        $b=print_r($medParser,true);
	file_put_contents("bbb",$b);
	if(count($medParser->results) > 0)
          foreach($medParser->results as $key => $value)
          {
            $resourceSesameID = $key;
            $resourceData = $value;
          } else  {
              $resourceData = array();
              $resourceSesameID = "";
            }
            $mM = new MeducatorMetadata($resourceSesameID, $resourceData);
		if (issuperadminloggedin()) {
		//TEODOR print_r($mM); 
                
                }
//			echo "<br />";
//			foreach($mM->getData("creator") as $niks)
//			echo $niks;

//	exit();   


}
$delid=substr($mM->ID,36);



	?>
<?php
	/*		if ($vars['entity']->issimpleuser!='yes'){
											echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => "large",
												'override' => true,
											  )
					); 
					}
			else	{	
				echo "HUMAN";
						echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => "large",
												'override' => true,
											  )
					); 


					}
			*/		?>

<?php

					if ($vars['entity']->issimpleuser!='yes'){	?>
	
	<script type="text/javascript">
    $(document).ready(function () {
        $("a.tab").click(function () {
            $(".active").removeClass("active");
            $(this).addClass("active");
            $(".content").slideUp();
            var content_show = $(this).attr("title");
            $("#" + content_show).slideDown();
            return false;
        });
    });
</script>
<table class="UnderMainGradient_sm" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td align="center" valign="top">
            <table cellpadding="0" cellspacing="0" border="0" width="960px">
                <!-- Admin Actions --> <?php if (issuperadminloggedin()) { ?>
                <tr>
                    <td valign="top">
                        <!-- TEODOR <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="txtAnnouncement" align="left">
                                    Admin Actions
                                </td>
                                <td>
                                    <span><a href="<?php echo $vars['url']; ?>pg/profile/<?php echo $vars['entity']->username; ?>/edit/"><?php echo elgg_echo("profile:edit"); ?></a></span>
									<span>[LINK BUTTON 2]</span>
									<span>[LINK BUTTON 3]</span>
									<span><?php echo "page_owner=".page_owner().","."vars['user']->guid=".$vars['user']->guid.",page_owner_entity()->creatorg=".page_owner_entity()->creatorg; ?> <span>
                                </td>
                            </tr>
                        </table> TEODOR -->
                    </td>
                </tr>  <?php } ?>
                <!-- User Actions -->
		<?php 
		$nik=page_owner_entity()->creatorg;
		if ($nik==$vars['user']->guid||issuperadminloggedin()||page_owner() == $vars['user']->guid ) { ?>

                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="txtAnnouncement" align="left">
                                    Owner Actions
                                </td>
                                <td>
                                    <span><a href="<?php echo $vars['url']; ?>pg/profile/<?php echo $vars['entity']->username; ?>/edit/"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn-EditResource.png" width="125px" height="49px" /> </a></span>
                                    <span><a href="<?php echo $vars['url']; ?>action/admin/user/delete?guid=<?php echo $vars['entity']->guid."&sesid=".$delid."&__elgg_token=$token&__elgg_ts=$ts"; ?>" onclick="return confirm('Are you sure you want to delete this Resource')" ><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_DeleteResource.png" width="125px" height="49px" /> </a></span>
                                    <span><a href="<?php echo $vars['url']; ?>mod/content_item/loginas.php?id=<?php echo page_owner(); ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_AssumeIdentity.png" width="125px" height="49px" /> </a></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>   <?php } ?>
                <!-- Resource Main Profile -->
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" >
                            <tr>
                                <!-- Main Profile Left -->
                                <td class="ResPro_MainData_Left">
                                    <table cellpadding="0" cellspacing="4" border="0">
                                        <tr>
                                            <td rowspan="2" valign="top" align="left" style="width: 115px;">
											<?php 									echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => "medium",
												'override' => true,
											  )
					); ?>
											</td>
                                            <td valign="top" align="left">
                                                <p>
                                                    <span class="txtResourceTitle"><?php echo $owner->meducator3; ?></span>
                                                </p>
												<p>														<?php if( page_owner_entity()->extparent==1)
															echo "<b>This resource has been added to the system as an external parent automatically by his metadata creator.For IPR licencing and metadata check the original source <br />"; ?> </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <table cellpadding="0" cellspacing="2" border="0">
                                                    <tr>
                                                        <td class="txtResourceSubTitle">
                                                            License
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                            <?php $lic=$mM->getData("rights");
																if ($lic=="")
																	echo "No license has been declared. License your work with <a href=\"http://creativecommons.org/choose/\">Creative Commons</a>";
																else
																	for ($i=0;$i<sizeof($lic);$i++){
																	echo ($i+1).")".$lic[$i]."<br />";		}?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="txtResourceSubTitle">
                                                            Resource Language
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                            <?php echo $mM->getData("language"); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="txtResourceSubTitle">
                                                            Metadata Language
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                             <?php echo $mM->getData("metadataLanguage"); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="txtResourceSubTitle">
                                                            Quality Stamp
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                             <?php
															 $qual=$mM->getData("http://www.purl.org/meducator/ns/quality");
															if ($qual)
															for ($i=0;$i<sizeof($qual);$i++) 
															{
															 echo ($i+1).")".$qual[$i]."<br />";
                                                        
															} ?>
														</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" align="right">
                                                           <br /> [Resource Identifiers] <br />
														<?php 	$uris=$mM->getData("identifier");
														if ($uris)
														foreach($uris as $uri)
														{
														if ($uri[description]=='URL'||$uri[description]=='URI')
														{	if ($uri[value]!="")
															echo $uri[description].": "."<a href=\"$uri[value]\" target=\"_BLANK\" ><img src=\"http://metamorphosis.med.duth.gr/_graphics/images/Button Images/btn_DownloadResource.png\"></a>"."<br />"; }
														else
															echo $uri[description].": ".$uri[value]."<br />";
																								
														} ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <!-- Main Profile Right -->
                                <td class="ResPro_MainData_Right">
                                    <table cellpadding="0" cellspacing="4" border="0">
                                        <tr>
                                            <td colspan="2" align="left">
                                                <span class="txtCreatedRepurposed"><b>Metadata Creator:</b></span>
                                                <hr style="color: Gray;" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px" align="center">
                                                <?php echo elgg_view("profile/icon",array('entity' => $creat, 'size' => 'small')); ?>
                                            </td>
                                            <td align="left" valign="top">
                                                <span class="txtPlainLink"><b><?php echo $creat->name;?></b></span>
                                                <br />
												<span class="txtPlainText">
												                                                <br />

                                                <b>Affiliation: </b><?php echo $creat->Affiliation; ?>

                                                <br />
                                                <br />
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="left">
                                                <span class="txtCreatedRepurposed"><b>Resource Author(s):</b></span>
                                                <hr style="color: Gray;" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="left" valign="top">
												<span class="txtPlainText">
													<?php 	$i=1;		foreach($mM->getData("creator") as $niks){
																		echo $i.")Name: $niks[name] <br/> &nbsp&nbsp Affiliation: $niks[memberOf] <br/> &nbsp&nbsp FOAF URI: $niks[profileURI]"; echo "<br />"; $i++; }?>
												</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Under Main Profile -->
                <tr>
                    <td valign="top">
                        <table cellpadding="10" cellspacing="0" border="0" width="100%">
                            <tr>
                                <!-- Tabs Container -->
                                <td valign="top" align="left" style="width: 630px">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td valign="top" align="left" width="100%">
                                                <!-- Tabs -->
                                                <div class="tabbed_area">
                                                    <ul class="tabs">
                                                        <li><a href="#" title="content_1" class="tab active">DESCRIPTION</a></li>
                                                        <li><a href="#" title="content_2" class="tab">CLASSIFICATION</a></li>
                                                        <li><a href="#" title="content_3" class="tab">EDUCATIONAL</a></li>
                                                        <li><a href="#" title="content_4" class="tab">PARENT RESOURCES</a></li>
                                                    </ul>
                                                    <div id="content_1" class="content">
                                                        <ul>
                                                            <li><b>Date of Creation :</b>  <?php echo $mM->getData("created"); ?> </li>
													        <li><b>Cite This Resource as :</b>  <?php echo $mM->getData("citation"); ?> </li>
															<li><b>Educational Description :</b>  <?php echo $mM->getData("description"); ?> </li>
															<li><b>Technical Description :</b>  <?php echo $mM->getData("technicalDescription"); ?> </li>
                                                           <li><b>Companion Resources :</b>  <?php $nik=$mM->getData("isAccompaniedBy");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li>													 
													 </ul>
                                                    </div>
                                                    <div id="content_2" class="content">
                                                        <ul>
                                                           <li><b>Media Types :</b>  <?php $nik=$mM->getData("mediaType");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li>
														  <li><b>Resource Types :</b>  <?php $nik=$mM->getData("resourceType");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li>  
														  <li><b>Keywords :</b>  <?php $nik=$mM->getData("subject");
																							if (is_array($nik))
																							{
																							echo "<br />";
																								foreach ($nik as $temp)
																								{
																								echo "- ".$temp[label]."<i> ($temp[externalSource])</i> <br />";
																								}
																							}
																							else echo $nik;	?> </li>  
														  <li><b>Discipline :</b>  <?php $nik=$mM->getData("discipline");
																							if (is_array($nik))
																							{
																							echo "<br />";
																								foreach ($nik as $temp)
																								{
																								echo "- ".$temp[label]."<i> ($temp[externalSource])</i> <br />";
																								}
																							}
																							else echo $nik;	?> </li>  
														  <li><b>Speciality of Discipline :</b>  <?php $nik=$mM->getData("disciplineSpeciality");
																							if (is_array($nik))
																							{
																							echo "<br />";
																								foreach ($nik as $temp)
																								{
																								echo "- ".$temp[label]."<i> ($temp[externalSource])</i> <br />";
																								}
																							}
																							else echo $nik;	?> </li>  

														<li><b>Educational Level :</b>  <?php $nik=$mM->getData("educationalLevel");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li> 	
													</ul>
                                                    </div>
                                                    <div id="content_3" class="content">
                                                        <ul>
                                                    	<li><b>Educational Context :</b>  <?php $nik=$mM->getData("educationalContext");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li> 
														<li><b>Educational Instructions :</b>  <?php $nik=$mM->getData("teachingLearningInstructions");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li>                                                      
														<li><b>Educational Objectives :</b>  <?php $nik=$mM->getData("educationalObjectives");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li> 
														<li><b>Educational Outcomes :</b>  <?php $nik=$mM->getData("educationalOutcomes");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li> 
														<li><b>Assessment Methods :</b>  <?php $nik=$mM->getData("assessmentMethods");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li> 
														<li><b>Educational Prerequisites :</b>  <?php $nik=$mM->getData("educationalPrerequisites");
																							if (is_array($nik))
																							echo implode(",",$nik);
																							else echo $nik;	?> </li> 



													 </ul>
                                                    </div>
                                                    <div id="content_4" class="content">
                                                        <ul>
                                                            <li> <br />
															<?php 
															$nik=$mM->getData("hasRepurposingContext");
														//	print_r($nik);
												
															if (is_array($nik)) {
															
															if (isset($nik)&&$nik!=NULL){
															
															foreach ($nik as $ni) 
															{
																$parentdets=$ni[fromRepurposingContext];
																$parentit=$ni[repurposedFrom];
																foreach ($parentit as $parentitle) 
																	{
																		$tit=$parentitle[title];
																	}
													//			$parenturi=$nik[$x][repurposedFrom]
																$parentdescr=$ni[repurposingDescription];
																$order=$x+1;
																$x++;
																echo "<b>($order) ". $tit."</b><br />";
															if (is_array($parentdets))
																	foreach ($parentdets as $pd)
													  				echo "-Repurposing Context:".$pd."<br />";
																else echo "-Repurposing Context:".$parentdets."<br/>";
																echo "<br />-Repurposing Descripion:<br />".$parentdescr."<br /><br />-------------------------------------------------------------------------------------------------------------------------<br/>";	
																
															} 
												} else echo " Only information for repurposed resources will appear here";
												}	?>
                                                       </li>
													   </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <!-- Right Column -->
                                <td valign="top" align="left" style="width: 330px">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <!-- Action Icons -->
                                        <tr>
                                            <td valign="top" align="left">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding-bottom:10px;">
                                   <?php if (isloggedin()) { ?>                         <div class="ActionButtonContainer">
                                                                <div class="ActionButton"> 
	<a class='example6' href="<?php echo $vars['url'];?>mod/content_item/eval.php?id=<?php echo $vars['entity']->guid; ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_RateThis.png" width="45px" height="49px" /></a></p>
                                                                </div>
                                                            </div> 
                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
                                                                   <a href="javascript:location.href='<?php echo $vars['url']; ?>mod/bookmarks/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_Bookmark.png" width="45px" height="49px" /></a></p>
                                                                </div>
                                                            </div> 
                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
	<a href="<?php echo $vars['url'];?>mod/content_item/showrdf.php?id=<?php echo $vars['entity']->guid; ?>" target="_BLANK"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_ViewRDF.png" width="45px" height="49px" /></a></p>
                                                                </div>
                                                            </div><?php } ?>
<?php if (isloggedin()) { ?>
                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton"> 
                                 <a href="javascript:location.href='<?php echo $vars['url']; ?>/mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_Report.png" width="45px" height="49px" /></a></p>
                                                                </div>
                                                            </div> 
                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
	<a href="<?php echo $vars['url'];?>mod/content_item/show_history_content_item.php?content_item=<?php echo $vars['entity']->guid; ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_RepurposingHistory.png" width="45px" height="49px" /></a></p>
                                                                </div>
                                                            </div><?php } ?>
															                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
	<a href="http://www.facebook.com/sharer.php?u=<?php echo $vars['url'];?>pg/profile/<?php echo $vars['entity']->username; ?>&src=sp" target="_blank"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_FBShare.png" width="45px" height="49px" /></a></p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- Repurposed from/to -->
                                        <tr>
                                            <td valign="top" align="left" style="background-color: #eeeeee; padding:10px;">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <span class="txtCreatedRepurposed">Repurposed from:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                               <?php  ////////////KWDIKAS GIA  PARENTS
	if (is_array($friends) && sizeof($friends) > 0) {
	
	echo "<div >";

		foreach($friends as $friend) {
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($friend->guid), 'size' => $size_value));
			echo "</div>";
		}
}
		echo "</div>";
	
	
	?></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
														<br />
                                                            <span class="txtCreatedRepurposed">Repurposed to:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                 <?php if (is_array($friendso) && sizeof($friendso) > 0) { 
											  echo "<div>";
		
		foreach($friendso as $friendoa) {
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($friendoa->guid), 'size' => $size_value));
			echo "</div>";
		}

		echo "</div>";
		

   } ?></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- Latest Activity -->
                                        <tr>
                                            <td valign="top" align="left" style="padding:10px;">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <span class="txtCreatedRepurposed">Latest Activity:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                 <?php $type = $vars['entity']->content_type;
	if(!$type)
		$type = "mine";
		
	//based on type grab the correct content type
	if($type == "mine")
		$content_type = '';
	else
		$content_type = 'friend';
		
	//get the number of items to display
	$limit = $vars['entity']->num_display;
	if(!$limit)
		$limit = 4;
	
	//grab the river
	$river = elgg_view_river_items($owner->getGuid(), 0, $content_type, $content[0], $content[1], '', $limit,0,0,false);
	
	//display
	echo "<div class=\"contentWrapper\">";
	if($type != 'mine')
		echo "<div class='content_area_user_title'><h2>" . elgg_echo("friends") . "</h2></div>";
	echo $river;
	echo "</div>"; ?></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- Comments -->
                                        <tr>
                                            <td valign="top" align="left" style="background-color: #eeeeee; padding:10px;">
                                                <table cellpadding="0" cellspacing="0" border="0" >                                                    <tr>
                                                        <td>
                                                            <span class="txtCreatedRepurposed">Comments:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <?php

     /**
	 * Elgg messageboard plugin view page
	 *
	 * @todo let users choose how many messages they want displayed
	 *
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
	 // a couple of required variables
	 
	 //get the full page owner entity
     $entity = get_entity(page_owner());
     
     //the number of message to display
     $num_display = $vars['entity']->num_display;
     
     //if no num set, set to one
     if(!$num_display)
        $num_display = 5;
		
	 
?>
<script type="text/JavaScript">
$(document).ready(function(){
     
    $("#postit").click(function(){
        
        //display the ajax loading gif at the start of the function call
        //$('#loader').html('<img src="<?php echo $vars['url']; ?>_graphics/ajax_loader.gif" />');
        $('#loader').html('<?php echo elgg_view('ajax/loader',array('slashes' => true)); ?>');
        
        //load the results back into the message board contents and remove the loading gif
        //remember that the actual div being populated is determined on views/default/messageboard/messageboard.php     
        $("#messageboard_wrapper").load("<?php echo $vars['url']; ?>mod/messageboard/ajax_endpoint/load.php", {messageboard_content:$("[name=message_content]").val(), pageOwner:$("[name=pageOwner]").val(), numToDisplay:<?php echo $num_display; ?>}, function(){
                    $('#loader').empty(); // remove the loading gif
                    $('[name=message_content]').val(''); // clear the input textarea
                }); //end 
                 
    }); // end of the main click function
        
}); //end of the document .ready function   
</script>

<div><!-- start of mb_input_wrapper div -->

    <!-- message textarea -->
    <textarea name="message_content" id="testing" value="" class="input_textarea" style="width:100%" /></textarea>
   
    <!-- the person posting an item on the message board -->
    <input type="hidden" name="guid" value="<?php echo $_SESSION['guid']; ?>" class="guid"  />
   
    <!-- the page owner, this will be the profile owner -->
    <input type="hidden" name="pageOwner" value="<?php echo page_owner(); ?>" class="pageOwner"  />
   
    <!-- submit button -->
    <input type="submit" id="postit" value="<?php echo elgg_echo('messageboard:postit'); ?>">
    
    <!-- menu options -->
    <div id="messageboard_widget_menu">
        <a href="<?php echo $vars['url']; ?>pg/messageboard/<?php echo get_entity(page_owner())->username; ?>"><?php echo elgg_echo("messageboard:viewall"); ?></a>
    </div>
    
    <!-- loading graphic -->
    <div id="loader" class="loading">  </div>

</div><!-- end of mb_input_wrapper div -->

<?php

        //this for the first time the page loads, grab the latest 5 messages.
		$contents = $entity->getAnnotations('messageboard', $num_display, 0, 'desc');
		
		//as long as there is some content to display, display it
		if (!empty($contents)) {
    		
    		echo elgg_view('messageboard/messageboard',array('annotation' => $contents));
		
		} else {
    		
    		//put the required div on the page for the first message
    		echo "<div id=\"messageboard_wrapper\"></div>";
	
    	}
	
?></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

	
	
	
	
	
	
	
	
 <?php ///////////////////////////////////////////////  HUMAN PROFILE
 } else {?>

	<script type="text/javascript">
    $(document).ready(function () {
        $("a.tab").click(function () {
            $(".active").removeClass("active");
            $(this).addClass("active");
            $(".content").slideUp();
            var content_show = $(this).attr("title");
            $("#" + content_show).slideDown();
            return false;
        });
    });
</script>

<script type="text/javascript">
    //<![CDATA[
        function ShowHide() {
            $("#slidingDiv").animate({ "height": "toggle" }, { duration: 1000 });
        }
    //]]>
    </script>
<table class="UnderMainGradient_sm" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td align="center" valign="top">
            <table cellpadding="0" cellspacing="0" border="0" width="960px">
                <!-- Admin Actions --> <?php if (issuperadminloggedin()) { ?>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="txtAnnouncement" align="left">
                                    Admin Actions
                                </td>
                                <td>
                                    <span><a href="<?php echo $vars['url']; ?>pg/profile/<?php echo $vars['entity']->username; ?>/edit/"><?php echo elgg_echo("profile:edit"); ?></a></span>
                                    <span><a href="<?php echo $vars['url']; ?>action/admin/user/delete?guid=<?php echo $vars['entity']->guid."&__elgg_token=$token&__elgg_ts=$ts"; ?>" onclick="return confirm('Are you sure you want to delete this Resource')" ><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_DeleteResource.png" width="125px" height="49px" /> </a></span>
                                    <span><a href="<?php echo $vars['url']; ?>mod/content_item/loginas.php?id=<?php echo page_owner(); ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_AssumeIdentity.png" width="125px" height="49px" /> </a></span>
                                    <span><a href="<?php echo $vars['url']; ?>action/superadmin/makesuperadmin?guid=<?php echo $vars['entity']->guid."&__elgg_token=$token&__elgg_ts=$ts"; ?>" onclick="return confirm('Are you sure you want to make this user a superadmin?')" >MAKE SUPERADMIN</a></span>

									<span><?php echo "page_owner=".page_owner().","."vars['user']->guid=".$vars['user']->guid.",page_owner_entity()->creatorg=".page_owner_entity()->creatorg; ?> <span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>  <?php } ?>
                <!-- User Actions -->
		<?php 
	
		if (issuperadminloggedin()||page_owner() == $vars['user']->guid ) { ?>

                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="txtAnnouncement" align="left">
                                    My Actions
                                </td>
                                <td>
                                    <span><a href="<?php echo $vars['url']; ?>pg/profile/<?php echo $vars['entity']->username; ?>/edit/"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_EditThisProfile.png" width="125px" height="49px" /> </a></span>
                                    <span><a href="<?php echo $vars['url']; ?>pg/settings/user/<?php echo $vars['entity']->username; ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_EditLoginDetails.png" width="125px" height="49px" /> </a></span>
                                    <span><a href="<?php echo $vars['url']; ?>action/admin/user/resetpassword?guid=<?php echo $vars['entity']->guid."&__elgg_token=$token&__elgg_ts=$ts"; ?>" onclick="return confirm('Are you sure you want to reset your password?)" ><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_ResetPasswords.png" width="125px" height="49px" /> </a></span>

								</td>
                            </tr>
                        </table>
                    </td>
                </tr>   <?php } ?>
                <!-- Resource Main Profile -->
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <!-- Main Profile Left -->
                                <td class="ResPro_MainData_Left">
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td valign="top">
										                                    <table cellpadding="0" cellspacing="4" border="0">
                                        <tr>
                                            <td rowspan="2" valign="top" align="left" style="width: 215px;">
											<?php 									echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => "large",
												'override' => true,
											  )
					); ?>
						<br />    <a onclick="ShowHide(); return false;" href="#">Show/Hide full details</a> <br/>						</td>
                                            <td valign="top" align="left">
                                                <p>
                                                    <span class="txtResourceTitle"><?php echo $vars['entity']->name; ?></span>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <table cellpadding="0" cellspacing="2" border="0">
                                                    <tr>
                                                        <td class="txtResourceSubTitle">
                                                            Occupation
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                           <?php echo $vars['entity']->user6 ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="txtResourceSubTitle">
                                                            Affiliation
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                            <?php echo $vars['entity']->Affiliation ?>
                                                        </td>
                                                    </tr>
													  <tr>
                                                        <td class="txtResourceSubTitle">
                                                            Country
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                            <?php echo $vars['entity']->Location ?>
                                                        </td>
                                                    </tr>
													  <tr>
                                                        <td class="txtResourceSubTitle">
                                                            City
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                             <?php echo $vars['entity']->city ?>
                                                        </td>
                                                    </tr>
													  
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

										</td>
									</tr>
									<tr>
										<td valign="top">
										<div id="slidingDiv" style="display:none">
											<table cellpadding="0" cellspacing="2" border="0">
											<tr>
                                                        <td class="txtResourceSubTitle200">
                                                            <p>Research Interests</p>
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                             <p><?php echo $vars['entity']->user1 ?></p>
                                                        </td>
                                                    </tr>
													  <tr>
                                                        <td class="txtResourceSubTitle200">
                                                           <p>Teaching Interests/Subjects</p>
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                              <p><?php echo $vars['entity']->user2 ?></p>
                                                        </td>
                                                    </tr>
													  <tr>
                                                        <td class="txtResourceSubTitle200">
                                                            <p>Courses I am teaching</p>
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                            <p><?php echo $vars['entity']->user3 ?></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="txtResourceSubTitle200">
                                                          <p>Learning Interests/Subjects</p>
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                               <p><?php echo $vars['entity']->user4 ?></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="txtResourceSubTitle200">
                                                           <p>Reasons for searching / contributing medical information </p>
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                            <p><?php echo $vars['entity']->user5 ?></p>
                                                        </td>
                                                    </tr>
													 <tr>
                                                        <td class="txtResourceSubTitle200">
                                                           <p>Member of the Meducator Project?</p>
                                                        </td>
                                                        <td class="txtResourceTitleMetaData">
                                                            <p><?php echo $vars['entity']->user7 ?></p>
                                                        </td>
                                                    </tr>
											</table> </div>
										</td>
									</tr>
								</table>
                                </td>
                                <!-- Main Profile Right -->
                                <td class="ResPro_MainData_Right">
                                    <table cellpadding="0" cellspacing="4" border="0">
                                        <tr>
                                            <td align="left">
                                                <span class="txtCreatedRepurposed"><b>Has friends</b></span>
                                                <hr style="color: Gray;" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                               <?php  ////////////KWDIKAS GIA FRIENDS

	if (is_array($friends) && sizeof($friends) > 0) {
	
	echo "<div >";

		foreach($friends as $friend) {
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($friend->guid), 'size' => $size_value));
			echo "</div>";
		}
}
		echo "</div>";
	
	
	?>
								   
											   
                                            </td>
                                            
                                        </tr>
										<tr>
                                            <td align="left">
                                                <span class="txtCreatedRepurposed"><b>Is a friend of</b></span>
                                                <hr style="color: Gray;" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                              <?php if (is_array($friendso) && sizeof($friendso) > 0) { 
											  echo "<div>";
		
		foreach($friendso as $friendoa) {
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($friendoa->guid), 'size' => $size_value));
			echo "</div>";
		}

		echo "</div>";
		

   } ?>
                                            </td>
                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Under Main Profile -->
                <tr>
                    <td valign="top">
                        <table cellpadding="10" cellspacing="0" border="0" width="100%">
                            <tr>
                                <!-- Tabs Container -->
                                <td valign="top" align="left" style="width: 650px">
								     <table cellpadding="0" cellspacing="0" border="0">
                                    <tr><td>   <br/>                             
								<span class="txtAnnouncement">Created Resources </span> <hr /> </td></tr>
								<tr><td><div>
								 <?php     $created=get_entities_from_metadata('creatorg',page_owner(), 'user', '', '',10000); 
										foreach ($created as $creates) { ?>
								<div style="background: transparent url('<?php echo $vars['url']; ?>_graphics/ResourceList.png') no-repeat top left; padding-left: 45px; width: 270px; height: 90px; padding-bottom:10px; float: left; ">
    <div style="height: 63px; vertical-align: top; padding-top: 6px;">
        <span class="txtResourceList"><?php echo $creates->name; ?></span>
    </div>
    <div style="height: 27px;">
        <span class="txtResourceDate">Creation Date: <?php $time=$creates->getTimeCreated();
	$dat=date('Y-m-d', $time); echo $dat; ?> |
        </span><a href="<?php echo $vars['url']; ?>pg/profile/<?php echo $creates->username ?>" ><span class="txtResourceLink"> read more..</span></a>
    </div>
</div>
					 <?php } ?>			
								
								
								
								
								
								</div></td></tr>
                                    </table>
                                </td>
                                <!-- Right Column -->
                                <td valign="top" align="left" style="width: 330px">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <!-- Action Icons -->
                                        <tr>
                                            <td valign="top" align="left">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding-bottom:10px;">
                                                  <?php if(isloggedin()){ ?>  
														<div class="ActionButtonContainer">
                                                                <div class="ActionButton">
                      <a href="<?php echo $vars['url']; ?>action/friends/add?friend=<?php echo $vars['entity']->guid."&__elgg_token=$token&__elgg_ts=$ts"; ?>" onclick="return confirm('Are you sure you want to add this user to your friends?')" ><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_AddFriend.png" width="45px" height="49px" /></a>
                                                                </div>
                                                            </div> 
												  <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
	<a href="<?php echo $vars['url'];?>mod/messages/send.php?send_to=<?php echo $vars['entity']->guid; ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_MessageThisUser.png" width="45px" height="49px" /></a>
                                                                </div>
                                                            </div> 
                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
                                                                   <a href="javascript:location.href='<?php echo $vars['url']; ?>mod/bookmarks/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_Bookmark.png" width="45px" height="49px" /></a>
                                                                </div>
                                                            </div><?php }?>
                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
	<a href="<?php echo $vars['url'];?>pg/blog/<?php echo $vars['entity']->username; ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_ViewBlog.png" width="45px" height="49px" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
	<a href="<?php echo $vars['url'];?>pg/file/<?php echo $vars['entity']->username; ?>"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_ViewFiles.png" width="45px" height="49px" /></a>
                                                                </div>
                                                            </div>
                                    <?php if (isloggedin()) { ?>                        <div class="ActionButtonContainer">
                                                                <div class="ActionButton">
                                 <a href="javascript:location.href='<?php echo $vars['url']; ?>reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)"><img src="<?php echo $vars['url']; ?>_graphics/images/Button Images/btn_Report.png" width="45px" height="49px" /></a>
                                                                </div>
                                                            </div> <?php } ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                      
                                        <!-- Latest Activity -->
                                        <tr>
                                            <td valign="top" align="left" style="padding:10px;">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <span class="txtCreatedRepurposed">Latest Activity:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <?php $type = $vars['entity']->content_type;
	if(!$type)
		$type = "mine";
		
	//based on type grab the correct content type
	if($type == "mine")
		$content_type = '';
	else
		$content_type = 'friend';
		
	//get the number of items to display
	$limit = $vars['entity']->num_display;
	if(!$limit)
		$limit = 4;
	
	//grab the river
	$river = elgg_view_river_items($owner->getGuid(), 0, $content_type, $content[0], $content[1], '', $limit,0,0,false);
	
	//display
	echo "<div class=\"contentWrapper\">";
	if($type != 'mine')
		echo "<div class='content_area_user_title'><h2>" . elgg_echo("friends") . "</h2></div>";
	echo $river;
	echo "</div>"; ?></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- Comments -->
                                        <tr>
                                            <td valign="top" align="left" style="background-color: #eeeeee; padding:10px;">
                                                <table cellpadding="0" cellspacing="0" border="0" >
                                                    <tr>
                                                        <td>
                                                            <span class="txtCreatedRepurposed">Comments:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <?php

     /**
	 * Elgg messageboard plugin view page
	 *
	 * @todo let users choose how many messages they want displayed
	 *
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
	 // a couple of required variables
	 
	 //get the full page owner entity
     $entity = get_entity(page_owner());
     
     //the number of message to display
     $num_display = $vars['entity']->num_display;
     
     //if no num set, set to one
     if(!$num_display)
        $num_display = 5;
		
	 
?>
<script type="text/JavaScript">
$(document).ready(function(){
     
    $("#postit").click(function(){
        
        //display the ajax loading gif at the start of the function call
        //$('#loader').html('<img src="<?php echo $vars['url']; ?>_graphics/ajax_loader.gif" />');
        $('#loader').html('<?php echo elgg_view('ajax/loader',array('slashes' => true)); ?>');
        
        //load the results back into the message board contents and remove the loading gif
        //remember that the actual div being populated is determined on views/default/messageboard/messageboard.php     
        $("#messageboard_wrapper").load("<?php echo $vars['url']; ?>mod/messageboard/ajax_endpoint/load.php", {messageboard_content:$("[name=message_content]").val(), pageOwner:$("[name=pageOwner]").val(), numToDisplay:<?php echo $num_display; ?>}, function(){
                    $('#loader').empty(); // remove the loading gif
                    $('[name=message_content]').val(''); // clear the input textarea
                }); //end 
                 
    }); // end of the main click function
        
}); //end of the document .ready function   
</script>

<div><!-- start of mb_input_wrapper div -->

    <!-- message textarea -->
    <textarea name="message_content" id="testing" value="" class="input_textarea" style="width:100%" /></textarea>
   
    <!-- the person posting an item on the message board -->
    <input type="hidden" name="guid" value="<?php echo $_SESSION['guid']; ?>" class="guid"  />
   
    <!-- the page owner, this will be the profile owner -->
    <input type="hidden" name="pageOwner" value="<?php echo page_owner(); ?>" class="pageOwner"  />
   
    <!-- submit button -->
    <input type="submit" id="postit" value="<?php echo elgg_echo('messageboard:postit'); ?>">
    
    <!-- menu options -->
    <div id="messageboard_widget_menu">
        <a href="<?php echo $vars['url']; ?>pg/messageboard/<?php echo get_entity(page_owner())->username; ?>"><?php echo elgg_echo("messageboard:viewall"); ?></a>
    </div>
    
    <!-- loading graphic -->
    <div id="loader" class="loading">  </div>

</div><!-- end of mb_input_wrapper div -->

<?php

        //this for the first time the page loads, grab the latest 5 messages.
		$contents = $entity->getAnnotations('messageboard', $num_display, 0, 'desc');
		
		//as long as there is some content to display, display it
		if (!empty($contents)) {
    		
    		echo elgg_view('messageboard/messageboard',array('annotation' => $contents));
		
		} else {
    		
    		//put the required div on the page for the first message
    		echo "<div id=\"messageboard_wrapper\"></div>";
	
    	}
	
?></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

	
	
	
	
	<?php } ?>



 
	<?php
			$hgsigh=1;
		} else {
			if (get_input('search_viewtype') == "gallery") {
				echo elgg_view('profile/gallery',$vars); 				
			} else {
				echo elgg_view("profile/listing",$vars);
			}
		}
	
?>