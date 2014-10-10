var results = new Array();
var currentPage = new Array();
var totalPages = new Array();
var sourcesQuestioned = new Array();
var sourcesAnswered = new Array();
var sourcesErrors = new Array();
var resultsOnPage = 10;
var sortAscending = true;

$(document).ready(function(){
  $("#tabs").tabs();
  $("#accordion").accordion();
  $("#languages_list").asmSelect({
        addItemTarget: 'bottom',
        animate: true,
        highlight: false,
        sortable: false
    });
  $("#subjects_list").asmSelect({
        addItemTarget: 'bottom',
        animate: true,
        highlight: false,
        sortable: false
    });
  
  var auxHTML = "";
  for(i=0; i<services_info.length; i++)
    auxHTML += '<option value="' + i +'">' + getLCMSDeveloper(services_info[i]["uri"]) + '</option>';
  $("#providers_list").html(auxHTML);
  $("#providers_list").sortOptionsByText();
  
  $("#providers_list").asmSelect({
        addItemTarget: 'bottom',
        animate: true,
        highlight: false,
        sortable: false
    });
});

function searchError(data)
{
    sourcesErrors[data.sentData.searchID]++;
    displayResults(data.sentData.searchID);
    displaySourcesInfo(data.sentData.searchID);
}

function searchResults(data)
{
  try
  {
    //var result = $.parseJSON(data);
    var result = data;
    if(!results[result.searchID])
      results[result.searchID] = result;
    else
      for(i=0; i<result.results.length; i++)
        results[result.searchID].results.push(result.results[i]);
    //count answered services
    sourcesAnswered[result.searchID]++;
    //compute pagination info
    if(!currentPage[result.searchID]) currentPage[result.searchID] = 1;
    totalPages[result.searchID] = Math.ceil(results[result.searchID].results.length / resultsOnPage);
    ts = result.__elgg_ts;
    token = result.__elgg_token;
    //display found information
    displayResults(result.searchID);
    displaySourcesInfo(result.searchID);
  }
  catch(e) {}
}

function displayResults(searchID)
{
  var result = results[searchID];
  if(!result) return;
  if(result.results.length == 0)
  {
    //if all the sources answered successfully or in error, display the message
    if(sourcesAnswered[searchID] + sourcesErrors[searchID] >= sourcesQuestioned[searchID])
        $("#results-display-" + searchID).html("No results found.");
    return;
  }
  //clear the current content
  $("#results-display-" + searchID).html("");
  //display the searching animation
  if(sourcesAnswered[searchID] + sourcesErrors[searchID] < sourcesQuestioned[searchID])
    $("#sources-search-img-display-" + searchID).css("display", "inline");
  //display the results
  var startResult = (currentPage[searchID]-1) * resultsOnPage;
  var endResult = currentPage[searchID] * resultsOnPage;
  if(endResult > result.results.length) endResult = result.results.length;
  for(i=startResult; i<endResult; i++)
      $("#results-display-" + searchID).html($("#results-display-" + searchID).html() + displayResult(result.results[i], searchID));
  //display pagination
  displayPagination(searchID);
}

function displayResult(data, searchID)
{
  var resultTemplate = $("#searchResultTemplate").html();
  resultTemplate = resultTemplate.replace(":title:", data.title);
  resultTemplate = resultTemplate.replace(":t_title:", data.title);
  resultTemplate = resultTemplate.replace(":result_id:", data.ID);
  resultTemplate = resultTemplate.replace(":search_nr:", searchID);
  resultTemplate = resultTemplate.replace(":internal_id:", data.internalID);
  resultTemplate = resultTemplate.replace(":mdate:", data.metadataCreated);
  resultTemplate = resultTemplate.replace(":rdate:", data.resourceCreated);
  resultTemplate = resultTemplate.replace(":rlang:", data.resourceLanguage);
  resultTemplate = resultTemplate.replace(":mlang:", data.metadataLanguage);
  resultTemplate = resultTemplate.replace(":license:", data.IPR);
  resultTemplate = resultTemplate.replace(":t_license:", data.IPR);
  resultTemplate = resultTemplate.replace(":rauthors:", data.resourceAuthors);
  resultTemplate = resultTemplate.replace(":t_rauthors:", data.resourceAuthors);
  resultTemplate = resultTemplate.replace(":mauthors:", data.metadataAuthors);
  resultTemplate = resultTemplate.replace(":LCMS_info:", "LCMS: " + getLCMSDeveloper(data.serviceUri));
  return resultTemplate;
}

function displaySourcesInfo(searchID)
{
    var displayText = "";
    if(sourcesQuestioned[searchID] > sourcesAnswered[searchID] + sourcesErrors[searchID])
      displayText = 'Querying... <span class="bold">' + sourcesQuestioned[searchID] + '</span> distributed LCMS services.';
    else
    {
      displayText = 'Queried: <span class="bold">' + sourcesQuestioned[searchID] + '</span> distributed LCMS services.';
      //hide searching animation
      $("#sources-search-img-display-" + searchID).css("display", "none");
    }
    displayText += ' Successfull answers: <span class="bold">' + sourcesAnswered[searchID] + '</span>.';
    displayText += ' Errors: <span class="bold">' + sourcesErrors[searchID] + '</span>.';
    $("#sources-info-display-" + searchID).html(displayText);
}

