<link rel="stylesheet" type="text/css" href="<?php echo $vars['url']; ?>vendors/jquery.asmselect.css" />

<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery.asmselect.js"></script>
<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/sort_select.js"></script>
<script language="javascript" type="text/javascript">
  function saveSearch()
  {
    //document.getElementById("searchForm").action = "<?php echo $vars['url']; ?>action/mmsearch/save";
    //document.getElementById("searchForm").submit();
    alert("Your search parameters have been saved!");
  }

  function doSearch(searchType)
  {
    var params = util_PrepareSearchRequestVars(searchType);
    //prepare POST data to be sent to the server
    if(searchType == "basic")
    {
      params["keyword"] = $("#basic_keyword").val();
    }
    else if(searchType == "advanced")
    {
      var criteriaNr = parseInt($('#criteria_nr').val());
      for(i=0; i<criteriaNr; i++)
      {
        var property = $("#property_"+i).val();
        var value = $("#value_"+i).val();
        if(property && value)
          params[property] = value;
      }
    }
    else if(searchType == "specific")
    {
       var idNr = parseInt($('#specific_id_nr').val());
       for(i=0; i<idNr; i++)
       {
         var auxID = $("#specific_id_"+i).val();
         if(auxID) params["id_" + i] = auxID;
       }
       $("#specific_search_propertis_list option:selected").each(function(i, element) {
         params["property_" + i] = $(element).val();
       });
    }
    //make the request
    $.post(searchActionURL, params, searchResults, "JSON");
    //create a new tab to display the information
    createNewTab(searchNr, params);
  }

  function addNewCriteria()
  {
    var criteriaNr = parseInt($('#criteria_nr').val());
    var template = advancedSearchTemplate.replace(/_0/g, "_" + criteriaNr);
    $('#fields_div').append(template);
    $('#criteria_nr').val(criteriaNr + 1);
  }

  function specificAddNewID()
  {
    var idNr = parseInt($('#specific_id_nr').val());
    var template = specificSearchTemplate.replace(/_0/g, "_" + idNr);
    $('#specific-search-ids-list-div').append(template);
    $('#specific_id_nr').val(idNr + 1);
  }

  function changedProperty(tail)
  {
    var value = $("#property" + tail).val();
    var displayHTML = "";
    if(properties_values[value])
    {
      displayHTML = '<select name="value' + tail + '" id="value' + tail + '">';
      for(var key in properties_values[value])
        displayHTML += '<option value="' + key + '">' + properties_values[value][key] + '</option>';
      displayHTML += '</select>';
    }
    else displayHTML = '<input type="text" class="input-text" name="value' + tail + '" id="value' + tail + '" value="">';
    $("#right_col" + tail).html(displayHTML);
  }
</script>

<?php
  require_once dirname(dirname(__FILE__)) . "/resources/properties_list.php";
  //transpose in JavaScript the PropertiesValues and PropertiesList
  echo '<script type="text/javascript" language="javascript">' . "\n";

  //PropertiesList
  echo 'var properties_list = new Array();' . "\n";
  foreach($properties_list as $key => $value)
    echo 'properties_list["' . $key . '"] = "' . $value . '";' . "\n";

  //PropertiesValues
  echo 'var properties_values = new Array();' . "\n";
  foreach($properties_values as $key => $values)
  {
    echo 'properties_values["' . $key . '"] = new Array();' . "\n";
    foreach($values as $vKey => $value)
      echo 'properties_values["' . $key . '"]["' . $vKey . '"] = "' . $value . '";' . "\n";
  }
  echo '</script>' . "\n";
?>

<form action="<?php echo $vars['url']; ?>mod/mmsearch/view_results.php" method="post" id="searchForm">


<!--<div style="clear:both; margin: auto; width: 540px;">
  <div style="float:left; width: 250px; padding-top:14px;"><?php echo elgg_echo("Save search parameters under title:"); ?></div>
  <div style="float:left; width: 200px; padding-top: 8px;"><?php echo elgg_view('input/text',array('internalname' => 'title')); ?></div>
  <div style="float:left; width: 80px; text-align: right;"><?php echo elgg_view('input/button', array('value' => elgg_echo('Save'), 'type'=>'button', 'js' => 'onclick=saveSearch()')); ?></div>
  <div class="clear_div"></div>
