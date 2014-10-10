<?php

	/**
	 * Elgg profile edit form
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 * @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
	 */

?>
<div class="contentWrapper">

<form id="profile_edit_form" action="http://metamorphosis.med.duth.gr/action/profile/edit" method="post">

				<script type="text/javascript">
					$(document).ready(function(){
						changeProfileType();
					});
		
					function changeProfileType(){
						var selVal = $('#profile_edit_form select[name="custom_profile_type"]').val();
		
						$('.custom_fields_edit_profile_category').hide();
						$('.custom_profile_type_description').hide();
		
						if(selVal != ""){
							$('.custom_profile_type_' + selVal).show();
							$('#custom_profile_type_description_'+ selVal).show();
						}

												$('#elgg_horizontal_tabbed_nav li:visible:first>a').click();
											}
				</script>
				<p>
<label>
Select your profile type (either User or Educational Resource)<br />


<select name="custom_profile_type"  onchange='changeProfileType();'  class="input-pulldown">
<option value="356">educational resource</option><option value="355" selected="selected">user</option> 
</select></label>

</p>
<b><FONT COLOR="FF0000">Click on the tabs below this text to fill both primary and secondary profile Fields(for resources only) </b></FONT>			<script type="text/javascript">
				function toggle_tabbed_nav(div_id, element){
					$('#profile_manager_profile_edit_tab_content_wrapper>div').hide();
					$('#profile_manager_profile_edit_tab_content_' + div_id).show();
		
					$('#elgg_horizontal_tabbed_nav .selected').removeClass('selected');
					$(element).parent('li').addClass("selected");
				}
		
				$(document).ready(function(){
					$('#elgg_horizontal_tabbed_nav li:visible:first>a').click();
				});
			</script>
			
			<div id="elgg_horizontal_tabbed_nav">
				<ul>
					<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("354", this);'>General</a></li>
<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("742", this);'>Description</a></li>
<li class='custom_fields_edit_profile_category custom_profile_type_355'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("353", this);'>User Profile</a></li>
<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("1965", this);'>Classification</a></li>

<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("1966", this);'>Educational</a></li>
<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("1967", this);'>Repurposing</a></li>
				</ul>
			</div>
			<div id="profile_manager_profile_edit_tab_content_wrapper">
				<div id='profile_manager_profile_edit_tab_content_354' class='profile_manager_profile_edit_tab_content'>
<p>
<span class='custom_fields_more_info' id='more_info_meducator3'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator3'>A descriptive title of the resource.</span><label>
Resource Title<br />

</label>

<input type="text"   name="meducator3"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator3]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator1'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator1'>Please give the exact full URL one can use to retrieve the resource. <br />e.g. http://labyrinth.sgul.ac.uk/mnode.asp?id=qgxlrdbu3lpfvf4jesngxlrdbtpr9kq</span><label>

URL of the Resource<br />
</label>

<input type="text"   name="meducator1"  value="" class="input-url"/> <br />

<select  name="accesslevel[meducator1]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>

<p>
<span class='custom_fields_more_info' id='more_info_meducator2'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator2'>Resource descriptors within certain known cataloguing schemes. Can have multiple values. E.g. URN:isbn:0521523192, evip:vp:1000523</span><label>
URN of the Resource<br />
</label>

<input type="text"   name="meducator2"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator2]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator28'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator28'>Provide an Okkam ID for this Resource (if applicable)</span><label>
Okkam ID<br />
</label>

<input type="text"   name="meducator28"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator28]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator18b'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator18b'>More info on the licences at : http://creativecommons.org/about/licenses/meet-the-licenses</span><label>
-If the resource is protected by a Creative Commons licence, please choose which one. (If your resource is not protected by any IPR licence, please get one by visiting the <a href="http://creativecommons.org/choose/" target="_blank"> Creative Commons website</a>, and then state which licence you acquired below). <br /><br />
</label>