function displayPagination(searchID)
{
  var displayText;
  var startResultsDisplay = (currentPage[searchID] - 1) * resultsOnPage + 1;
  var endResultsDisplay = currentPage[searchID] * resultsOnPage;
  if(endResultsDisplay > results[searchID].results.length) endResultsDisplay = results[searchID].results.length;

  displayText = 'The search returned: <span class="bold">' + results[searchID].results.length + '</span> results.';
  displayText += ' Displaying results from <span class="bold">' + startResultsDisplay + '</span> to ' + '<span class="bold">' + endResultsDisplay + '</span>.';
  displayText += '<br>Page: ';
  
  for(i=1; i<=totalPages[searchID]; i++)
  {
    displayText += "&nbsp;";
    if(i == currentPage[searchID]) displayText += '<span class="bold">' + i + '</span>';
    else displayText += '<a href="javascript:changePage(\'' + searchID + '_' + i + '\')">' + i + '</a>';
  }
  $("#pagination-display-" + searchID).html(displayText);
}

function changePage(val)
{
  var aux = val.split("_");
  var pageNr = aux[1];
  var searchID = parseInt(aux[0]);
  
  if(pageNr == "next") auxPage = currentPage[searchID] + 1;
  else if(pageNr == "prev") auxPage = currentPage[searchID] - 1;
  else pageNr = parseInt(pageNr);
  if((pageNr > 0) && (pageNr <= totalPages[searchID]))
  {
    currentPage[searchID] = pageNr;
    displayResults(searchID);
  }
}

function viewDetails(resourceID, searchID)
{
  var auxResults = results[searchID].results;
  var found = false;
  //identify the resource
  for(i=0; i<auxResults.length; i++)
    if(resourceID == auxResults[i].ID)
    {
      $("#details_div_content").html(auxResults[i].resource_details);
      found = true;
      break;
    }
  //if the resource could not be identified
  if(!found)
      $("#details_div_content").html("No details found!");
  //display the details div
  $("#details_div").css("display", "block");
  $('#details_div').positionCenter();

}

function hideDetails()
{
  $("#details_div").css("display", "none");
}

function createNewTab(id, searchInfo)
{
  var nrTabs = $("#tabs").tabs( "length" );
  var searchInfoDisplay = "";

  //establish tab title and search info
  if(searchInfo["searchType"] == "basic")
  {
    $("#tabs").tabs( "add" , "#search" + id , "Search by: \"" + searchInfo["query"] + "\"");
    searchInfoDisplay = "Search type: <em><u>basic</u></em><br/>Search by: <em>" + searchInfo["query"] + "</em>";
  }
  else if(searchInfo["searchType"] == "advanced")
  {
    $("#tabs").tabs( "add" , "#search" + id , "Advanced search");
    searchInfoDisplay = "Search type: <em><u>advanced</u></em> with the following criteria:<br/>";
    for(var key in searchInfo)
    {
      //skip all the searchInfo fields that are not criteria
      if((key.substr(0, 4) == "mdc:") && searchInfo[key])
      {
        var auxDisplay = (properties_values[key]) ? properties_values[key][searchInfo[key]] : searchInfo[key];
        searchInfoDisplay += "<strong>" + properties_list[key] + "</strong>: <em>" + auxDisplay + "</em><br/>";
      }
    }
  }
  if(searchInfo["searchType"] == "specific")
  {
    $("#tabs").tabs( "add" , "#search" + id , "Specific search by: \"" + searchInfo["query"] + "\"");
    searchInfoDisplay = "Search type: <em><u>specific</u></em><br/>Search by: <em>" + searchInfo["query"] + "</em>";
  }

  //add the content for the new tab
  var template = $("#newTabTemplate").html();
  template = template.replace(/:search_nr:/g, id);
  $("#search"+id).html(template);
  //update search info
  $("#search-info-display-"+id).html(searchInfoDisplay);
  //make tab visible
  $("#tabs").tabs( "select", nrTabs );
  //display information about the LCMS services
  displaySourcesInfo(id);
}

function closeTab(searchID)
{
  var index = $('#tabs').tabs('option', 'selected');
  $("#tabs").tabs( "remove", index );
  //delete the information regarding the search
  results[searchID] = null;
}

function util_PrepareSearchRequestVars(searchType)
{
  searchNr++;

  var params = new Object();
  params["searchType"] = searchType;
  params["searchID"] = searchNr;
  params["__elgg_token"] = token;
  params["__elgg_ts"] = ts;

  return params;
}


function sort(searchID, by, direction)
{
  var searchInfo = results[searchID];
  var sorted = false;
  var aux;

  if(direction == "asc") sortAscending = true;
  else if(direction == "desc") sortAscending = false;
  else sortAscending = !sortAscending;

  while (!sorted)
  {
    sorted = true;
    for(i=1; i<searchInfo.results.length; i++)
    {
      var element = searchInfo.results[i];
      var prevElement = searchInfo.results[i-1];
      if(by == "title")
      {
        if(prevElement.title < element.title)
          if(sortAscending) continue;
          else
          {
            aux = searchInfo.results[i];
            searchInfo.results[i] = searchInfo.results[i-1];
            searchInfo.results[i-1] = aux;
            sorted = false;
          }
        else if(prevElement.title > element.title)
          if(!sortAscending) continue;
          else
          {
            aux = searchInfo.results[i];
            searchInfo.results[i] = searchInfo.results[i-1];
            searchInfo.results[i-1] = aux;
            sorted = false;
          }
      }
    }
  }
  displayResults(searchID);
}