<?php
/**
 * File for class AcccaStructGetAnnotation
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * This class stands for AcccaStructGetAnnotation originally named getAnnotation
 * Meta informations extracted from the WSDL
 * - from schema : http://www.biosemantics.org:80/ACCCA/AnnotationWebservicePort?xsd=1
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
class AcccaStructGetAnnotation extends AcccaWsdlClass
{
	/**
	 * The arg0
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $arg0;
	/**
	 * Constructor method for getAnnotation
	 * @see parent::__construct()
	 * @param string $_arg0
	 * @return AcccaStructGetAnnotation
	 */
	public function __construct($_arg0 = NULL)
	{
		parent::__construct(array('arg0'=>$_arg0));
	}
	/**
	 * Get arg0 value
	 * @return string|null
	 */
	public function getArg0()
	{
		return $this->arg0;
	}
	/**
	 * Set arg0 value
	 * @param string the arg0
	 * @return string
	 */
	public function setArg0($_arg0)
	{
		return ($this->arg0 = $_arg0);
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