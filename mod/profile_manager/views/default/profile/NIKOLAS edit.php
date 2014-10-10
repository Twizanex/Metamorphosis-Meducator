<?php
	/**
	* Profile Manager 5.3
	* 
	* Replaces default Elgg profile edit form
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	* 
	* @uses $vars['entity'] The user entity
	* @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
	*/
	
        require_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/mmsearch/custom/MeducatorMetadata.php");

	$ts = time();
//	$token = generate_action_token($ts);

        $editAction = false;
        $resourceData = array();
        if(is_array($vars["data"]) && ($vars["sesame_id"] != ""))
        {
            $editAction = true;
            $resourceData = $vars["data"];
        }
        $mM = new MeducatorMetadata($vars["sesame_id"], $vars["data"]);
        //print_r($mM);
?>

<script type="text/javascript" language="javascript">
    function submitForm()
    {
        var typesList = "";
        //save the settings for the current selectedParent
        if(previousParent != null)
        {
            $('#repurpose_desc_' + previousParent).val($('#meducator27').val());
            $('#meducator265 option:selected').each(function(i, element){
               typesList += (typesList == "") ? $(element).text() : ";" + $(element).text();
             });
             $('#repurpose_types_' + previousParent).val(typesList);
        }
        //manage the selected Parents
        var optionsText = "";
        $('#selectedResources option').each(function(x, element){
            optionsText += '<option value="' + $(element).val() + '" selected="selected"></option>';
        });
        $('#profile_edit_form').append('<select multiple="multiple" name="selectedParentsList[]" style="display:none">' + optionsText + '</select>');
        //send the form
        $('#profile_edit_form').submit();
    }
</script>

<?php if($editAction) { ?>
<script type="text/javascript" language="javascript">
    <?php require_once(dirname(__FILE__) . "/profile_edit.js"); ?>
</script>
<?php } ?>

<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery.asmselect.js"></script>
<script type="text/javascript">
    var multipleFieldsList = new Array("meducator2", "meducator4", "meducator16", "meducator17", "meducator19", "meducator20", "meducator28" );
    var fieldTemplate = new Array();

    function addNewField(fieldID)
    {
      var nr = $('#' + fieldID + '_nr').val();
      nr++;

      var newFieldName = fieldID + "_" + nr;
      var template = fieldTemplate[fieldID].replace(eval('/'+fieldID+'/g'), newFieldName);
      //replace occurences for multiple fields
      if(nr > 1)
      {
            template = '<div id="' + newFieldName + '_div"><div class="multiple_input_field">' + template + '</div>';
            template += '<div class="multiple_input_remove"><a href="javascript:removeField(\'' + newFieldName + '\')">[-]</a></div></div>';
      }
      $('#' + fieldID + '_content_div').append(template);
      $('#' + fieldID + '_nr').val(nr);
    }

    function removeField(fieldID)
    {
            $('#' + fieldID + '_div').remove();
    }

    $(document).ready(function(){
      $('#multipleFieldsList').val(multipleFieldsList.join(";"));
      for(i=0; i<multipleFieldsList.length; i++)
      {
            var target = $('#' + multipleFieldsList[i] + '_div');
            var template = target.html();
            fieldTemplate[multipleFieldsList[i]] = template;
            target.html('<div id="' + multipleFieldsList[i] + '_content_div"></div>' +
                        '<div><a href="javascript:addNewField(\'' + multipleFieldsList[i] + '\')">[add]</a></div>' +
                        '<input type="hidden" name="' + multipleFieldsList[i] + '_nr" id="' + multipleFieldsList[i] + '_nr" value="0">'
                   );
            addNewField(multipleFieldsList[i]);
      }
    });
</script>



<link rel="stylesheet" type="text/css" href="<?php echo $vars['url']; ?>vendors/jquery.asmselect.css" />


<div class="contentWrapper">
<form id="profile_edit_form" name="masterForm" action="http://galinos.med.duth.gr:81/elgg/action/profile/edit" method="post">
<input type="hidden" name="multipleFieldsList" id="multipleFieldsList" value="">
<input type="hidden" name="pickedLabels" value="">
<input type="hidden" name="pickedIds" value="">
<input type="hidden" name="pickedNames" value="">

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



<select name="custom_profile_type"  onchange='changeProfileType();'  class="input-pulldown" DISABLED>
<?php if ($vars[entity]->issimpleuser=="no") { ?>
<option value="356" selected="selected">educational resource</option><option value="355" >user</option>
<?php }else { ?>
<option value="356">educational resource</option><option value="355" selected="selected">user</option> 
<?php } ?>
</select></label>
</p>
<script type="text/javascript">
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
<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("1967", this);'>Repurposing </a></li>
<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("1969", this);'>Vocabularies </a></li>

<li class='custom_fields_edit_profile_category custom_profile_type_356'><a href='javascript:void(0);' onclick='toggle_tabbed_nav("1968", this);'>Companion</a></li>
				
				</ul>
			</div>

			<div id="profile_manager_profile_edit_tab_content_wrapper">
				<div id='profile_manager_profile_edit_tab_content_354' class='profile_manager_profile_edit_tab_content'>
<p>
<span class='custom_fields_more_info' id='more_info_meducator3'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator3'>A descriptive title of the resource.</span><label>
Resource Title<br />
</label>

<input type="text" name="meducator3"  value="<?php echo $mM->getData("title"); ?>" class="input-text"/> <br />


</p>
<!--
<p>
<span class='custom_fields_more_info' id='more_info_meducator1'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator1'>Please give the exact full URL one can use to retrieve the resource. <br />e.g. http://labyrinth.sgul.ac.uk/mnode.asp?id=qgxlrdbu3lpfvf4jesngxlrdbtpr9kq</span>
<label>URL of the Resource<br /></label>
<div id="meducator1_div">
    <input type="text" name="meducator1"  value="" class="input-url"/> <br />
</div>
</p>-->
<p>
<span class='custom_fields_more_info' id='more_info_meducator2'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator2'>Resource descriptors within certain known cataloguing schemes. Can have multiple values. E.g. URN:isbn:0521523192, evip:vp:1000523</span><label>
Identifiers<br />
</label>
<style type="text/css">
select {
    -moz-border-radius: 5px 5px 5px 5px;
    border: 1px solid #CCCCCC;
    color: #666666;
    font: 120% Arial,Helvetica,sans-serif;
    padding: 3px;
    /*width: 100%;*/
}
</style>
<div id="meducator2_div">
    <div style="float:left; width: 85%;"><input type="text" name="meducator2"  value="" class="input-text"/></div>
    <div style="float:right; width: 14%; margin-right: 10px;">
        <select name="type_meducator2" id="type_meducator2" class="input-pulldown">
            <option value="">-- identifier type --</option>
            <option value="URL">URL</option>
            <option value="URI">URI</option>
            <option value="URN">URN</option>
            <option value="OkkamID">Okkam ID</option>
            <option value="DOI">DOI</option>
            <option value="ISBN">ISBN</option>
            <option value="ISSN">ISSN</option>
        </select>
    </div>
    <div style="clear:both; margin:auto;"></div>
</div>
<br>
</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator18b'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator18b'>More info on the licences at : http://creativecommons.org/about/licenses/meet-the-licenses</span><label>
-If the resource is protected by a Creative Commons licence, please choose which one. (If your resource is not protected by any IPR licence, please get one by visiting the <a href="http://creativecommons.org/choose/" target="_blank"> Creative Commons website</a>, and then state which licence you acquired below). <br /><br />
</label>


<select name="meducator18b"  id="meducator18b"  class="input-pulldown">
    <option selected="selected"></option>
    <option value="http://purl.org/meducator/licenses/Attribution">Attribution cc by</option>
    <option value="http://purl.org/meducator/licenses/Attribution-Share-Alike">Attribution Share Alike cc by-sa</option>
    <option value="http://purl.org/meducator/licenses/Attribution-No-Derivatives">Attribution No Derivatives cc by-nd</option>
    <option value="http://purl.org/meducator/licenses/Attribution-Non-Commercial">Attribution Non-Commercial cc by-nc</option>
    <option value="http://purl.org/meducator/licenses/Attribution-Non-Commercial-Share-Alike">Attribution Non-Commercial Share Alike cc by-nc-sa</option>
    <option value="http://purl.org/meducator/licenses/Attribution-Non-Commercial-No-Derivatives">Attribution Non-Commercial No Derivatives cc by-nc-nd</option>
</select><br />

</p>
<p>
<label>
If the resource is protected by any other IPR licence please state which one:
</label>
<input type="text" name="meducator18a" id="meducator18a" value="" class="input-text"/> <br />
</p>

<p>
<span class='custom_fields_more_info' id='more_info_meducator19'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator19'>State any quality stamp the resource may have from a relevant issuing body. If possible, formulate it as a URN, e.g. URN:issuing_body:quality_stamp_name:stamp_value .</span><label>
Quality stamp</label>
<div id="meducator19_div">
    <input type="text"   name="meducator19"  value="" class="input-text"/> <br />
