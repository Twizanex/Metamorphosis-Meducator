<?php

	/**
	 * Elgg Sitemap plugin
	 * 
	 * @package
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Matthias Sutter email@matthias-sutter.de
	 * @copyright CubeYoo.de
	 * @link http://cubeyoo.de
	 */
	 
	 
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	global $CONFIG;
	
	$title = elgg_echo("sitemap");
	
	$body = elgg_view_title($title);
	

	$body .=  elgg_view('sitemap/output');
	
    
    $body = elgg_view_layout('one_column', $body);
	
	// Finally draw the page
	page_draw($title, $body);
?>
