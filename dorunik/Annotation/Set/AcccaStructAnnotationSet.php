<?php
/**
 * File for class AcccaStructAnnotationSet
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * This class stands for AcccaStructAnnotationSet originally named annotationSet
 * Meta informations extracted from the WSDL
 * - from schema : http://www.biosemantics.org:80/ACCCA/AnnotationWebservicePort?xsd=1
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
class AcccaStructAnnotationSet extends AcccaWsdlClass
{
	/**
	 * The document
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $document;
	/**
	 * The simpleAnnotationList
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var AcccaStructSimpleAnnotation
	 */
	public $simpleAnnotationList;
	/**
	 * Constructor method for annotationSet
	 * @see parent::__construct()
	 * @param string $_document
	 * @param AcccaStructSimpleAnnotation $_simpleAnnotationList
	 * @return AcccaStructAnnotationSet
	 */
	public function __construct($_document = NULL,$_simpleAnnotationList = NULL)
	{
		parent::__construct(array('document'=>$_document,'simpleAnnotationList'=>$_simpleAnnotationList));
	}
	/**
	 * Get document value
	 * @return string|null
	 */
	public function getDocument()
	{
		return $this->document;
	}
	/**
	 * Set document value
	 * @param string the document
	 * @return string
	 */
	public function setDocument($_document)
	{
		return ($this->document = $_document);
	}
	/**
	 * Get simpleAnnotationList value
	 * @return AcccaStructSimpleAnnotation|null
	 */
	public function getSimpleAnnotationList()
	{
		return $this->simpleAnnotationList;
	}
	/**
	 * Set simpleAnnotationList value
	 * @param AcccaStructSimpleAnnotation the simpleAnnotationList
	 * @return AcccaStructSimpleAnnotation
	 */
	public function setSimpleAnnotationList($_simpleAnnotationList)
	{
		return ($this->simpleAnnotationList = $_simpleAnnotationList);
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