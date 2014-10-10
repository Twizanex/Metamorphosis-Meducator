var fields_to_tabs = new Array();
fields_to_tabs["meducator3"] = 
fields_to_tabs["meducator2"] = 
fields_to_tabs["meducator18a"] = 
fields_to_tabs["meducator18b"] = 
fields_to_tabs["meducator5"] = 
fields_to_tabs["meducator24"] = "1";
fields_to_tabs["meducator20"] = 
fields_to_tabs["meducator211"] = 
fields_to_tabs["meducator7"] = "2";
fields_to_tabs["meducator_subjects"] = "7";


var fields_titles = new Array();
fields_titles["meducator3"] = "Resource title";
fields_titles["meducator2"] = "Resource identifiers";
fields_titles["meducator18a"] = "Resource license";
fields_titles["meducator18b"] = "Resource license";
fields_titles["meducator5"] = "Resource language";
fields_titles["meducator24"] = "Language of metadata";
fields_titles["meducator20"] = "Resource Author(s)";
fields_titles["meducator211"] = "Date of Creation";
fields_titles["meducator7"] = "Description of the resource in terms of its educational content";
fields_titles["meducator_subjects"] = "Chosen keywords";

var tabs_data = new Array();
tabs_data[1] = "354";
tabs_data[2] = "742";
tabs_data[7] = "1969";

var tabs_errors = new Array(10);
var switchedToTab;

function checkFormData()
{
  var error_msg = "";
  var fieldToCheck = "";
  var nrOfFields = 0;
  var nrCorrectFields = 0;
  var localError;
  
  //reset tabs errors flags
  for(i=0; i<tabs_errors.length; i++)
    tabs_errors[i] = false;
  
  //check the TITLE, RESOURCE LANGUAGE, METADATA LANGUAGE
  var fields_to_check = new Array("meducator3", "meducator5", "meducator24", "meducator211", "meducator7");
  for(i=0; i<fields_to_check.length; i++)
    if(!nonEmpty(fields_to_check[i]))
    {
      error_msg += " - " + fields_titles[fields_to_check[i]] + "\n";
      tabs_errors[fields_to_tabs[fields_to_check[i]]] = true;
    }
  
  
  
  //check the IDENTIFIERS
  localError = false;
  fieldToCheck = "meducator2"; //IDENTIFIERS
  nrOfFields = $('#' + fieldToCheck + "_nr").val();
  //reset the fields (if previously marked as errorneous)
  for(i=1; i<=nrOfFields; i++) setIdentifiersError(fieldToCheck, i, "ok");
  //we need at least one correct identifier; on the other hand, each identifier must have a type specified
  nrCorrectFields = 0;
  for(i=1; i<=nrOfFields; i++)
  {
    var auxFieldName = fieldToCheck + "_" + i;
    var auxFieldType = "type_" + auxFieldName;
    if($('#' + auxFieldName).val() != "")
    {
      if($('#' + auxFieldType).val() != "") nrCorrectFields++;
      else
      {
        localError = true;
        setIdentifiersError(fieldToCheck, i, "error");
      }
    }
  }
  //if no correct identifier found, all displayed fields are marked as errorneous
  if(nrCorrectFields == 0)
  {
    localError = true;
    for(i=1; i<=nrOfFields; i++)
      setIdentifiersError(fieldToCheck, i, "error");
  } 
  //modify the message
  if(localError) 
  {
    error_msg += " - " + fields_titles["meducator2"] + "\n";
    tabs_errors[fields_to_tabs["meducator2"]] = true;
  }
  
  
  //check the RIGHTS
  if(($("#meducator18a").val() == "")&&($("#meducator18b").val() == ""))
  {
    error_msg += " - " + fields_titles["meducator18a"] + "\n";
    tabs_errors[fields_to_tabs["meducator18a"]] = true;
    $("#meducator18a").addClass("field_error");
    $("#meducator18b").addClass("field_error");
  }
  else
  {
    $("#meducator18a").removeClass("field_error");
    $("#meducator18b").removeClass("field_error");
  }
  
  
  //check for at least one RESOURCE AUTHOR
  localError = false;
  fieldToCheck = "meducator20"; //RESOURCE AUTHORS
  nrOfFields = $('#' + fieldToCheck + "_nr").val();
  //reset the fields (if previously marked as errorneous)
  for(i=1; i<=nrOfFields; i++) setAuthorsError(fieldToCheck, i, "ok");
  //we need at least one author name
  nrCorrectFields = 0;
  for(i=1; i<=nrOfFields; i++)
  {
    var auxFieldName = "name_" + fieldToCheck + "_" + i;
    if($('#' + auxFieldName).val() != "") nrCorrectFields++;
  }
  //if no author found, all displayed fields are marked as errorneous
  if(nrCorrectFields == 0)
  {
    localError = true;
    for(i=1; i<=nrOfFields; i++)
      setAuthorsError(fieldToCheck, i, "error");
  } 
  //modify the message
  if(localError) 
  {
    error_msg += " - " + fields_titles["meducator20"] + "\n";
    tabs_errors[fields_to_tabs["meducator20"]] = true;
  }
  
  
  
  //check the SUBJECTS
  if($("#meducator_subjects option").length == 0)
  {
    $("#meducator_subjects").addClass("field_error");
    error_msg += " - " + fields_titles["meducator_subjects"] + "\n";
    tabs_errors[fields_to_tabs["meducator_subjects"]] = true;
  }
  else $("#meducator_subjects").removeClass("field_error");
  
  //FORMAT THE TABS HEADERS
  switchedToTab = false;
  $('#elgg_horizontal_tabbed_nav ul li').each(function(i, item){
    if(tabs_errors[i+1] === true) 
    {
      $(item).addClass("tab_error");
      //switch to the first tab with errors
      if(!switchedToTab)
      {
        switchedToTab = true;
        var link_element = $(item).find("a").get();
        toggle_tabbed_nav(tabs_data[i+1], link_element);
      }
    }
    else $(item).removeClass("tab_error");
  });
  
  //DISPLAY ERROR MESSAGE
  if(error_msg != "") alert("There are errors in your form! Please check:\n" + error_msg);
  return (error_msg == "");
}

function setIdentifiersError(fieldName, index, markType)
{
  var auxFieldName = fieldName + "_" + index;
  var auxFieldType = "type_" + auxFieldName;
    
  if(markType == "error")
  {
    $('#' + auxFieldName).addClass("field_error");
    $('#' + auxFieldType).addClass("field_error");
  }
  else
  {
    $('#' + auxFieldName).removeClass("field_error");
    $('#' + auxFieldType).removeClass("field_error");
  }
}


function setAuthorsError(fieldName, index, markType)
{
  var auxFieldName = "name_" + fieldName + "_" + index;
  var auxFieldAff = "affiliation_" + fieldName + "_" + index;
  var auxFieldURI = "foafURI_" + fieldName + "_" + index;
    
  if(markType == "error")
  {
    $('#' + auxFieldName).addClass("field_error");
    $('#' + auxFieldAff).addClass("field_error");
    $('#' + auxFieldURI).addClass("field_error");
  }
  else
  {
    $('#' + auxFieldName).removeClass("field_error");
    $('#' + auxFieldAff).removeClass("field_error");
    $('#' + auxFieldURI).removeClass("field_error");
  }
}


function nonEmpty(fieldName)
{
  if($('#' + fieldName).val() == "") 
  {
    $('#' + fieldName).addClass("field_error");
    return false;
  }
  else 
  {
    $('#' + fieldName).removeClass("field_error");
    return true;
  }
}