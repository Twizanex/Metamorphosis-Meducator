/**
 * Use form module to add fields to file upload forms and file views
 * 
 * @package flexfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 * 
 */
 
The Elgg 1.x flexfile plugin requires the form plugin.

You can use the "Add form" link on the Manage forms page provided by 
the form module to add fields to your file upload form and other file views.
Specify a "file" form when creating your form.

If you specify a file form, the fields from this form will replace the current
tags field in the file upload form. This is so you can optionally have your
users select from a fixed vocabulary rather than use a free form tags box.

You can also add other metadata fields (eg. original author and publication
date if the person uploading the document was not the original creator).

This plugin will not affect the title, description or access boxes.

You can optionally specify list and display templates if you want to add your new
fields to the list view used for searches or the view used to display a specific
file. If you do not supply these templates, these fields will appear only on the
file upload form. (These values will also affect the result of generic searches
if the user doing the search has access to that file.)

You can, of course, include a tags box in your file form if you want to add tags
back to the file upload form. If you want it to be formatted in the same way as 
in the standard file view, put this in your file form display template:

<div class="filerepo_tags">
{{$file_tags}}
</div>

(assuming that your tags field is called "file tags").

Activating the flexfile plugin

Create a file form as described above. More documentation on creating forms
is contained in the form plugin README.txt.

Then extract the flexform plugin into the mod directory and activate it as
usual using the Elgg 1.x tool administration.

Once activated, the file upload form should show the fields that you have
defined for your file form.

You can safely activate the flexfile plugin even if you have not defined a
file form. If no file form is available, the flexfile profile will display the
standard file views unchanged.

