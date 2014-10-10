<?php

 /* This file contains the configuration for triplify when working with Elgg (http://www.elgg.org)
 The configuration is pretty basicjust to demonstrate how to do it on Elgg. 
 It just provides triples for the names of the users and SHA1 encypted email addresses.
 Feel free to create your own queries/properties.
 
 Created By: Nikolas Dovrolis (Democritus University of Thrace)
 For Use on http://metamorphosis.med.duth.gr
 */

 
/*Following line assumes you have installed triplify under elgg's main directory .
If not, you have to change it accordingly. */
include_once ("../engine/start.php");


global $CONFIG;
/*Adjust the pdo with the your correct values*/
$triplify['db']=new PDO('mysql:host=localhost;dbname=elgg','elgg','');

$triplify['namespaces']=array(
	'vocabulary'=>'http://your-webapp.com/vocabulary/',
	'rdf'=>'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
	'rdfs'=>'http://www.w3.org/2000/01/rdf-schema#',
	'owl'=>'http://www.w3.org/2002/07/owl#',
	'foaf'=>'http://xmlns.com/foaf/0.1/',
	'sioc'=>'http://rdfs.org/sioc/ns#',
	'sioctypes'=>'http://rdfs.org/sioc/types#',
	'dc'=>'http://purl.org/dc/elements/1.1/',
	'dcterms'=>'http://purl.org/dc/terms/',
	'skos'=>'http://www.w3.org/2004/02/skos/core#',
	'tag'=>'http://www.holygoat.co.uk/owl/redwood/0.1/tags/',
	'xsd'=>'http://www.w3.org/2001/XMLSchema#',
	'update'=>'http://triplify.org/vocabulary/update#',
);

$triplify['queries']=array(
	
	'user'=>array("SELECT guid id,
         name AS 'foaf:name',
         SHA(CONCAT('mailto:',email)) AS 'sioc:email_sha1'
         FROM {$CONFIG->dbprefix}users_entity" ,

        ),
	
);
 
 
$triplify['objectProperties']=array(
	'foaf:name'=>'user',
	'sioc:email_sha1'=>'user',	
);
 
 
 
$triplify['classMap']=array(
	'user'=>'foaf:person',
);



$triplify['license']='http://creativecommons.org/licenses/by/3.0/us/';

$triplify['register']=true;

$triplify['TTL']=0;

$triplify['cachedir']='cache/';

$triplify['LinkedDataDepth']='0
';

$triplify['pingback']= array(
    'enabled' => true,          // Whether X-Pingback header should be exposed and XML-RPC is active.
    'write'   => true,          // Whether to write Pingbacks with the instance data.
);

$triplify['use_mod_rewrite'] = true;  // Whether to use mod_rewrite, if it is available...


?>