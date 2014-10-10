<?php
/**
 * Test with Accca
 * @package Accca
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 09/04/2013
 */
ini_set('memory_limit','512M');
ini_set('display_errors', true);
error_reporting(-1);
/**
 * Load autoload
 */
require_once dirname(__FILE__) . '/AcccaAutoload.php';
/**
 * Accca Informations
 */
define('ACCCA_WSDL_URL','http://www.biosemantics.org/ACCCA/AnnotationWebservicePort?wsdl');
define('ACCCA_USER_LOGIN','');
define('ACCCA_USER_PASSWORD','');
/**
 * Wsdl instanciation infos
 */
$wsdl = array();
$wsdl[AcccaWsdlClass::WSDL_URL] = ACCCA_WSDL_URL;
$wsdl[AcccaWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
$wsdl[AcccaWsdlClass::WSDL_TRACE] = true;
if(ACCCA_USER_LOGIN !== '')
	$wsdl[AcccaWsdlClass::WSDL_LOGIN] = ACCCA_USER_LOGIN;
if(ACCCA_USER_PASSWORD !== '')
	$wsdl[AcccaWsdlClass::WSDL_PASSWD] = ACCCA_USER_PASSWORD;
// etc....
/**
 * Examples
 */


/*****************************
 * Example for AcccaServiceGet
 */
 
$acccaServiceGet = new AcccaServiceGet($wsdl);
// sample call for AcccaServiceGet::getAnnotation()
if($acccaServiceGet->getAnnotation(new AcccaStructGetAnnotation("Prematurity, Respiratory distress syndrome, Hyaline membrane disease, Pneumothorax, Sepsis")))
	
	{$test=$acccaServiceGet->getResult();
	print_r($test);
	$a1=$test->return;
	$a2=$a1->return;
	$a3=$a2->simpleAnnotationList;
//	print_r ($a3);
foreach ($a3 as $list)
	echo $list->conceptText." is a ".$list->conceptType."</br>";
	}
else
	print_r($acccaServiceGet->getLastError());
?>