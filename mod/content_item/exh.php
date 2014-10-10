

<link href="propertysearch.xml" rel="exhibit/data" type="application/rdf+xml" />

	    <script src="http://api.simile-widgets.org/exhibit/2.2.0/exhibit-api.js"
            type="text/javascript"></script>  
    <script src="http://api.simile-widgets.org/exhibit/2.2.0/exhibit-api.js"
            type="text/javascript"></script>
 <script src="http://api.simile-widgets.org/exhibit/2.2.0/extensions/time/time-extension.js"
     type="text/javascript"></script>
<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
 
    // set the title
    $title = "Exhibit Test on keyword search for Virtual ";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;


//$response = file_get_contents(



   $area2.="
    




    <table width=\"100%\">
        <tr valign=\"top\">
            <td ex:role=\"viewPanel\">
                <div ex:role=\"view\"> </div>
				<div ex:role=\"view\"
				    ex:viewClass=\"Timeline\"
     ex:start=\".metadataCreated\"
     ex:colorKey=\".title\">
				
				</div>
 <div ex:role=\"exhibit-view\"
     ex:viewClass=\"Exhibit.TabularView\"
     ex:columns=\".title, .metadataCreated, .metadataCreator\"
     ex:columnLabels=\"title, year, creator\"
     ex:columnFormats=\"list, list, list\"
     ex:sortColumn=\"3\"
     ex:sortAscending=\"false\">
 </div>




				</td>
            <td width=\"25%\">
               <div ex:role=\"facet\" ex:expression=\".title\" ex:facetLabel=\"Title\"></div>
 <div ex:role=\"facet\" ex:expression=\".metadataCreated\" ex:facetLabel=\"Creation Date\"></div>
 <div ex:role=\"facet\" ex:expression=\".metadataLanguage\" ex:facetLabel=\"MetaData Language\"></div>

            </td>
        </tr>
    </table>
" ;
 $body =elgg_view_layout('one_column', $area2);
 
    page_draw($title, $body);
	





	?>