</div>-->
<br/>

<div id="accordion">
  <h3><a href="#">Basic search</a></h3>
  <div>
    <?php echo elgg_echo("Keyword(s):"); ?>
    <?php echo elgg_view('input/text',array('internalname' => 'basic_keyword', 'internalid' => 'basic_keyword')); ?>

    <?php //require_once dirname(dirname(__FILE__)) . "/resources/languages_list.php"; ?>
    <?php //echo elgg_echo("Resource language:"); ?>
    <?php //echo elgg_view('input/pulldown',array('internalname' => 'basic_rLanguage', 'internalid' => 'basic_rLanguage', 'options_values' => $languages_list)); ?>
    
    <?php //echo elgg_echo("Metadata language:"); ?>
    <?php //echo elgg_view('input/pulldown',array('internalname' => 'basic_mLanguage', 'internalid' => 'basic_mLanguage', 'options_values' => $languages_list)); ?>
    <br/>
    <?php echo elgg_view('input/button', array('value' => elgg_echo('Search'), 'type'=>'button', 'js' => 'onclick=doSearch("basic")')); ?>
  </div>

  <!--<h3><a href="#">Specific search</a></h3>
  <div>
    <?php echo elgg_echo("Resource ID"); ?><br />
    <?php echo elgg_view('input/text',array('internalname' => 'id')); ?>
    <?php echo elgg_view('input/button', array('value' => elgg_echo('Search'), 'type'=>'button', 'js' => 'onclick=doSearch("specific")')); ?>
  </div>-->

  <h3><a href="#">Advanced search</a></h3>
  <div>
    <?php
      //end transposing PropertiesValues
      echo elgg_view('input/hidden',array('internalname' => 'criteria_nr', 'internalid' => 'criteria_nr', 'value' => '1'));
    ?>
    <div class="left_col"><strong><center>Property name</center></strong></div>
    <div class="right_col"><strong><center>Property contains</center></strong></div>
    <div class="clear_div"></div>
    <br/>
    <div id="fields_div">
      <div id="left_col_0" class="left_col">
        <?php echo elgg_view('input/pulldown',array('internalname' => 'property_0', 'internalid' => 'property_0', 'options_values' => $properties_list, 'js' => 'onchange="changedProperty(\'_0\')"')); ?>
      </div>
      <div id="right_col_0" class="right_col">
        <?php echo elgg_view('input/text',array('internalname' => 'value_0', 'internalid' => 'value_0')); ?>
      </div>
      <div class="clear_div"></div>
    </div>
    <div class="add_criteria_div"><a href="javascript:addNewCriteria()" alt="Add new search criteria" title="Add new search criteria">[ + ]</a></div>
    <?php echo elgg_view('input/button', array('value' => elgg_echo('Search'), 'type'=>'button', 'js' => 'onclick=doSearch("advanced")')); ?>
  </div>
  <h3><a href="#">Specific search</a></h3>
  <div>
    <?php
      //end transposing PropertiesValues
      echo elgg_view('input/hidden',array('internalname' => 'specific_id_nr', 'internalid' => 'specific_id_nr', 'value' => '1'));
    ?>
    <strong>List of metadata IDs to search for:</strong><br/>
    <div id="specific-search-ids-list-div">
      <?php echo elgg_view('input/text',array('internalname' => 'specific_id_0', 'internalid' => 'specific_id_0')); ?>
    </div>
    <div class="add_criteria_div"><a href="javascript:specificAddNewID()" alt="Add new ID" title="Add new ID">[ + ]</a></div>
    <br/>
    <strong>Select the properties to be displayed:</strong><br>
    <select id="specific_search_propertis_list" name="specific_search_propertis_list[]" multiple="multiple" style="display:none" title="-- properties list --">
    <?php
      foreach($properties_list as $key => $value)
        if($key != "")
          echo '<option value="'.$key.'">'.$value.'</option>'. "\n";
    ?>
    </select>
    <?php echo elgg_view('input/button', array('value' => elgg_echo('Search'), 'type'=>'button', 'js' => 'onclick=doSearch("specific")')); ?>
  </div>
</div>
</form>