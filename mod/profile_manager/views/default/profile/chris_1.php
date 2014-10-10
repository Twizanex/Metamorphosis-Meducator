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
	  // skip if already picked
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
	  // skip if already picked
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
	  // skip if already picked
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
    function buildRDF() {
	  form=document.forms['masterForm'];
      sel1=form.pickedKey.selectedIndex;
	  sel2=form.pickedDis.selectedIndex;
	  sel3=form.pickedSpec.selectedIndex;
	  // disabled - although in theory we could still do this
      // if (form.pickedKey.length < 1 && form.pickedDis.length < 1 && form.pickedSpec.length < 1) return false;
      // eliminate empty array elements
	  // disciplines
      DisLabelsList='';
	  DisLabelsArray = new Array();
	  for (i=0; i < pickedDisLabels.length; i++) {
	    if (pickedDisLabels[i]) {
		  DisLabelsList=DisLabelsList + pickedDisLabels[i] + '|';
		  DisLabelsArray.push(pickedDisLabels[i]);
		}
      }
	  DisLabelsList=DisLabelsList.substring(0,DisLabelsList.length-3);
      DisIdsList='';
	  DisIdsArray = new Array();
      for (i=0; i < pickedDisIds.length; i++) {
        if (pickedDisIds[i]) {
		  DisIdsList=DisIdsList + pickedDisIds[i] + '|';
		  DisIdsArray.push(pickedDisIds[i]);
		}
      }
	  DisIdsList=DisIdsList.substring(0,DisIdsList.length-3);
      DisNamesList='';
	  DisNamesArray = new Array();
      for (i=0; i < pickedDisNames.length; i++) {
        if (pickedDisNames[i]) {
		  DisNamesList = DisNamesList + pickedDisNames[i] + '|';
		  DisNamesArray.push(pickedDisNames[i]);
		}
      }
	  DisNamesList=DisNamesList.substring(0,DisNamesList.length-3);

	  // keywords
      KeyLabelsList='';
	  KeyLabelsArray=new Array();
      for (i=0; i < pickedKeyLabels.length; i++) {
        if (pickedKeyLabels[i]) {
		  KeyLabelsList = KeyLabelsList + pickedKeyLabels[i] + '|';
		  KeyLabelsArray.push(pickedKeyLabels[i]);
		}
      }
	  KeyLabelsList=KeyLabelsList.substring(0,KeyLabelsList.length-3);
      KeyIdsList='';
	  KeyIdsArray=new Array();
      for (i=0; i < pickedKeyIds.length; i++) {
        if (pickedKeyIds[i]) {
		  KeyIdsList = KeyIdsList + pickedKeyIds[i] + '|';
		  KeyIdsArray.push(pickedKeyIds[i]);
		}
      }
	  KeyIdsList=KeyIdsList.substring(0,KeyIdsList.length-3);
      KeyNamesList='';
	  KeyNamesArray=new Array();
      for (i=0; i < pickedKeyNames.length; i++) {
        if (pickedKeyNames[i]) {
		  KeyNamesList = KeyNamesList + pickedKeyNames[i] + '|';
		  KeyNamesArray.push(pickedKeyNames[i]);
		}
      }
	  KeyNamesList=KeyNamesList.substring(0,KeyNamesList.length-3);
	  
	  // specialties
	  SpecLabelsList='';
	  SpecLabelsArray=new Array();
      for (i=0; i < pickedSpecLabels.length; i++) {
        if (pickedSpecLabels[i]) {
		  SpecLabelsList = SpecLabelsList + pickedSpecLabels[i] + '|';
		  SpecLabelsArray.push(pickedSpecLabels[i]);
		}
      }
	  SpecLabelsList=SpecLabelsList.substring(0,SpecLabelsList.length-3);
      SpecIdsList='';
	  SpecIdsArray=new Array();
      for (i=0; i < pickedSpecIds.length; i++) {
        if (pickedSpecIds[i]) {
		  SpecIdsList = SpecIdsList + pickedSpecIds[i] + '|';
		  SpecIdsArray.push(pickedSpecIds[i]);
		}
      }
	  SpecIdsList=SpecIdsList.substring(0,SpecIdsList.length-3);
      SpecNamesList='';
	  SpecNamesArray=new Array();
      for (i=0; i < pickedSpecNames.length; i++) {
        if (pickedSpecNames[i]) {
		  SpecNamesList = SpecNamesList + pickedSpecNames[i] + '|';
		  SpecNamesArray.push(pickedSpecNames[i]);
		}
      }
	  SpecNamesList=SpecNamesList.substring(0,SpecNamesList.length-3);
      // put the new arrays into hidden form fields
      document.forms['masterForm'].pickedDisLabels.value=DisLabelsList;
	  // document.getElementById('pickedDisLabels').value=DisLabelsArray;
      document.forms['masterForm'].pickedDisIds.value=DisIdsList;
	  // document.getElementById('pickedDisIds').value=DisIdsArray;
      document.forms['masterForm'].pickedDisNames.value=DisNamesList;
	  // document.getElementById('pickedDisNames').value=DisNamesArray;
      document.forms['masterForm'].pickedKeyLabels.value=KeyLabelsList;
	  // document.getElementById('pickedKeyLabels').value=KeyLabelsArray;
      document.forms['masterForm'].pickedKeyIds.value=KeyIdsList;
	  // document.getElementById('pickedKeyIds').value=KeyIdsArray;
      document.forms['masterForm'].pickedKeyNames.value=KeyNamesList;
	  // document.getElementById('pickedKeyNames').value=KeyNamesArray;
      document.forms['masterForm'].pickedSpecLabels.value=SpecLabelsList;
	  // document.getElementById('pickedSpecLabels').value=SpecLabelsArray;
      document.forms['masterForm'].pickedSpecIds.value=SpecIdsList;
	  // document.getElementById('pickedSpecIds').value=SpecIdsArray;
      document.forms['masterForm'].pickedSpecNames.value=SpecNamesList;
	  // document.getElementById('pickedSpecNames').value=SpecNamesArray;
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
        url: "http://metamorphosis.med.duth.gr/mod/profile_manager/views/default/profile/ajax_fetch_data.php?searchfor=" + searchfor,
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
<input type='hidden' name='pickedDisLabels[]' id='pickedDisLabels' value='' />
<input type='hidden' name='pickedDisIds[]' id='pickedDisIds' value='' />
<input type='hidden' name='pickedDisNames[]' id='pickedDisNames' value='' />
<input type='hidden' name='pickedKeyLabels[]' id='pickedKeyLabels' value='' />
<input type='hidden' name='pickedKeyIds[]' id='pickedKeyIds' value='' />
<input type='hidden' name='pickedKeyNames[]' id='pickedKeyNames' value='' />
<input type='hidden' name='pickedSpecLabels[]' id='pickedSpecLabels' value='' />
<input type='hidden' name='pickedSpecIds[]' id='pickedSpecIds' value='' />
<input type='hidden' name='pickedSpecNames[]' id='pickedSpecNames' value='' />
Enter your search term: <input type='text' name='searchfor' size='32' maxlength='100' />
<input type='button' value='Search' onclick='return fetchHits(this.form);' /> <img src='/elgg/mod/profile_manager/views/default/profile/blank.gif' id='wait' width='31' height='31' alt='' style='border: 0; vertical-align: middle;' />
<input type='text' name='statusBox' size='100' class='noBox' /></p>
<!-- <input type='hidden' name='action' value='' /> -->
</td></tr>
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

<tr><td colspan='3' nowrap><br />
<!-- <input type='button' value='submit your choice' onclick='return buildRDF(this.form);' />&nbsp;&nbsp;&nbsp;&nbsp; -->
<input type='reset' value='reset and search again' onclick='return resetForm(this.form);' /></td></tr>
</table>

<p>
<span class='custom_fields_more_info' id='more_info_meducator4'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator4'><b>Some keywords describing the resource</b>. They can be a mixture of keywords related to content, educational method, technical characteristics, etc. <br />For example meningitis, paediatrics, virtual patient, open labyrinth, cc: by-nc-sa</span>
<label>
Keywords<br />
</label>

<div id="meducator4_div">
<input type="text"   name="meducator4"  value="" class="input-text"/> <br />
</div>
<!--
<a href="http://metamorphosis.med.duth.gr/mod/profile_manager/views/default/profile/new_form.html"  onclick="window.open('http://metamorphosis.med.duth.gr/mod/profile_manager/views/default/profile/new_form.html','popup','width=800,height=600,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false">CHRIS' FORM</a>

<a class='example6' href="http://metamorphosis.med.duth.gr/mod/profile_manager/views/default/profile/new_form.html" title="CHRIS">COLORBOX CHRIS</a> -->
</p>

<p>
<span class='custom_fields_more_info' id='more_info_meducator16'></span><span class='custom_fields_more_info_text' id='text_more_info_meducator16'>e.g. medicine, nursing, sociology, medical informatics, etc</span>
<label>
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