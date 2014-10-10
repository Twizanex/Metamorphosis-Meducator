/**
 * Use form module to manage profile questions through the web
 * 
 * @package flexprofile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 * 
 */
 
The Elgg 1.x flexprofile plugin requires the form plugin.

Do not activate the flexprofile plugin without creating a profile form first -
if you do, your profiles will be blank and there will be nothing to edit
as no profile questions will be defined.

You can use the "Add form" link on the Manage forms page provided by 
the form module to manage your profile questions through the web. Specify a
"user profile" form when creating your form.

Field type restrictions

Currently the invitation box field type does not work for
profile forms.

It is also not possible to make profile fields required.

Some of these restrictions may be removed eventually.

For profile forms, there are a few extra profile field definition options.

You can specify whether the profile field should be displayed in the profile
summary area on the left, right or bottom area of the profile (or not at all). 
By default they are displayed on the right. Even if the field cannot be viewed
in the profile summary area, it can still be viewed in the extended profile
if the user has the correct permissions and you have activated the extended
profile option (see below).

You can also specify whether a profile field is invisible. This is a value that
can be edited by the user but is never displayed.

You can specify the profile format. Currently there are three formats. The
default format is similar to the standard Elgg 1.1 summary profile with the
addition of an extended profile (accessed by a link) that shows all the visible
fields (separated by tabs if more than one tab was specified). The default 
format (no extended profile) does not include the link to the extended profile.
The tabbed format shows just the tabbed extended profile and no summary
profile.

You can also specify different user profiles for different user categories.
There is currently no way to set user categories, however, so this feature
is for future use.

Activating the flexprofile plugin

Create a profile form as described above. More documentation on creating forms
is contained in the form plugin README.txt.

Then extract the flexprofile plugin into the mod directory and activate it as
usual using the Elgg 1.x tool administration.

Once activated, the edit details link should show the fields that you have
defined for your profile form.

You can have multiple profile forms defined, but the flexprofile plugin will
always use the one most recently created.
