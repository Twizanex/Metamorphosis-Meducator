<?php
/**
 * File for class AcccaServiceGet
 * @package Accca
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * This class stands for AcccaServiceGet originally named Get
 * @package Accca
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
class AcccaServiceGet extends AcccaWsdlClass
{
	/**
	 * Method to call the operation originally named getAnnotation
	 * @uses AcccaWsdlClass::getSoapClient()
	 * @uses AcccaWsdlClass::setResult()
	 * @uses AcccaWsdlClass::getResult()
	 * @uses AcccaWsdlClass::saveLastError()
	 * @uses AcccaStructGetAnnotation::getArg0()
	 * @param AcccaStructGetAnnotation $_acccaStructGetAnnotation
	 * @return AcccaStructGetAnnotationResponse
	 */
	public function getAnnotation(AcccaStructGetAnnotation $_acccaStructGetAnnotation)
	{
		try
		{
			$this->setResult(new AcccaStructGetAnnotationResponse(self::getSoapClient()->getAnnotation(array('arg0'=>$_acccaStructGetAnnotation->getArg0()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Returns the result
	 * @see AcccaWsdlClass::getResult()
	 * @return AcccaStructGetAnnotationResponse
	 */
	public function getResult()
	{
		return parent::getResult();
	}
	/**
	 * Method returning the class name
	 * @return string __CLASS__
	 */
	public function __toString()
	{
		return __CLASS__;
	}
}
?>