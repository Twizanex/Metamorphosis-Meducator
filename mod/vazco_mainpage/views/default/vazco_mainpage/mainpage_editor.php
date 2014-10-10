<?php

	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */
		// set some defaults
		$owner = page_owner_entity();

		$mainpageWidgets = $_SERVER['mainpageWidgets'];		
		$mainpageWidgets->load();
		$leftWidgets 	= $mainpageWidgets->getLeftColumnWidgets();
		$rightWidgets 	= $mainpageWidgets->getRightColumnWidgets();
		$middleWidgets 	= $mainpageWidgets->getCenterColumnWidgets();
		$widgettypes 	= $mainpageWidgets->getWidgetList();
		$htmlWidgets 	= $mainpageWidgets->getHtmlWidgets();
		$show3columns 	= $mainpageWidgets->use3ColumnLayout();
		
		
		$vertical_pos 	= $mainpageWidgets->getBackgroundVerticalPos();
		$horizontal_pos = $mainpageWidgets->getBackgroundHorizontalPos();
		$repeat 		= $mainpageWidgets->getBackgroundRepeat();
		$background 	= $mainpageWidgets->getBackgroundImg();

//print_r($rightWidgets);die();
?>
<script type="text/javascript">
$(document).ready(function () {
	$('div#customise_editpanel').slideToggle("fast");
});
</script>

<div id="customise_editpanel" style="visibility:visible;">

<div id="customise_editpanel_rhs">
<h2><?php echo elgg_echo("vazco_mainpage:gallery"); ?></h2>
<div id="widget_picker_gallery">


<?php

	foreach($widgettypes as $widget) {
		echo elgg_view("vazco_mainpage/widget_table",array('widget'=>$widget));
	}
?>

<br /><!-- bit of space at the bottom of the widget gallery -->

</div><!-- /#customise_editpanel_rhs -->
</div><!-- /#widget_picker_gallery -->


<div class="customise_editpanel_instructions">
<h2><?php echo elgg_echo('vazco_mainpage:title'); ?></h2>
<?php echo autop(elgg_echo('vazco_mainpage:description')); ?>
</div>


<div id="customise_page_view">

<table cellspacing="0">
	<tr>
		<td colspan="2" align="left" valign="top">
		<h2><?php echo elgg_echo("widgets:leftcolumn"); ?></h2>
		<div id="leftcolumn_widgets">
		
		<?php
			$leftcolumn_widgets = "";
			foreach($leftWidgets as $widget) {
				if (!empty($leftcolumn_widgets)) {
					$leftcolumn_widgets .= "::";
				}
				$leftcolumn_widgets .= "{$widget->getName()}::0";
				echo elgg_view("vazco_mainpage/widget_table",array('widget'=>$widget));
			}
		?>
		</div>
		</td>	
    	<?php if ($show3columns == "yes"){?>
    	<td colspan="2" align="left" valign="top">
			<h2><?php echo elgg_echo("widgets:middlecolumn"); ?></h2>
			<div id="middlecolumn_widgets" <?php if(get_context() == "profile")echo "class=\"long\""; ?>>
			<?php 
				$middlecolumn_widgets = "";
				foreach($middleWidgets as $widget) {
					if (!empty($middlecolumn_widgets)) {
						$middlecolumn_widgets .= "::";
					}
					$middlecolumn_widgets .= "{$widget->getName()}::0";
			
					echo elgg_view("vazco_mainpage/widget_table",array('widget'=>$widget));
				}
			?>
			</div>    
	    </td>
	    <?php }?>
	    <td rowspan="2" align="left" valign="top">
			<h2><?php echo elgg_echo("widgets:rightcolumn"); ?></h2>
			<div id="rightcolumn_widgets" <?php if(get_context() == "profile")echo "class=\"long\""; ?>>
			<?php 
				$rightcolumn_widgets = "";
				foreach($rightWidgets as $widget) {
					if (!empty($rightcolumn_widgets)) {
						$rightcolumn_widgets .= "::";
					}
					$rightcolumn_widgets .= "{$widget->getName()}::0";
			
					echo elgg_view("vazco_mainpage/widget_table",array('widget'=>$widget));
				}
			?>
			
			</div>
    	</td><!-- /rightcolumn td -->
	</tr>
	
</table>

</div><!-- /#customise_page_view -->

<form action="<?php echo $vars['url']; ?>action/vazco_mainpage/update" method="post">
<textarea type="textarea" value="Left widgets"   style="display:none" name="debugField1" id="debugField1" /><?php echo $leftcolumn_widgets; ?></textarea>
<textarea type="textarea" value="Middle widgets" style="display:none" name="debugField2" id="debugField2" /></textarea>
<textarea type="textarea" value="Right widgets"  style="display:none" name="debugField3" id="debugField3" /><?php echo $rightcolumn_widgets; ?></textarea>
<?php
	echo elgg_view('input/hidden',array('internalname' => '__elgg_token', 'value' => $vars['token']));
	echo elgg_view('input/hidden',array('internalname' => '__elgg_ts', 'value' => $vars['ts']));
