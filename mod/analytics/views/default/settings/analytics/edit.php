<?php
	/**
	* Google Analytics settings configuration.
	* 
	* @package analytics
	* @author ColdTrick IT Solutions
	* @copyright ColdTrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	global $CONFIG;
	
	$enabled = $vars['entity']->analyticsEnabled;
	$trackID = $vars['entity']->analyticsSiteID;
	$subDomainTracking = $vars['entity']->analyticsSubDomainTraking;
	$domain = $vars['entity']->analyticsDomain;
	
	$host = $_SERVER['HTTP_HOST'];
	$hostArray = explode(".", $host);
	$host = "";
	for($i = 1; $i < count($hostArray); $i++){
		$host .= "." . $hostArray[$i];
	}
	
	if($domain != $host){
		$sample = true;
	}
?>
<script language="javascript" type="text/javascript">
	function analyticsEnableClicked(){
		var anEnabled = document.getElementById("analyticsEnabledY");
		
		if(anEnabled.checked && anEnabled.value == "yes"){
			document.getElementById("analyticsSiteID").disabled = false;
			document.getElementById("analyticsSubDomainTrakingY").disabled = false;
			document.getElementById("analyticsSubDomainTrakingN").disabled = false;
			
			analyticsSubDomainTrackingClicked();
		} else {
			document.getElementById("analyticsSiteID").disabled = true;
			document.getElementById("analyticsSubDomainTrakingY").disabled = true;
			document.getElementById("analyticsSubDomainTrakingN").disabled = true;
			document.getElementById("analyticsDomain").disabled = true;
		}
	}
	
	function analyticsSubDomainTrackingClicked(){
		var trEnabled = document.getElementById("analyticsSubDomainTrakingY");
		
		if(trEnabled.checked && trEnabled.value == "yes"){
			document.getElementById("analyticsDomain").disabled = false;
		} else {
			document.getElementById("analyticsDomain").disabled = true;
		}
	}
</script>
<p>
	<table>
		<tr>
			<td><?php echo elgg_echo("analytics:enable"); ?></td>
			<td>
				<input type="radio" onChange="analyticsEnableClicked();" id="analyticsEnabledY" name="params[analyticsEnabled]" value="yes" <?php if($enabled == "yes"){ echo "CHECKED"; }?> /> <?php echo elgg_echo("option:yes"); ?>
				<input type="radio" onChange="analyticsEnableClicked();" id="analyticsEnabledN" name="params[analyticsEnabled]" value="no" <?php if($enabled == "no"){ echo "CHECKED"; }?> /> <?php echo elgg_echo("option:no"); ?>
			</td>
		<tr>
		<tr>
			<td><?php echo elgg_echo("analytics:trackid"); ?></td>
			<td><input type="text" id="analyticsSiteID" name="params[analyticsSiteID]" value="<?php echo $trackID; ?>" maxlength="15" /></td>
		</tr>
		<tr>
			<td><?php echo elgg_echo("analytics:subdomaintracking"); ?></td>
			<td>
				<input type="radio" onChange="analyticsSubDomainTrackingClicked();" id="analyticsSubDomainTrakingY" name="params[analyticsSubDomainTraking]" value="yes" <?php if($subDomainTracking == "yes"){ echo "CHECKED"; }?> /> <?php echo elgg_echo("option:yes"); ?>
				<input type="radio" onChange="analyticsSubDomainTrackingClicked();" id="analyticsSubDomainTrakingN" name="params[analyticsSubDomainTraking]" value="no" <?php if($subDomainTracking == "no"){ echo "CHECKED"; }?> /> <?php echo elgg_echo("option:no"); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo elgg_echo("analytics:domain"); ?></td>
			<td><input type="text" id="analyticsDomain" name="params[analyticsDomain]" value="<?php echo $domain; ?>" /> <?php if($sample){ echo sprintf(elgg_echo("analytics:sample"), $host); } ?></td>
		</tr>
	</table>
</p>
<script language="javascript" type="text/javascript">
analyticsEnableClicked();
</script>