<?php 
$widget = $vars['widget'];

?>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
			<h3>
				<?php echo $widget->getCuteName(); ?>
				<input type="hidden" name="multiple" value="" />
				<input type="hidden" name="side" value="<?php echo $widget->isSide();?>" />
				<input type="hidden" name="main" value="<?php echo $widget->isMain();?>" />
				<?php if ($widget->isHtml()){?>
					<input class="htmlHandler" id="htmlHandler<?php echo $widget->getId();?>" type="hidden" name="handler" value = ""/></input>
				<?php }else{?>
					<input type="hidden" name="handler" value="<?php echo $widget->getName(); ?>" />
				<?php }?>
				<input type="hidden" name="description" value="<?php echo $widget->getDescription(); ?>" />
				<input type="hidden" name="guid" value="0" />				
			</h3>
		</td>
		<td width="17px" align="right"></td>
		<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="14px" height="14px" class="more_info" /></a></td>
		<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="15px" height="15px" class="drag_handle" /></a></td>
		</tr>
		<?php if ($widget->isHtml()){?>
		<tr><td colspan="4">
			<textarea class="mainpage_edit" id="htmlContent<?php echo $widget->getId();?>" type="text" name="context<?php echo $widget->getId();?>"/><?php echo $widget->getHtml(); ?></textarea>
		</td></tr>		
		<?php }?>
		</table>