?>
<input type="hidden" name="owner" value="<?php echo page_owner(); ?>" />
<input id="submit_widg_button" type="submit" value="<?php echo elgg_echo('save'); ?>" class="submit_button"  />

</form>
</div><!-- /customise_editpanel -->

<div class="contentWrapper vazco_mainpage_border">
	<form action="<?php echo $vars['url']; ?>action/vazco_mainpage/upload"  enctype="multipart/form-data" method="post">
	<?php
		echo elgg_view('input/hidden',array('internalname' => '__elgg_token', 'value' => $vars['token']));
		echo elgg_view('input/hidden',array('internalname' => '__elgg_ts', 'value' => $vars['ts']));
	?>
		<div class="vazco_mainpage_border_container">
			<div>		
					<p>
						<?php echo elgg_echo('vazco_mainpage:bckg:upload');?> <input type="file" name="imgfile">
					</p>
					<p>
						<?php echo elgg_echo('vazco_mainpage:bckg:position');?>
						<select name="horizontal_pos">
					        <option value="center" <?php if ($horizontal_pos == 'center') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:center'); ?></option>
					        <option value="left" <?php if ($horizontal_pos == 'left') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:left'); ?></option>
					        <option value="right" <?php if ($horizontal_pos == 'right') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:right'); ?></option>
					    </select>
					    <select name="vertical_pos">
					        <option value="center" <?php if ($vertical_pos == "middle") echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:middle'); ?></option>
					        <option value="top" <?php if ($vertical_pos == "top") echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:top'); ?></option>
					        <option value="bottom" <?php if ($vertical_pos == "bottom") echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:bottom'); ?></option>
					    </select>
					</p>
					<p>
						<?php echo elgg_echo('vazco_mainpage:bckg:position');?>
						<select name="repeat">
					        <option value="no-repeat" <?php if ($repeat == 'no-repeat') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:none'); ?></option>
					        <option value="repeat" <?php if ($repeat == 'repeat') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:repeat'); ?></option>
					        <option value="repeat-x" <?php if ($repeat == 'repeat-x') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:repeat-x'); ?></option>
					        <option value="repeat-y" <?php if ($repeat == 'repeat-y') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('vazco_mainpage:bckg:repeat-y'); ?></option>
					    </select>	    
					</p>
					<p>
					<input type="checkbox" name="remove_images" value="1" /><?php echo elgg_echo('vazco_mainpage:background:clear');?>
					</p>
			</div>
			<div class="mainpage_bckg_example">
				<p>
					<?php echo elgg_echo('vazco_mainpage:bckg:current');?>
				</p>
				<p>
					<img src="<?php echo $background; ?>" class="vazco_mainpage_bckg_example">
				</p>
			</div>
		</div>
		<div>
			<input id="submit_widg_button" type="submit" value="<?php echo elgg_echo('save'); ?>" class="submit_button"  />
		</div>
	</form>	
</div>



<script type="text/javascript">

function outputWidgetListMainpage(forElement) {
	return( $("input[@name='handler'], input[@name='guid']", forElement ).makeDelimitedList("value") );	
}

function refreshWidgetTables(){
	<?php 
	foreach ($htmlWidgets as $widget){
		if ($widget->isHtml()){
		?>
		content = $('#htmlContent<?php echo $widget->getId();?>').val();
		document.getElementById('htmlHandler<?php echo $widget->getId();?>').value='[html]'+content;
	<?php 
		}
	}
	?>
	var widgetNamesLeft = outputWidgetListMainpage('#leftcolumn_widgets');
	var widgetNamesMiddle = outputWidgetListMainpage('#middlecolumn_widgets');
	var widgetNamesRight = outputWidgetListMainpage('#rightcolumn_widgets');
	
	document.getElementById('debugField1').value = widgetNamesLeft;
	document.getElementById('debugField2').value = widgetNamesMiddle;
	document.getElementById('debugField3').value = widgetNamesRight;
}

$(document).ready(function () {
	 $(".htmlHandler").change(function () {
		 refreshWidgetTables();
	 });
	 $("#submit_widg_button").click(function () {
		 refreshWidgetTables();
	 });
	<?php foreach ($htmlWidgets as $widget){?>
 	$("#htmlContent<?php echo $widget->getId();?>").change(function () {
		 $("#htmlHandler<?php echo $widget->getId();?>").val('[html]'+$("#htmlContent<?php echo $widget->getId();?>").val());
		 refreshWidgetTables();
	 });
	<?php }?>
});
</script>