</div>
<br />
</p>
<p>
<label>Resource Language<br /></label>
<select name="meducator5" id="meducator5" class="input-pulldown">
    <option value="">-- select language --</option>
    <option value="af">Afrikaans</option>
    <option value="sq">Albanian</option>
    <option value="ar">Arabic</option>
    <option value="be">Belarusian</option>

    <option value="bg">Bulgarian</option>
    <option value="ca">Catalan</option>
    <option value="zh">Chinese</option>
    <option value="hr">Croatian</option>
    <option value="cs">Czech</option>
    <option value="da">Danish</option>

    <option value="nl">Dutch</option>
    <option value="en">English</option>
    <option value="et">Estonian</option>
    <option value="eu">Euskera</option>
    <option value="tl">Filipino</option>
    <option value="fi">Finnish</option>

    <option value="fr">French</option>
    <option value="gl">Galician</option>
    <option value="de">German</option>
    <option value="el">Greek</option>
    <option value="ht">Haitian Creole</option>
    <option value="iw">Hebrew</option>

    <option value="hi">Hindi</option>
    <option value="hu">Hungarian</option>
    <option value="is">Icelandic</option>
    <option value="id">Indonesian</option>
    <option value="ga">Irish</option>
    <option value="it">Italian</option>

    <option value="ja">Japanese</option>
    <option value="ko">Korean</option>
    <option value="lv">Latvian</option>
    <option value="lt">Lithuanian</option>
    <option value="mk">Macedonian</option>
    <option value="ms">Malay</option>

    <option value="mt">Maltese</option>
    <option value="no">Norwegian</option>
    <option value="fa">Persian</option>
    <option value="pl">Polish</option>
    <option value="pt">Portuguese</option>
    <option value="ro">Romanian</option>

    <option value="ru">Russian</option>
    <option value="sr">Serbian</option>
    <option value="sk">Slovak</option>
    <option value="sl">Slovenian</option>
    <option value="es">Spanish</option>
    <option value="sw">Swahili</option>

    <option value="sv">Swedish</option>
    <option value="th">Thai</option>
    <option value="tr">Turkish</option>
    <option value="uk">Ukrainian</option>
    <option value="vi">Vietnamese</option
    ><option value="cy">Welsh</option>
    <option value="yi">Yiddish</option>
</select><br />
</p>
<p>
<label>Language of metadata<br /></label>
    <select name="meducator24" id="meducator24" class="input-pulldown">
        <option value="">-- select language --</option>
        <option value="af">Afrikaans</option>
        <option value="sq">Albanian</option>
        <option value="ar">Arabic</option>
        <option value="be">Belarusian</option>

        <option value="bg">Bulgarian</option>
        <option value="ca">Catalan</option>
        <option value="zh">Chinese</option>
        <option value="hr">Croatian</option>
        <option value="cs">Czech</option>
        <option value="da">Danish</option>

        <option value="nl">Dutch</option>
        <option value="en">English</option>
        <option value="et">Estonian</option>
        <option value="eu">Euskera</option>
        <option value="tl">Filipino</option>
        <option value="fi">Finnish</option>

        <option value="fr">French</option>
        <option value="gl">Galician</option>
        <option value="de">German</option>
        <option value="el">Greek</option>
        <option value="ht">Haitian Creole</option>
        <option value="iw">Hebrew</option>

        <option value="hi">Hindi</option>
        <option value="hu">Hungarian</option>
        <option value="is">Icelandic</option>
        <option value="id">Indonesian</option>
        <option value="ga">Irish</option>
        <option value="it">Italian</option>

        <option value="ja">Japanese</option>
        <option value="ko">Korean</option>
        <option value="lv">Latvian</option>
        <option value="lt">Lithuanian</option>
        <option value="mk">Macedonian</option>
        <option value="ms">Malay</option>

        <option value="mt">Maltese</option>
        <option value="no">Norwegian</option>
        <option value="fa">Persian</option>
        <option value="pl">Polish</option>
        <option value="pt">Portuguese</option>
        <option value="ro">Romanian</option>

        <option value="ru">Russian</option>
        <option value="sr">Serbian</option>
        <option value="sk">Slovak</option>
        <option value="sl">Slovenian</option>
        <option value="es">Spanish</option>
        <option value="sw">Swahili</option>

        <option value="sv">Swedish</option>
        <option value="th">Thai</option>
        <option value="tr">Turkish</option>
        <option value="uk">Ukrainian</option>
        <option value="vi">Vietnamese</option
        ><option value="cy">Welsh</option>
        <option value="yi">Yiddish</option>
    </select><br />

</p>

</div>
<div id='profile_manager_profile_edit_tab_content_742' class='profile_manager_profile_edit_tab_content'>
<p>
  <label>Resource Author(s)</label>
<div>
  <div style="float:left; width: 30%;">Name</div>
  <div style="float:left; width: 30%; margin-left: 10px;">Affiliation</div>
  <div style="float:left; width: 30%; margin-left: 10px;">Foaf Profile URI</div>
</div>
<div style="clear:both; margin:auto;"></div>
<div id="meducator20_div">
    <div style="float:left; width: 30%;"><input type="text" name="name_meducator20"  value="" class="input-text"/></div>
    <div style="float: left; width: 30%; margin-left: 10px;"><input type="text" name="affiliation_meducator20"  value="" class="input-text"/></div>
    <div style="float:left; width: 30%; margin-left: 10px;"><input type="text" name="foafURI_meducator20"  value="" class="input-text"/></div>
    <div style="clear:both; margin:auto;"></div>
</div>


</p>
<p>

<label>
Date of Creation<br />
</label>
        
