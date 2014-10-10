var results = new Array();
var currentPage = new Array();
var totalPages = new Array();
var resultsOnPage = 10;
var advancedSearchTemplate = "";
var specificSearchTemplate = "";
var sortAscending = true;

$(document).ready(function(){
  $("#tabs").tabs();
  $("#accordion").accordion();
  $('#property_0').sortOptionsByText();
  $("#specific_search_propertis_list").asmSelect({
				addItemTarget: 'bottom',
				animate: true,
				highlight: false,
				sortable: false
			});

  advancedSearchTemplate = $("#fields_div").html();
  specificSearchTemplate = $("#specific-search-ids-list-div").html();
});

function searchResults(data)
{
  try
  {
    var result = $.parseJSON(data);
    //compute pagination info
    currentPage[result.searchID] = 1;
    totalPages[result.searchID] = Math.ceil(result.results.length / resultsOnPage);
    results[result.searchID] = result;
    if(result.searchType != "specific")
      sort(result.searchID, "title", "asc");
    //update security tokens
    ts = result.__elgg_ts;
    token = result.__elgg_token;
    //display found information
    displayResults(result.searchID);
  }
  catch(e) {}
}

function displayResults(searchID)
{
  var result = results[searchID];
  if(!result) return;
  if(result.results.length == 0)
  {
    $("#results-display-" + searchID).html("No results found.");
    return;
  }
  //clear the current content
  $("#results-display-" + searchID).html("");
  //display the results
  var startResult = (currentPage[searchID]-1) * resultsOnPage;
  var endResult = currentPage[searchID] * resultsOnPage;
  if(endResult > result.results.length) endResult = result.results.length;
  for(i=startResult; i<endResult; i++)
    if(result.searchType != "specific")
      $("#results-display-" + searchID).html($("#results-display-" + searchID).html() + displayResult(result.results[i]));
    else
      $("#results-display-" + searchID).html($("#results-display-" + searchID).html() + displaySpecificResult(result.results[i]));
  //display pagination
  displayPagination(searchID);
}

function displayResult(data)
{
  var resultTemplate = $("#searchResultTemplate").html();
  resultTemplate = resultTemplate.replace(":title:", data.title);
  resultTemplate = resultTemplate.replace(":t_title:", data.title);
  resultTemplate = resultTemplate.replace(":result_id:", data.ID);
  resultTemplate = resultTemplate.replace(":profile_url:", website_url + data.profileURI);
  resultTemplate = resultTemplate.replace(":mdate:", data.metadataCreated);
  resultTemplate = resultTemplate.replace(":rdate:", data.resourceCreated);
  resultTemplate = resultTemplate.replace(":rlang:", data.resourceLanguage);
  resultTemplate = resultTemplate.replace(":mlang:", data.metadataLanguage);
  resultTemplate = resultTemplate.replace(":license:", data.IPR);
  resultTemplate = resultTemplate.replace(":t_license:", data.IPR);
  resultTemplate = resultTemplate.replace(":rauthors:", data.resourceAuthors);
  resultTemplate = resultTemplate.replace(":t_rauthors:", data.resourceAuthors);
  resultTemplate = resultTemplate.replace(":mauthors:", data.metadataAuthors);
  return resultTemplate;
}

function displaySpecificResult(data)
{
  var resultTemplate = '<div class="specific-search-result-element">';
  for(var prop in data)
    resultTemplate += "<em><u>" + properties_list["mdc:" + prop] + "</u></em>: " + data[prop] + "<br/>";
  resultTemplate += '<div style="text-align:center;">***</div>'
  return resultTemplate + "</div>";
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

function viewDetails(resourceID)
{
  var params = util_PrepareSearchRequestVars("details");
  params["resourceID"] = resourceID;
  $("#details_div_content").html("Loading ...");
  $("#details_div_content").load(searchActionURL, params);
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
    $("#tabs").tabs( "add" , "#search" + id , "Search by: \"" + searchInfo["keyword"] + "\"");
    searchInfoDisplay = "Search type: <em><u>basic</u></em><br/>Search by: <em>" + searchInfo["keyword"] + "</em>";
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
  else if(searchInfo["searchType"] == "specific")
  {
    $("#tabs").tabs( "add" , "#search" + id , "Specific search");
    searchInfoDisplay = "Search type: <em><u>specific</u></em>, displaying the following properties:<br/><em>";
    var counter = 0;
    for(var key in searchInfo)
    {
      //skip all the searchInfo fields that are not properties
      if((key.substr(0, 9) == "property_") && searchInfo[key])
      {
        if(counter != 0) searchInfoDisplay += ", ";
        if((counter % 3 == 0)&&(counter != 0)) searchInfoDisplay += "<br/>";
        searchInfoDisplay += "<strong>" + properties_list[searchInfo[key]] + "</strong>";
        counter++;
      }
    }
    searchInfoDisplay += "</em>";
  }

  //add the content for the new tab
  var template = $("#newTabTemplate").html();
  template = template.replace(/:search_nr:/g, id);
  $("#search"+id).html(template);
  //update search info
  $("#search-info-display-"+id).html(searchInfoDisplay);
  //hide the sorting list for "specific" searches
  if(searchInfo["searchType"] == "specific")
    $("#sort-display-"+id).css("display", "none");
  //make tab visible
  $("#tabs").tabs( "select", nrTabs );
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