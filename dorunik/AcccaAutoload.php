<?php
/**
 * File to load generated classes once at once time
 * @package Accca
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * Includes for all generated classes files
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
require_once dirname(__FILE__) . '/AcccaWsdlClass.php';
require_once dirname(__FILE__) . '/Exception/AcccaStructException.php';
require_once dirname(__FILE__) . '/Simple/Annotation/AcccaStructSimpleAnnotation.php';
require_once dirname(__FILE__) . '/Annotation/Set/AcccaStructAnnotationSet.php';
require_once dirname(__FILE__) . '/Get/Response/AcccaStructGetAnnotationResponse.php';
require_once dirname(__FILE__) . '/Get/Annotation/AcccaStructGetAnnotation.php';
require_once dirname(__FILE__) . '/Get/AcccaServiceGet.php';
require_once dirname(__FILE__) . '/AcccaClassMap.php';
?>