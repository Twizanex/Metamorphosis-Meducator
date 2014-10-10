<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 

    // set the title
    $title = "About MetaMorphosis+";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;
	
	$area2 .= "<div class=\"contentWrapper\">";
	$area2 .="Metamorphosis+ is a Semantic social platform for sharing educational resources across the Web.<br /> <br /> It is fundamentally based on Linked Data and Service-oriented principles.<br /> Based on Linked Services technologies - such as iServe (<a href=\"http://iserve.kmi.open.ac.uk/\">http://iserve.kmi.open.ac.uk/</a>) and SmartLink (<a hrf=\"http://purl.org/smartlink\">http://purl.org/smartlink</a>)<br />  were a constantly growing number of existing educational data and resource stores is being integrated and can be accessed via Metamorphosis+.<br /><br /> Smart search and enrichment mechanisms allow users not only to search educational resources across the Web, but also to enrich existing metadata with original structured annotations.<br /><br /> New resource metadata within Metamorphosis+ is exposed following Linked Data principles and is semi-automatically enriched with links to public biomedical data sets to provide rich and widely accessible data about educational resources.
<br /><br />
		Metamorphosis+ was created as joint collaboration between several institutions led by The Open University, UK (<a href=\"http://www.open.ac.uk\">http://www.open.ac.uk</a>)<br /> Democritus University of Thrace, Greece (<a href=\"http://www.duth.edu\">http://www.duth.edu</a>) <br /> Technical University of Cluj Napoca, Romania (<a href=\"http://www.utcluj.ro\">http://www.utcluj.ro</a>) <br />and CNR, Italy. <br /><br />The work is partially developed as part of the mEducator eContent Plus Best Practice Network (<a href=\"http://www.meducator.net\">http://www.meducator.net</a>), kindly funded by the European Commission.
<br /><br />

Contact points:<br />
- DUTH: <a href=\"http://metamorphosis.med.duth.gr/pg/profile/lordanton\">Nikolas Dovrolis</a>,<a href=\"http://metamorphosis.med.duth.gr/pg/profile/kaldoud\"> Eleni Kaldoudi</a>(Web, Application)<br />
- UTCN: <a href=\"http://metamorphosis.med.duth.gr/pg/profile/teodor.stefanut\">Teodor Stefanut</a>(Application,Web)<br />
- OU: <a href=\"http://metamorphosis.med.duth.gr/pg/profile/stefan\">Stefan Dietze</a>,<a href=\"http://metamorphosis.med.duth.gr/pg/profile/harryyu\"> H.Q Yu </a> (Data and service integration)<br />
- CNR: <a href=\"http://metamorphosis.med.duth.gr/pg/profile/davide.taibi\">Davide Taibi</a>(API)<br />
-UNICT:<a href=\"http://metamorphosis.med.duth.gr/pg/profile/dani\">Daniela Giordano</a> (Exploratory Search)";

	$area2 .= "</div>";
    // layout the page
	 $body =elgg_view_layout('one_column', $area2);

 
 	
    // draw the page
    page_draw($title, $body);
	fclose($ourFileHandle);

?>