<select name="meducator18b"    class="input-pulldown">
<option selected="selected"></option><option>Attribution Non-commercial No Derivatives (by-nc-nd)</option><option>Attribution Non-commercial Share Alike (by-nc-sa)</option><option>Attribution Non-commercial (by-nc)</option><option>Attribution No Derivatives (by-nd)</option><option>Attribution Share Alike (by-sa)</option><option>Attribution (by)</option> 

</select><br />

<select  name="accesslevel[meducator18b]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<label>
If the resource is protected by any other IPR licence please state which one:<br />
</label>

<input type="text"   name="meducator18a"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator18a]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator19'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator19'>State any quality stamp the resource may have from a relevant issuing body. If possible, formulate it as a URN, e.g. URN:issuing_body:quality_stamp_name:stamp_value .</span><label>

Quality stamp<br />
</label>

<input type="text"   name="meducator19"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator19]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>

<p>
<label>
Resource Language<br />
</label>


<select name="meducator5"    class="input-pulldown">
<option selected="selected"></option><option>English</option><option>Irish</option><option>Scots</option><option>Welsh</option><option>Afrikaans</option><option>Albanian</option><option>Arabic</option><option>Armenian</option><option>Basque</option><option>Belarusian</option><option>Bosnian</option><option>Bulgarian</option><option>Catalan</option><option>Chinese</option><option>Cornish</option><option>Croatian</option><option>Czech</option><option>Danish</option><option>Dutch</option><option>Estonian</option><option>Finnish</option><option>French</option><option>Frisian</option><option>Galician</option><option>German</option><option>Greek</option><option>Hebrew</option><option>Hindi</option><option>Hungarian</option><option>Icelandic</option><option>Italian</option><option>Japanese</option><option>Korean</option><option>Latin</option><option>Latvian</option><option>Letzeburgesch</option><option>Limburgish</option><option>Lithuanian</option><option>Malay</option><option>Maltese</option><option>Maori</option><option>Moldavian</option><option>Norwegian</option><option>Polish</option><option>Portuguese</option><option>Romanian</option><option>Russian</option><option>Slovak</option><option>Slovenian</option><option>Sanskrit</option><option>Serbian</option><option>Spanish</option><option>Swedish</option><option>Thai</option><option>Turkish</option><option>Ukrainian</option><option>Other</option> 

</select><br />

<select  name="accesslevel[meducator5]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<label>
Language of metadata<br />
</label>


<select name="meducator24"    class="input-pulldown">
<option selected="selected"></option><option>English</option><option>Irish</option><option>Scots</option><option>Welsh</option><option>Afrikaans</option><option>Albanian</option><option>Arabic</option><option>Armenian</option><option>Basque</option><option>Belarusian</option><option>Bosnian</option><option>Bulgarian</option><option>Catalan</option><option>Chinese</option><option>Cornish</option><option>Croatian</option><option>Czech</option><option>Danish</option><option>Dutch</option><option>Estonian</option><option>Finnish</option><option>French</option><option>Frisian</option><option>Galician</option><option>German</option><option>Greek</option><option>Hebrew</option><option>Hindi</option><option>Hungarian</option><option>Icelandic</option><option>Italian</option><option>Japanese</option><option>Korean</option><option>Latin</option><option>Latvian</option><option>Letzeburgesch</option><option>Limburgish</option><option>Lithuanian</option><option>Malay</option><option>Maltese</option><option>Maori</option><option>Moldavian</option><option>Norwegian</option><option>Polish</option><option>Portuguese</option><option>Romanian</option><option>Russian</option><option>Slovak</option><option>Slovenian</option><option>Sanskrit</option><option>Serbian</option><option>Spanish</option><option>Swedish</option><option>Thai</option><option>Turkish</option><option>Ukrainian</option><option>Other</option> 

</select><br />

<select  name="accesslevel[meducator24]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<label>
Suggest any other fields you find necessary for the description of your resource<br />
</label>

<textarea class="input-textarea" name="meducatorcustom"   ></textarea> <br />