<script type="text/javascript" src="http://galinos.med.duth.gr:81/elgg/mod/profile_manager/vendors/jquery.datepick.package-3.5.2/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="http://galinos.med.duth.gr:81/elgg/mod/profile_manager/vendors/jquery.datepick.package-3.5.2/redmond.datepick.css">        <script type="text/javascript">
	$(document).ready(function(){
		$('#meducator211').datepick({
			dateFormat: 'mm/dd/yy', 
		    altField: '#meducator211_alt', 
		    altFormat: $.datepick.TIMESTAMP,
		    buttonImage: "http://galinos.med.duth.gr:81/elgg/mod/profile_manager/vendors/jquery.datepick.package-3.5.2/calendar.gif",
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
<input class="datepicker_hidden" type="text" READONLY name="meducator21" value="" id="meducator211_alt" /> <input type="text" READONLY id="meducator211" value="<?php echo ($editAction) ? substr($resourceData["created"],5,2) . "/" . substr($resourceData["created"],8,2) . "/" . substr($resourceData["created"],0,4) : ""; ?>" style="width:200px"/><br />


</p>
<p>
<label>
Cite this resource as:<br />
</label>

<input type="text"   name="meducator22"  value="" class="input-text"/> <br />

</p>

<p>
<span class='custom_fields_more_info' id='more_info_meducator7'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator7'>For example, resource synopsis, resource contents, resource overview, etc</span><label>
Description of the resource in terms of its educational content<br />
</label>

<textarea class="input-textarea" name="meducator7"   ></textarea> <br />


</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator8'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator8'>Technical issues pertaining to the resource. E.g. file size, hardware and operating system requirements, etc. This field may also be a URL to a site that lists all these requirements.</span><label>
Description of the resource in terms of its technical format/characteristics/requirements<br />
</label>

<textarea class="input-textarea" name="meducator8"   ></textarea> <br />


</p>
</div>
<div id='profile_manager_profile_edit_tab_content_353' class='profile_manager_profile_edit_tab_content'>
<p>
<label>
Occupation<br />
</label>

<input type="text"   name="user6"  value="" class="input-text"/> <br />

</p>
<p>
<label>
Affiliation<br />
</label>
<input type="text"  name="Affiliation"  value="" class="input-tags"/> <br />


</p>
<p>
<label>
Country<br />
</label>
<select id="" name="Location"  class="input-pulldown">
	<option value="">Select a country</option>
	<option value="Afghanistan" >Afghanistan</option>
	<option value="Albania" >Albania</option>

	<option value="Algeria" >Algeria</option>
	<option value="American Samoa" >American Samoa</option>
	<option value="Andorra" >Andorra</option>
	<option value="Angola" >Angola</option>
	<option value="Anguilla" >Anguilla</option>
	<option value="Antarctica" >Antarctica</option>

	<option value="Antigua and Barbuda" >Antigua and Barbuda</option>
	<option value="Argentina" >Argentina</option>
	<option value="Armenia" >Armenia</option>
	<option value="Aruba" >Aruba</option>
	<option value="Australia" >Australia</option>
	<option value="Austria" >Austria</option>

	<option value="Azerbaidjan" >Azerbaidjan</option>
	<option value="Bahamas" >Bahamas</option>
	<option value="Bahrain" >Bahrain</option>
	<option value="Bangladesh" >Bangladesh</option>
	<option value="Barbados" >Barbados</option>
	<option value="Belarus" >Belarus</option>

	<option value="Belgium" >Belgium</option>
	<option value="Belize" >Belize</option>
	<option value="Benin" >Benin</option>
	<option value="Bermuda" >Bermuda</option>
	<option value="Bhutan" >Bhutan</option>
	<option value="Bolivia" >Bolivia</option>

	<option value="Bosnia-Herzegovina" >Bosnia-Herzegovina</option>
	<option value="Botswana" >Botswana</option>
	<option value="Bouvet Island" >Bouvet Island</option>
	<option value="Brazil" >Brazil</option>
	<option value="British Indian Ocean Territory" >British Indian Ocean Territory</option>
	<option value="Brunei Darussalam" >Brunei Darussalam</option>

	<option value="Bulgaria" >Bulgaria</option>
	<option value="Burkina Faso" >Burkina Faso</option>
	<option value="Burundi" >Burundi</option>
	<option value="Cambodia" >Cambodia</option>
	<option value="Cameroon" >Cameroon</option>
	<option value="Canada" >Canada</option>

	<option value="Cape Verde" >Cape Verde</option>
	<option value="Cayman Islands" >Cayman Islands</option>
	<option value="Central African Republic" >Central African Republic</option>
	<option value="Chad" >Chad</option>
	<option value="Chile" >Chile</option>
	<option value="China" >China</option>

	<option value="Christmas Island" >Christmas Island</option>
	<option value="Cocos (Keeling) Islands" >Cocos (Keeling) Islands</option>
	<option value="Colombia" >Colombia</option>
	<option value="Comoros" >Comoros</option>
	<option value="Congo" >Congo</option>
	<option value="Cook Islands" >Cook Islands</option>

	<option value="Costa Rica" >Costa Rica</option>
	<option value="Croatia" >Croatia</option>
	<option value="Cuba" >Cuba</option>
	<option value="Cyprus" >Cyprus</option>
	<option value="Czech Republic" >Czech Republic</option>
	<option value="Denmark" >Denmark</option>

	<option value="Djibouti" >Djibouti</option>
	<option value="Dominica" >Dominica</option>
	<option value="Dominican Republic" >Dominican Republic</option>
	<option value="East Timor" >East Timor</option>
	<option value="Ecuador" >Ecuador</option>
	<option value="Egypt" >Egypt</option>

	<option value="El Salvador" >El Salvador</option>
	<option value="Equatorial Guinea" >Equatorial Guinea</option>
	<option value="Eritrea" >Eritrea</option>
	<option value="Estonia" >Estonia</option>
	<option value="Ethiopia" >Ethiopia</option>
	<option value="Falkland Islands" >Falkland Islands</option>

	<option value="Faroe Islands" >Faroe Islands</option>
	<option value="Fiji" >Fiji</option>
	<option value="Finland" >Finland</option>
	<option value="Former Czechoslovakia" >Former Czechoslovakia</option>
	<option value="France" >France</option>

	<option value="France (European Territory)" >France (European Territory)</option>
	<option value="French Guyana" >French Guyana</option>
	<option value="French Southern Territories" >French Southern Territories</option>
	<option value="Gabon" >Gabon</option>
	<option value="Gambia" >Gambia</option>
	<option value="Georgia" >Georgia</option>

	<option value="Germany" >Germany</option>
	<option value="Ghana" >Ghana</option>
	<option value="Gibraltar" >Gibraltar</option>
	<option value="United Kingdom" >United Kingdom</option>
	<option value="Greece">Greece</option>
	<option value="Greenland" >Greenland</option>

	<option value="Grenada" >Grenada</option>
	<option value="Guadeloupe (French)" >Guadeloupe (French)</option>
	<option value="Guam (USA)" >Guam (USA)</option>
	<option value="Guatemala" >Guatemala</option>
	<option value="Guinea" >Guinea</option>
	<option value="Guinea Bissau" >Guinea Bissau</option>

	<option value="Guyana" >Guyana</option>
	<option value="Haiti" >Haiti</option>
	<option value="Heard and McDonald Islands" >Heard and McDonald Islands</option>
	<option value="Honduras" >Honduras</option>
	<option value="Hong Kong" >Hong Kong</option>
	<option value="Hungary" >Hungary</option>

	<option value="Iceland" >Iceland</option>
	<option value="India" >India</option>
	<option value="Indonesia" >Indonesia</option>
	<option value="Iran" >Iran</option>
	<option value="Iraq" >Iraq</option>

	<option value="Ireland" >Ireland</option>
	<option value="Israel" >Israel</option>
	<option value="Italy" >Italy</option>
	<option value="Ivory Coast (Cote D&#39;Ivoire)" >Ivory Coast (Cote D&#39;Ivoire)</option>
	<option value="Jamaica" >Jamaica</option>
	<option value="Japan" >Japan</option>

	<option value="Jordan" >Jordan</option>
	<option value="Kazakhstan" >Kazakhstan</option>
	<option value="Kenya" >Kenya</option>
	<option value="Kiribati" >Kiribati</option>
	<option value="Kuwait" >Kuwait</option>
	<option value="Kyrgyzstan" >Kyrgyzstan</option>

	<option value="Laos" >Laos</option>
	<option value="Latvia" >Latvia</option>
	<option value="Lebanon" >Lebanon</option>
	<option value="Lesotho" >Lesotho</option>
	<option value="Liberia" >Liberia</option>
	<option value="Libya" >Libya</option>

	<option value="Liechtenstein" >Liechtenstein</option>
	<option value="Lithuania" >Lithuania</option>
	<option value="Luxembourg" >Luxembourg</option>
	<option value="Macau" >Macau</option>
	<option value="FYROM" >FYROM</option>
	<option value="Madagascar" >Madagascar</option>

	<option value="Malawi" >Malawi</option>
	<option value="Malaysia" >Malaysia</option>
	<option value="Maldives" >Maldives</option>
	<option value="Mali" >Mali</option>
	<option value="Malta" >Malta</option>
	<option value="Marshall Islands" >Marshall Islands</option>

	<option value="Martinique (French)" >Martinique (French)</option>
	<option value="Mauritania" >Mauritania</option>
	<option value="Mauritius" >Mauritius</option>
	<option value="Mayotte" >Mayotte</option>
	<option value="Mexico" >Mexico</option>
	<option value="Micronesia" >Micronesia</option>

	<option value="Moldavia" >Moldavia</option>
	<option value="Monaco" >Monaco</option>
	<option value="Mongolia" >Mongolia</option>
	<option value="Montserrat" >Montserrat</option>
	<option value="Morocco" >Morocco</option>
	<option value="Mozambique" >Mozambique</option>

	<option value="Myanmar" >Myanmar</option>
	<option value="Namibia" >Namibia</option>
	<option value="Nauru" >Nauru</option>
	<option value="Nepal" >Nepal</option>
	<option value="Netherlands" >Netherlands</option>
	<option value="Netherlands Antilles" >Netherlands Antilles</option>

	<option value="New Caledonia (French)" >New Caledonia (French)</option>
	<option value="New Zealand" >New Zealand</option>
	<option value="Nicaragua" >Nicaragua</option>
	<option value="Niger" >Niger</option>
	<option value="Nigeria" >Nigeria</option>

	<option value="Niue" >Niue</option>
	<option value="Norfolk Island" >Norfolk Island</option>
	<option value="North Korea" >North Korea</option>
	<option value="Northern Mariana Islands" >Northern Mariana Islands</option>
	<option value="Norway" >Norway</option>
	<option value="Oman" >Oman</option>

	<option value="Pakistan" >Pakistan</option>
	<option value="Palau" >Palau</option>
	<option value="Panama" >Panama</option>
	<option value="Papua New Guinea" >Papua New Guinea</option>
	<option value="Paraguay" >Paraguay</option>
	<option value="Peru" >Peru</option>

	<option value="Philippines" >Philippines</option>
	<option value="Pitcairn Island" >Pitcairn Island</option>
	<option value="Poland" >Poland</option>
	<option value="Polynesia (French)" >Polynesia (French)</option>
	<option value="Portugal" >Portugal</option>
	<option value="Puerto Rico" >Puerto Rico</option>

	<option value="Qatar" >Qatar</option>
	<option value="Reunion (French)" >Reunion (French)</option>
	<option value="Romania" >Romania</option>
	<option value="Russian Federation" >Russian Federation</option>
	<option value="Rwanda" >Rwanda</option>
	<option value="S. Georgia & S. Sandwich Isls." >S. Georgia & S. Sandwich Isls.</option>

	<option value="Saint Helena" >Saint Helena</option>
	<option value="Saint Kitts & Nevis Anguilla" >Saint Kitts & Nevis Anguilla</option>
	<option value="Saint Lucia" >Saint Lucia</option>
	<option value="Saint Pierre and Miquelon" >Saint Pierre and Miquelon</option>
	<option value="Saint Tome (Sao Tome) and Principe" >Saint Tome (Sao Tome) and Principe</option>
	<option value="Saint Vincent & Grenadines" >Saint Vincent & Grenadines</option>

	<option value="Samoa" >Samoa</option>
	<option value="San Marino" >San Marino</option>
	<option value="Saudi Arabia" >Saudi Arabia</option>
	<option value="Senegal" >Senegal</option>
	<option value="Seychelles" >Seychelles</option>
	<option value="Sierra Leone" >Sierra Leone</option>

	<option value="Singapore" >Singapore</option>
	<option value="Slovak Republic" >Slovak Republic</option>
	<option value="Slovenia" >Slovenia</option>
	<option value="Solomon Islands" >Solomon Islands</option>
	<option value="Somalia" >Somalia</option>
	<option value="South Africa" >South Africa</option>

	<option value="South Korea" >South Korea</option>
	<option value="Spain" >Spain</option>
	<option value="Sri Lanka" >Sri Lanka</option>
	<option value="Sudan" >Sudan</option>
	<option value="Suriname" >Suriname</option>
	<option value="Svalbard and Jan Mayen Islands" >Svalbard and Jan Mayen Islands</option>

	<option value="Swaziland" >Swaziland</option>
	<option value="Sweden" >Sweden</option>
	<option value="Switzerland" >Switzerland</option>
	<option value="Syria" >Syria</option>
	<option value="Tadjikistan" >Tadjikistan</option>
	<option value="Taiwan" >Taiwan</option>

	<option value="Tanzania" >Tanzania</option>
	<option value="Thailand" >Thailand</option>
	<option value="Togo" >Togo</option>
	<option value="Tokelau" >Tokelau</option>
	<option value="Tonga" >Tonga</option>
	<option value="Trinidad and Tobago" >Trinidad and Tobago</option>

	<option value="Tunisia" >Tunisia</option>
	<option value="Turkey" >Turkey</option>
	<option value="Turkmenistan" >Turkmenistan</option>
	<option value="Turks and Caicos Islands" >Turks and Caicos Islands</option>
	<option value="Tuvalu" >Tuvalu</option>
	<option value="Uganda" >Uganda</option>

	<option value="Ukraine" >Ukraine</option>
	<option value="United Arab Emirates" >United Arab Emirates</option>
	<option value="United Kingdom" >United Kingdom</option>
	<option value="Uruguay" >Uruguay</option>
	<option value="USA Minor Outlying Islands" >USA Minor Outlying Islands</option>

	<option value="United States" >United States</option>
	<option value="Uzbekistan" >Uzbekistan</option>
	<option value="Vanuatu" >Vanuatu</option>
	<option value="Vatican City State" >Vatican City State</option>
	<option value="Venezuela" >Venezuela</option>
	<option value="Vietnam" >Vietnam</option>

	<option value="Virgin Islands (British)" >Virgin Islands (British)</option>
	<option value="Virgin Islands (USA)" >Virgin Islands (USA)</option>
	<option value="Wallis and Futuna Islands" >Wallis and Futuna Islands</option>
	<option value="Western Sahara" >Western Sahara</option>
	<option value="Yemen" >Yemen</option>
	<option value="Yugoslavia" >Yugoslavia</option>

	<option value="Zaire" >Zaire</option>
	<option value="Zambia" >Zambia</option>
	<option value="Zimbabwe" >Zimbabwe</option>
</select><br />


</p>
<p>
<label>
City<br />
</label>

<input type="text"   name="city"  value="" class="input-text"/> <br />


</p>
<p>
<label>
Research Interests<br />
</label>

<textarea class="input-textarea" name="user1"   ></textarea> <br />


</p>
<p>
<label>
Teaching Interests/Subjects<br />
</label>

<textarea class="input-textarea" name="user2"   ></textarea> <br />


</p>
<p>
<label>
Courses  I am teaching<br />
</label>

<textarea class="input-textarea" name="user3"   ></textarea> <br />


</p>
<p>
<label>
Learning Interests/Subjects<br />
</label>

<textarea class="input-textarea" name="user4"   ></textarea> <br />

</p>
<p>
<label>
Other / personal reasons for searching / contibuting medical information :<br />
</label>

<textarea class="input-textarea" name="user5"   ></textarea> <br />



</p>
<p>
<label>
Member of the mEducator project<br />
</label>
<label><input type="radio"   name="user7"  value="Yes" class="input-radio" />Yes</label><br /><label><input type="radio"   name="user7"  value="No"  class="input-radio" />No</label><br /> <br />



</p>
</div>
<div id='profile_manager_profile_edit_tab_content_1965' class='profile_manager_profile_edit_tab_content'>
<p>
<label>
Media Type<br />
</label>
	<script type="text/javascript">

		$(document).ready(function() {
			$("select[multiple]").asmSelect({
				addItemTarget: 'bottom',
				animate: true,
				highlight: false,
				sortable: false
			});
			      $("#add_city_btn").click(function() {

        var city = $("#add_city").val();
        var $option = $("<option></option>").text(city).attr("selected", true); 

        $("#meducator6b1").append($option).change();
        $("#add_city").val('');

        return false; 
      }); 

		}); 

	</script>

<p style="display:inline;">
	<select id="meducator6b1" name="meducator6b[]" multiple="multiple" style="display:none title="selection" ">
	<optgroup label="Primary Media">
											  						<option>Text</option>
											  						<option>Image</option>
											  						<option>Sketch/graphical annotation</option>
											  						<option>Animation</option>

											  						<option>Audio</option>
											  						<option>Video</option>
											  						<option>3DModel</option>
											  					</optgroup>	
											  					<optgroup label="Multimedia">
											  						<option>Multimedia slide/presentation</option>
											  						<option>e-book</option>

											  						<option>Virtual Patient</option>
											  						<option>Interactive Learning Environment</option>
											  						<option>Immersive environment</option>
											  					</optgroup>
											  					<optgroup label="Web/Social Media"> 
											  						<option>Website</option>
											  						<option>Blog</option>

											  						<option>Discussion Forum</option>
											  						<option>Post</option>
											  						<option>Podcast</option>
											  						<option>Webinar</option>
											  						<option>Wiki</option>
											  					</optgroup>

											  					<optgroup label="Media Package">
											  						<option>SCORM pacakage</option>
											  						<option>IMS Package</option>
											  						<option>Dicom</option>
											  					</optgroup>			
</select>
</p><br />



</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator6b1'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator6'>Fill this if the above list doesn't contain your Media Type</span><label>
or provide a different Media Type.<br />
</label>

<input type="text"   name="meducator6b1"  value="" class="input-text"/> <br />



</p>


<p>
<label>
Select the type(s) of the Educational Resource from the following dropdown list<br />
</label>

<p style="display:inline;">
	<select id="meducator6a2" name="meducator6a[]" multiple="multiple" style="display:none">
											  					<optgroup label="Profesional Practice Artefact">
											  						<option>Software</option>

											  						<option>Forms</option>
											  						<option>Guideline</option>
											  						<option>Scientific Journal article</option>
											  						<option>Table</option>
											  						<option>Bio-signal dataset</option>
											  						<option>Clinical case</option>

											  						<option>Clinical record</option>
											  						<option>Clinical record with annotations</option>
											  						<option>Diagnostic exam</option>
											  						<option>Diagnostic exam with annotations</option>
											  						<option>Experiment</option>
											  						<option>Evidence based medicine form</option>

											  						<option>Diagnostic alogorithm</option>
											  						<option>Clinical Practice Guideline</option>
											  						<option>Database</option>
											  					</optgroup>
											  					<optgroup label="Reference Material">
											  						<option>Atlas</option>
											  						<option>Bibliography</option>

											  						<option>Dictionary</option>
											  						<option>Encyclopedia</option>
											  						<option>Handbook</option>
											  						<option>Indexes</option>
											  						<option>Laoratory manuals</option>
											  						<option>Pharmacopoeias</option>

											  						<option>Legislation</option>
											  						<option>Terminology</option>
											  					</optgroup>
											  					<optgroup label="Educational Practice Artefact">
											  						<option>Reading List</option>
											  						<option>Activity/Fieldwork/Project</option>
											  						<option>Assessment Item With Answeres/Feedback</option>

											  						<option>Study Guide</option>
											  						<option>Educational Policy</option>
											  						<option>Curriculum/Syllabus/Programme</option>
											  						<option>Figure/Diagram/Picture</option>
											  						<option>Assessment Item</option>
											  						<option>Worked Example</option>

											  						<option>Demostration</option>
											  						<option>Course/Module/Unit</option>
											  						<option>Lecture</option>
											  						<option>Tutorial/Handout</option>
											  						<option>Problems and Exercises</option>
											  						<option>Problems and Exercises with feedback</option>

											  						<option>Textbook/Chapter</option>
											  						<option>Case Study</option>
											  						<option>Simulation</option>
											  						<option>Objetive Structured Clinical Examination(OSCE)</option>
											  						<option>Game,serious game</option>
											  						<option>Virtual patient</option>

											  						<option>Teaching File</option>
											  						<option>Lecture Notes</option>
											  						<option>Practical</option>
											  					</optgroup>
																</select>

</p><br />


<p>
<span class='custom_fields_more_info' id='more_info_meducator6'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator6'>Fill this if the above list doesn't contain your Educational Resource Type</span><label>
or provide a different Educational Resource Type.<br />
</label>

<input type="text"   name="meducator6"  value="" class="input-text"/> <br />



</p>

<p>
<span class='custom_fields_more_info' id='more_info_meducator15'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator15'>For example, 1st year undergraduate, post graduate, continuing education, etc.</span><label>
Educational level for which the resource is intended<br />

</label>

<p style="display:inline;">
	<select id="meducator153" name="meducator15[]" multiple="multiple" style="display:none">
	<option>Undergraduate</option><option>Postgraduate</option><option>Continuing Education</option><option>Patient/General Public Education</option>	</select>
</p><br />



</p>
</div>
<div id='profile_manager_profile_edit_tab_content_1966' class='profile_manager_profile_edit_tab_content'>
<p>
<span class='custom_fields_more_info' id='more_info_meducator9'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator9'>Use this field to give a comprehensive description of the educational context for which this resource is meant for. This description can be further analysed using a number of secondary profile fields.</span><label>
Educational context for which this resource is recommended<br />
</label>

<textarea class="input-textarea" name="meducator9"><?php echo $mM->getData("educationalContext"); ?></textarea> <br />


</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator10'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator10'>You may include reference to the pedagogical approach that should be followed in order to use this resource for teaching/learning, and any other instructions for the educational (not technical) use of this resource.</span><label>
Instructions of how to use this resource for teaching and/or learning<br />
</label>

<textarea class="input-textarea" name="meducator10"><?php echo $mM->getData("teachingLearningInstructions"); ?></textarea> <br />



</p>
<p>
<label>
Educational objectives<br />
</label>

<textarea class="input-textarea" name="meducator11"><?php echo $mM->getData("educationalObjectives"); ?></textarea> <br />



</p>
<p>
<label>
Educational Outcomes<br />
</label>


<p style="display:inline;">
    <select id="meducator124" name="meducator12[]" multiple="multiple" style="display:none">
        <optgroup label="Apply scientific Knowledge to profesional practice">
            <option>Explain normal human structure and function</option>
            <option>Explain bio-chemical-physical processes from first principles</option>

            <option>Explain the scientific basis for disease presentation</option>
            <option>Apply the principles of evidence-based medicine</option>
            <option>Carry out scientific research</option>
        </optgroup>
        <optgroup label="Apply socio-psychological knowledge to profesional practice">
            <option>Explain normal behaviour at individual level</option>
            <option>Explain varied responses of individuals, groups and society to disease</option>

            <option>Assess psychological factors that contribute to illness, the course of the disease and the success of the treatment success</option>
            <option>Manage patients with dependence or self-harm issues</option>
            <option>Recognise stress or abuse situations</option>
            <option>Appreciate diversity and multi-culturality</option>
        </optgroup>
        <optgroup label="Carry out a consultation with a patient">
            <option>Take and record medical history</option>

            <option>Perform a full physical examination</option>
            <option>Perform a mental state examination</option>
            <option>Assess the severity of clinical presentations</option>
            <option>Provide explanation, advice and support</option>
        </optgroup>
        <optgroup label="Diagnosing">
            <option>Explain the physio-pathology underlying clinical and para-clinical signs</option>

            <option>Make a differential diagnosis</option>
            <option>Discuss the etiological and differential diagnosis w.r.t. epidemiological data and morbidity</option>
            <option>Justify the diagnostic procedure and the strategy of investigation</option>
            <option>Order investigations according to protocols and guidelines</option>
            <option>Interpret the results of laboratory test</option>
            <option>Interpret the results of bio-imaging investigation(RX,MR,CAT etc.)</option>

            <option>Interpret the results of bio-signal investigation(ECG,EEG etc.)</option>
            <option>Correlate the interpretation of a test/investigation to patient's clinical picture</option>
            <option>Define the likely diagnosis or diagnoses assessing the uncertainty degree</option>
        </optgroup>
        <optgroup label="Plan for treatment and patient follow-up">
            <option>Explain drugs therapeutics, pharmacokinetics, side effects and interaction</option>
            <option>Evaluate drug?s potential benefits and risks</option>

            <option>Prescribe drugs clearly and accurately</option>
            <option>Detect and report adverse drug reaction</option>
            <option>Justify the choice of therapeutic strategy and explain to patient</option>
            <option>Describe the modes of monitoring a disease and its treatment</option>
            <option>Explain the procedures of individual and collective prevention</option>
        </optgroup>

        <optgroup label="Carry out practical procedures safely and effectively">
            <option>Provide immediate care of medical emergencies(First Aid,Lide Support)</option>
            <option>Perform a measurement and recording procedure(e.g.,measure blood pressure...)</option>
            <option>Perform a therapeutic procedure(e.g. vein cannulation,catheterisation...)</option>
            <option>Perform a procedures for clinical investigations(e.g., endoscopy,cervical smear...)</option>
            <option>Perform a bio-sampling procedure</option>

            <option>Perform gestures and physical manipulation</option>
            <option>Perform a surgical procedure</option>
        </optgroup>
        <optgroup label="Professionalism">
            <option>Communicate effectively with patients and colleagues</option>
            <option>Apply ethical and legal principles in professional practice</option>
            <option>Conform to professional regulation</option>

            <option>Promote health</option>
            <option>Use Information Technology for communication and collaboration</option>
            <option>Use Information Technology to access,analyse, manage and exchange bio-medical data</option>
            <option>Work affectively in a team and in a collaborative setting</option>
        </optgroup>
    </select>
</p><br />



</p>
<p>
<label>
Assessment methods<br />
</label>

    <textarea class="input-textarea" name="meducator13"><?php echo $mM->getData("assessmentMethods"); ?></textarea> <br />



</p>
<p>
<span class='custom_fields_more_info' id='more_info_meducator14'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator14'>Expected background knowledge/capability/etc in order to engage efficiently with this resource.</span><label>
Educational prerequisites<br />
</label>

<textarea class="input-textarea" name="meducator14"><?php echo $mM->getData("educationalPrerequisites"); ?></textarea> <br />



</p>
</div>
<!-- SRART REPURPOSING TAB -->
<div id='profile_manager_profile_edit_tab_content_1967' class='profile_manager_profile_edit_tab_content'>
<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/sort_select.js"></script>
<script type="text/javascript" language="javascript">
  var availableParentsList = new Array();
  var previousParent = null;
  var selectedParentsList = new Array()
  var externalParentsNr = 0;

  function updateAvailableParentsList(ID, val)
  {
      //mark the changes in the availableParentsList
       for(i=0; i<availableParentsList.length; i++)
           if(availableParentsList[i][0] == ID)
           {
               availableParentsList[i][2] = val;
               break;
           }
  }

  function isExternalParent(ID)
  {
      alert(ID.substr(0, 5));
      if(ID.substr(0, 5) == "_ext_") return true;
      return false;
  }

  $(document).ready(function() {
     //sort the information into the list
     $('#availableResources').sortOptionsByText();
     //save the complete list of available resources
     $('#availableResources option').each(function(x, element) {
         //valoare, text, true = display in the available list
        availableParentsList.push(new Array($(element).val(), $(element).text(), true));
     });
     //populate the selectedParentsList control
     //the hidden fields related to these Parents should already exist into the document
     for(i=0; i<selectedParentsList.length; i++) {
       $('#availableResources option[value=' + selectedParentsList[i] + ']').appendTo('#selectedResources');
     }

     //add/remove functionality
     $('#addBtn').click(function() {
       var auxVal = $('#availableResources').val();
       if(!auxVal) return;
       $('#profile_edit_form').append('<input type="hidden" id="repurpose_types_' + auxVal + '" name="repurpose_types_' + auxVal + '" value=""/>');
       $('#profile_edit_form').append('<input type="hidden" id="repurpose_desc_' + auxVal + '" name="repurpose_desc_' + auxVal + '" value=""/>');
       updateAvailableParentsList($('#availableResources').val(), false);
       $('#availableResources option:selected').appendTo('#selectedResources');
       $('#selectedResources').sortOptionsByText();
       $('#selectedResources').change();
       //ensure the fields are visible
       //$('#editParentContent_div').css("display", "block");
     });

     //add/remove external parent functionality
     $('#addBtnExternal').click(function() {
       var auxTitle = $('#external_parent_title').val();
       var auxIdentifier = $('#external_parent_identifier').val();
       if((auxTitle == "")||(auxIdentifier == ""))
       {
           alert("Please complete both fields for external resources description!");
           return;
       }
       //mark resource to be external
       auxID = '_ext_' + externalParentsNr++;

       $('#external_parent_title').val('');
       $('#external_parent_identifier').val('');

       $('#profile_edit_form').append('<input type="hidden" id="repurpose_types_' + auxID + '" name="repurpose_types_' + auxID + '" value=""/>');
       $('#profile_edit_form').append('<input type="hidden" id="repurpose_desc_' + auxID + '" name="repurpose_desc_' + auxID + '" value=""/>');
       $('#profile_edit_form').append('<input type="hidden" id="external_id_' + auxID + '" name="external_id_' + auxID + '" value="' + auxIdentifier + '"/>');
       $('#profile_edit_form').append('<input type="hidden" id="external_title_' + auxID + '" name="external_title_' + auxID + '" value="' + auxTitle + '"/>');
       
       $('#selectedResources').append('<option value="' + auxID + '">' + auxTitle + '</option>');
       $('#selectedResources').sortOptionsByText();
       $('#selectedResources').change();
       //ensure the fields are visible
       //$('#editParentContent_div').css("display", "block");
     });

     $('#removeBtn').click(function() {
       var auxVal = $('#selectedResources').val();
       if(!auxVal) return;
       $('#repurpose_types_' + auxVal).remove();
       $('#repurpose_desc_' + auxVal).remove();
       $('#meducator27').val('');
       $('#meducator265').val('');
       $('#meducator265').change();
       $('#parentName').html('');
       if(!isExternalParent(auxVal))
       {
           updateAvailableParentsList($('#selectedResources').val(), true);
           $('#selectedResources option:selected').appendTo('#availableResources');
           $('#availableResources').sortOptionsByText();
       }
       else
       {
           $('#external_id_' + auxVal).remove();
           $('#external_title_' + auxVal).remove();
           $('#selectedResources option:selected').remove();
       }
       //hide the fields, as no other value will be selected
       $('#editParentContent_div').css("display", "none");
     });

     $('#clear_available_btn').click(function() {
         $('#filter_available').val('');
     })

     $('#filter_available').keyup(function() {
         var auxFilter = $('#filter_available').val();
         if(auxFilter.length <= 2) auxFilter = '';
         //empty the list
         $('#availableResources option').remove();
         //add to the list only the elements that correspond to the filter
         for(i=0; i<availableParentsList.length; i++)
             if(availableParentsList[i][2])  //if the resource is still available
             {
                if(auxFilter != '')
                    canBeAdded = (availableParentsList[i][1].toUpperCase().indexOf(auxFilter.toUpperCase()) > -1) ? true : false;
                else canBeAdded = true;
                if(canBeAdded) $('#availableResources').append('<option value="' + availableParentsList[i][0] +'">' + availableParentsList[i][1] + '</option>');
             }
     });

     //add listener for selecting a specific parent
     $('#selectedResources').change(function(){
       var typesList = "";
       var auxVal = $('#selectedResources').val();
       if(!auxVal) return;
       //save current displayed data
       if(previousParent != null)
       {
         $('#repurpose_desc_' + previousParent).val($('#meducator27').val());
         $('#meducator265 option:selected').each(function(i, element){
           typesList += (typesList == "") ? $(element).text() : ";" + $(element).text();
         });
         $('#repurpose_types_' + previousParent).val(typesList);
       }
       //reset fields
       $('#meducator265').val('');
       //load new data
       $('#parentName').html('<br/><br/><label>Repurposing information for parent: ' + $('#selectedResources option:selected').text() + "</label><br/>");
       $('#meducator27').val($('#repurpose_desc_' + auxVal).val());
       var typesList = $('#repurpose_types_' + auxVal).val().split(";");
       $('#meducator265 option').each(function(i, element){
          for(j=0; j<typesList.length; j++)
            if($(element).text() == typesList[j])
            {
                $(element).attr('selected','selected');
                break;
            }
       });
       $('#meducator265').change();
       previousParent = auxVal;
       //ensure the fields are visible
       $('#editParentContent_div').css("display", "block");
     });
  });

</script>
<p>
    <label>If the Resource is a repurposed one, use this section to connect it with it's parents.</label>
    <br><br>
</p>
    <table>
      <tr>
        <td rowspan="3">
          <label>Selected parent resources:</label><br>
          <select name="selectedResources" id="selectedResources" size="16" style="width:473px;">

          </select>
        </td>
      </tr>
      <tr>
        <td style="width:100px; text-align:center; vertical-align: middle;">
            <input type="button" id="addBtnExternal" value=" << ">
        </td>
        <td style="padding-bottom: 10px;">
          <table width="100%">
          <tr>
              <td colspan="2"><label>Parent resource that is not in the system:</label></td>
          </tr>
          <tr>
            <td style="width: 150px; vertical-align: middle;">Parent title:</td>
            <td><input type="text" name="external_parent_title" id="external_parent_title" style="width:95%"></td>
          </tr>
          <tr>
              <td style="vertical-align: middle;">Parent identifier:</td>
              <td><input type="text" name="external_parent_identifier" id="external_parent_identifier" style="width:95%"></td>
          </tr>
          </table>
        </td>
       </tr>
       <tr>
        <td style="width:100px; text-align:center; vertical-align: middle;">
          <input type="button" id="addBtn" value=" << ">
        </td>
        <td style="padding-top: 10px;">
          <label>Parent resource that is in the system:</label><br>
          <select name="availableResources" id="availableResources" size="10" style="width: 473px;">
            <?php
                $members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
                foreach ($members as $member)
                {
                    echo "<option value=\"$member->guid\">";
                    echo $member->name;
                    echo "</option>";
                }
            ?>
            </select>
        </td>
      </tr>
      <tr>
          <td>
              <input type="button" id="removeBtn" value=" Remove selected entry from the list ">
          </td>
          <td></td>
          <td>
              Filter: <input type="text" name="filter_available" id="filter_available" style="width:356px;">
              <input type="button" id="clear_available_btn" value="Clear">
          </td>
      </tr>
    </table>
 </p>
 
<div id="editParentContent_div" style="display:none;">
    <p id="parentName"></p>
    <p style="display:inline;">
        <strong>Repurposing context (choose all that may apply):<br /></strong>
            <select id="meducator265" name="meducator26[]" multiple="multiple" style="display:none; width: 100%;">
                <option>different language</option>
                <option>different culture</option>
                <option>different pedagogy</option>
                <option>different educational levels</option>
                <option>different disciplines or professions</option>
                <option>different content type</option>
                <option>different technology</option>
                <option>different abilities of end-users</option>
            </select>

    </p><br />

    <p>
        <span class='custom_fields_more_info' id='more_info_meducator27'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator27'>A detailed account of the reasons for repurposing and of the differences between the initial and repurposed resources.</span>
        <strong>Repurposing description</strong>
        <textarea class="input-textarea" name="meducator27" id="meducator27"   ></textarea> <br />
    </p>
</div>
</div>
<!-- END REPURPOSING TAB -->
<div id='profile_manager_profile_edit_tab_content_1969' class='profile_manager_profile_edit_tab_content'><!-- form from Chris Valentine -->
  <style type="text/css">
    .noBox { border: 0; margin-bottom: 4px; font-size: 90%; }
    .responseSelector { font-size: 85%; border: 1px solid #000000; margin: 4px 0; width: 500px; }
    .mainSelectors { font-size: 85%; border: 1px solid #000000; margin: 4px 0; width: 260px; }
    .dim { color: #101010; }
  </style>
  <script type="text/javascript">/* <![CDATA[ */
    var labels=new Array();
    var conceptIds=new Array();
    var preferredNames=new Array();
    var uniqueLabels=new Array();
    // arrays of the results actually picked by the user
    var pickedIndex=new Array(); // index number from search results
    var pickedLabels=new Array();
    var pickedIds=new Array();
    var pickedNames=new Array();
    // disciplines
    var pickedDisIndex=new Array(); // index number from search results
    var pickedDisLabels=new Array();
    var pickedDisIds=new Array();
    var pickedDisNames=new Array();
    // keywords
    var pickedKeyIndex=new Array(); // index number from search results
    var pickedKeyLabels=new Array();
    var pickedKeyIds=new Array();
    var pickedKeyNames=new Array();
    // specialties
    var pickedSpecIndex=new Array(); // index number from search results
    var pickedSpecLabels=new Array();
    var pickedSpecIds=new Array();
    var pickedSpecNames=new Array();
    // array search function
    Array.prototype.in_array = function(p_val) {
      for (var i = 0, l = this.length; i < l; i++) {
        if (this[i] == p_val) {
          return true;
        }
      }
      return false;
    }
    // remove ontologies filter
    function removeFilter(form) {
      form.hitOnologies.selectedIndex=-1;
      form.hits.length=0;
      for (i=0; i < labels.length; i++) {
        form.hits.options[i]=new Option(preferredNames[i] + ' | ' + labels[i], i);
        // form.hits.options[i]=new Option(preferredNames[i], i);
      }
      form.hitLabel.value='';
      form.hitConceptId.value='';
      return true;
    }
    // pick a hit as a discipline
    function addDisHit(form) {
      selIndex=form.hits.selectedIndex;
      if (selIndex < 0) return false;
      newVal=form.hits.options[selIndex].value;
      if ($.inArray(newVal,pickedDisIndex)!=-1) return false;
      newText=preferredNames[newVal];
      select=form.pickedDis;
      l=select.length;
      select.options[l]=new Option(newText, newVal);
      pickedDisIndex[newVal]=newVal;
      pickedDisLabels[newVal]=labels[newVal];
      pickedDisIds[newVal]=conceptIds[newVal];
      pickedDisNames[newVal]=preferredNames[newVal];
      return true;
    }
    // pick a hit as a keyword
    function addKeyHit(form) {
      selIndex=form.hits.selectedIndex;
      if (selIndex < 0) return false;
      newVal=form.hits.options[selIndex].value;
      if ($.inArray(newVal,pickedKeyIndex)!=-1) return false;
      newText=preferredNames[newVal];
      select=form.pickedKey;
      l=select.length;
      select.options[l]=new Option(newText, newVal);
      pickedKeyIndex[newVal]=newVal;
      pickedKeyLabels[newVal]=labels[newVal];
      pickedKeyIds[newVal]=conceptIds[newVal];
      pickedKeyNames[newVal]=preferredNames[newVal];
      return true;
    }
    // pick a hit as a speciality
    function addSpecHit(form) {
      selIndex=form.hits.selectedIndex;
      if (selIndex < 0) return false;
      newVal=form.hits.options[selIndex].value;
      if ($.inArray(newVal,pickedSpecIndex)!=-1) return false;
      newText=preferredNames[newVal];
      select=form.pickedSpec;
      l=select.length;
      select.options[l]=new Option(newText, newVal);
      pickedSpecIndex[newVal]=newVal;
      pickedSpecLabels[newVal]=labels[newVal];
      pickedSpecIds[newVal]=conceptIds[newVal];
      pickedSpecNames[newVal]=preferredNames[newVal];
      return true;
    }
    // remove a single picked hit
    function remDisHit(form) {
      selIndex=form.pickedDis.selectedIndex;
      if (selIndex < 0) return false;
      indexVal=form.pickedDis.options[selIndex].value;
      form.pickedDis.options[selIndex]=null;
      pickedDisIndex[indexVal]=null;
      pickedDisLabels[indexVal]=null;
      pickedDisIds[indexVal]=null;
      pickedDisNames[indexVal]=null;
      return true;
    }
    function remKeyHit(form) {
      selIndex=form.pickedKey.selectedIndex;
      if (selIndex < 0) return false;
      indexVal=form.pickedKey.options[selIndex].value;
      form.pickedKey.options[selIndex]=null;
      pickedKeyIndex[indexVal]=null;
      pickedKeyLabels[indexVal]=null;
      pickedKeyIds[indexVal]=null;
      pickedKeyNames[indexVal]=null;
      return true;
    }
    function remSpecHit(form) {
      selIndex=form.pickedSpec.selectedIndex;
      if (selIndex < 0) return false;
      indexVal=form.pickedSpec.options[selIndex].value;
      form.pickedSpec.options[selIndex]=null;
      pickedSpecIndex[indexVal]=null;
      pickedSpecLabels[indexVal]=null;
      pickedSpecIds[indexVal]=null;
      pickedSpecNames[indexVal]=null;
      return true;
    }
    // clear the picked hits
    function clearAllDis(form) {
      if (form.pickedDis.length < 1) return false;
      if (!confirm('Are you sure you want to clear all disciplines?')) return false;
      form.pickedDis.length=0;
      pickedDisIndex.length=0;
      pickedDisLabels.length=0;
      pickedDisIds.length=0;
      pickedDisNames.length=0;
      return true;
    }
    function clearAllKey(form) {
      if (form.pickedKey.length < 1) return false;
      if (!confirm('Are you sure you want to clear all keywords?')) return false;
      form.pickedKey.length=0;
      pickedKeyIndex.length=0;
      pickedKeyLabels.length=0;
      pickedKeyIds.length=0;
      pickedKeyNames.length=0;
      return true;
    }
    function clearAllSpec(form) {
      if (form.pickedSpec.length < 1) return false;
      if (!confirm('Are you sure you want to clear all specialties?')) return false;
      form.pickedSpec.length=0;
      pickedSpecIndex.length=0;
      pickedSpecLabels.length=0;
      pickedSpecIds.length=0;
      pickedSpecNames.length=0;
      return true;
    }
    // display details of a particular hit
    function highlightHit(form) {
      selIndex=form.hits.selectedIndex;
      if (selIndex < 0) return false;
      indexVal=form.hits.options[selIndex].value;
      form.hitLabel.value=labels[indexVal];
      form.hitConceptId.value=conceptIds[indexVal];
    }
    // fiter hits on ontology
    function filterOntology(form) {
      selIndex=form.hitOnologies.selectedIndex;
      if (selIndex < 0) return false;
      filterOntologies=new Array();
      // get the text of the filter ontologies - can be more than one
      
      for (i=0; i < form.hitOnologies.length; i++) {
        if (form.hitOnologies.options[i].selected) filterOntologies.push(form.hitOnologies.options[i].text);
      }
      // ontFilter=form.hitOnologies.options[selIndex].text; // the ontology we want
      form.hits.length=0; // empty the hits filter
      l=0;
      // re-build the hits selector - don't show the ontology name if we've filtered on only one
      for (i=0; i < preferredNames.length; i++) {
        // if (labels[i]==ontFilter) {
        if (filterOntologies.in_array(labels[i])) {
          if (filterOntologies.length > 1) {
            form.hits.options[l]=new Option(preferredNames[i] + ' | ' + labels[i], i);
          } else {
            form.hits.options[l]=new Option(preferredNames[i], i);
          }
          l++;
        }
      }
      form.hitLabel.value='';
      form.hitConceptId.value='';
      return true;
    }
    function buildRDF(form) {
      selIndex=form.picked.selectedIndex;
      if (selIndex.length < 1) return false;
      // eliminate empty array elements
      DisLabelsList=new Array();
      for (i=0; i < pickedLabels.length; i++) {
        if (pickedDisLabels[i]) DisLabelsList.push(pickedLabels[i]);
      }
      DisIdsList=new Array();
      for (i=0; i < pickedIds.length; i++) {
        if (pickedDisIds[i]) DisIdsList.push(pickedIds[i]);
      }
      DisNamesList=new Array();
      for (i=0; i < pickedNames.length; i++) {
        if (pickedDisNames[i]) DisNamesList.push(pickedNames[i]);
      }
      KeyLabelsList=new Array();
      for (i=0; i < pickedLabels.length; i++) {
        if (pickedKeyLabels[i]) KeyLabelsList.push(pickedLabels[i]);
      }
      KeyIdsList=new Array();
      for (i=0; i < pickedIds.length; i++) {
        if (pickedKeyIds[i]) KeyIdsList.push(pickedIds[i]);
      }
      KeyNamesList=new Array();
      for (i=0; i < pickedNames.length; i++) {
        if (pickedKeyNames[i]) KeyNamesList.push(pickedNames[i]);
      }
	  SpecLabelsList=new Array();
      for (i=0; i < pickedLabels.length; i++) {
        if (pickedSpecLabels[i]) SpecLabelsList.push(pickedLabels[i]);
      }
      SpecIdsList=new Array();
      for (i=0; i < pickedIds.length; i++) {
        if (pickedSpecIds[i]) SpecIdsList.push(pickedIds[i]);
      }
      SpecNamesList=new Array();
      for (i=0; i < pickedNames.length; i++) {
        if (pickedSpecNames[i]) SpecNamesList.push(pickedNames[i]);
      }
    // pass the arrays back to the main (opening) form
      document.forms['masterForm'].pickedDisLabels.value=DislabelsList;
      document.forms['masterForm'].pickedDisIds.value=DisIdsList;
      document.forms['masterForm'].pickedDisNames.value=DisNamesList;
      document.forms['masterForm'].pickedKeyLabels.value=KeylabelsList;
      document.forms['masterForm'].pickedKeyIds.value=KeyIdsList;
      document.forms['masterForm'].pickedKeyNames.value=KeyNamesList;
      document.forms['masterForm'].pickedSpecLabels.value=SpeclabelsList;
      document.forms['masterForm'].pickedSpecIds.value=SpecIdsList;
      document.forms['masterForm'].pickedSpecNames.value=SpecNamesList;
      return true;
    }
    // jQuery Ajax call
    function fetchHits(form) {
      if (form.searchfor.value=='') return false;
      searchfor = form.searchfor.value;
      form.statusBox.value = 'Searching...';
	  document.getElementById('wait').src='/elgg/mod/profile_manager/views/default/profile/wait_animated.gif';
      $.ajax({
        type: "GET",
        url: "http://galinos.med.duth.gr:81/elgg/mod/profile_manager/views/default/profile/ajax_fetch_data.php?searchfor=" + searchfor,
        // url: "ajax_fetch_data.php?searchfor=" + searchfor,
        dataType: "xml",
        error: function() {
          document.getElementById('wait').src='/elgg/mod/profile_manager/views/default/profile/blank.gif';
          alert('Timeout or other error querying bioportal');
        },
        success: function(xml) {
		  document.getElementById('wait').src='/elgg/mod/profile_manager/views/default/profile/blank.gif';
          $(xml).find('hit').each(function(){
            labels.push($(this).find("label").text());
            conceptIds.push($(this).find("conceptId").text());
            preferredNames.push($(this).find("preferredName").text());
          });
          $(xml).find('ontology').each(function(){
            uniqueLabels.push($(this).find("name").text());
          });
          if (labels.length > 0) {
            form.statusBox.value = 'Your search found ' + labels.length + ' hits';
            // populate the hits selector
            l=0;
            for (i=0; i < preferredNames.length; i++) {
              form.hits.options[l]=new Option(preferredNames[i] + ' | ' + labels[i], i);
              l++;
            }
            l=0;
            if (uniqueLabels.length > 0) {
              form.statusBox.value = form.statusBox.value + ' from ' + uniqueLabels.length + ' ontologies';
              document.getElementById('ontolFilter').style.visibility='visible';
              //alert(uniqueLabels.length);
              for (i=0; i < uniqueLabels.length; i++) {
                form.hitOnologies.options[l]=new Option(uniqueLabels[i] , '');
                l++;
              }
            }
          } else {
            form.statusBox.value = 'Sorry but your search did not find any hits';
          }
        }
      });
    }
    // reset the entire form; empty the JavaScript arrays
    function resetForm(form) {
      if (!confirm('Are you certain you want to start from scratch?')) return false;
      form.searchfor.value='';
      form.hits.options.length=0;
      form.hitOnologies.options.length=0;
      document.getElementById('ontolFilter').style.visibility='hidden';
      
      labels.length=0;
      conceptIds.length=0;
      preferredNames.length=0;
      uniqueLabels.length=0;
      
	  form.pickedDis.options.length=0;
      pickedDisIndex.length=0;
      pickedDisLabels.length=0;
      pickedDisIds.length=0;
      pickedDisNames.length=0;
	  
	  form.pickedKey.options.length=0;
      pickedKeyIndex.length=0;
      pickedKeyLabels.length=0;
      pickedKeyIds.length=0;
      pickedKeyNames.length=0;
	  
	  form.pickedSpec.options.length=0;
      pickedSpecIndex.length=0;
      pickedSpecLabels.length=0;
      pickedSpecIds.length=0;
      pickedSpecNames.length=0;
    }
    /* ]]> */
  </script>

<table><tr><td colspan='3' style='vertical-align: top;' nowrap>
<input type='hidden' name='pickedDisLabels' value='' />
<input type='hidden' name='pickedDisIds' value='' />
<input type='hidden' name='pickedDisNames' value='' />
<input type='hidden' name='pickedKeyLabels' value='' />
<input type='hidden' name='pickedKeyIds' value='' />
<input type='hidden' name='pickedKeyNames' value='' />
<input type='hidden' name='pickedSpecLabels' value='' />
<input type='hidden' name='pickedSpecIds' value='' />
<input type='hidden' name='pickedSpecNames' value='' />
Enter your search term: <input type='text' name='searchfor' size='32' maxlength='100' /> 
<input type='button' value='Search' onclick='return fetchHits(this.form);' /> <img src='/elgg/mod/profile_manager/views/default/profile/blank.gif' id='wait' width='31' height='31' alt='' style='border: 0; vertical-align: middle;' />
<input type='text' name='statusBox' size='100' class='noBox' /></p>
<input type='hidden' name='action' value='' /></td></tr>
<tr><td colspan='2' style='vertical-align: top;'>Bio-portal response:<br />
<select name='hits' size='12' class='responseSelector' onclick='highlightHit(this.form);'></select>
</td>
<td style='width: 32%; text-align: center; vertical-align: top;' nowrap><br />
Ontology: <input type='text' name='hitLabel' size='50' class='noBox' /><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;URL: <input type='text' name='hitConceptId' size='50' class='noBox' /><br />
<br />
<div id='ontolFilter' style='visibility: hidden;'>
Ontologies in results:<br /><select name='hitOnologies' size='6' class='mainSelectors'></select><br />
<input type='button' value='filter by ontology' onclick='return filterOntology(this.form);' /> <input type='button' value='none' onclick='return removeFilter(this.form);' />
</div></td>
</tr>

<tr height='8'><td colspan='3'>&nbsp;</td></tr>

<tr>
<td style='width: 32%; text-align: center; vertical-align: top;' nowrap>
Chosen keywords: <input type='button' value='Add as keyword' title='Click to add entry' onclick='return addKeyHit(this.form);' /> <input type='button' value='x' title='Click to remove entry' onclick='return remKeyHit(this.form);' /><br />
<select name='pickedKey' size='12' class='mainSelectors'></select><br />
<input type='button' value='clear all keywords' onclick='return clearAllKey(this.form);' /></td>

<td style='width: 32%; text-align: center; vertical-align: top;' nowrap>
Chosen disciplines: <input type='button' value='Add as discipline' title='Click to add entry' onclick='return addDisHit(this.form);' /> <input type='button' value='x' title='Click to remove entry' onclick='return remDisHit(this.form);' /><br />
<select name='pickedDis' size='12' class='mainSelectors'></select><br />
<input type='button' value='clear all disciplines' onclick='return clearAllDis(this.form);' /></td>

<td style='width: 32%; text-align: center; vertical-align: top;' nowrap>
Chosen specialities: <input type='button' value='Add as speciality' title='Click to add entry' onclick='return addSpecHit(this.form);' /> <input type='button' value='x' title='Click to remove entry' onclick='return remSpecHit(this.form);' /><br />
<select name='pickedSpec' size='12' class='mainSelectors'></select><br />
<input type='button' value='clear all specialties' onclick='return clearAllSpec(this.form);' /></td>
</tr>

<tr><td colspan='3' nowrap><br /><input type='button' value='submit your choice' onclick='return buildRDF(this.form);' />&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='reset and search again' onclick='return resetForm(this.form);' /></td></tr>
</table>

<p>
<span class='custom_fields_more_info' id='more_info_meducator4'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator4'><b>Some keywords describing the resource</b>. They can be a mixture of keywords related to content, educational method, technical characteristics, etc. <br />For example meningitis, paediatrics, virtual patient, open labyrinth, cc: by-nc-sa</span><label>
Keywords<br />
</label>

<div id="meducator4_div">
<input type="text"   name="meducator4"  value="" class="input-text"/> <br />
</div>

<a href="http://galinos.med.duth.gr:81/elgg/mod/profile_manager/views/default/profile/new_form.html"  onclick="window.open('http://galinos.med.duth.gr:81/elgg/mod/profile_manager/views/default/profile/new_form.html','popup','width=800,height=600,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false">CHRIS' FORM</a> 

<a class='example6' href="http://galinos.med.duth.gr:81/elgg/mod/profile_manager/views/default/profile/new_form.html" title="CHRIS">COLORBOX CHRIS</a>
</p>

<p>
<span class='custom_fields_more_info' id='more_info_meducator16'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator16'>e.g. medicine, nursing, sociology, medical informatics, etc</span><label>
Discipline for which the resource is intended<br />

</label>

<div id="meducator16_div">
<input type="text"   name="meducator16"  value="" class="input-text"/>
</div>
<br /> 


</p>
<p>
<label>
Speciality within this discipline<br />

</label>

<div id="meducator17_div">
<input type="text"  name="meducator17"  value="" class="input-tags"/>
</div>
<br />



</p>



</div>


<div id='profile_manager_profile_edit_tab_content_1968' class='profile_manager_profile_edit_tab_content'>


<p>
<span class='custom_fields_more_info' id='more_info_meducator28'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator28'>Resources which accompany and/or supplement the educational resource. </span><label>
Companion URL<br />
</label>

<div id="meducator28_div">
<input type="text"   name="meducator28"  value="" class="input-text"/> <br />
</div>



</div>

			</div>


	<p>
		<input type="hidden" name="__elgg_ts" value="<?php echo $ts; ?>" />

		<?php echo elgg_view('input/securitytoken') ?>
		<!--<input type="hidden" name="username" value="<?php echo page_owner_entity()->username; ?>" />-->
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
                <input type="button" id="submit_button" class="submit_button" value="<?php echo elgg_echo("save"); ?>" onclick="submitForm()" />

	</p>

</form>
<?php 
	if(get_plugin_setting("simple_access_control","profile_manager") == "yes"){ 
	?>
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
	<?php 
	} 
?>
</div>