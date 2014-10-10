<?php

	/**
	 * Tag Cumulus
	 * 
	 * @package tag_cumulus
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Pedro Prez
	 * @copyright 2009
	 * @link http://www.pedroprez.com.ar/
 	*/

	if (!empty($vars['subtype'])) {
		$subtype = "&subtype=" . urlencode($vars['subtype']);
	} else {
		$subtype = "";
	}
	if (!empty($vars['object'])) {
		$object = "&object=" . urlencode($vars['object']);
	} else {
		$object = "";
	}
	
	if (!empty($vars['speed']))
		$speed = (int)$vars['speed'];
	else
		$speed = TAG_CUMULUS_SPEED;
		
	if (!empty($vars['color_max']))
		$color_max = $vars['color_max'];
	else
		$color_max = TAG_CUMULUS_COLOR_MAX;
		
	if (!empty($vars['hi_color_max']))
		$hi_color_max = $vars['hi_color_max'];
	else
		$hi_color_max = TAG_CUMULUS_HI_COLOR_MAX;
		
		
	if (!empty($vars['tcolor']))
		$tcolor = $vars['tcolor'];
	else
		$tcolor = TAG_CUMULUS_T_COLOR;

	if (!empty($vars['tcolor2']))
		$tcolor2 = $vars['tcolor2'];
	else
		$tcolor2 = TAG_CUMULUS_T_COLOR2;

	if (!empty($vars['hicolor']))
		$hicolor = $vars['hicolor'];
	else
		$hicolor = TAG_CUMULUS_HI_COLOR;	
	
	if (!empty($vars['wmode']))
		$wmode = $vars['wmode'];
	else
		$wmode = TAG_CUMULUS_WMODE;

	if (!empty($vars['width']))
		$width = $vars['width'];
	else
		$width = TAG_CUMULUS_WIDTH;	
		
	if (!empty($vars['height']))
		$height = $vars['height'];
	else
		$height = TAG_CUMULUS_HEIGHT;

	if (!empty($vars['background']))
		$background = $vars['background'];
	else
		$background = TAG_CUMULUS_BACKGROUND;
	
	
	if (empty($vars['tagcloud']) && !empty($vars['value']))
		$vars['tagcloud'] = $vars['value'];
		
	if (!empty($vars['tagcloud']) && is_array($vars['tagcloud'])) {
        
        $counter = 0;
        $cloud = array();
        $max = 0;
        foreach($vars['tagcloud'] as $tag) {
        	if ($tag->total > $max) {
        		$max = $tag->total;
        	}
        }
        
        foreach($vars['tagcloud'] as $tag) {
            $size = round((log($tag->total) / log($max)) * 100) + 30;
            if($size){
            	
            	switch($size)
			    {
			    	case ($size <= 40):
			    		$style = "9";
			    		break;
			
			    	case ($size <= 60):
			    		$style = "10";
			    		break;
			    		
			    	case ($size <= 80):
			    		$style = "12";
			    		break;
			    		
			    	case ($size <= 100):
			    		$style = "14";
			    		break;
			    		
			    	case ($size <= 120):
			    		$style = "16";
			    		break;
			    		
			    	case ($size <= 140):
			    		$style = "22";
			    		break;
			    	
			    	default:
			    		$style = "9";
			    		break;
			    }
            	
            	$item = new StdClass();
            	$item->url = $vars['url'] . "search/?tag=". urlencode($tag->tag);/* . $object . $subtype;*/
            	$item->style = $style; 
            	$item->title = htmlentities($tag->tag, ENT_QUOTES, 'UTF-8');
            	
            	$cloud[] = $item;
            }
        }
        
        $cumulus = "";
        if(is_array($cloud) && !empty($cloud)){
        	
        	foreach($cloud as $item){
        		
        		$item_color ='';
        		if($item->style==22)
        			$item_color =  "color='$color_max' hicolor='$hi_color_max'";
        			        		
        		$cumulus .= "<a href='{$item->url}' style='{$item->style}' {$item_color}>{$item->title}</a>";
        	}
        }
        
     }
    
?>

<div id="tag_cumulus">This will be shown to users with no Flash or Javascript.</div>

<script type="text/javascript">
	
	var so = new SWFObject("<?php echo $vars['url'] ?>/mod/tag_cumulus/vendors/tagcloud.swf", "tagcloud", "<?php echo $width; ?>", "<?php echo $height; ?>", "7", "<?php echo $background; ?>");

	// uncomment next line to enable transparency
	so.addParam("wmode", "<?php echo $wmode ?>");
	so.addVariable("hicolor", "<?php echo $hicolor ?>");
	so.addVariable("tcolor", "<?php echo $tcolor ?>");
	so.addVariable("tcolor2", "<?php echo $tcolor2 ?>");
	so.addVariable("mode", "tags");
	so.addVariable("distr", "true");
	so.addVariable("tspeed", "<?php echo $speed ?>");
	so.addVariable("tagcloud", "<tags><?php echo $cumulus ?></tags>");
	
	so.write("tag_cumulus");
	
</script>