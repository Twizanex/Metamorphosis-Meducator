<?php
	/**
	* Google Analytics footer extension.
	* 
	* @package analytics
	* @author ColdTrick IT Solutions
	* @copyright ColdTrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	global $CONFIG;
	
	$enabled = get_plugin_setting('analyticsEnabled', 'analytics');
	$trackID = get_plugin_setting('analyticsSiteID', 'analytics');
	$subDomainTracking = get_plugin_setting('analyticsSubDomainTraking', 'analytics');
	$domain = get_plugin_setting('analyticsDomain', 'analytics');
	
	if($enabled == "yes"){
?>
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
	var pageTracker = _gat._getTracker("<?php echo $trackID; ?>");
	<?php if($subDomainTracking == "yes") { ?>
	pageTracker._setDomainName("<?php echo $domain; ?>");
	<?php } ?>
	pageTracker._trackPageview();
} catch(err) {}
</script>

<?php } ?>