<select  name="accesslevel[meducatorcustom]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
</div>
<div id='profile_manager_profile_edit_tab_content_742' class='profile_manager_profile_edit_tab_content'>

<p>
<label>
Resource Author(s)<br />
</label>

<input type="text"   name="meducator20"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator20]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<label>
Date of Creation<br />
</label>
        
<script type="text/javascript" src="http://metamorphosis.med.duth.gr/mod/profile_manager/vendors/jquery.datepick.package-3.5.2/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="http://metamorphosis.med.duth.gr/mod/profile_manager/vendors/jquery.datepick.package-3.5.2/redmond.datepick.css">        <script type="text/javascript">
	$(document).ready(function(){
		$('#meducator211').datepick({
			dateFormat: 'mm/dd/yy', 
		    altField: '#meducator211_alt', 
		    altFormat: $.datepick.TIMESTAMP,
		    buttonImage: "http://metamorphosis.med.duth.gr/mod/profile_manager/vendors/jquery.datepick.package-3.5.2/calendar.gif",
			buttonImageOnly: true, 
			showOn: 'both',
			yearRange: '-90:+10',
			onSelect: function(value, date) { 		    	
				var curval = $('#meducator211_alt').val();
				if(curval > 0){
		    		$('#meducator211_alt').val((curval/1000) - (new Date().getTimezoneOffset() * 60));
				} 
			}
			});
	});

</script>
<input class="datepicker_hidden" type="text" READONLY name="meducator21" value="" id="meducator211_alt" /> <input type="text" READONLY id="meducator211" value="" style="width:200px"/><br />

<select  name="accesslevel[meducator21]"   class="input-access">

<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<label>
Cite this resource as:<br />
</label>

<input type="text"   name="meducator22"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator22]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator4'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator4'><b>Some keywords describing the resource</b>. They can be a mixture of keywords related to content, educational method, technical characteristics, etc. <br />For example meningitis, paediatrics, virtual patient, open labyrinth, cc: by-nc-sa</span><label>
Keywords<br />

</label>

<input type="text"   name="meducator4"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator4]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator7'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator7'>For example, resource synopsis, resource contents, resource overview, etc</span><label>

Description of the resource in terms of its educational content<br />
</label>

<textarea class="input-textarea" name="meducator7"   ></textarea> <br />

<select  name="accesslevel[meducator7]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator8'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator8'>Technical issues pertaining to the resource. E.g. file size, hardware and operating system requirements, etc. This field may also be a URL to a site that lists all these requirements.</span><label>
Description of the resource in terms of its technical format/characteristics/requirements<br />
</label>

<textarea class="input-textarea" name="meducator8"   ></textarea> <br />

<select  name="accesslevel[meducator8]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
</div>
<div id='profile_manager_profile_edit_tab_content_353' class='profile_manager_profile_edit_tab_content'>
<p>
<label>
Occupation<br />
</label>

<input type="text"   name="user6"  value="Systems and Networks Administrator, Associate Researcher" class="input-text"/> <br />

<select  name="accesslevel[user6]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Affiliation<br />
</label>
<input type="text"  name="Affiliation"  value="democritus university of thrace" class="input-tags"/> <br />

