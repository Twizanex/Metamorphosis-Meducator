
$(document).ready(function(){
    var fieldName;
    var fieldNames;
    var fieldData;
    var fieldDatas;
    var otherFieldName;
    var otherFieldContent;
    var nr, i, j;
    
    //fill in IDENTIFIERS
    for(i=0; i<identifiers.length; i++)
    {
      //for the first value we already have the field
      if(i != 0) addNewField("meducator2");
      //add the value to the field
      nr = $('#meducator2_nr').val();
      $('#meducator2_' + nr).val(identifiers[i]["value"]);
      //fill in IDENTIFIER TYPE fields
      identifierIndexToCheck = i;
      $('#type_meducator2_' + nr + ' option').each(function(i, element){
          if(($(element).text() == identifiers[identifierIndexToCheck]["description"])||($(element).val() == identifiers[identifierIndexToCheck]["description"])) {
              $(element).attr('selected','selected');
              return;
          }
      });
    }
    
  
    //fill in QUALITY, COMPANION
    fieldNames = new Array("meducator19", "meducator28");
    fieldDatas = new Array(quality, companion);
    for(j=0; j<fieldNames.length; j++)
    {
      fieldName = fieldNames[j];
      fieldData = fieldDatas[j];
      for(i=0; i<fieldData.length; i++)
      {
        //for the first value we already have the field
        if(i != 0) addNewField(fieldName);
        //add the value to the field
        nr = $('#' + fieldName + '_nr').val();
        $('#' + fieldName + "_" + nr).val(fieldData[i]);
      }
    }
    
    //fill in LANGUAGE fields
    $('#meducator5 option').each(function(i, element){
        if(($(element).text() == resourceLanguage)||($(element).val() == resourceLanguage)) {
            $(element).attr('selected','selected');
            return;
        }
    });
    $('#meducator24 option').each(function(i, element){
        if(($(element).text() == metadataLanguage)||($(element).val() == metadataLanguage)) {
            $(element).attr('selected','selected');
            return;
        }
    });
    $('#meducator5').change();
    $('#meducator24').change();

    //fill in CREATOR
    fieldName = "meducator20";
    for(i=0; i<creator.length; i++)
    {
      //for the first value we already have the field
      if(i != 0) addNewField(fieldName);
      //add the values to the fields
      nr = $('#' + fieldName + '_nr').val();
      $('#name_' + fieldName + "_" + nr).val(creator[i]["name"]);
      $('#affiliation_' + fieldName + "_" + nr).val(creator[i]["memberOf"]);
      $('#foafURI_' + fieldName + "_" + nr).val(creator[i]["profileURI"]);
    }
    
    //fill in RIGHTS, MEDIA TYPE, RESOURCE TYPE, EDUCATIONAL LEVEL, EDUCATIONAL OUTCOMES
    fieldNames = new Array("meducator18b", "meducator6b", "meducator6a", "meducator153", "meducator124");
    fieldDatas = new Array(rights, mediaType, resourceType, educationalLevel, educationalOutcomes);
    var otherFieldNames = new Array("meducator18a", "meducator6b1", "meducator6", "", "");
    for(j=0; j<fieldNames.length; j++)
    {
      fieldName = fieldNames[j];
      fieldData = fieldDatas[j];
      otherFieldName = otherFieldNames[j];
      otherFieldContent = "";
      for(i=0; i<fieldData.length; i++)
      {
        var foundInList = false;
        $('#' + fieldName + ' option').each(function(p, element){
          if(($(element).text() == fieldData[i])||($(element).val() == fieldData[i])) {
              $(element).attr('selected','selected');
              foundInList = true;
              return;
          }
        });
        //if not in the list, add it to the Other Media Types field
        if(!foundInList)
        {
          if(otherFieldContent != "") otherFieldContent += ", ";
          otherFieldContent += fieldData[i];
        }
      }
      //aply the changes for the SELECT and MULTIPLESELECT controls
      $('#' + fieldName).change();
      //display the values in the OTHER fields, if specified
      if(otherFieldName != "")
        $('#' + otherFieldName).val(otherFieldContent);
    }
    
    var form = document.getElementById("profile_edit_form");
    //fill in KEYWORDS
    for(j=0; j<subject.length; j++)
      addKeyCompute(form, subject[j]["seeAlso"], subject[j]["label"], subject[j]["externalSource"], subject[j]["seeAlso"]);
    //fill in DISCIPLINES
    for(j=0; j<discipline.length; j++)
      addDisCompute(form, discipline[j]["seeAlso"], discipline[j]["label"], discipline[j]["externalSource"], discipline[j]["seeAlso"]);
    //fill in DISCIPLINE SPECIALITIES
    for(j=0; j<disciplineSpeciality.length; j++)
      addSpecCompute(form, disciplineSpeciality[j]["seeAlso"], disciplineSpeciality[j]["label"], disciplineSpeciality[j]["externalSource"], disciplineSpeciality[j]["seeAlso"]);
})

function fillInRepurposingInfo()
{
  if(!repurposing) return;
  
  autoTriggerSelectOnChosenValues = false;
  for(ri=0; ri<repurposing.length; ri++)
  {
    //test only non empty repurposing parents
    if(repurposing[ri]["title"] == "") continue;
    //look for GUIDS
    var internalID = null;
    var sesameID = "";
    for(rj=0; rj<repurposingParentsResult.results.length; rj++)
      if(repurposing[ri]["title"] == repurposingParentsResult.results[rj].title)
      {
        internalID = repurposingParentsResult.results[rj].internalID;
        sesameID = repurposingParentsResult.results[rj].ID;
        break;
      }
    //check if any GUID found
    if(internalID == null)
    {
      //don't attempt to add an external resource that is incomplete
      if(repurposing[ri]["title"] == "") continue;
      //this is external resource
      internalID = "_ext_" + externalParentsNr;
      $('#external_parent_title').val(repurposing[ri]["title"]);
      $('#external_parent_identifier').val(repurposing[ri]["identifier"]);
      $('#addBtnExternal').trigger("click");
    }
    else
    {
      //this is internal resource
      $('#availableResources option[value=' + internalID + ']').attr('selected','selected');
      $('#addBtn').trigger("click");
      //mark the "selected" attribute as void
      $('#selectedResources option[value=' + internalID + ']').attr('selected','');
    }
    //populate the other fields
    $('#repurpose_types_' + internalID).val(repurposing[ri]["fromRepurposingContext"].join(";"));
    $('#repurpose_desc_' + internalID).val(repurposing[ri]["repurposingDescription"]);
    $('#repurpose_sesameid_' + internalID).val(sesameID);
  }
  autoTriggerSelectOnChosenValues = true;
}
