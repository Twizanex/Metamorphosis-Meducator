<?php


function readMultipleValues($fieldID, $mainFieldName = "", $dependantFields = array())
{
        if($mainFieldName == "") $mainFieldName = $fieldID;
        
	$nrOfFields = get_input($mainFieldName . "_nr");

        $values = array();
        $values[$fieldID] = array();
        for($j=0; $j<count($dependantFields); $j++) $values[$dependantFields[$j]] = array();

        for($i=0; $i<=$nrOfFields; $i++)
	{
		$val = get_input($fieldID . '_' .$i);
		if($val != "")
                {
                    $values[$fieldID][] = $val;
                    for($j=0; $j<count($dependantFields); $j++)
                        $values[$dependantFields[$j]][] = get_input($dependantFields[$j] . '_' .$i);
                }
	}
        //reverse the array
        array_reverse($values, true);
        
        if(count($dependantFields) == 0) return join(",", $values[$fieldID]);
        else return $values;
}

	/**
	 * Elgg profile plugin edit action
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
		
	// Load configuration


        global $CONFIG;

        gatekeeper();
        action_gatekeeper();

        

	// Get profile fields
	$access_id = ACCESS_PUBLIC;
	$title=get_input('meducator3');
	//$ur=get_input('meducator1');
	$ur=readMultipleValues('meducator1');
	$ident=readMultipleValues('meducator2');
	$ident_type=readMultipleValues('type_meducator2', 'meducator2');
	$cclic=get_input('meducator18b');
	$otherlic=get_input('meducator18a');
	//$qual=get_input('meducator19');
	$qual=readMultipleValues('meducator19');
	$reslang=get_input('meducator5');
	$metlang=get_input('meducator24');
	//$aut=get_input('meducator20');

        $auxAuth = readMultipleValues('name_meducator20', "meducator20", array("affiliation_meducator20", "foafURI_meducator20"));
        $aut_names = $auxAuth["name_meducator20"];
        $aut_affiliation = $auxAuth["affiliation_meducator20"];
        $aut_foafURI = $auxAuth["foafURI_meducator20"];


	$aut=readMultipleValues('meducator20');
	$imer=get_input('meducator21');
	$cite=get_input('meducator22');
	//$keyw=get_input('meducator4');
	$keyw=readMultipleValues('meducator4');
	$edudesc=get_input('meducator7');
	$tecdesc=get_input('meducator8');
	$mediatype=get_input('meducator6b');
	$restype=get_input('meducator6a');
	$othertype=get_input('meducator6');
	//$disc=get_input('meducator16');
	$disc=readMultipleValues('meducator16');
	//$specialty=get_input('meducator17');
	$specialty=readMultipleValues('meducator17');
	$level=get_input('meducator15');
	$educontext=get_input('meducator9');
	$instr=get_input('meducator10');
	$eduobj=get_input('meducator11');
	$eduout=get_input('meducator12');
	$assm=get_input('meducator13');
	$prereqs=get_input('meducator14');
	$repurpurl=get_input('meducator25');
	$repcont=get_input('meducator26');
	$repdesc=get_input('meducator27');
	$othermedia=get_input('meducator6b1');
	$compan=readMultipleValues('meducator28');
	
	$parents=get_input('selectedParentsList');
	
	$pl1=get_input('pickedKeyLabels');
	$pi1=get_input('pickedKeyIds');
	$pn1=get_input('pickedKeyNames');
	$pl2=get_input('pickedDisLabels');
	$pi2=get_input('pickedDisIds');
	$pn2=get_input('pickedDisNames');
	$pl3=get_input('pickedSpecLabels');
	$pi3=get_input('pickedSpecIds');
	$pn3=get_input('pickedSpecNames');
	/*
	
	print_r($pl1); echo "<br/>";
	print_r($pi1); echo "<br/>";
	print_r($pn1); echo "<br/>";
	print_r($pl2); echo "<br/>";
	print_r($pi2); echo "<br/>";
	print_r($pn2); echo "<br/>";
	print_r($pl3); echo "<br/>";
	print_r($pi3); echo "<br/>";
	print_r($pn3); echo "<br/>";
	
	
	exit(); */
	
	$urar=explode(",",$ur);
	$identar=explode(",",$ident);
	$qualar=explode(",",$qual);
	$autar=explode(",",$aut);
	$keywar=explode(",",$keyw);
	$discar=explode(",",$disc);
	$specar=explode(",",$specialty);
	$companar=explode(",",$compan);
	$identypar=explode(",",$ident_type);

        //print_r($identypar);
        //exit();
	
	
	