<select  name="accesslevel[Affiliation]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Country<br />
</label>
<select id="" name="Location"  class="input-pulldown">
	<option value="">Select a country</option>
	<option value="AF" >Afghanistan</option>
	<option value="AL" >Albania</option>

	<option value="DZ" >Algeria</option>
	<option value="AS" >American Samoa</option>
	<option value="AD" >Andorra</option>
	<option value="AO" >Angola</option>
	<option value="AI" >Anguilla</option>
	<option value="AQ" >Antarctica</option>

	<option value="AG" >Antigua and Barbuda</option>
	<option value="AR" >Argentina</option>
	<option value="AM" >Armenia</option>
	<option value="AW" >Aruba</option>
	<option value="AU" >Australia</option>
	<option value="AT" >Austria</option>

	<option value="AZ" >Azerbaidjan</option>
	<option value="BS" >Bahamas</option>
	<option value="BH" >Bahrain</option>
	<option value="BD" >Bangladesh</option>
	<option value="BB" >Barbados</option>
	<option value="BY" >Belarus</option>

	<option value="BE" >Belgium</option>
	<option value="BZ" >Belize</option>
	<option value="BJ" >Benin</option>
	<option value="BM" >Bermuda</option>
	<option value="BT" >Bhutan</option>
	<option value="BO" >Bolivia</option>

	<option value="BA" >Bosnia-Herzegovina</option>
	<option value="BW" >Botswana</option>
	<option value="BV" >Bouvet Island</option>
	<option value="BR" >Brazil</option>
	<option value="IO" >British Indian Ocean Territory</option>
	<option value="BN" >Brunei Darussalam</option>

	<option value="BG" >Bulgaria</option>
	<option value="BF" >Burkina Faso</option>
	<option value="BI" >Burundi</option>
	<option value="KH" >Cambodia</option>
	<option value="CM" >Cameroon</option>
	<option value="CA" >Canada</option>

	<option value="CV" >Cape Verde</option>
	<option value="KY" >Cayman Islands</option>
	<option value="CF" >Central African Republic</option>
	<option value="TD" >Chad</option>
	<option value="CL" >Chile</option>
	<option value="CN" >China</option>

	<option value="CX" >Christmas Island</option>
	<option value="CC" >Cocos (Keeling) Islands</option>
	<option value="CO" >Colombia</option>
	<option value="KM" >Comoros</option>
	<option value="CG" >Congo</option>
	<option value="CK" >Cook Islands</option>

	<option value="CR" >Costa Rica</option>
	<option value="HR" >Croatia</option>
	<option value="CU" >Cuba</option>
	<option value="CY" >Cyprus</option>
	<option value="CZ" >Czech Republic</option>
	<option value="DK" >Denmark</option>

	<option value="DJ" >Djibouti</option>
	<option value="DM" >Dominica</option>
	<option value="DO" >Dominican Republic</option>
	<option value="TP" >East Timor</option>
	<option value="EC" >Ecuador</option>
	<option value="EG" >Egypt</option>

	<option value="SV" >El Salvador</option>
	<option value="GQ" >Equatorial Guinea</option>
	<option value="ER" >Eritrea</option>
	<option value="EE" >Estonia</option>
	<option value="ET" >Ethiopia</option>
	<option value="FK" >Falkland Islands</option>

	<option value="FO" >Faroe Islands</option>
	<option value="FJ" >Fiji</option>
	<option value="FI" >Finland</option>
	<option value="CS" >Former Czechoslovakia</option>
	<option value="SU" >Former USSR</option>
	<option value="FR" >France</option>

	<option value="FX" >France (European Territory)</option>
	<option value="GF" >French Guyana</option>
	<option value="TF" >French Southern Territories</option>
	<option value="GA" >Gabon</option>
	<option value="GM" >Gambia</option>
	<option value="GE" >Georgia</option>

	<option value="DE" >Germany</option>
	<option value="GH" >Ghana</option>
	<option value="GI" >Gibraltar</option>
	<option value="GB" >United Kingdom</option>
	<option value="GR" selected='selected'>Greece</option>
	<option value="GL" >Greenland</option>

	<option value="GD" >Grenada</option>
	<option value="GP" >Guadeloupe (French)</option>
	<option value="GU" >Guam (USA)</option>
	<option value="GT" >Guatemala</option>
	<option value="GN" >Guinea</option>
	<option value="GW" >Guinea Bissau</option>

	<option value="GY" >Guyana</option>
	<option value="HT" >Haiti</option>
	<option value="HM" >Heard and McDonald Islands</option>
	<option value="HN" >Honduras</option>
	<option value="HK" >Hong Kong</option>
	<option value="HU" >Hungary</option>

	<option value="IS" >Iceland</option>
	<option value="IN" >India</option>
	<option value="ID" >Indonesia</option>
	<option value="INT" >International</option>
	<option value="IR" >Iran</option>
	<option value="IQ" >Iraq</option>

	<option value="IE" >Ireland</option>
	<option value="IL" >Israel</option>
	<option value="IT" >Italy</option>
	<option value="CI" >Ivory Coast (Cote D&#39;Ivoire)</option>
	<option value="JM" >Jamaica</option>
	<option value="JP" >Japan</option>

	<option value="JO" >Jordan</option>
	<option value="KZ" >Kazakhstan</option>
	<option value="KE" >Kenya</option>
	<option value="KI" >Kiribati</option>
	<option value="KW" >Kuwait</option>
	<option value="KG" >Kyrgyzstan</option>

	<option value="LA" >Laos</option>
	<option value="LV" >Latvia</option>
	<option value="LB" >Lebanon</option>
	<option value="LS" >Lesotho</option>
	<option value="LR" >Liberia</option>
	<option value="LY" >Libya</option>

	<option value="LI" >Liechtenstein</option>
	<option value="LT" >Lithuania</option>
	<option value="LU" >Luxembourg</option>
	<option value="MO" >Macau</option>
	<option value="MK" >Macedonia</option>
	<option value="MG" >Madagascar</option>

	<option value="MW" >Malawi</option>
	<option value="MY" >Malaysia</option>
	<option value="MV" >Maldives</option>
	<option value="ML" >Mali</option>
	<option value="MT" >Malta</option>
	<option value="MH" >Marshall Islands</option>

	<option value="MQ" >Martinique (French)</option>
	<option value="MR" >Mauritania</option>
	<option value="MU" >Mauritius</option>
	<option value="YT" >Mayotte</option>
	<option value="MX" >Mexico</option>
	<option value="FM" >Micronesia</option>

	<option value="MD" >Moldavia</option>
	<option value="MC" >Monaco</option>
	<option value="MN" >Mongolia</option>
	<option value="MS" >Montserrat</option>
	<option value="MA" >Morocco</option>
	<option value="MZ" >Mozambique</option>

	<option value="MM" >Myanmar</option>
	<option value="NA" >Namibia</option>
	<option value="NR" >Nauru</option>
	<option value="NP" >Nepal</option>
	<option value="NL" >Netherlands</option>
	<option value="AN" >Netherlands Antilles</option>

	<option value="NT" >Neutral Zone</option>
	<option value="NC" >New Caledonia (French)</option>
	<option value="NZ" >New Zealand</option>
	<option value="NI" >Nicaragua</option>
	<option value="NE" >Niger</option>
	<option value="NG" >Nigeria</option>

	<option value="NU" >Niue</option>
	<option value="NF" >Norfolk Island</option>
	<option value="KP" >North Korea</option>
	<option value="MP" >Northern Mariana Islands</option>
	<option value="NO" >Norway</option>
	<option value="OM" >Oman</option>

	<option value="PK" >Pakistan</option>
	<option value="PW" >Palau</option>
	<option value="PA" >Panama</option>
	<option value="PG" >Papua New Guinea</option>
	<option value="PY" >Paraguay</option>
	<option value="PE" >Peru</option>

	<option value="PH" >Philippines</option>
	<option value="PN" >Pitcairn Island</option>
	<option value="PL" >Poland</option>
	<option value="PF" >Polynesia (French)</option>
	<option value="PT" >Portugal</option>
	<option value="PR" >Puerto Rico</option>

	<option value="QA" >Qatar</option>
	<option value="RE" >Reunion (French)</option>
	<option value="RO" >Romania</option>
	<option value="RU" >Russian Federation</option>
	<option value="RW" >Rwanda</option>
	<option value="GS" >S. Georgia & S. Sandwich Isls.</option>

	<option value="SH" >Saint Helena</option>
	<option value="KN" >Saint Kitts & Nevis Anguilla</option>
	<option value="LC" >Saint Lucia</option>
	<option value="PM" >Saint Pierre and Miquelon</option>
	<option value="ST" >Saint Tome (Sao Tome) and Principe</option>
	<option value="VC" >Saint Vincent & Grenadines</option>

	<option value="WS" >Samoa</option>
	<option value="SM" >San Marino</option>
	<option value="SA" >Saudi Arabia</option>
	<option value="SN" >Senegal</option>
	<option value="SC" >Seychelles</option>
	<option value="SL" >Sierra Leone</option>

	<option value="SG" >Singapore</option>
	<option value="SK" >Slovak Republic</option>
	<option value="SI" >Slovenia</option>
	<option value="SB" >Solomon Islands</option>
	<option value="SO" >Somalia</option>
	<option value="ZA" >South Africa</option>

	<option value="KR" >South Korea</option>
	<option value="ES" >Spain</option>
	<option value="LK" >Sri Lanka</option>
	<option value="SD" >Sudan</option>
	<option value="SR" >Suriname</option>
	<option value="SJ" >Svalbard and Jan Mayen Islands</option>

	<option value="SZ" >Swaziland</option>
	<option value="SE" >Sweden</option>
	<option value="CH" >Switzerland</option>
	<option value="SY" >Syria</option>
	<option value="TJ" >Tadjikistan</option>
	<option value="TW" >Taiwan</option>

	<option value="TZ" >Tanzania</option>
	<option value="TH" >Thailand</option>
	<option value="TG" >Togo</option>
	<option value="TK" >Tokelau</option>
	<option value="TO" >Tonga</option>
	<option value="TT" >Trinidad and Tobago</option>

	<option value="TN" >Tunisia</option>
	<option value="TR" >Turkey</option>
	<option value="TM" >Turkmenistan</option>
	<option value="TC" >Turks and Caicos Islands</option>
	<option value="TV" >Tuvalu</option>
	<option value="UG" >Uganda</option>

	<option value="UA" >Ukraine</option>
	<option value="AE" >United Arab Emirates</option>
	<option value="GB" >United Kingdom</option>
	<option value="UY" >Uruguay</option>
	<option value="MIL" >USA Military</option>
	<option value="UM" >USA Minor Outlying Islands</option>

	<option value="US" >United States</option>
	<option value="UZ" >Uzbekistan</option>
	<option value="VU" >Vanuatu</option>
	<option value="VA" >Vatican City State</option>
	<option value="VE" >Venezuela</option>
	<option value="VN" >Vietnam</option>

	<option value="VG" >Virgin Islands (British)</option>
	<option value="VI" >Virgin Islands (USA)</option>
	<option value="WF" >Wallis and Futuna Islands</option>
	<option value="EH" >Western Sahara</option>
	<option value="YE" >Yemen</option>
	<option value="YU" >Yugoslavia</option>

	<option value="ZR" >Zaire</option>
	<option value="ZM" >Zambia</option>
	<option value="ZW" >Zimbabwe</option>
</select><br />

<select  name="accesslevel[Location]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
City<br />
</label>

<input type="text"   name="city"  value="Alexandroupolis" class="input-text"/> <br />

<select  name="accesslevel[city]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Research Interests<br />
</label>

<textarea class="input-textarea" name="user1"   >Network applications, Social networking on the web, Cryptography</textarea> <br />

<select  name="accesslevel[user1]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Teaching Interests/Subjects<br />
</label>

<textarea class="input-textarea" name="user2"   >Networks, Programming Languages, System Security</textarea> <br />

<select  name="accesslevel[user2]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Courses  I am teaching<br />
</label>

<textarea class="input-textarea" name="user3"   >Internet Basics and applications in Medicine</textarea> <br />

<select  name="accesslevel[user3]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Learning Interests/Subjects<br />
</label>

<textarea class="input-textarea" name="user4"   >Anything related to Informatics</textarea> <br />

<select  name="accesslevel[user4]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Other / personal reasons for searching / contibuting medical information :<br />
</label>

<textarea class="input-textarea" name="user5"   ></textarea> <br />

<select  name="accesslevel[user5]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
<p>
<label>
Member of the mEducator project<br />
</label>
<label><input type="radio"   name="user7"  value=""  class="input-radio" /></label><br /><label><input type="radio"   name="user7"  value="Yes" checked = "checked" class="input-radio" />Yes</label><br /><label><input type="radio"   name="user7"  value="No"  class="input-radio" />No</label><br /> <br />

<select  name="accesslevel[user7]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
</div>
<div id='profile_manager_profile_edit_tab_content_1965' class='profile_manager_profile_edit_tab_content'>
<p>
<label>
Select the type(s) of the Educational Resource from the following dropdown list<br />
</label>
			<link rel="stylesheet" type="text/css" href="http://metamorphosis.med.duth.gr/mod/profile_manager/vendors/dropdown-check-list.0.5/ui.dropdownchecklist.css" />
			
			<script type="text/javascript" src="http://metamorphosis.med.duth.gr/mod/profile_manager/vendors/dropdown-check-list.0.5/ui.core.js"></script>
			<script type="text/javascript" src="http://metamorphosis.med.duth.gr/mod/profile_manager/vendors/dropdown-check-list.0.5/ui.dropdownchecklist.js"></script>

		<script type="text/javascript">
	$(document).ready(function() {
    	$("#meducator6a1").dropdownchecklist({ width: 200});
    });
</script>
<p style="display:inline;">
	<select id="meducator6a1" name="meducator6a[]" multiple="multiple" style="display:none">
	<option>book</option><option>lecture notes</option><option>lecture presentation</option><option>image</option><option>video</option><option>sound</option><option>diagram</option><option>figure</option><option>graph</option><option>index</option><option>table</option><option>narrative text</option><option>questionnaire</option><option>exam questions</option><option>practical</option><option>anatomical atlas</option><option>teaching file</option><option>virtual patient</option><option>didactic problem</option><option>teaching case</option><option>scientific paper</option><option>algorithm</option><option>simulator</option><option>evidence based medicine form</option><option>objective structured clinical examination</option><option>clinical guidelines</option><option>wiki</option><option>blog</option><option>discussion forum</option><option>serious game</option><option>electronic traces of images</option><option>web trace</option>	</select>

</p><br />

<select  name="accesslevel[meducator6a]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator6'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator6'>Fill this if the above list doesn't contain your Educational Resource Type</span><label>
or provide a different Educational Resource Type.<br />

</label>

<input type="text"   name="meducator6"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator6]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator16'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator16'>e.g. medicine, nursing, sociology, medical informatics, etc</span><label>

