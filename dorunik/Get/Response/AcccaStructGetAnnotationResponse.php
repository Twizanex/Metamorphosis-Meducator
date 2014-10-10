<?php
/**
 * File for class AcccaStructGetAnnotationResponse
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * This class stands for AcccaStructGetAnnotationResponse originally named getAnnotationResponse
 * Meta informations extracted from the WSDL
 * - from schema : http://www.biosemantics.org:80/ACCCA/AnnotationWebservicePort?xsd=1
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
class AcccaStructGetAnnotationResponse extends AcccaWsdlClass
{
	/**
	 * The return
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var AcccaStructAnnotationSet
	 */
	public $return;
	/**
	 * Constructor method for getAnnotationResponse
	 * @see parent::__construct()
	 * @param AcccaStructAnnotationSet $_return
	 * @return AcccaStructGetAnnotationResponse
	 */
	public function __construct($_return = NULL)
	{
		parent::__construct(array('return'=>$_return));
	}
	/**
	 * Get return value
	 * @return AcccaStructAnnotationSet|null
	 */
	public function getReturn()
	{
		return $this->return;
	}
	/**
	 * Set return value
	 * @param AcccaStructAnnotationSet the return
	 * @return AcccaStructAnnotationSet
	 */
	public function setReturn($_return)
	{
		return ($this->return = $_return);
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