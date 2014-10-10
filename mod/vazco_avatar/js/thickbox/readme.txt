This is thickbox, a plugin modified by Michal Zacher to work with Elgg.

In order to thickbox on other content, you have to enable vazco_fields, and include those three lines of code above the content you want to display with thickbox:

global $CONFIG;
$path = $CONFIG->pluginspath.'vazco_fields/lightboxlibraries.php';
require_once($path);

Then you have to add a proper class to the image or link you want to display in thickbox. For more informations about how you can use thickbox, go here: http://jquery.com/demo/thickbox/