Discipline for which the resource is intended<br />
</label>

<input type="text"   name="meducator16"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator16]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>

<p>
<label>
Specialty within this discipline<br />
</label>

<input type="text"   name="meducator17"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator17]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator15'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator15'>For example, 1st year undergraduate, post graduate, continuing education, etc.</span><label>
Educational level for which the resource is intended<br />
</label>

<input type="text"   name="meducator15"  value="" class="input-text"/> <br />

<select  name="accesslevel[meducator15]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
</div>
<div id='profile_manager_profile_edit_tab_content_1966' class='profile_manager_profile_edit_tab_content'>
<p>
<span class='custom_fields_more_info' id='more_info_meducator9'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator9'>Use this field to give a comprehensive description of the educational context for which this resource is meant for. This description can be further analysed using a number of secondary profile fields.</span><label>
Educational context for which this resource is recommended<br />
</label>

<textarea class="input-textarea" name="meducator9"   ></textarea> <br />

<select  name="accesslevel[meducator9]"   class="input-access">

<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator10'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator10'>You may include reference to the pedagogical approach that should be followed in order to use this resource for teaching/learning, and any other instructions for the educational (not technical) use of this resource.</span><label>
Instructions of how to use this resource for teaching and/or learning<br />
</label>

