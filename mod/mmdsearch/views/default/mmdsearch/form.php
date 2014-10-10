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
    var selectedServices;
    var auxI;
    var params = util_PrepareSearchRequestVars("data");
    //prepare POST data to be sent to the server
    if((searchType == "basic")||(searchType == "specific"))
    {
      params["query"] = (searchType == "basic") ? $("#basic_keyword").val() : $("#specific_keyword").val();
      
      if(searchType == "specific")
      {
         selectedServices = $('#providers_list').val() || [];
         if(!(selectedServices instanceof Array))
           selectedServices = new Array(selectedServices);
      }
      else selectedServices = new Array();
      
      //query all available sources
      sourcesQuestioned[searchNr] = 0;
      for(i=0; i<services_info.length; i++)
      {
        if(selectedServices.length > 0)
        {
          var found = false;
          for(auxI=0; auxI<selectedServices.length; auxI++)
            if(selectedServices[auxI] == i)
            {
              found = true;
              break;
            }
            
          if(!found) continue;
        }
         
        params["service_uri"] = services_info[i]["uri"];
        params["service_lifting"] = services_info[i]["lifting"];
        //make the request
        sourcesQuestioned[searchNr]++;
        //$.post(searchActionURL, params, searchResults, "JSON");
        var auxReq = $.ajax({type:"POST", cache:false, url:searchActionURL, success:searchResults, error:searchError, data:params, accepts:"JSON"});
        auxReq.sentData = params;
      }
      sourcesAnswered[searchNr] = 0;
      sourcesErrors[searchNr] = 0;
    }
    else if(searchType == "advanced")
    {
      alert("Under development!");
      return;
    }

    //create a new tab to display the information
    params["searchType"] = searchType;
    createNewTab(searchNr, params);
  }
</script>

<script language="javascript" type="text/javascript">
  var services_info = new Array();
<?php
  $counter = 0;
  foreach($vars["services"] as $key => $info) {
    if($info["liftingSchemaMapping"] == "") $info["liftingSchemaMapping"] = "0";
    
    echo 'services_info['.$counter.'] = new Array();'."\n";
    echo 'services_info['.$counter.']["uri"] = "'.$info["hasAddress"].'";'."\n";
    echo 'services_info['.$counter.']["lifting"] = "'.$info["liftingSchemaMapping"].'";'."\n";
    echo 'services_info['.$counter.']["subject"] = "'.$info["http://www.purl.org/meducator/ns/subject"].'";'."\n";
    echo 'services_info['.$counter.']["languages"] = "'.$info["http://www.purl.org/meducator/ns/metadataLanguage"].'";'."\n";
    echo 'services_info['.$counter.']["developer"] = "'.$info["hasDeveloper"].'";'."\n";
    $counter++;
  }
?>

  function getLCMSDeveloper(LCMS_uri)
  {
    var si;
    LCMS_uri = LCMS_uri.replace(/&amp;/g, "&");
    for(si=0; si<services_info.length; si++)
      if(services_info[si]["uri"] == LCMS_uri)
        //if(services_info[si]["developer"]) return services_info[si]["developer"];
        if(services_info[si]["developer"]) 
        {
          var serviceLabel = services_info[si]["developer"].split('/');
          serviceLabel = serviceLabel[serviceLabel.length - 1];
          //serviceLabel.replace(/[-]/g, " ");
          return CapWords(serviceLabel);
        }
        else return "";
    return "";
  }
  
  function CapWords(name)
  {
    var aux = name.split("-");
    var i;
    
    if(aux.length > 1)
    {
      for(i=0; i<aux.length; i++)
        aux[i] = aux[i].charAt(0).toUpperCase() + aux[i].substring(1);
      return aux.join(" ");
    }
    else return name;
  }
  
</script>
<?php

 function processData($dataInfo, &$collectorArray)
 {
   if(!isset($collectorArray[$dataInfo]))
   {
     $auxData = explode("#", $dataInfo);
     $auxData = $auxData[count($auxData) - 1];
     $collectorArray[$dataInfo] = ucfirst($auxData);
   }
 }
 
 $available_languages_list = array();
 $available_subjects_list = array();

 foreach($vars["services"] as $key => $info)
 {
   $langData = $info["http://www.purl.org/meducator/ns/metadataLanguage"];
   if(is_array($langData))
     for($i=0; $i<count($langData); $i++)
        processData($langData[$i], $available_languages_list);
   else
     processData($langData, $available_languages_list);
   
   $subData = $info["http://www.purl.org/meducator/ns/subject"];
   if(is_array($subData))
     for($i=0; $i<count($subData); $i++)
        processData($subData[$i], $available_subjects_list);
   else
     processData($subData, $available_subjects_list);
 }

 //asort($available_languages_list);
 $available_languages_list = array_unique($available_languages_list);
 asort($available_languages_list);
 asort($available_subjects_list);
?>
<form action="#" method="post" id="searchForm">

<!--<div style="clear:both; margin: auto; width: 540px;">
  <div style="float:left; width: 250px; padding-top:14px;"><?php echo elgg_echo("Save search parameters under title:"); ?></div>
  <div style="float:left; width: 200px; padding-top: 8px;"><?php echo elgg_view('input/text',array('internalname' => 'title')); ?></div>
  <div style="float:left; width: 80px; text-align: right;"><?php echo elgg_view('input/button', array('value' => elgg_echo('Save'), 'type'=>'button', 'js' => 'onclick=saveSearch()')); ?></div>
  <div class="clear_div"></div>
</div>-->
<br/>

<div id="accordion">
  <h3><a href="#">Basic search</a></h3>
  <div style="height: 300px;">
    <?php echo elgg_echo("Keyword(s):"); ?>
    <?php echo elgg_view('input/text',array('internalname' => 'basic_keyword', 'internalid' => 'basic_keyword')); ?>
 
    <br/>
    <?php echo elgg_view('input/button', array('value' => elgg_echo('Search'), 'type'=>'button', 'js' => 'onclick=doSearch("basic")')); ?>
  </div>

  <h3><a href="#">Advanced search</a></h3>
  <div>
    <?php require_once dirname(dirname(__FILE__)) . "/resources/languages_list.php"; ?>

    <label>Search language(s):</label>
    <select id="languages_list" name="languages_list[]" multiple="multiple" style="display:none" title="-- available languages --">
      <?php
        foreach($available_languages_list as $key => $value)
            echo '<option value="'.$value.'">'.$value.'</option>'. "\n";
      ?>
    </select>
    
    <label>Search subject(s):</label>
    <select id="subjects_list" name="subjects_list[]" multiple="multiple" style="display:none" title="-- available subjects --">
      <?php
        foreach($available_subjects_list as $key => $value)
            echo '<option value="'.$key.'">'.$value.'</option>'. "\n";
      ?>
    </select>

    <?php echo elgg_view('input/button', array('value' => elgg_echo('Search'), 'type'=>'button', 'js' => 'onclick=doSearch("advanced")')); ?>
  </div>
  
  <h3><a href="#">Specific search</a></h3>
  <div>
    <?php echo elgg_echo("Keyword(s):"); ?>
    <?php echo elgg_view('input/text',array('internalname' => 'specific_keyword', 'internalid' => 'specific_keyword')); ?>
    
    <label>Data provider(s):</label>
    <select id="providers_list" name="providers_list[]" multiple="multiple" style="display:none" title="-- available providers --">
      
    </select>

    <?php echo elgg_view('input/button', array('value' => elgg_echo('Search'), 'type'=>'button', 'js' => 'onclick=doSearch("specific")')); ?>
  </div>
</div>
</form>