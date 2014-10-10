<?php
/**
 *	Autocomplete Facebook Style Plugin
 *	@package autocomplete facebook style
 *	@author Liran Tal <liran.tal@gmail.com>
 *	@license GNU General Public License (GPL) version 2
 *	@copyright (c) Liran Tal of Enginx 2009
 *	@link http://www.enginx.com
 **/


function autocomplete_facebook_style()
{
	// extend css view
	extend_view('css','css/autocomplete_fcbkcomplete');
}


register_elgg_event_handler('init','system','autocomplete_facebook_style');


?>
