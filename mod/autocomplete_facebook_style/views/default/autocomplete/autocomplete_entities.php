<?php
/**
 *	Autocomplete Facebook Style Plugin
 *	@package autocomplete facebook style
 *	@author Liran Tal <liran.tal@gmail.com>
 *	@license GNU General Public License (GPL) version 2
 *	@copyright (c) Liran Tal of Enginx 2009
 *	@link http://www.enginx.com
 **/

$entities = $vars['entities'];
	

// example to json-format that should be returned:
// [{"caption":"Name","value":1},{"caption":"Name2","value":2}, ... ]

$str = "[";
foreach($entities as $entityObj) {
	$entityName = $entityObj->name;
	$entityGUID = $entityObj->guid;
	
	//clean up entity name
	$entityName = str_replace("'","\'", $entityName);
	$entityName = str_replace("\"","\'", $entityName);
	$entityName = str_replace("\r\n"," ", $entityName);

	$str .= '{"caption":"'.$entityName.'","value":'.$entityGUID."},";
}

$str = substr($str, 0, -1);
$str .= "]";
echo $str;


?>