/////HUMAN FIELDS
$user6=get_input('user6');
$Affiliation=get_input('Affiliation');
$Location=get_input('Location');
$city=get_input('city');
$user11=get_input('user1');
$user2=get_input('user2');
$user3=get_input('user3');
$user4=get_input('user4');	
$user5=get_input('user5');
$user7=get_input('user7');
	
	
	
	
	
	
	
//		$input = array();
		$accesslevel = get_input('accesslevel');
		if (!is_array($accesslevel)) $accesslevel = array();
		
		foreach($CONFIG->profile as $shortname => $valuetype) {
			$input[$shortname] = get_input($shortname);
			
			if ($valuetype == 'tags')
				$input[$shortname] = string_to_tag_array($input[$shortname]);
		}

		$selected_item;		
	// Save stuff if we can, and forward to the user's profile
		
		if ($user1 = page_owner()) {
			$selected_item = $user1;
			$user = page_owner_entity();			
		} else {
			$user = $_SESSION['user'];
			set_page_owner($user->getGUID());
			
		}
		
	
		$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid and {$CONFIG->dbprefix}_content_item_discrimination.creator_guid = \"".$_SESSION['id']."\""; 

	$result = get_data($query);
	//echo $query;
	
	$flag =false;
	$total_users = count($result);
	for($i=0;$i<$total_users;$i++)
	{
		$row = $result[$i];
				
		if($row->guid == $selected_item)
		{
			$flag = true;
		}
	}
	
	///////////////
		
	
		
		
		if ($user->guid==page_owner_entity()->creatorg||issuperadminloggedin() || $flag) {
				//3	
				if($title){
				create_metadata($user->guid, 'meducator3', $title, 'text', $user->guid, $access_id);
				$user->name=$title;
				}
				//1
				if (is_array($urar)) {
				remove_metadata($user->guid,'meducator1' );
						$i = 0;
						foreach($urar as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator1', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator1' );
						if($urar)
						create_metadata($user->guid, 'meducator1', $urar, 'text', $user->guid, $access_id);
					}
				//2
				if (is_array($identar)) {
				remove_metadata($user->guid,'meducator2' );
						$i = 0;
						foreach($identar as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator2', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator2' );
						if($identar)
						create_metadata($user->guid, 'meducator2', $identar, 'text', $user->guid, $access_id);
					}
				//2_type
				if (is_array($identypar)) {
				remove_metadata($user->guid,'meducator2_type' );
						$i = 0;
						foreach($identypar as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator2_type', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator2_type' );
						if($identypar)
						create_metadata($user->guid, 'meducator2_type', $identypar, 'text', $user->guid, $access_id);
					}
				
				//18b
				if($cclic)
				create_metadata($user->guid, 'meducator18b', $cclic, 'text', $user->guid, $access_id);
				//18a
				if($otherlic)
				create_metadata($user->guid, 'meducator18a', $otherlic, 'text', $user->guid, $access_id);
				//19
				if (is_array($qualar)) {
				remove_metadata($user->guid,'meducator19' );
						$i = 0;
						foreach($qualar as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator19', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator19' );
						if($qualar)
						create_metadata($user->guid, 'meducator19', $qualar, 'text', $user->guid, $access_id);
					}
				//5
				if (is_array($reslang)) {
				remove_metadata($user->guid,'meducator5' );
						$i = 0;
						foreach($reslang as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator5', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator5' );
						if($reslang)
						create_metadata($user->guid, 'meducator5', $reslang, 'text', $user->guid, $access_id);
					}
				//24
						if($metlang)
						create_metadata($user->guid, 'meducator24', $metlang, 'text', $user->guid, $access_id);
				
				//20 _name
				if (is_array($aut_names)) {
				remove_metadata($user->guid,'name_meducator20' );
						$i = 0;
						foreach($aut_names as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'name_meducator20', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'name_meducator20' );
						if($aut_names)
						create_metadata($user->guid, 'name_meducator20', $aut_names, 'text', $user->guid, $access_id);
					}
				//20 _affil
				if (is_array($aut_affiliation)) {
				remove_metadata($user->guid,'affil_meducator20' );
						$i = 0;
						foreach($aut_affiliation as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'affil_meducator20', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'affil_meducator20' );
						if($aut_affiliation)
						create_metadata($user->guid, 'affil_meducator20', $aut_affiliation, 'text', $user->guid, $access_id);
					}
					
				//20_foaf
				if (is_array($aut_foafURI)) {
				remove_metadata($user->guid,'foaf_meducator20' );
						$i = 0;
						foreach($aut_foafURI as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'foaf_meducator20', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'foaf_meducator20' );
						if($aut_foafURI)
						create_metadata($user->guid, 'foaf_meducator20', $aut_foafURI, 'text', $user->guid, $access_id);
					}
					
					
					//21
					if($imer)
						create_metadata($user->guid, 'meducator21', $imer, 'text', $user->guid, $access_id);
					//22
					if($cite)
						create_metadata($user->guid, 'meducator22', $cite, 'text', $user->guid, $access_id);
					//4
						if($pn1)
						{
						create_metadata($user->guid, 'keywords', $pn1, 'text', $user->guid, $access_id);
						create_metadata($user->guid, 'keyontos', $pl1, 'text', $user->guid, $access_id);
						create_metadata($user->guid, 'keyuris', $pi1, 'text', $user->guid, $access_id);

						}
					//7
					if($edudesc)
						create_metadata($user->guid, 'meducator7', $edudesc, 'text', $user->guid, $access_id);
					//8
					if($tecdesc)
						create_metadata($user->guid, 'meducator8', $tecdesc, 'text', $user->guid, $access_id);
					//6b
						if (is_array($mediatype)) {
						remove_metadata($user->guid,'meducator6b');
						$i = 0;
						foreach($mediatype as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator6b', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
						if($mediatype)
						{ remove_metadata($user->guid,'meducator6b');
						create_metadata($user->guid, 'meducator6b', $mediatype, 'text', $user->guid, $access_id);
						}	
					}
					
					//6b1
					if($othermedia) {
					remove_metadata($user->guid,'meducator6b1' ); 

						create_metadata($user->guid, 'meducator6b1', $othermedia, 'text', $user->guid, $access_id);
						
					}
					else
						remove_metadata($user->guid,'meducator6' );
					
					
					
					
					//6a
						if (is_array($restype)) {
						remove_metadata($user->guid, 'meducator6a'); 
						$i = 0;
						foreach($restype as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator6a', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else 
						if($restype)
						{
							remove_metadata($user->guid,'meducator6a' ); 
						create_metadata($user->guid, 'meducator6a', $restype, 'text', $user->guid, $access_id);
						}else
							remove_metadata($user->guid,'meducator6a' ); 
						
					//6
					if($othertype) {
					remove_metadata($user->guid,'meducator6' ); 

						create_metadata($user->guid, 'meducator6', $othertype, 'text', $user->guid, $access_id);
						
					}
					else
						remove_metadata($user->guid,'meducator6' );
					//16
						if($pn2)
						{
						create_metadata($user->guid, 'disciplines', $pn2, 'text', $user->guid, $access_id);
						create_metadata($user->guid, 'discontos', $pl2, 'text', $user->guid, $access_id);
						create_metadata($user->guid, 'discurisuris', $pi2, 'text', $user->guid, $access_id);

						}
						
						
					//17
						if($pn3)
						{
						create_metadata($user->guid, 'specialities', $pn3, 'text', $user->guid, $access_id);
						create_metadata($user->guid, 'specontos', $pl3, 'text', $user->guid, $access_id);
						create_metadata($user->guid, 'specuris', $pi3, 'text', $user->guid, $access_id);

						}
                                       
					//15
						if (is_array($level)) {
						remove_metadata($user->guid,'meducator15' );
						$i = 0;
						foreach($level as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator15', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator15' );
						if($level)
						create_metadata($user->guid, 'meducator15', $level, 'text', $user->guid, $access_id);
					}	
					//9
						if($educontext)
						create_metadata($user->guid, 'meducator9', $educontext, 'text', $user->guid, $access_id);
					//10
						if($instr)
						create_metadata($user->guid, 'meducator10', $instr, 'text', $user->guid, $access_id);	
					//11
						if($eduobj)
						create_metadata($user->guid, 'meducator11', $eduobj, 'text', $user->guid, $access_id);					
					//12
						if (is_array($eduout)) {
						remove_metadata($user->guid,'meducator12' );
						$i = 0;
						foreach($eduout as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator12', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator12' );
						if($eduout)
						create_metadata($user->guid, 'meducator12', $eduout, 'text', $user->guid, $access_id);
					}				
					//13
                                         
						if($assm)
						create_metadata($user->guid, 'meducator13', $assm, 'text', $user->guid, $access_id);					
					//14
						if($prereqs)
						create_metadata($user->guid, 'meducator14', $prereqs, 'text', $user->guid, $access_id);					
	//						print_r($pl);
		//					echo ("</br>");
			//				print_r($pi);
				//			echo ("</br>");
					//		print_r($pn);
							//exit();
					//28
				if (is_array($companar)) {
				remove_metadata($user->guid,'meducator28' );
						$i = 0;
						foreach($companar as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, 'meducator28', $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
					remove_metadata($user->guid,'meducator28' );
						if($companar)
						create_metadata($user->guid, 'meducator28', $companar, 'text', $user->guid, $access_id);
					}
							//REPURPOSING
							$friends = $user->getFriends("", $num=30, $offset = 0);
							foreach ($friends as $friend)
							{
								$idd=$friend->guid;
								$user->removefriend($idd);
							}
								
							remove_metadata($user->guid,'parentids' );
							remove_metadata($user->guid,'parentdescs' );
							remove_metadata($user->guid,'parenttypes' );
							remove_metadata($user->guid,'parentits' );
							remove_metadata($user->guid,'parentidents' );
							remove_metadata($user->guid,'parentses' );
							$i=0;
							$parentcnt=0;
                                                        
                                                        //DEBUG
                                                        $debugFile = fopen(dirname(dirname(dirname(__FILE__))) . "/debug.txt", "w");
                                                        fwrite($debugFile, "EDIT ACTION SCRIPT\n");
                                                        //end DEBUG
                                                        
                                                        $repdesc="";
							$repcont="";
							$reptit="";
							$sesameid="";
							$repident="";
                                                        $reppids = "";
                                                        $separator = "|||";
														
							foreach ($parents as $parent)
							{	
                                                          $aux_separator = ($repdesc=="") ? "" : $separator;

                                                          $reppids .= $aux_separator . $parent;
                                                          $repdesc .= $aux_separator . get_input('repurpose_desc_'.$parent);
                                                          $repcont .= $aux_separator . get_input('repurpose_types_'.$parent);
                                                          $reptit .= $aux_separator . get_input('repurpose_title_'.$parent);
                                                          $sesameid .= $aux_separator . get_input('repurpose_sesameid_'.$parent);
                                                          $repident .= $aux_separator . get_input('repurpose_identifier_'.$parent);

                                                          $addparent=$user->addfriend($parent);
							}
                                                        
                                                        $multiple = false;
                                                        create_metadata($user->guid, 'parentids', $reppids, 'text', $user->guid, $access_id, $multiple);
							create_metadata($user->guid, 'parentdescs', $repdesc, 'text', $user->guid, $access_id, $multiple);
							create_metadata($user->guid, 'parentits', $reptit, 'text', $user->guid, $access_id, $multiple);
							create_metadata($user->guid, 'parentses', $sesameid, 'text', $user->guid, $access_id, $multiple);
                                                        create_metadata($user->guid, 'parentidents', $repident, 'text', $user->guid, $access_id, $multiple);
                                                        create_metadata($user->guid, 'parenttypes', $repcont, 'text', $user->guid, $access_id, $multiple);
                                                        
                                                        //DEBUG
                                                        fwrite($debugFile, "\n\nEND OF ADD ACTIONS. RESULT:\n");
                                                        fwrite($debugFile, "\nDESCRIPTIONS = " . print_r($user->parentdescs, true));
                                                        fwrite($debugFile, "\nTITLES = " . print_r($user->parentits, true));
                                                        fwrite($debugFile, "\nIDS = " . print_r($user->parentids, true));
                                                        fclose($debugFile);
                                                        //end DEBUG
					
///////////////////////////////HUMAN PROFILES
						if($user1)
						{remove_metadata($user->guid, 'user1');
						create_metadata($user->guid, 'user1', $user11, 'text', $user->guid, $access_id);
						}
						if($user2)
						{remove_metadata($user->guid, 'user2');
						create_metadata($user->guid, 'user2', $user2, 'text', $user->guid, $access_id);	
						}
						if($user3)
						{remove_metadata($user->guid, 'user3');
						create_metadata($user->guid, 'user3', $user3, 'text', $user->guid, $access_id);
						}
						if($user4)
						{remove_metadata($user->guid, 'user4');
						create_metadata($user->guid, 'user4', $user4, 'text', $user->guid, $access_id);
						}
						if($user5)
						{remove_metadata($user->guid, 'user5');
						create_metadata($user->guid, 'user5', $user5, 'text', $user->guid, $access_id);	
						}
						if($user6)
						{remove_metadata($user->guid, 'user6');
						create_metadata($user->guid, 'user6', $user6, 'text', $user->guid, $access_id);		
						}
						if($user7)
						{remove_metadata($user->guid, 'user7');
						create_metadata($user->guid, 'user7', $user7, 'text', $user->guid, $access_id);							
						}
						if($Affiliation)
						{
						remove_metadata($user->guid, 'Affiliation');
						create_metadata($user->guid, 'Affiliation', $Affiliation, 'text', $user->guid, $access_id);
						}
						if($Location)
						create_metadata($user->guid, 'Location', $Location, 'text', $user->guid, $access_id);	
						if($city)
						create_metadata($user->guid, 'city', $city, 'text', $user->guid, $access_id);							
		
		
		
						
			/*              OLD DEFAULT
			// Save stuff
			if (sizeof($input) > 0)
				foreach($input as $shortname => $value) {
					
					//$user->$shortname = $value;
					remove_metadata($user->guid, $shortname);    WE WILL NEED THIS ONE TO CLEAR VALUES
					if (isset($accesslevel[$shortname])) {
						$access_id = (int) $accesslevel[$shortname];
					} else {
						// this should never be executed since the access level should always be set
						$access_id = ACCESS_PRIVATE;
					}
					if (is_array($value)) {
						$i = 0;
						foreach($value as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, $shortname, $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
						create_metadata($user->guid, $shortname, $value, 'text', $user->guid, $access_id);
					}
					
				}
				
				*/
			$user->save();
			if ($user->issimpleuser=='no')
				$user->name=$title;
			// Notify of profile update
			trigger_elgg_event('profileupdate',$user->type,$user);
			
			//add to river
			add_to_river('river/user/default/profileupdate','update',$_SESSION['user']->guid,$_SESSION['user']->guid);
			
			//system_message(elgg_echo("profile:saved").$user->guid.$malakia);
			system_message(elgg_echo("profile:saved").$ur.$user->guid);
			
			// Forward to the user's profile
	//		forward($user->getUrl());
   //                     print_r(page_owner_entity()->parentdescs);
   //                     print_r(page_owner_entity()->parentits);
    //                    print_r(page_owner_entity()->parentidents);
    //                    print_r(page_owner_entity()->parenttypes);
                        
//	exit();
		if ($user->issimpleuser=='no'){
		//{echo "NIKOLAS"; print_r($user->name_meducator20); echo "<br />";print_r($user->affil_meducator20); echo "<br />";print_r($user->foaf_meducator20); exit();}
                       // echo "i am here";
                  $isAnUpdate = get_input("isAnUpdate");
		forward("http://metamorphosis.med.duth.gr/mod/content_item/createrdf.php?id=".$user->guid."&update=".$isAnUpdate);
                } else forward($user->getUrl());
		} else {
	// If we can't, display an error
			
			system_error(elgg_echo("profile:cantedit"));
		}

?>