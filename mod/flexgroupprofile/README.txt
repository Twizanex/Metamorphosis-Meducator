/**
 * Use form module to manage group profile questions through the web
 * 
 * @package flexgroupprofile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 * 
 */
 
The Elgg 1.x flexgroupprofile plugin requires the form plugin.

Do not activate the flexgroupprofile plugin without creating a group profile
form first - if you do, your group profiles will be blank and there will be
nothing to edit as no group profile questions will be defined.

You can use the "Add form" link on the Manage forms page provided by 
the form module to manage your group profile questions through the web. Specify
a "group profile" form when creating your form. You can define multiple group
profile categories and have a different set of profile questions for groups
with different categories.

Field type restrictions

Currently the invitation box field type does not work for group profile
forms.

It is also not possible to make group profile fields required.

Some of these restrictions may be removed eventually.

For group profile forms, there are a few extra profile field definition options.

You can specify whether the profile field should be displayed in the profile
summary area on the left, right or bottom area of the profile (or not at all). 
By default they are displayed on the right. Even if the field cannot be viewed
in the profile summary area, it can still be viewed in the extended profile
if the user has the correct permissions and you have activated the extended
profile option (see below).

You can also specify whether a profile field is invisible. This is a value that
can be edited by the user but is never displayed.

You can specify the profile format. Currently there are four formats. The
default format is similar to the standard Elgg 1.1 summary profile with the
addition of an extended profile (accessed by a link) that shows all the visible
fields (separated by tabs if more than one tab was specified). The default 
format (no extended profile) does not include the link to the extended profile.
The tabbed format shows just the tabbed extended profile and no summary
profile. The wide tabbed format shows the same information as the tabbed format,
but places it below the group icon image so there is more romm to display the
information.

You can also specify different group profiles for different group categories
by entering a category name when creating the group profile. (If the
group category field is left blank, then this used for groups with no
explicit category *or* groups with a category set but no profile form set
for that category.)

Setting the group category

The group category is defined by the "group_profile_category" metadata value
for each group. The flexgroupprofile plugin provides two ways to set this
value. 

First, you can pass a value to the group creation page.

Typically this would be done using the url:

//link-to-your-elgg/pg/groups/new/?group_profile_category=xxx

where xxx is the group category. This will cause the group creation page
to display the appropriate questions for that group category and to add the
group_profile_category metadata to the new group. Adding links like this to
your site typically requires a small amount of plugin coding. A through-the-web
feature to add these links may eventually be added to the flexgroupprofile
plugin.

Second, you can define a field with the name "group_profile_category" in a
group profile form. Create this form with a blank group category field.

Then users can enter a group category or select it from a list. Once the
group has been created, they can then edit the group profile which will then
show the appropriate questions for that group.

Activating the flexgroupprofile plugin

Create a group profile form as described above. More documentation on creating forms
is contained in the form plugin README.txt.

Then extract the flexgroupprofile plugin into the mod directory and activate it as
usual using the Elgg 1.x tool administration.

Once activated, the edit details link should show the fields that you have
defined for your group profile form.

