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
	
	function sitemap_init()
	{
	
	global $CONFIG;
							// Extend footer
			extend_view("footer/links", "sitemap/footer");
						extend_view('css','sitemap/css');
			

	
  // Register page handler and translations
  register_page_handler('sitemap', 'sitemap_page_handler');
  register_translations($CONFIG->pluginspath . "sitemap/languages/");
}


function sitemap_page_handler($page) {
  @include(dirname(__FILE__) . "/sitemap.php");
}
	
	
	register_elgg_event_handler('init','system','sitemap_init');
?>
