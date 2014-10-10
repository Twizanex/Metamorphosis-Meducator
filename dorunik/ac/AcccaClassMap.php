<?php
/**
 * File for the class which returns the class map definition
 * @package Accca
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * Class which returns the class map definition by the static method AcccaClassMap::classMap()
 * @package Accca
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
class AcccaClassMap
{
	/**
	 * This method returns the array containing the mapping between WSDL structs and generated classes
	 * This array is sent to the SoapClient when calling the WS
	 * @return array
	 */
	final public static function classMap()
	{
		return array (
  'Exception' => 'AcccaStructException',
  'annotationSet' => 'AcccaStructAnnotationSet',
  'getAnnotation' => 'AcccaStructGetAnnotation',
  'getAnnotationResponse' => 'AcccaStructGetAnnotationResponse',
  'simpleAnnotation' => 'AcccaStructSimpleAnnotation',
);
	}
}
?>