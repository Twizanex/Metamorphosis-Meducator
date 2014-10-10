<?php
/**
 * File for class AcccaStructException
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * This class stands for AcccaStructException originally named Exception
 * Meta informations extracted from the WSDL
 * - from schema : http://www.biosemantics.org:80/ACCCA/AnnotationWebservicePort?xsd=1
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
class AcccaStructException extends AcccaWsdlClass
{
	/**
	 * The message
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $message;
	/**
	 * Constructor method for Exception
	 * @see parent::__construct()
	 * @param string $_message
	 * @return AcccaStructException
	 */
	public function __construct($_message = NULL)
	{
		parent::__construct(array('message'=>$_message));
	}
	/**
	 * Get message value
	 * @return string|null
	 */
	public function getMessage()
	{
		return $this->message;
	}
	/**
	 * Set message value
	 * @param string the message
	 * @return string
	 */
	public function setMessage($_message)
	{
		return ($this->message = $_message);
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