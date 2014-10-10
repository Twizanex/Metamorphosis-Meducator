<?php


include_once("C:/Program Files (x86)/Apache Software Foundation/Apache2.2/htdocs/arc/ARC2.php");
/* ARC2 static class inclusion */ 


/* configuration */ 
$config = array(
  /* db */
  'db_host' => 'localhost', /* optional, default is localhost */
  'db_name' => 'arc',
  'db_user' => 'elgg',
  'db_pwd' => '',

   /* store name */
  'store_name' => 'arc_store',

  /* endpoint */
  'endpoint_features' => array(
    'select', 'construct', 'ask', 'describe', 
    'load', 'insert', 'delete', 
    'dump' /* dump is a special command for streaming SPOG export */
  ),
  'endpoint_timeout' => 60, /* not implemented in ARC2 preview */
);

/* instantiation */
$ep = ARC2::getStoreEndpoint($config);

if (!$ep->isSetUp()) {
  $ep->setUp(); /* create MySQL tables */
}

/* request handling */
$ep->go();




?>