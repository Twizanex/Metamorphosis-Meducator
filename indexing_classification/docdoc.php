<?php
	/*
	It converts the doc-term matrices into doc-doc matrices
	Usage: php -f docdoc.php M N C I
	N is the number of processes you want to process the doc-term matrices
	M is the process to launch among the N
	C is 1 if you want to consider contexts in the conversion
	I is 1 if you want to do a completely new indexing (slow but more accurate), 0 if you want to use the results of  the last done indexing (faster but sometimes not very accurate)
	For example, to launch 3 processes with contexts and with a complete new indexing, you have to launch, in order:
		php -f docdoc.php 1 3 1 1
		php -f docdoc.php 2 3 1 1
		php -f docdoc.php 3 3 1 1
	If instead you want the matrix is processed only by a process, simply launch
		php -f docdoc.php 1 1 1 1
	
	Note: if N > 1, you will have N files for each matrix, you need then to tie them together. 
	Note: when you have the doc-doc matrix (you have tied the pieces or N==1), it will have only half values, you can create the other half easily (since it is a triangular matrix), following the rule that $element[$row][$col]=$element[$col][$row]
	*/;
	$IOdir="C:/Progra~2/Apache~1/Apache2.2/htdocs/iodir/";
	$M=$argv[1];
	$N=$argv[2];
	$use_contexts=$argv[3];
	$new_indexing_required=$argv[4];
	$metadata_dt=unserialize(file_get_contents($IOdir."metadata_dt"));
	$uses_dt=unserialize(file_get_contents($IOdir."uses_dt"));
	$tags_dt=unserialize(file_get_contents($IOdir."tags_dt"));
	$guids=unserialize(file_get_contents($IOdir."guids"));
	if(!$new_indexing_required) {
		
		echo 'Starting creating METADATA Doc-doc matrix...' . "\n";
		if(file_exists($IOdir."old_metadata_dd") && file_exists($IOdir."old_metadata_dt")) {
			echo "I'm using, where possible, the old METADATA Doc-doc matrix...\n"; 
			$old_metadata_dd=unserialize(file_get_contents($IOdir."old_metadata_dd"));
			$old_metadata_dt=unserialize(file_get_contents($IOdir."old_metadata_dt"));
			$metadata_dd=toDocDocMetadataUses($metadata_dt,$M,$N,$old_metadata_dd,$old_metadata_dt);
		}
		else $metadata_dd=toDocDocMetadataUses($metadata_dt,$M,$N);
		echo 'METADATA doc-doc matrix created' . "\n";
		
		echo 'Starting creating USES Doc-doc matrix...' . "\n";
		if(file_exists($IOdir."old_uses_dd") && file_exists($IOdir."old_uses_dt")) {
			echo "I'm using, where possible, the old USES Doc-doc matrix...\n"; 
			$old_uses_dd=unserialize(file_get_contents($IOdir."old_uses_dd"));
			$old_uses_dt=unserialize(file_get_contents($IOdir."old_uses_dt"));
			$uses_dd=toDocDocMetadataUses($uses_dt,$M,$N,$old_uses_dd,$old_uses_dt);
		}
		else $uses_dd=toDocDocMetadataUses($uses_dt,$M,$N);
		echo 'USES doc-doc matrix created' . "\n";
		
		echo 'Starting creating TAGS Doc-doc matrix...' . "\n";
		if(file_exists($IOdir."old_tags_dd") && file_exists($IOdir."old_tags_dt")) {
			echo "I'm using, where possible, the old TAGS Doc-doc matrix...\n"; 
			$old_tags_dd=unserialize(file_get_contents($IOdir."old_tags_dd"));
			$old_tags_dt=unserialize(file_get_contents($IOdir."old_tags_dt"));
			$tags_dd=toDocDocTags($tags_dt,$M,$N,$old_tags_dd,$old_tags_dt);
		}
		else $tags_dd=toDocDocTags($tags_dt,$M,$N);
		echo 'TAGS doc-doc matrix created' . "\n";
	}
	else {
		echo 'Starting creating METADATA Doc-doc matrix...' . "\n";
		$metadata_dd=toDocDocMetadataUses($metadata_dt,$M,$N);
		echo 'METADATA doc-doc matrix created' . "\n";
		
		echo 'Starting creating USES Doc-doc matrix...' . "\n";
		$uses_dd=toDocDocMetadataUses($uses_dt,$M,$N);
		echo 'USES doc-doc matrix created' . "\n";
		
		echo 'Starting creating TAGS Doc-doc matrix...' . "\n";
		$tags_dd=toDocDocTags($tags_dt,$M,$N);
		echo 'TAGS doc-doc matrix created' . "\n";
	}
	file_put_contents($IOdir."metadata_dd".$M,serialize($metadata_dd));
	if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir."metadata_dd".$M)) chmod($IOdir."metadata_dd".$M,0666); //set all the permissions for everybody for this file
	file_put_contents($IOdir."uses_dd".$M,serialize($uses_dd));
	if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir."uses_dd".$M)) chmod($IOdir."uses_dd".$M,0666); //set all the permissions for everybody for this file
	file_put_contents($IOdir."tags_dd".$M,serialize($tags_dd));
	if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir."tags_dd".$M)) chmod($IOdir."tags_dd".$M,0666); //set all the permissions for everybody for this file
			
	function toDocDocMetadataUses($docterm,$M,$N,$old_docdoc=array(),$old_docterm=array()){
		global $use_contexts,$guids;
		$return = array();
		$length=count($guids);
		$z=0;
		foreach($guids as $guid){
			if($z>=floor(($M-1)/$N*$length) && $z<floor($M/$N*$length)) {  //we work only on 1/N of the matrix for each subprocess
				$return[$guid] = array();
				$w=0;
				foreach($guids as $guid_cmp){
					if($z<$w) {   //we work only on some elements, since the doc-doc matrix will be triangular and so we have already the other elements
						if(!isset($docterm[$guid]) || !isset($docterm[$guid_cmp])) $return[$guid][$guid_cmp]=0; //if at least one of the two elements has no metadata/uses, the score between the two elements is 0
						elseif(!empty($old_docdoc) && !empty($old_docterm) && isset($old_docterm[$guid]) && isset($old_docterm[$guid_cmp]) && $docterm[$guid]==$old_docterm[$guid] && $docterm[$guid_cmp]==$old_docterm[$guid_cmp]) {   //if we have chosen not to do a new indexing and both the elements haven't changed since the old version, we can take the value from the old doc-doc matrix, saving so time
							$return[$guid][$guid_cmp]=$old_docdoc[$guid][$guid_cmp];
						}
						else {
							$return[$guid][$guid_cmp]=0; //initialization
							$common_keywords=array_intersect(array_keys($docterm[$guid]),array_keys($docterm[$guid_cmp]));  //it finds the keywords in common for the two documents
							foreach($common_keywords as $keyword){
								$context_add=0;
								$context2_add=0;
								if($use_contexts==1) {
									$common_contexts=array_intersect(array_keys($docterm[$guid][$keyword]),array_keys($docterm[$guid_cmp][$keyword])); //it finds the contexts in common for the current keyword for the two documents
									foreach($common_contexts as $context) {
										if($context==$keyword) continue;  //the first context is the keyword, so we don't consider it
										$context_add+=get_scoreLU_c($context,$docterm[$guid][$keyword]);  //we calculate the weight of the current context for the current keyword in the first document
										$context2_add+=get_scoreLU_c($context,$docterm[$guid_cmp][$keyword]); //we calculate the weight of the current context for the current keyword in the second document
									}
								}
								$return[$guid][$guid_cmp]+=(get_scoreLU($keyword,$docterm[$guid])*(1+$context_add))+(get_scoreLU($keyword,$docterm[$guid_cmp])*(1+$context2_add));  //with this formula we consider both the keywords and the contexts weights
							}
							//now we normalize the value in the range 0-5
							//element : max = x : 5
							//x = (element * 5) / max
							if($use_contexts==1) $max=4;
							else $max=2;
							$return[$guid][$guid_cmp]=($return[$guid][$guid_cmp]*5)/$max;
						}
					}
					elseif($z==$w) $return[$guid][$guid_cmp] = 0;
					$w++;
				}
			}
			$z++;
		}
		return $return;
	}
	
	function toDocDocTags($docterm,$M,$N,$old_docdoc=array(),$old_docterm=array()){
		global $guids;
		$return = array();
		$length=count($guids);
		$z=0;
		foreach($guids as $guid){
			if($z>=floor(($M-1)/$N*$length) && $z<floor($M/$N*$length)) {   //we work only on 1/N of the matrix for each subprocess
				$return[$guid] = array();
				$w=0;
				foreach($guids as $guid_cmp){
					if($z<$w) {   //we work only on some elements, since the doc-doc matrix will be triangular and so we have already the other elements
						if(!isset($docterm[$guid]) || !isset($docterm[$guid_cmp])) $return[$guid][$guid_cmp]=0; //if at least one of the two elements has no tags, the score between the two elements is 0
						elseif(!empty($old_docdoc) && !empty($old_docterm) && isset($old_docterm[$guid]) && isset($old_docterm[$guid_cmp]) && $docterm[$guid]==$old_docterm[$guid] && $docterm[$guid_cmp]==$old_docterm[$guid_cmp]) {   //if we have chosen not to do a new indexing and both the elements haven't changed since the old version, we can take the value from the old doc-doc matrix, saving so time
							$return[$guid][$guid_cmp]=$old_docdoc[$guid][$guid_cmp];
						}
						else {
							$return[$guid][$guid_cmp]=0; //initialization
							$common_tags=array_intersect(array_keys($docterm[$guid]),array_keys($docterm[$guid_cmp]));  //it finds the tags in common for the two documents
							$return[$guid][$guid_cmp]+=(count($common_tags)/count($docterm[$guid]))+(count($common_tags)/count($docterm[$guid_cmp]));
							//now we normalize the value in the range 0-5
							//element : max = x : 5
							//x = (element * 5) / max
							$max=2;
							$return[$guid][$guid_cmp]=($return[$guid][$guid_cmp]*5)/$max;
						}
					}
					elseif($z==$w) $return[$guid][$guid_cmp] = 0;
					$w++;
				}
			}
			$z++;
		}
		return $return;
	}
	
	 //we calculate the weight of the current keyword in the current document
	function get_scoreLU($key,$element) {
		$sum=0;
		foreach($element as $keyword => $keyword_context) {
			$sum+=$keyword_context[$keyword];
		}
		$score=$element[$key][$key]/$sum;
		return $score;
	}
	
	 //we calculate the weight of the current context for the current keyword in the current document
	function get_scoreLU_c($cont,$element) {
		$i=0;
		$sum=0;
		foreach($element as $frequency) {
			if($i!=0) $sum+=$frequency;  //avoid the first element because it is not a context (it is the keyword frequency)
			$i=1;
		}
		$score=$element[$cont]/$sum;
		return $score;
	}
?>