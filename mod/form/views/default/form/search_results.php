<?php

	/**
	 * Elgg display search form
	 * 
	 * @package Form
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Kevin Jardine <kevin@radagast.biz>
	 * @copyright Radagast Solutions 2008
	 * @link http://radagast.biz/
	 * 
	 * @uses $vars['form_id'] Optionally, the form to add a search definition for
	 */

	 // load form model
	 require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/models/model.php");
	 	 
$sd = $vars['search_definition'];
$fd = form_get_data_from_form_submit($sd->form_id);

$form = get_entity($sd->form_id);

$offset = (int) get_input('offset',0);
$limit = 5;

$search_page_link = '<p><a href="'.$vars['url'].'mod/form/search.php?sid='.$sd->getGUID().'" >'.elgg_echo('form:return_to_search').'</a></p>';

//echo $search_page_link;
echo '<div class="contentWrapper">';
echo '<div class="form_listing">';

$result = form_get_data_with_search_conditions($fd,$sd,$limit,$offset);

$count = $result[0];
$entities = $result[1];

if ($entities) {
	if (($form->profile == 1) || ($form->profile == 2)) {
		echo elgg_view_entity_list($entities, $count, $offset, $limit, false, false);
	} else {
        echo form_view_entity_list($entities, $form, $count, $offset, $limit, false, false);
	}
} else {
        echo '<p>'.elgg_echo('form:no_search_results').'</p>';
}

echo '</div>';
echo '</div>';

//echo $search_page_link;

?>