<textarea class="input-textarea" name="meducator10"   ></textarea> <br />

<select  name="accesslevel[meducator10]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<label>
Educational objectives<br />
</label>

<textarea class="input-textarea" name="meducator11"   ></textarea> <br />

<select  name="accesslevel[meducator11]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<label>
Expected learning outcomes<br />

</label>

<textarea class="input-textarea" name="meducator12"   ></textarea> <br />

<select  name="accesslevel[meducator12]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>

<label>
Assessment methods<br />
</label>

<textarea class="input-textarea" name="meducator13"   ></textarea> <br />

<select  name="accesslevel[meducator13]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator14'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator14'>Expected background knowledge/capability/etc in order to engage efficiently with this resource.</span><label>
Educational prerequisites<br />
</label>

<textarea class="input-textarea" name="meducator14"   ></textarea> <br />

<select  name="accesslevel[meducator14]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

</p>
</div>
<div id='profile_manager_profile_edit_tab_content_1967' class='profile_manager_profile_edit_tab_content'>
<p>
<span class='custom_fields_more_info' id='more_info_meducator25'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator25'><b>If you click on the link you will be transfered to a separate page to connect Educational Resources.Select the one you are editing right now first and then it's parent.</b> Alternatively if the resources  that have been used as a basis for this resource  are not in the system give their identifiers (URLs) separated by commas (,) for each resource   (only for  repurposed resources).</span><label>
If the Resource is a repurposed one, <a href="http://metamorphosis.med.duth.gr/mod/content_item/connect_content_items.php" target="_blank">use this page to connect any resource to it's parents</a> or provide  their full URL in the following textbox if they are not in the system:<br />
</label>

