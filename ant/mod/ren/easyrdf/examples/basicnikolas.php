<?php
    /**
     * Basic "Hello World" type example
     *
     * A new EasyRdf_Graph object is created and then the contents
     * of my FOAF profile is loaded from the web. An EasyRdf_Resource for
     * the primary topic of the document (me, Nicholas Humfrey) is returned
     * and then used to display my name.
     *
     * @package    EasyRdf
     * @copyright  Copyright (c) 2009-2011 Nicholas J Humfrey
     * @license    http://unlicense.org/
     */

    set_include_path(get_include_path() . PATH_SEPARATOR . '../lib/');
    require_once "EasyRdf.php";
	
	    EasyRdf_Namespace::set('mdc', 'http://www.purl.org/meducator/ns/');
?>
<html>
<head>
  <title>Basic FOAF example</title>
</head>
<body>

<?php
  $foaf = new EasyRdf_Graph("http://localhost/mod/content_item/metadata42922.rdf");
  $foaf->load();
  $me = $foaf->resource("http://metamorphosis.med.duth.gr/uid#42922");
?>

<p>
  My name is: <?= $me->get('mdc:title') ?>
</p>

</body>
</html>