<input type="text"   name="meducator25"  value="" class="input-url"/> <br />

<select  name="accesslevel[meducator25]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>

<p>
        <select name="resources[]" multiple="multiple">
<?php 
			$members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
			foreach ($members as $member)
			{
							echo "<option value=\"$member->guid\">";
							echo $member->name;
							echo "</option>"; }
				}
?>		
     
        </select> </p>





<p>
<label>
Repurposing context (choose all that may apply):<br />

</label>
<script type="text/javascript">
	$(document).ready(function() {
    	$("#meducator262").dropdownchecklist({ width: 200});
    });
</script>
<p style="display:inline;">
	<select id="meducator262" name="meducator26[]" multiple="multiple" style="display:none">
	<option>different language</option><option>different culture</option><option>different pedagogy</option><option>different educational levels</option><option>different disciplines or professions</option><option>different content type</option><option>different technology</option><option>different abilities of end-users</option><option>Other</option>	</select>

</p><br />

<select  name="accesslevel[meducator26]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator27'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator27'>A detailed account of the reasons for repurposing and of the differences between the initial and repurposed resources.</span><label>
Repurposing description<br />

</label>

<textarea class="input-textarea" name="meducator27"   ></textarea> <br />

<select  name="accesslevel[meducator27]"   class="input-access">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 
</select>

</p>
</div>

			</div>
					<p>
			<label>
				Select who can view your profile information<br />
			</label>
				
<select  name="simple_access_control" onchange="set_access_control(this.value)"  class="simple_access_control">
<option value="0">Private</option><option value="-2">Friends</option><option value="1">Logged in users</option><option value="2" selected="selected">Public</option><option value="9">Group: Medical terminologies and learning object metadata</option> 

</select>

		</p>
			<p>
		<input type="hidden" name="__elgg_ts" value="1288682511" />

		<input type="hidden"  name="__elgg_token"  value="14a6447386064896cd3b34b9b71ebf0a" /> <input type="hidden"  name="__elgg_ts"  value="1288682512" /> 		<input type="hidden" name="username" value="lordanton" />
		<input type="submit" class="submit_button" value="Save" />
	</p>

</form>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".simple_access_control").val($(".input-access:first").val()).trigger("change");
		});
	
		function set_access_control(val){
			$(".input-access").val(val);
		}
	</script>
	<style type="text/css">
		.input-access {
			display: none;
		}
	</style>
	</div>